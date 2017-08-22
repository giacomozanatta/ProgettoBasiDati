<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
    <?php session_start(); ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT" rel="stylesheet"> 
        <link href="css.css" rel="stylesheet" type="text/css">
        <title>
            Progetto basi di dati
        </title>
    </head>
     <body bgcolor="FFFFF3">
        <?php 
            require "header.php";
            include "includes/libreria.php";
        ?>
        <h1>CERCA</h1>
        <?php
            stampa_form_cerca();
            if(empty($_GET['titolo']))
                echo "<font color=red>Errore: devi inserire il titolo del libro che vuoi cercare!</font>";
            else
                echo "Risultati di ricerca per: ".$_GET['titolo'];
            stampa_ricerca_libri_per($_GET['titolo']);
            
        ?>
    </body>
</html>