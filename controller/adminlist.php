<?php 
require_once '../model/db-connection.php';

session_start();

if (isset($_SESSION['Admin']))
{
    function getPosts($userId, $ndxArticle, $postsPerPage, $orderBy, $searchTerm)
{    
    switch ($orderBy) {
        case 'date-asc':
            $orderBySQL = "ORDER BY id ASC";
            break;
        case 'title-asc':
            $orderBySQL = "ORDER BY title ASC";
            break;
        case 'title-desc':
            $orderBySQL = "ORDER BY title DESC";
            break;
        case 'date-desc':
        default:
            $orderBySQL = "ORDER BY id DESC";
            break;
    }

    if (!empty($searchTerm)) {
        $whereClause = "WHERE title LIKE \"%$searchTerm%\" OR synopsis LIKE \"%$searchTerm%\"";
        $andClause = str_replace('WHERE', 'AND', $whereClause);
    }
    else $whereClause = $andClause = "";

    try {
        $connexio = getConnection();
        $statement = "";

        if ($userId == 0) $statement = $connexio->prepare("
        SELECT title, director, link, synopsis, id, dateTime, image_path, user_id,
        (SELECT nickname FROM users WHERE id = user_id) AS nickname
        FROM posts
        $whereClause
        $orderBySQL
        LIMIT :qty OFFSET :ndx");
        else {
            $statement = $connexio->prepare("
            SELECT title, director, link, synopsis, id, dateTime, image_path, user_id,
            (SELECT nickname FROM users WHERE id = user_id) AS nickname
            FROM posts 
            WHERE user_id = :userId
            $andClause
            $orderBySQL
            LIMIT :qty OFFSET :ndx");
            $statement->bindParam('userId', $userId, PDO::PARAM_INT);
        }

        $statement->bindParam('ndx', $ndxArticle, PDO::PARAM_INT);
        $statement->bindParam('qty', $postsPerPage, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll();
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
    include '../view/index.view.php';
}

}

?>