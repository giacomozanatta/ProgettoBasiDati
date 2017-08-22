<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<?php
    include "includes/libreria.php";
    session_start(); //faccio una partire una sessione, anche se l'utente non è loggato. In questo caso, è una sessione per un utente ospite
    $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //mi permette di trovare l'ultima pagina (utile per il login, o per l'inserimento di un autore/libro)
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
            if(empty($_GET["libro"]))
                echo "<font color=red>Errore: devi prima selezionare un libro!</font>";
            else{
                echo "<h2>LIBRO</h2>";
                stampa_info_libro($_GET["libro"]);
            }
        ?>
    </body>
</html>