<?php
    session_start() ;
    require "includes/libreria.php";

    if(empty($_POST['nome']) && empty($_POST['cognome']))
        header('Location:inserisci_autore.php?errore=problemainput');
    else if(!empty($_POST['data_nascita']) && !controlla_data($_POST['data_nascita']))
        header('Location:inserisci_autore.php?errore=problemadata');
    else{
        ins_autore($_POST['nome'], $_POST['cognome'], $_POST['nazionalita'], $_POST['data_nascita'], $_SESSION['id_user']);
        if (strpos($_SESSION['last_page'], 'inserisci_libro') !== false)
            header('Location:inserisci_libro.php');
        else
            header('Location:inserisci_autore.php?inserito=true');
    }
?>