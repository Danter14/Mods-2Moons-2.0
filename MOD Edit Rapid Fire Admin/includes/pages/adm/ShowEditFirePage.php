<?php

/**
 * @mods Edit Rapid Fire
 * @version 1.2
 * @author noonn
 * @modified & optimised by Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

if (!allowedTo(str_replace(array(dirname(__FILE__), '\\', '/', '.php'), '', __FILE__))) throw new Exception("Permission error!");

function ShowEditFirePage()
{
    global $LNG;

    // Connexion à la base de donnée
    $db     = Database::get();

    // On créer notre instance de template
    $template    = new template();

    // On récupère le mode de l'action
    $mode    = HTTP::_GP('mode', '');
    if ($mode) {
        // On lance l'action en fonction du mode demandé
        action($mode);
    }

    // Ajout de la Pagination
    $pagination    = HTTP::_GP('pagination', 1);
    $currentPage = isset($pagination) ? $pagination : 1;
    $itemsPerPage = 10;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Barre de recherche
    $searchQuery = HTTP::_GP('search', '');

    // On récupère les données de la table %%VARS_RAPIDFIRE%% avec la pagination + recherche
    $params = [];
    $sql = "SELECT * FROM %%VARS_RAPIDFIRE%%";
    if (!empty($searchQuery)) {
        $sql .= " WHERE elementID LIKE :searchQuery OR rapidfireID LIKE :searchQuery";
        $params[':searchQuery'] = $searchQuery;
    }
    $sql .= " ORDER BY elementID, rapidfireID LIMIT :offset, ". $itemsPerPage ." ;";
    $params[':offset'] = $offset;

    $rapidResult = $db->select($sql, $params);

    // On boucle sur les résultats récupérer
    $fleet_i    = array();
    foreach ($rapidResult as $row) {
        $fleet_i[]    = array(
            'elementID'        => $row['elementID'],
            'rapidfireID'    => $row['rapidfireID'],
            'shoots'        => $row['shoots'],
        );
    }

    // On Compte le nombre total d'éléments pour la pagination
    $sql = "SELECT COUNT(*) as total FROM %%VARS_RAPIDFIRE%%";
    $totalCount = $db->selectSingle($sql);

    // Calculer le nombre total de pages
    $totalPages = ceil($totalCount["total"] / $itemsPerPage);

    // On assigne les variables au template
    $template->assign_vars(array(
        'fleet_i'    => $fleet_i,
        'short'        => $LNG['shortNames'],
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
        'offset' => $offset,
    ));

    // On affiche le template avec les variable passer
    $template->show('ShowEditFirePage.tpl');
}

/**
 * Elle permet de vérifier si les ID sont bien présent
 *
 * @param String $elementId
 * @param String $rapidfireId
 * @param boolean $isBDD
 * @return boolean
 */
function controleIDExist(String $elementId, String $rapidfireId, bool $isBDD = false): bool
{
    // On vérifie si les ID sont bien présent
    if (isset($elementId) && isset($rapidfireId)) {
        // On vérifie si les ID sont vide si oui on renvoie false
        if(empty($elementId) || empty($rapidfireId)) {
            return false;
        }

        // On demande si on doit vérifier dans la base de donnée
        if($isBDD) {
            // On vérifie dans la base de donnée si les ID existe
            $db = Database::get();
            $sql = "SELECT COUNT(*) as count FROM %%VARS_RAPIDFIRE%% WHERE elementID = :elemID AND rapidfireID = :rapID ;";
            $checkID = $db->selectSingle($sql, array(
                ':elemID'        => $elementId,
                ':rapID'        => $rapidfireId,
            ), 'count');

            // Si les ID existe on renvoie true
            if ($checkID == 1) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    return false;
}

/**
 * Renvoi une erreur avec le message passer en paramètre
 *
 * @param String $message
 * @return void
 */
function errorControle(String $message): void
{
    throw new Exception($message);
}

/**
 * Permet de lancer une fonction en fonction du mode passer en paramètre
 *
 * @param String $action
 * @return void
 */
function action(String $action): void
{
    $elemID    =  HTTP::_GP('elemID', '');
    $rapID    =  HTTP::_GP('rapID', '');
    $shoots   =  HTTP::_GP('shoots', 1);
    $newshoots	=  HTTP::_GP('newshoots', 1);

    // On lance l'action en fonction du mode demandé
    switch ($action) {
        case 'del':
            !controleIDExist($elemID, $rapID) ? errorControle("Un Id est obligatoire ainsi que le rapidfire") : del($elemID, $rapID);
            break;
        case 'add':
            !controleIDExist($elemID, $rapID) ? errorControle("Un Id est obligatoire ainsi que le rapidfire") : add($elemID, $rapID, $shoots);
            break;
        case 'edit':
            !controleIDExist($elemID, $rapID) ? errorControle("Un Id est obligatoire ainsi que le rapidfire") : edit($elemID, $rapID, $newshoots);
            break;
        case 'clear':
            clear();
            break;
        default:
            errorControle("Une erreur est survenu aucune action trouver");
        break;
    }
}

/**
 * Permet de supprimer un rapidfire de la base de donnée
 *
 * @param String $elementId
 * @param String $rapidfireId
 * @return void
 */
function del(String $elementId, String $rapidfireId): void
{
    if (!controleIDExist($elementId, $rapidfireId, true)) {
        errorControle("Une erreur est survenu aucune information trouver");
    }

    $db = Database::get();

    // On supprime les données de la base de donnée
    $sql	= 'DELETE FROM %%VARS_RAPIDFIRE%% WHERE elementID = :elemID AND rapidfireID = :rapID ;';
	$db->delete($sql, array(
		':elemID'	=> $elementId,
		':rapID'	=> $rapidfireId,
	));

    clear();
}

/**
 * Permet d'ajouter dans la base de donnée un rapidfire
 *
 * @param String $elementId
 * @param String $rapidfireId
 * @param String $shoots
 * @return void
 */
function add(String $elementId, String $rapidfireId, String $shoots): void
{
    if (controleIDExist($elementId, $rapidfireId, true)) {
        errorControle("Une erreur est sruvenu une information existe déjà");
    }

    $db     = Database::get();

    // On insert les données dans la base de donnée
    $sql = 'INSERT INTO %%VARS_RAPIDFIRE%% SET elementID = :elemID, rapidfireID = :rapID, shoots = :shoots ;';
    $db->insert($sql, array(
        ':elemID'       => $elementId,
        ':rapID'        => $rapidfireId,
        ':shoots'       => $shoots,
    ));

    clear();
}

/**
 * Permet la modification d'un rapidfire
 *
 * @param String $elementId
 * @param String $rapidfireId
 * @param String $newShoot
 * @return void
 */
function edit(String $elementId, String $rapidfireId, String $newShoot): void
{
    if (!controleIDExist($elementId, $rapidfireId, true)) {
        errorControle("Une erreur est survenu aucune information trouver");
    }

    $db     = Database::get();

    // On modifie les données dans la base de donnée
    $sql = "UPDATE %%VARS_RAPIDFIRE%% SET shoots = :shoots WHERE elementID = :elemID AND rapidfireID = :rapID ;";
	$db->update($sql, array(
		':shoots'	 => $newShoot,
		':elemID'	=> $elementId,
		':rapID'	=> $rapidfireId,
	));

    clear();
}

/**
 * Permet de vider le cache
 */
function clear(): void
{
    ClearCache();

    // redirection sur la page une fois la Cache vidé
    header("Location: admin.php?page=edit-fire");
}
