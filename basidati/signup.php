<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<?php 
    session_start() ;
    //stampa_form_login: visualizza il form di login (username e password)
    function stampa_form_signup(){ ?>
        <form name="signup" method="post" action="signup_validation.php">
            Username: <input type="text" name="username">
            Password: <input type="password" name="password">
            Email: <input type="text" name="email">
            Data di Nascita: <input type="text" name="data_nascita">
            <br>Nome:  <input type="text" name="nome">
            Cognome:  <input type="text" name="cognome">
            <input type="submit" value="Registrati">
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
        <h2>SIGNUP</h2>
        <?php if(!isset($_SESSION['username'])) {
            if($_GET['registrato']=="true")
                echo "<p><font color=darkgreen><center>Sei stato registrato correttamente! Ora puoi eseguire il login con i tuoi dati.</center></font></p> ";
            else{
                if($_GET['errore']=="problemainput")
                    echo "<p><font color=red>Attenzione: obbligatorio inserire username, password, email!</font></p>";
                else if($_GET['errore']=="mailesistente")
                    echo "<p><font color=red>Attenzione: mail gia' presente nel database!</font></p>";
                else if($_GET['errore']=="useresistente")
                    echo "<p><font color=red>Attenzione: username gia' presente nel database!</font></p>";
                else if($_GET['errore']=="data")
                    echo "<p><font color=red>Attenzione: data errata! Inserire la data nel seguente modo: GG-MM-AAAA (es: 02-12-1975)!</font></p>";
                stampa_form_signup();
            }
        } else {?>
           <center>Attenzione: un utente è già connesso. Devi prima effettuare il logout!</center>
        <?php } ?>
    </body>
</html>