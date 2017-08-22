<?php
    include "includes/libreria.php";
    session_start(); //faccio una partire una sessione, anche se l'utente non è loggato. In questo caso, è una sessione per un utente ospite
    
    if((empty($_GET['commento']) && empty($_GET['recensione'])) || !isset($_GET['voto']))
        header("Location: index.php");
    else if(empty($_GET['recensione'])){
        inserisci_voto_commento($_SESSION['id_user'], $_GET['commento'], $_GET['voto']);
        header("Location: ".$_SESSION['last_page']);
    }
    else if(empty($_GET['commento'])){
        inserisci_voto_recensione($_SESSION['id_user'], $_GET['recensione'], $_GET['voto']);
        header("Location: ".$_SESSION['last_page']);
    }
    else
        header("Location: index.php"); //non dovrebbe mai capitare!
?>