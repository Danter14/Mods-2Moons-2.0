<?php

/**
 * @mods Voucher System
 * @version 1.0.0
 * @author Danter14
 * @package 2Moons, Newstar, Steemnova
 * @version 2.0
 */

if (!allowedTo(str_replace(array(dirname(__FILE__), '\\', '/', '.php'), '', __FILE__))) throw new Exception("Permission error!");

/**
 * Show Voucher Page
 *
 * @return void
 */
function ShowVoucherPage()
{
    global $USER;

    if (!empty($_POST)) {
        $action     = HTTP::_GP("action", "");

        if ($action == "createVoucher") {
            setVoucher($_POST);
        } elseif ($action == "createVoucherReward") {
            setReward($_POST);
        } elseif ($action == "editVoucher") {
            $result = getVoucherById($_POST['id']);

            $result['start_time'] = _date("Y-m-d H:i:s", $result['start_time'], $USER['timezone']);

            if ($result['end_time'] == 0 || $result['end_time'] == null) {
                $result['end_time'] = false;
            } else {
                $result['end_time'] = _date("Y-m-d H:i:s", $result['end_time'], $USER['timezone']);
            }

            $template    = new template();
            $template->assign_vars($result);
            $template->show('VoucherEditMods.tpl');
        } elseif ($action == "updateVoucher") {
            updateVoucher($_POST);
        } elseif ($action == "deleteVoucher") {
            deleteVoucher($_POST['id']);
        } elseif ($action == "deleteVoucherUse") {
            deleteUserVoucher($_POST['id']);
        }
    }

    $template    = new template();

    $template->assign_vars([
        'vouchers' => getVoucher(false),
        'vouchersList' => getVoucher(true),
        'rewards' => getRewards(),
        'rewardsUser' => getRewardsUser(),
    ]);

    $template->show('VoucherMods.tpl');
}

/**
 * Generate random code
 *
 * @param int $length
 * @return string
 */
