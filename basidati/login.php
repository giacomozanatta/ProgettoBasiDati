<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<?php
    session_start() ;
    //stampa_form_login: visualizza il form di login (username e password)
    function stampa_form_login(){ ?>
        <form name="login" method="post" action="login_validation.php">
            Username: <input type="text" name="username">
            Password: <input type="password" name="password">
            <input type="submit" value="login">
        </form>
<?php } ?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT" rel="stylesheet"> 
        <link href="css.css" rel="stylesheet" type="text/css">
        <title>Progetto basi di dati</title>
    </head>
    <body bgcolor="FFFFF3">
        <?php require "header.php" ?>
        <h2>LOGIN</h2>
        <?php if(!isset($_SESSION['username'])) {
            if($_GET['errore']=="problemainput")
                echo "<p><font color=red>Attenzione: inserisci username e/o password!</font></p>";
            else if($_GET['errore']=="noncorretti")
                echo "<p><font color=red>Attenzione: hai sbagliato ad inserire l'username e/o la password!</font></p>";
            else if($_GET['errore']=="nonpermesso")
                echo "<p><font color=red>Attenzione: funzione non permessa da utente ospite. Accedi!</font></p>";
            stampa_form_login();
        } else {?>
           <center>Attenzione: un utente è già connesso. Devi prima effettuare il logout!</center>
        <?php } ?>
    </body>
</html>