<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<?php
    include "includes/libreria.php";
    session_start();
    $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
            if(empty($_GET["id"]))
                echo "<font color=red>Errore: devi prima selezionare una recensione!</font>";
            else{
                if($_GET['errore']=='notesto')
                    echo "<font color=red>Errore: un commento non puo' essere vuoto!</font>";
                stampa_info_recensione($_GET["id"]);
                echo "<hr>";
                echo "<h4>Commenti degli utenti</h4>";
                stampa_commenti_recensione($_GET["id"]);
            }
        ?>
    </body>
</html>