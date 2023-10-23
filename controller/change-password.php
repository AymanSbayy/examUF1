<?php
session_start();

require_once "../model/pdo-users.php";

if(isset($_SESSION['user_id']) && isset($_POST['pass'])) {
    $user_id = $_SESSION['user_id'];
    $new_password = $_POST['pass'];
    restPass($userId, $password);
}



include "../view/changepass.php";
?>