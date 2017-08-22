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
        <p>
        <a href="inserisci_autore.php">Inserisci nuovo autore</a><br>
            <a href="inserisci_libro.php">Inserisci nuovo libro</a><br>
        </p>
        <hr>
        <?php 
            echo "<h2>CERCA</h2>";
            stampa_form_cerca();
            echo "<hr>";
            echo "<h2>LISTA LIBRI</h2>";
            echo 'Ordina per: <a href="index.php?order=titolo">titolo</a>, <a href="index.php?order=titolodesc">titolo desc</a>, <a href="index.php?order=valutazione">valutazione</a>, <a href="index.php?order=valutazionedesc">valutazione desc</a>';
            if(($_GET["order"]=="valutazione"))
                stampa_libri_ordinati("valutazione");
            else if(($_GET["order"]=="valutazionedesc"))
                stampa_libri_ordinati("valutazione desc");
            else if(($_GET["order"]=="titolodesc"))
                stampa_libri_ordinati("titolo desc");
            else
                stampa_libri_ordinati("titolo");
        ?>
    </body>
</html>