function randomCode($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * get voucher from database
 *
 * @return array
 */
function getVoucher($isComplete = false)
{
    global $LNG, $USER;

    $sql = "SELECT *
    FROM " . VOUCHER . " WHERE start_time <= " . TIMESTAMP . "";

    if (!$isComplete) {
        $sql .= " AND (end_time >= " . TIMESTAMP . " OR end_time = 0 OR end_time IS NULL)";
    }

    $sql .= " ORDER BY id_voucher DESC";

    $result = $GLOBALS['DATABASE']->query($sql);

    $voucher = [];

    if ($result->num_rows == 0) {
        return $voucher;
    }

    while ($row = $result->fetch_assoc()) {
        $isFinish = $row['end_time'] >= TIMESTAMP || $row['end_time'] == 0 || $row['end_time'] == null;
        $voucher[] = [
            'id' => $row['id_voucher'],
            'code' => $row['code'],
            'start_time' => _date($LNG['php_tdformat'], $row['start_time'], $USER['timezone']),
            'end_time' => $row['end_time'] == 0 ? 'Never' : _date($LNG['php_tdformat'], $row['end_time'], $USER['timezone']),
            'isComplete' => $isFinish ? true : false,
        ];
    }

    return $voucher;
}

/**
 * Delete voucher
 *
 * @param int $id
 * @return void|boolean
 */
function deleteVoucher($id)
{

    if (empty($id) || !getVoucherById($id)) {
        return false;
    }

    $GLOBALS['DATABASE']->query("DELETE FROM " . VOUCHER_USER_USE . " WHERE id_voucher = " . $id);
    $GLOBALS['DATABASE']->query("DELETE FROM " . VOUCHER_REWARD . " WHERE id_voucher = " . $id);
    $GLOBALS['DATABASE']->query("DELETE FROM " . VOUCHER . " WHERE id_voucher = " . $id);
}

/**
 * Get voucher by id
 *
 * @param int $id
 * @return array|boolean
 */
function getVoucherById($id)
{
    $result = $GLOBALS['DATABASE']->query("SELECT * FROM " . VOUCHER . " WHERE id_voucher = " . $id);

    if ($result->num_rows == 0) {
        return false;
    }

    return $result->fetch_assoc();
}

/**
 * Update voucher
 *
 * @param [array] $donnes
 * @return void
 */
function updateVoucher($donnes)
{
    extract($donnes);

    if (isVerif($donnes)) {
        if (empty($start_time)) {
            $start_time = TIMESTAMP;
        } else {
            $start_time = strtotime($start_time);
        }

        if (empty($end_time)) {
            $end_time = 0;
        } else {
            $end_time = strtotime($end_time);
        }

        $GLOBALS['DATABASE']->query("UPDATE " . VOUCHER . " SET code = '" . $voucher . "', start_time = '" . $start_time . "', end_time = '" . $end_time . "' WHERE id_voucher = " . $id);
    }
}

/**
 * Get rewards
 *
 * @return array
 */
function getRewards()
{
    global $reslist, $resource, $LNG;

    $rewards = [];

    // Addon list resources
    foreach ($reslist['resstype'][1] as $ID) {
        $rewards[]    = [
            'id'    => $ID,
            'name'    => $LNG['tech'][$ID],
        ];
    }

    foreach ($reslist['resstype'][3] as $ID) {
        $rewards[]    = [
            'id'    => $ID,
            'name'    => $LNG['tech'][$ID],
        ];
    }

    // Addon list fleet
    foreach ($reslist['fleet'] as $ID) {
        $rewards[]    = [
            'id'    => $ID,
            'name'    => $LNG['tech'][$ID],
        ];
    }

    // Addon list defense
    foreach ($reslist['defense'] as $ID) {
        $rewards[]    = [
            'id'    => $ID,
            'name'    => $LNG['tech'][$ID],
        ];
    }

    return $rewards;
}

/**
 * Set voucher in database if valid data
 *
 * @param [string] $donnes
 * @return void
 */
function setVoucher($donnes)
{
    extract($donnes);

    if (isVerif($donnes)) {
        if (empty($start_time)) {
            $start_time = TIMESTAMP;
        } else {
            $start_time = strtotime($start_time);
        }

        if (empty($end_time)) {
            $end_time = 0;
        } else {
            $end_time = strtotime($end_time);
        }

        saveVoucher($voucher, $start_time, $end_time);
    }
}

/**
 * Check if voucher is valid
 *
 * @param [array] $donnes
 * @return boolean
 */
function isVerif($donnes)
{
    if (empty($donnes['voucher'])) {
        return false;
    }

    return true;
}

/**
 * Save voucher
 * @param string $voucher
 * @param int $start_time
 * @param int $end_time
 *
 * @return void
 */
function saveVoucher($voucher, $start_time, $end_time)
{
    $GLOBALS['DATABASE']->query("INSERT INTO " . VOUCHER . "
        (code, start_time, end_time) VALUES
        ('" . $voucher . "', '" . $start_time . "', '" . $end_time . "')");
}

/**
 * Set reward in database
 *
 * @param [array] $donnes
 * @return void
 */
function setReward($donnes)
{
    extract($donnes);

    if (isVerifReward($donnes)) {
        saveReward($voucher_code, $reward, $quantity);
    }
}

/**
 * Check if reward is valid
 *
 * @param [array] $donnes
 * @return boolean
 */
function isVerifReward($donnes)
{
    if (empty($donnes['voucher_code']) || empty($donnes['reward']) || empty($donnes['quantity'])) {
        return false;
    }

    $id_voucher = (int) $donnes['voucher_code'];
    $reward = (int) $donnes['reward'];
    $quantity = (int) $donnes['quantity'];

    if (!is_numeric($id_voucher) || !is_numeric($quantity) || !is_numeric($reward)) {
        return false;
    }

    return true;
}

/**
 * Save reward
 *
 * @param int $id_voucher
 * @param int $reward
 * @param int $quantity
 * @return void
 */
function saveReward($id_voucher, $reward, $quantity)
{
    $GLOBALS['DATABASE']->query("INSERT INTO " . VOUCHER_REWARD . "
        (id_voucher, reward, price_reward) VALUES
        ('" . $id_voucher . "', '" . $reward . "', '" . $quantity . "')");
}

function getRewardsUser()
{
    global $LNG;

    $sql = "SELECT vu.id_voucher_use, vu.id_voucher, vu.id_user, vu.use_time, u.username, u.timezone, v.id_voucher as voucherID, v.code
        FROM " . VOUCHER_USER_USE . " as vu
        INNER JOIN " . USERS . " as u ON vu.id_user = u.id
        INNER JOIN " . VOUCHER . " as v ON vu.id_voucher = v.id_voucher 
        ORDER BY id_voucher_use DESC;";

    $result = $GLOBALS['DATABASE']->query($sql);

    $usersList = [];

    if ($result->num_rows == 0) {
        return $usersList;
    }

    while ($row = $result->fetch_assoc()) {
        $usersList[] = [
            'code' => $row['code'],
            'id' => $row['id_voucher_use'],
            'username' => $row['username'],
            'use_time' => _date($LNG['php_tdformat'], $row['use_time'], $row['timezone']),
        ];
    }

    return $usersList;
}

function getVoucherUseById($id)
{
    $result = $GLOBALS['DATABASE']->query("SELECT * FROM " . VOUCHER_USER_USE . " WHERE id_voucher_use = " . $id);

    if ($result->num_rows == 0) {
        return false;
    }

    return $result->fetch_assoc();
}

function deleteUserVoucher($id)
{
    if (empty($id) || !getVoucherUseById($id) || !is_numeric($id)) {
        return false;
    }

    $GLOBALS['DATABASE']->query("DELETE FROM " . VOUCHER_USER_USE . " WHERE id_voucher_use = " . $id);
}
