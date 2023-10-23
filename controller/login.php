<?php

require_once '../model/pdo-users.php';
require_once '../controller/input-common.php';
require_once '../controller/session.php';

$errors = [];
$email;
$password;

session_start();
$userId = getSessionUserId();
if ($userId != 0) {
    redirectHome();
    return;
}

if (isset($_POST['submit'])) {
    onSubmit();
}

include '../view/login.view.php'; //EX2

function onSubmit()
{
    global $errors, $email, $password;

    $email = $_POST['email'];
    $password = $_POST['password'];
    $keepSession = isset($_POST['keep-session']);


    checkUserInput($email, $password);

    if (empty($errors))
        login($email, $keepSession);

    if (isset($_SESSION["loginTries"])){
        $_SESSION["loginTries"]++;
        ini_set( "session.gc_maxlifetime", 900); //EX9
    }
        else $_SESSION["loginTries"] = 1;
}

/**
 * Comprova l'input de l'usuari i si n'hi ha errors, els afegeix a l'array global d'errors
 *
 * @param string $email email de l'usuari
 * @param string $password password
 * 
 */
function checkUserInput($email, $password)
{
    global $errors;

    checkEmail($email, false);
    if (empty($password))
        $errors['password'] = "Password can't be empty.";

    if (!empty($errors))
        return;

    if (!userExistsByEmail($email)) {
        $errors['email'] = "This user email doesn't exist.";
        return;
    }


    //EX11
    //$md5Hash = md5($password);
    //$md5HashDB = getUserHash($email);
    $contradb = getUserHash($email);
    
    if (password_verify($password, $contradb)) {
        $errors['password'] = "Wrong password.";
        return;
    }
}

/**
 * Inicia sessió a l'usuari i el redirigeix a l'inici
 *
 * @param string $email email de l'usuari
 * 
 */
function login($email, $keepSession)
{
    if (isset($_SESSION["loginTries"]))
        unset($_SESSION["loginTries"]);

    startSession($email, $keepSession);
    redirectHome();
}