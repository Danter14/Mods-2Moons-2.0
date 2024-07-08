<?php

/**
 * @mods Voucher System
 * @version 1.0.0
 * @author Danter14
 * @package 2Moons, Newstar, Steemnova
 * @version 2.0
 */

class ShowVoucherPage extends AbstractGamePage
{
    private $result = [];
    private $rewards = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get list rewards user voucher
     *
     * @return array|boolean
     */
    private function getListRewardsUser()
    {
        global $USER, $LNG;

        $db = Database::get();

        $sql = "SELECT vu.id_voucher, vu.use_time, v.code
            FROM %%VOUCHER_USER_USE%% as vu
            LEFT JOIN %%VOUCHER%% as v ON vu.id_voucher = v.id_voucher
            WHERE `id_user` = :id_user
            ORDER BY vu.use_time DESC
            LIMIT 6;";
        $tab = [
            ':id_user' => $USER['id']
        ];

        $result = $db->select($sql, $tab);

        if (!$result) {
            return false;
        }

        foreach ($result as $voucher) {
            $rewardList = [];
            foreach ($this->getRewards($voucher['id_voucher']) as $reward) {
                $rewardList[$reward['reward']] = (int) $reward['price_reward'];
            }

            $response[] = [
                'code' => $voucher['code'],
                'use_time' => _date($LNG['php_tdformat'], $voucher['use_time'], $USER['timezone']),
                'rewards' => $rewardList,
                'rewards3' => $this->getRewards($voucher['id_voucher'])
            ];
        }

        return $response;
    }

    /**
     * Page content
     *
     * @return void
     */
    public function show()
    {
        $this->tplObj->loadscript('voucher.js');

        // Assign variables
        $this->assign([
            'rewardsUse' => $this->getListRewardsUser()
        ]);

        // Views file
        $this->display('page.voucher.default.tpl');
    }

    /**
     * Check if voucher is valid
     *
     * @param [string] $voucher
     * @return boolean
     */
    private function isValid($voucher)
    {
        // Check if voucher exists
        if (!$this->getVoucher($voucher)) {
            return false;
        }

        // Check if voucher is valid time and is used
        if (!$this->isValidTime() || !$this->isUsed()) {
            return false;
        }

        return true;
    }

    /**
     * Get voucher
     *
     * @param [string] $voucher
     * @return boolean
     */
    public function getVoucher($voucher)
    {
        $db = Database::get();

        $sql = "SELECT * FROM %%VOUCHER%% WHERE `code` = :code";
        $tab = [
            ':code' => $voucher
        ];

        $result = $db->selectSingle($sql, $tab);

        // Check if voucher exists
        if (!$result) {
            return false;
        }

        $this->result = $result;

        return true;
    }

    /**
     * Check if voucher is valid time
     *
     * @param [array] $result
     * @return boolean
     */
    private function isValidTime()
    {
        // Check if voucher is valid time start
        if ($this->result['start_time'] >= TIMESTAMP) {
            return false;
        }

        // Check if voucher is valid time end
        if ($this->result['end_time'] < TIMESTAMP && !is_null($this->result['end_time']) && !empty($this->result['end_time'])) {
            return false;
        }

        return true;
    }

    /**
     * Check if voucher is used
     *
     * @return boolean
     */
    private function isUsed()
    {
        global $USER;

        $db = Database::get();

        $sql = "SELECT * FROM %%VOUCHER_USER_USE%% WHERE `id_voucher` = :voucher_id AND `id_user` = :id_user ;";
        $tab = [
            ':voucher_id' => $this->result['id_voucher'],
            ':id_user' => $USER['id']
        ];

        $result = $db->selectSingle($sql, $tab);

        // Check if voucher is used
        if ($result) {
            return false;
        }

        return true;
    }

