<?php
    session_start() ;
    require "includes/libreria.php";
    if((empty($_POST['username'])) || (empty($_POST['password'])))
        header('Location:login.php?errore=problemainput');
    else{
        /*eseguo il login:
        /*ora si cerca se esiste l'utente nel database*/
        $hashed_pwd = login($_POST['username']);
        if(password_verify($_POST['password'], $hashed_pwd)){
                header("Location: ".$_SESSION['last_page']);
                $_SESSION['username'] = $_POST['username']; // si inserisce il nome utente
                $_SESSION['id_user']=get_id_user($_POST['username']);
            } else {
                header('Location:login.php?errore=noncorretti');
            }
    }
?>