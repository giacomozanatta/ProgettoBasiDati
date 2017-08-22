<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
    <?php session_start();
    include "includes/libreria.php";
        if(empty($_SESSION['username'])){
            $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: login.php?errore=nonpermesso");
        }
    ?>
    
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
            $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        ?>
        <h1>Inserisci libro</h1>
        <?php 
            if($_GET['errore']=="problemainput")
                echo "<font color=red>Errore: titolo obbligatorio!</font>";
            if($_GET['inserito']=="true")
                echo"<font color=darkgreen>Libro inserito correttamente!</font>";
            if($_GET['errore']=="problemaautore")
                echo "<font color=red>Attenzione: obbligatorio selezionare almeno 1 autore!</font>";
        ?>
        <form name="inserisci_libro" method="post" action="inserisci_libro_validation.php">
            Titolo: <input type="text" name="titolo"><br>
            Genere: <input type="text" name="genere"><br>
            Trama: <textarea name="trama"></textarea><br>
            Autori: <br>
            <a href="inserisci_autore.php" >Inserisci un autore</a>
            <br>
            <?php stampa_checkbox_autori(); ?>
            <input type="submit" value="Inserisci">
        </form>
    </body>
</html>