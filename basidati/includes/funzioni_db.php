<?php
    //CONNESSIONE: permette di connettersi al server*/
    function connessione() {
        $connection = new PDO('pgsql:host=dblab.dsi.unive.it;port=5432;dbname=XXXXXX','XXXXXX','XXXXXX');
        $connection -> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
    //LOGIN: esegue il login
    function login($username){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select esegui_login(?)');
            $statement->execute(array($username));
            return $statement->fetch(PDO::FETCH_NUM)[0];
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET ID USER: ritorna l'id di un utente, dato l'username
    function get_id_user($username){
           try{
                $dbconn = connessione();
                $statement = $dbconn->prepare("select get_id(?)");
                $statement->execute(array($username));
                return $statement->fetch(PDO::FETCH_NUM)[0];
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //USER ESISTE: verifica se l'username esiste
    function user_esiste($username){
        try{
                $dbconn = connessione();
                $statement = $dbconn->prepare('select username_esistente(?)');
                $statement->execute(array($username));
                return $statement->fetch(PDO::FETCH_NUM)[0];
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //MAIL ESISTE: verifica se l'indirizzo mail esiste
    function mail_esiste($email){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select mail_esistente(?)');
            $statement->execute(array($email));
            return $statement->fetch(PDO::FETCH_NUM)[0];
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //ESEGUI REGISTRAZIONE: registra l'utente del database
    function esegui_registrazione($username, $hashed_pwd, $email, $nome, $cognome){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select inserisci_utente(?, ?, ?, ?,  ?)');
            $statement->execute(array($username,$hashed_pwd, $email, $nome, $cognome));
        } catch (PDOException $e) { echo $e->getMessage(); }
    }

    function esegui_registrazione_con_data($username, $hashed_pwd, $email, $data, $nome, $cognome){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select inserisci_utente_con_data(?, ?, ?, ?,?,?)');
            $statement->execute(array($username,$hashed_pwd, $email, $data, $nome, $cognome));
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET AUTORI: ritorna un array associativo contentente le informazioni di tutti gli autori
    function get_autori(){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select cognome, nome, id from progetto_db.autori ORDER BY cognome, nome');
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET LIBRI ORDINATI: ritorna tutti i libri all'interno del DB ordinato in un determinato modo
    function get_libri_ordinati($ordinamento){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare("select id, titolo, valutazione FROM progetto_db.libri ORDER BY $ordinamento");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET LIBRI CERCA: ricerca un libro per titolo
    function get_libri_cerca($titolo){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare("select id, titolo, valutazione FROM progetto_db.libri WHERE titolo ilike ?");
            $statement->execute(array("%$titolo%"));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //INS LIBRO: inserisce un libro nel DB
    function ins_libro($titolo, $trama, $genere, $id_user, $autori){
        try{
            $dbconn = connessione();
            if(empty($trama) && empty($genere)){
                $statement = $dbconn->prepare('select inserisci_libro(?,?)');
                $statement->execute(array($titolo, $id_user));
            }
            else if(!empty($trama) && !empty($genere)){
                $statement = $dbconn->prepare('select inserisci_libro_genere_trama(?,?,?,?)');
                $statement->execute(array($titolo, $trama, $genere, $id_user));
            }
            else if(!empty($trama) && empty($genere)){
                $statement = $dbconn->prepare('select inserisci_libro_trama(?,?,?)');
                $statement->execute(array($titolo, $trama, $id_user));
            }
            else if(empty($trama) && !empty($genere)){
                $statement = $dbconn->prepare('select inserisci_libro_genere(?,?,?)');
                $statement->execute(array($titolo, $genere, $id_user));
            }
            $id_libro =  $statement->fetch(PDO::FETCH_NUM)[0];
            foreach($autori as $autore){
                $statement = $dbconn->prepare('insert into progetto_db.libri_autori(id_autore, id_libro) values(?, ?)');
                $statement->execute(array($autore, $id_libro));
            }
        } catch (PDOException $e) { echo $e->getMessage();}
    }
    //INS AUTORE: inserisce un autore nel DB
    function ins_autore($nome, $cognome, $nazionalita, $data_n, $user_id){
        try{
            $dbconn = connessione();
            if(empty($nazionalita) && empty($data_n)){
                $statement = $dbconn->prepare('select inserisci_autore(?,?, ?)');
                $statement->execute(array($nome, $cognome, $user_id));
            }
            else if(!empty($nazionalita) && !empty($data_n)){
                $statement = $dbconn->prepare('select inserisci_autore_nazionalita_data(?,?,?,?,?)');
                $statement->execute(array($nome, $cognome, $nazionalita, $data_n, $user_id));
            }
            else if(!empty($nazionalita) && empty($data_n)){
                $statement = $dbconn->prepare('select inserisci_autore_nazionalita(?,?,?,?)');
                $statement->execute(array($nome, $cognome, $nazionalita, $user_id));
            }
            else if(empty($nazionalita) && !empty($data_n)){
                $statement = $dbconn->prepare('select inserisci_autore_data(?,?,?,?)');
                $statement->execute(array($nome, $cognome, $data_n, $user_id));
            }
        } catch (PDOException $e) { echo $e->getMessage();}
    }
    //GET INFO LIBRO: ritorna le informazioni di un determinato libro
    function get_info_libro($id){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select titolo, genere, trama, username, id_user from progetto_db.libri join progetto_db.utenti on progetto_db.utenti.id=id_user where progetto_db.libri.id=?');
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET INFO UTENTE
    function get_info_utente($id){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select username, nome, cognome, data_nascita, email from progetto_db.utenti where id=?');
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];

        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET RECENSIONI UTENTI
    function get_recensioni_utente($id){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select id_recensione as id, progetto_db.recensioni.titolo as titolo, progetto_db.libri.titolo as titolo_libro from progetto_db.recensioni join progetto_db.utenti on progetto_db.utenti.id = id_autore 
            join progetto_db.libri on progetto_db.recensioni.id_libro = progetto_db.libri.id where id_autore=?');
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET AUTORI LIBRO: ritorna le informazioni degi autori di un libro
    function get_autori_libro($id){
            try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select nome, cognome from progetto_db.autori join progetto_db.libri_autori on id=id_autore where id_libro=?');
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET RECENSIONE: ritorna una recensione, specificata da un ID
    function get_recensione($id){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select progetto_db.recensioni.titolo, username, id_autore, testo, voto, progetto_db.libri.titolo as titolo_libro, progetto_db.libri.id as id_libro, punteggio from progetto_db.recensioni join progetto_db.utenti on id=id_autore join progetto_db.libri on progetto_db.libri.id=id_libro where id_recensione=?');
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET RECENSIONI LIBRO: ritorna tutte le recensioni di un determinato libro
    function get_recensioni_libro($id){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select id_recensione, titolo, username from progetto_db.recensioni join progetto_db.utenti on id=id_autore where id_libro=?');
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GIA RECENSITO: controlla se l'utente ha già recensito un libro
    function gia_recensito($id_libro, $username){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select id_recensione from progetto_db.recensioni  where id_libro=? and id_autore=?');
            $statement->execute(array($id_libro, $username));
            return !empty($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) { echo $e->getMessage(); } 
    }
    //INSERISCI RECENSIONE: inserisce una recensione all'interno del DB
    function inserisci_recensione($titolo, $testo, $valutazione, $id_username, $id_libro){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('insert into progetto_db.recensioni(titolo, testo, voto, id_autore, id_libro) values (?,?,?,?,?)');
            $statement->execute(array($titolo, $testo, $valutazione, $id_username, $id_libro));
            /*punteggio medio si aggiorna mediante trigger.*/
        } catch (PDOException $e) { echo $e->getMessage(); } 
    }
    //GET COMMENTI: ritorna i commenti di una recensione
    function get_commenti($id_rec){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select id_commento, testo, punteggio, data_commento, username from progetto_db.commenti join progetto_db.utenti on id_user=id where id_recensione=? and id_ref_comm is NULL order by data_commento');
            $statement->execute(array($id_rec));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //GET COMMENTI COMMENTO: ritorna i commenti di un determinato commento
    function get_commenti_commento($id_comm){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('select id_commento, testo, punteggio, data_commento, username from progetto_db.commenti join progetto_db.utenti on id_user=id where id_ref_comm=? order by data_commento');
            $statement->execute(array($id_comm));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
    //INSERISCI COMMENTO: inserisce un commento nel DB
    function inserisci_commento($testo, $id_user, $id_rec, $commento_padre, $data){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('insert into progetto_db.commenti(testo, id_user, id_recensione, id_ref_comm, data_commento) values (?,?,?,?,?)');
            $statement->execute(array($testo, $id_user, $id_rec, $commento_padre, $data));
        } catch (PDOException $e) { echo $e->getMessage(); } 
    }
    //INSERISCI VOTO COMMENTO: inserisce un voto al commento.
    //N.B: un utente può votare un commento una volta sola, se vuole rivotare, è necessario cancellare il voto vecchio
    function inserisci_voto_commento($id_user, $id_commento, $voto){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('delete from progetto_db.voti_commenti where id_utente=? and id_commento=?');
            $statement->execute(array($id_user, $id_commento));
            if($voto!=0){
                $statement = $dbconn->prepare('insert into progetto_db.voti_commenti (id_utente, id_commento, voto) values(?,?,?)');
                $statement->execute(array($id_user, $id_commento, $voto));
            }
        } catch (PDOException $e) { echo $e->getMessage(); } 
    }

    function inserisci_voto_recensione($id_user, $id_recensione, $voto){
        try{
            $dbconn = connessione();
            $statement = $dbconn->prepare('delete from progetto_db.voti_recensioni where id_utente=? and id_recensione=?');
            $statement->execute(array($id_user, $id_recensione));
            if($voto!=0){
                $statement = $dbconn->prepare('insert into progetto_db.voti_recensioni (id_utente, id_recensione, voto) values(?,?,?)');
                $statement->execute(array($id_user, $id_recensione, $voto));
            }
        } catch (PDOException $e) { echo $e->getMessage(); }
    }
?>