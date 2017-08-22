<?php
    error_reporting(E_ALL & ~E_NOTICE);
    include "funzioni_db.php";
    //CONTROLLA DATA: ritorna true se la data è stata inserita correttamente, false altrimenti
    function controlla_data($date){
        $dt = explode('-', $date);
        if (!empty($dt[0]) && !empty($dt[1]) && !empty($dt[2]) && strlen($date)==10) {
            return checkdate((int) $dt[1], (int) $dt[2], (int) $dt[0]);
        } else {
            return false;
        }
    }
    //STAMPA LIBRI ORDINATI: stampa una tabella contenente i libri nel database, ordinati
    function stampa_libri_ordinati($ordinamento){
        $arr_libri = get_libri_ordinati($ordinamento);
        echo "<table border=0><tr><th>Libro</th><th>Valutazione media</th></tr>";
        foreach($arr_libri as $libro){
            echo '<tr><td><a href="libro.php?libro='.$libro["id"].'">'.$libro["titolo"].'</a></td><td><center>';
                if(empty($libro["valutazione"]))
                    echo "N/A";
                else
                    echo round($libro["valutazione"],1);
                echo "</center></td></tr>";
            }
        echo "</table>";
    }
    //STAMPA FORM CERCA: stampa il form per cercare i libri
    function stampa_form_cerca(){
           echo' <form name="cerca" method="get"action="cerca.php">
            Titolo: <input type="text" name="titolo"> 
            <input type="submit" value="cerca"> 
            </form>';
    } 
    //STAMPA RICERCA LIBRI PER: stampa una tabella contenente le informazioni dei libri cercati
    function  stampa_ricerca_libri_per($titolo){
            $arr_libri = get_libri_cerca($titolo);
            if(empty($arr_libri))
                echo '<br><font color=red>Nessun libro trovato!</font>';
            else{
                echo "<table border=0><tr><th>Libro</th><th>Valutazione media</th></tr>";
                foreach($arr_libri as $libro){
                    echo '<tr><td><a href="libro.php?libro='.$libro["id"].'">'.$libro["titolo"].'</a></td><td><center>';
                        if(empty($libro["valutazione"]))
                            echo "N/A";
                        else
                            echo round($libro["valutazione"],1);
                        echo "</center></td></tr>";
                    }
                echo "</table>";
            }
        }
    //STAMPA CHECKBOX AUTORI: stampa il form di scelta degli autori
    function stampa_checkbox_autori(){
        $arr_autori=get_autori();
        //print_r($arr_autori); 
        foreach($arr_autori as $aut)
            echo '<input type="checkbox" name="autori[]" value="'.$aut["id"].'"/>'.$aut["cognome"].' '.$aut["nome"].'<br>';
    }
    //STAMPA FORM NUOVA RECENSIONE: stampa il form per inserire una recensione
    function stampa_form_nuova_recensione($id){
        echo ' <form name="nuova_recensione" method="post" action="nuova_recensione_validation.php">
                    Titolo: <input type="text" name="titolo"><br>
                    Testo: <textarea name="testo"></textarea><br>
                    Punteggio:  <select name="punteggio">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                    <input type="hidden" name="id_libro" value="'.$id.'">
                    <input type="submit" value="Inserisci">
                </form>';
    }
    //STAMPA INFO LIBRO: stampa informazioni su un libro (titolo, autori, trama, inserito da)
    function stampa_info_libro($id){
        $arr_libro = get_info_libro($id);
        if(empty($arr_libro))
            echo "<font color=red>Errore: nessun libro trovato! :(</font>";
        else{
            echo '<p><h2>Titolo: <font color="darkblue">'.$arr_libro[0]["titolo"]."</font></h2><br>";
            echo "<em><b>Genere:</b></em> ".$arr_libro[0]["genere"]."<br>";
            echo "<em><b>Autori:</b></em> ";
            $arr_autori = get_autori_libro($id);
            foreach($arr_autori as $autore){
                echo $autore["cognome"]." ".$autore["nome"].",";
            }
            echo '<br> <div id="testo"><em><b>Trama:</em></b> '.$arr_libro[0]["trama"]."</div>";
            echo "<br><em><b>Inserito da:</b></em> <a href='utente.php?id=".$arr_libro[0]["id_user"]."'>".$arr_libro[0]["username"]."</a>";
            echo "<hr>";
            if(empty($_SESSION["id_user"])){
                $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                echo '<br><a href="login.php">Accedi</a> per recensire il libro!<br>';
            }
            else if(!gia_recensito($id, $_SESSION["id_user"])){
                if($_GET['errore']=='problemainput')
                    echo "<br><font color=red>Errore: tutti i campi sono obbligatori!</font>";
                echo "<br>Scrivi una recensione per questo libro!<br>";
                stampa_form_nuova_recensione($id);
            }
            else{
                echo "<br>Hai gia' recensito questo libro!<br>";
            }
            echo "<hr>";
            echo "<br><h4>RECENSIONI</h4>";
            $arr_recensioni = get_recensioni_libro($id);
            echo "<table border=1><tr><th>Titolo</th><th>Recensore</th>";
            foreach($arr_recensioni as $recensione)
                echo '<tr><td><a href="recensione.php?id='.$recensione["id_recensione"].'">'.$recensione["titolo"].'</a></td><td>'.$recensione["username"]."</td></tr>";
            echo "</table>"; 
        }
    } 
    //STAMPA INFO RECENSIONE: stampa informazioni sulla recensione scelta
    function stampa_info_recensione($id){
        $arr_recensione = get_recensione($id);
        echo "<h2>Recensione di: <a href='utente.php?id=".$arr_recensione["id_autore"]."'>".$arr_recensione["username"]."</a></h2>";
        echo '<h3>Titolo: <font color="darkblue">'.$arr_recensione["titolo"]."</font></h3>";
        echo '<br><em><b>Libro:</b></em> <a href="libro.php?libro='.$arr_recensione["id_libro"].'">'.$arr_recensione["titolo_libro"]."</a><br>";
        echo '<br></em> <div id="testo"><em><b>Testo:</b></em>'.$arr_recensione["testo"]."</div><br>";
        echo "<br><em><b>Voto:</b></em> ".$arr_recensione["voto"]."<br>";
        stampa_form_commenta_recensione($id, $arr_recensione["punteggio"]);

    }
    //STAMPA FORM COMMENTA RECENSIONE: stampa il form per permettere ad un utente di commentare una recensine
    function stampa_form_commenta_recensione($id_rec, $voto){
        
        echo "<em><b>Voto degli utenti:</b></em> ".$voto." |";
        if(empty($_SESSION["id_user"])){
            $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            echo '<br><a href="login.php">Accedi</a> per commentare!<br>';
        }
        else{
            echo '| <a href="vota_validation.php?recensione='.$id_rec.'&voto=1">+</a> / ';
            echo '<a href="vota_validation.php?recensione='.$id_rec.'&voto=0">0</a> / ';
            echo '<a href="vota_validation.php?recensione='.$id_rec.'&voto=-1">-</a> |';
            echo "<h4>Commenta</h4>";
            echo '<form name="commenta_recensione" method="post" action="commento_validation.php">
                Testo: <textarea name="testo" rows="1" cols="50"></textarea>
                <input type="hidden" name="id_recensione" value="'.$id_rec.'">
                <input type="submit" value="Commenta">
            </form>';
        }
    }
    //STAMPA FORM COMMENTA COMMENTO: permette di commentare un commento, e di votarlo.
    function stampa_form_commenta_commento($id_rec, $id_commento, $span, $voto){
        if(empty($_SESSION["id_user"])){
            $_SESSION['last_page']="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            echo stampa_tab($span).'<a href="login.php">Accedi</a> per commentare!<br>';
        }
        else{
            echo stampa_tab($span);
            echo '| <a href="vota_validation.php?commento='.$id_commento.'&voto=1">+</a> / ';
            echo '<a href="vota_validation.php?commento='.$id_commento.'&voto=0">0</a> / ';
            echo '<a href="vota_validation.php?commento='.$id_commento.'&voto=-1">-</a> | punteggio: '.$voto. '| ';
            echo '<form name="commenta_commento" method="post" action="commento_validation.php">
                '.stampa_tab($span).'<textarea name="testo" rows="1" cols="50"></textarea>
                <input type="hidden" name="id_recensione" value="'.$id_rec.'">
                <input type="hidden" name="id_commento_padre" value="'.$id_commento.'">
                <input type="submit" value="Commenta">
            </form>';
        }
    }
    //STAMPA TAB: stampa N tab in html 
    function stampa_tab($tab){
        $str ="";
        for($i=0; $i<$tab; $i++)
            $str = $str."&emsp;";
        return $str;
    }
    //STAMPA COMMENTI COMMENTO: stampa i commenti di un commento, e i commenti di ogni commento del commento padre
    function stampa_commenti_commento($id_rec,$id_comm, $span_int){
        $array_commenti = get_commenti_commento($id_comm);
        foreach($array_commenti as $commento){
            echo stampa_tab($span_int).$commento['data_commento'].' -> '.$commento['username'].": ".$commento['testo']."<br>";
            echo stampa_tab($span_int);
            stampa_form_commenta_commento($id_rec, $commento["id_commento"], $span_int, $commento["punteggio"]);
            $parent_comm=$commento["id_commento"];
            stampa_commenti_commento($id_rec, $parent_comm, $span_int+1);
        }
    }
    //STAMPA COMMENTI RECENSIONE: stampa i commenti di una recensione.Inoltre stamperà anche i commenti di ogni commento
    function stampa_commenti_recensione($id_rec){
        $array_commenti = get_commenti($id_rec);
        $span_int=2;
        foreach($array_commenti as $commento){
            echo stampa_tab(1).$commento['data_commento'].' -> '.$commento['username'].": ".$commento['testo']."<br>";
            echo stampa_tab(1);
            stampa_form_commenta_commento($id_rec, $commento["id_commento"], 1, $commento["punteggio"]);
            $parent_comm=$commento["id_commento"];
            stampa_commenti_commento($id_rec, $parent_comm, $span_int);
            echo "<hr>";
        }
    }
     function stampa_info_utente($id){
        $utente = get_info_utente($id);
        echo "<h2>".$utente['username']."</h2>";
        echo "<br><em><b>Nome: </b></em> ".$utente['nome'];
        echo '<br><em><b>Cognome:</b></em>'.$utente["cognome"];
        echo '<br><em><b>Data di nascita:</b></em> '.$utente["data_nascita"];
        echo '<br><em><b>Email:</b></em> '.$utente["email"];
        echo "<hr>";
        echo "<h3>Le tue recensioni:</h3>";
        stampa_recensioni_utente($id);
    }
    function stampa_recensioni_utente($id){
        $arr_recensioni=get_recensioni_utente($id);
        $i=1;
        foreach($arr_recensioni as $recensione){
            echo "$i. <a href='recensione.php?id=".$recensione['id']."'>".$recensione['titolo_libro']." (".$recensione['titolo'].")</a><br>";
            $i++;
        }
    }
?>