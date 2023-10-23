<?php

require_once '../model/pdo-articles.php';
require_once '../controller/session.php';

session_start();
//EX7.1
if (!isset($_GET['postsPerPage'])) {
    if (isset($_COOKIE['postsPerPage'])) {
        $postsPerPage = $_COOKIE['postsPerPage'];
    } else {
        $postsPerPage = 10;
    }
} else {
    $postsPerPage = $_GET['postsPerPage'];
    setcookie('postsPerPage', $postsPerPage, time() + 900, "/");
}

//EX7.2
if (!isset($_GET['orderBy'])) {
    if (isset($_COOKIE['orderBy'])) {
        $orderBy = $_COOKIE['orderBy'];
    } else {
        $orderBy = 'date-desc';
    }
} else {
    $orderBy = $_GET['orderBy'];
    setcookie('orderBy', $orderBy, time() + 900, "/");
}


$searchTerm = "";
if (isset($_GET['search'])) $searchTerm = $_GET['search'];


$userId = getSessionUserId();

$nArticles = getCountOfPosts($userId, $searchTerm); 
$nPages = ceil($nArticles / $postsPerPage); 

if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

if ($nArticles > 0 && ($currentPage > $nPages || $currentPage < 1)) {
    header("Location: index.php");
}

$ndxArticle = $postsPerPage * ($currentPage - 1);

$articles = getPosts($userId, $ndxArticle, $postsPerPage, $orderBy, $searchTerm); //EX7.1

if ($currentPage <= 3) $backScope = $currentPage - 1;
else $backScope = 3;
if ($currentPage + 3 > $nPages) $frontScope = $nPages - $currentPage;
else $frontScope = 3;


$firstPage = $currentPage == 1;
$lastPage = $currentPage == $nPages;

$firstPageClass = $firstPage ? 'disabled' : '';
$lastPageClass = $lastPage ? 'disabled' : '';

$searchQuery = !empty($searchTerm) ? "?search=$searchTerm&" : "?";
$nextPageLink = $lastPage ? "#" : $searchQuery . "page=" . ($currentPage + 1);
$previousPageLink = $firstPage ? "#" : $searchQuery . "page=" . ($currentPage - 1);
$firstPageLink = $firstPage ? "#" : $searchQuery . "page=1";
$lastPageLink = $lastPage ? "#" : $searchQuery . "page=$nPages";

include '../view/index.view.php'; ////EX2