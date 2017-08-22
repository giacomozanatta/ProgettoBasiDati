<?php
    session_start() ;
    require "includes/libreria.php";


    if(empty($_POST['titolo']))
        header('Location:inserisci_libro.php?errore=problemainput');
    else if(!isset($_POST['autori']))
        header('Location:inserisci_libro.php?errore=problemaautore');
    else{
        ins_libro($_POST['titolo'], $_POST['trama'], $_POST['genere'], $_SESSION['id_user'], $_POST['autori']);
        header('Location:inserisci_libro.php?inserito=true');
    }
?>