<?php
//EX6 Tot desde 0
require_once '../model/pdo-articles.php';
require_once '../controller/session.php';
require_once '../model/pdo-users.php';

session_start();
$userId = getSessionUserId();
if ($userId == 0) {
    header('Location: login.php');
    return;
}

require_once '../model/pdo-articles.php';
require_once '../controller/session.php';
require_once '../model/pdo-users.php';

session_start();
$userId = getSessionUserId();
if ($userId == 0) {
    header('Location: login.php');
    return;
}


updateUserHistoric($userId, "eliminat");


deleteUser($userId);
session_destroy();
header('Location: login.php');

?>