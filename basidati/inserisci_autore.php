<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
    <?php session_start();
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
        <?php require "header.php" ?>
        <h1>Inserisci autore</h1>
        <?php
            if($_GET["errore"]=="problemainput")
                echo "<font color=red>Errore: nome e cognome obbligatori!</font>";
            else if($_GET["errore"]=="problemadata")
                echo "<font color=red>Errore: inserisci la data correttamente!</font>";
            else if($_GET["inserito"]=="true")
                echo "<font color=darkgreen>Autore inserito correttamente!</font>";
        ?>
        <form name="inserisci_autore" method="post" action="inserisci_autore_validation.php">
            Nome: <input type="text" name="nome"><br>
            Cognome: <input type="text" name="cognome"><br>
            Data di Nascita (AAAA-MM-GG) : <input type="text" name="data_nascita"><br>
            Nazionalita':  <input type="text" name="nazionalita"><br>
            <input type="submit" value="Inserisci">
        </form>
    </body>
</html>