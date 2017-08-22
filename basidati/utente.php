<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<?php
    include "includes/libreria.php";
    session_start(); //faccio una partire una sessione, anche se l'utente non è loggato. In questo caso, è una sessione per un utente ospite
    $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if(empty($_GET["id"]))
        header("Location: index.php");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT" rel="stylesheet"> 
        <link href="css.css" rel="stylesheet" type="text/css">
        <title>
            Progetto basi di dati
        </title>
    </head>
    <body bgcolor="FFFFF3">
        <?php require "header.php" ?>
        <?php 

            
            stampa_info_utente($_GET['id']);
            
        ?>
    </body>
</html>  