    /**
     * Set user voucher
     *
     * @return void
     */
    public function setUserVoucher()
    {
        global $USER;

        extract($_POST);

        $response = [];

        if (isset($voucher) && !empty($voucher)) {
            $voucher = trim($voucher);

            if ($voucher) {
                if ($this->isValid($voucher)) {
                    if ($this->getRewards()) {
                        $this->setRewards();
                        $this->setUser($USER['id']);
                    } else {
                        $response['message'] = 'There are no rewards for this code awaiting reward';
                        $this->sendJSON($response);
                    }
                } else {
                    $response['message'] = 'Voucher is not valid';
                    $this->sendJSON($response);
                }
            } else {
                $response['message'] = 'Voucher not found';
                $this->sendJSON($response);
            }
        } else {
            $response['message'] = 'Voucher is empty';
            $this->sendJSON($response);
        }

        $response['message'] = 'Votre code est valide !';
        $this->sendJSON($response);
    }

    /**
     * Set user
     *
     * @param [array] $user
     * @return void
     */
    private function setUser($user_id)
    {
        $db = Database::get();

        $sql = "INSERT INTO %%VOUCHER_USER_USE%% (`id_user`, `id_voucher`, `use_time`)
                VALUES (:user_id, :voucher_id, :used_time)";
        $tab = [
            ':user_id' => $user_id,
            ':voucher_id' => $this->result['id_voucher'],
            ':used_time' => TIMESTAMP
        ];

        $db->insert($sql, $tab);
    }

    /**
     * On récupère kes récompenses par rapport à l'id du voucher
     * @param [boolean] $all
     *
     * @return array|boulean
     */
    private function getRewards($voucher_id = false)
    {
        $db = Database::get();

        $sql = "SELECT id_voucher, reward, price_reward FROM %%VOUCHER_REWARD%% WHERE `id_voucher` = :voucher_id";
        $tab = [
            ':voucher_id' => $voucher_id ? $voucher_id : $this->result['id_voucher']
        ];

        $result = $db->select($sql, $tab);

        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
     * On set les récompenses pour l'utilisateur
     *
     * @return void|boolean
     */
    private function setRewards()
    {
        global $resource;

        $rewards = $this->getRewards();

        if (!$rewards) {
            return false;
        }

        $planetReward = [];
        $userReward = [];
        foreach ($rewards as $reward) {
            if (!isset($resource[$reward['reward']])) {
                continue;
            }

            if (
                $reward['reward'] >= 901 && $reward['reward'] <= 903
                || $reward['reward'] >= 202 && $reward['reward'] <= 299
                || $reward['reward'] >= 401 && $reward['reward'] <= 499
            ) {
                $planetReward[$resource[$reward['reward']]] = $reward['price_reward'];
                $this->rewards['PLANETS'] = $planetReward;
            } elseif ($reward['reward'] == 921) {
                $userReward[$resource[$reward['reward']]] = $reward['price_reward'];
                $this->rewards['USERS'] = $userReward;
            }
        }

        if (isset($this->rewards)) {
            $this->setRewardUser();
        }

        return true;
    }

    /**
     * On update les récompenses pour l'utilisateur
     *
     * @return void
     */
    private function setRewardUser()
    {
        global $USER, $PLANET;

        $db = Database::get();

        foreach ($this->rewards as $table => $rewards) {
            foreach ($rewards as $resource => $price_reward) {

                if ($table == "USERS" && $resource == "darkmatter") {
                    $USER[$resource] += $price_reward;
                } elseif (
                    $table == "PLANETS" && $resource == "metal"
                    || $resource == "crystal"
                    || $resource == "deuterium"
                ) {
                    $PLANET[$resource] += $price_reward;
                } else {
                    $sql = "UPDATE %%{$table}%%
                    SET `{$resource}` = `{$resource}` + :price_reward
                    WHERE `id` = :id_result";
                    $tab = [
                        ':price_reward' => $price_reward,
                        ':id_result' => $table == "USERS" ? $USER['id'] : $PLANET['id']
                    ];

                    $db->update($sql, $tab);
                }
            }
        }
    }
}
