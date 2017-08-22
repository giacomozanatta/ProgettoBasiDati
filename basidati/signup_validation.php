<?php
    session_start() ;
    require "includes/libreria.php";
    function preparapwd($password){
            return password_hash($password, PASSWORD_BCRYPT);
    }

    if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']))
        header('Location:signup.php?errore=problemainput');
    else{
        /*controllo data*/
        if(!empty($_POST['data_nascita']) && !controlla_data($_POST['data_nascita']))
            header('Location:signup.php?errore=data');
        else{
            /*controllo se esiste gia un utente con l'username scelto*/
            if(user_esiste($_POST['username']))
                header('Location:signup.php?errore=useresistente');
            else if(mail_esiste($_POST['email']))
                header('Location:signup.php?errore=mailesistente');
            else{
                if(empty($_POST["data_nascita"]))
                    esegui_registrazione($_POST['username'],preparapwd($_POST['password']), $_POST['email'], $_POST['nome'], $_POST['cognome']);
                else
                    esegui_registrazione_con_data($_POST['username'],preparapwd($_POST['password']), $_POST['email'], $_POST['data_nascita'], $_POST['nome'], $_POST['cognome']);
                header("Location:signup.php?registrato=true");
            }
        }
    }
?>