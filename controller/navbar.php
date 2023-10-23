<?php

require_once '../model/pdo-users.php';
require_once '../controller/session.php';

//EX12
    $userId = getSessionUserId();
    
    $anonUser = $userId == 0;

    if (!$anonUser) {        
        $nickname = getUserNicknameById($userId);
    } else $changePasswordVisibility = '';
    
    $file = pathinfo($_SERVER['PHP_SELF'])['filename'];
    
    $homeActive = $file == "index" ? "active" : "";
    $loginActive = $file== "login" ? "active" : "";
    $signupActive = $file == "sign-up" ? "active" : "";
    $createActive = $file == "edit" ? "active" : "";
    $passwordActive = $file == "change-password" ? "active" : "";    

    include '../view/navbar.view.php'; //EX2
