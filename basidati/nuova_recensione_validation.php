<?php
    session_start() ;
    require "includes/libreria.php";
    if(empty($_POST['titolo']) || empty($_POST['testo']))
        header('Location:libro.php?libro='.$_POST['id_libro'].'&errore=problemainput');
    else{
            /*inserisco la recensione nel database.*/
            inserisci_recensione($_POST['titolo'], $_POST['testo'], $_POST['punteggio'], $_SESSION['id_user'], $_POST['id_libro']);
           header('Location:libro.php?libro='.$_POST['id_libro']);
    }   
?>