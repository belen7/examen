<?php
session_start();
$uri = $_SERVER['REQUEST_URI'];

$redir = substr_count($uri,'funciones'); 

if ( !isset($_SESSION['user_rol']) || ($_SESSION['user_rol']=='')) {
    session_destroy();
    if ($redir>0) {
        header("Location: ../../index.php");
    } else {
        header("Location: ../index.php");
    }
};

?>
