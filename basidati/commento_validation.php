<?php
    session_start();
    include "includes/libreria.php";
    if(empty($_POST['id_recensione']))
        header("Location: index.php");
        //echo $_SESSION['last_page'];
    else if(empty($_POST['testo']))
            header("Location: recensione.php?id=".$_POST['id_recensione']."&errore=notesto");
    else{
        header("Location: recensione.php?id=".$_POST['id_recensione']);
        /*calcolo la data corrente ed inserisco il commento*/
        $data = date('Y-m-d h:i:s', time());
        inserisci_commento($_POST["testo"], $_SESSION["id_user"], $_POST["id_recensione"], $_POST["id_commento_padre"], $data);
    }
?>

