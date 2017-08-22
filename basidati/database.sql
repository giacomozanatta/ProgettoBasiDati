CREATE TABLE progetto_db.utenti(
    	id SERIAL,
    	username text not null UNIQUE,
    	password text not null,
    	nome text,
    	cognome text,
    	email text not null UNIQUE,
    	data_nascita date,
    	primary key(id));
    	

    CREATE TABLE progetto_db.libri(
    	id SERIAL,
    	titolo text,
			trama text,
			genere text,
			valutazione float(2) DEFAULT NULL,
    	id_user int,
    	primary key(id));
     
			alter table progetto_db.libri
				add foreign key (id_user) references progetto_db.utenti 
  			on delete set null;

    CREATE TABLE progetto_db.autori(
    	id SERIAL,
    	nome text not null,
    	cognome text not null,
    	data_nascita date,
			nazionalita text,
    	aggiunto_da int not null,
    	primary key(id));
    	
					alter table progetto_db.autori
						add foreign key (aggiunto_da) references progetto_db.utenti 
							on delete set null;

		CREATE TABLE progetto_db.libri_autori(
			id_autore int not null,
			id_libro int not null,
			primary key(id_autore, id_libro));
		);

		alter table progetto_db.libri_autori
		add foreign key (id_autore) references progetto_db.autori
						on delete set null;

		alter table progetto_db.libri_autori
		add foreign key (id_libro) references progetto_db.libri
						on delete set null;
			
    CREATE TABLE progetto_db.commenti(
    	id_commento SERIAL,
    	testo text NOT NULL,
    	punteggio int default 0,
    	id_user int NOT NULL,
    	id_recensione int not null,
    	id_ref_comm int,
			data_commento timestamp,
    	primary key(id_commento));
    	 
			alter table progetto_db.commenti
				add foreign key (id_user) references progetto_db.utenti 
							on delete set null;
			alter table progetto_db.commenti
				add foreign key (id_recensione) references progetto_db.recensioni
							on delete cascade;
			alter table progetto_db.commenti
				add foreign key (id_ref_comm) references progetto_db.commenti 
							on delete cascade;

    CREATE TABLE progetto_db.recensioni(
    	id_recensione SERIAL,
    	titolo text NOT NULL,
    	testo text NOT NULL,
    	voto int NOT NULL,
    	id_autore int NOT NULL,
			id_libro int NOT NULL,
    	primary key(id_recensione));

			alter table progetto_db.recensioni
				add foreign key (id_autore) references progetto_db.utenti 
							on delete set null;
			alter table progetto_db.recensioni
				add foreign key (id_libro) references progetto_db.libri 
							on delete cascade;

		CREATE TABLE progetto_db.voti_commenti(
			id_utente int NOT NULL,
			id_commento int NOT NULL,
			voto int NOT NULL,
			primary key(id_utente, id_commento));
		
		alter table progetto_db.voti_commenti
				add foreign key (id_utente) references progetto_db.utenti 
							on delete cascade;
			alter table progetto_db.voti_commenti
				add foreign key (id_commento) references progetto_db.commenti 
							on delete cascade;

		/*FUNZIONI DEL DATABASE*/

create or replace function esegui_login(u text) returns text as $$
declare
  pwd text;
begin
  select password into pwd from progetto_db.utenti where u = username;
  return pwd;
end;
$$ language plpgsql;

create or replace function get_id(u text) returns int as $$
declare
  id_user text;
begin
  select id into id_user from progetto_db.utenti where u = username;
  return id_user;
end;
$$ language plpgsql;

create or replace function inserisci_utente(u text, p text, e text, nom text, co text) returns void as $$
  insert into progetto_db.utenti (username, password, email, nome, cognome) values (u, p, e, nom, co);
$$ language sql;

create or replace function inserisci_utente_con_data(u text, p text, e text, d date, nom text, co text) returns void as $$
  insert into progetto_db.utenti (username, password, email, data_nascita, nome, cognome) values (u, p, e, d, nom, co);
$$ language sql;

create or replace function username_esistente(u text) returns boolean as $$
declare
  usr text;
begin
	select username into usr from progetto_db.utenti where u = username;
	if u = usr
		then return true;
		else return false;
	end if;
end;
$$ language plpgsql;

create or replace function mail_esistente(m text) returns boolean as $$
	declare
		mail text;
	begin
 	select email into mail from progetto_db.utenti where m = email;
	if m = mail
		then return true;
		else return false;
	end if;
end;
$$ language plpgsql;

create or replace function get_autori() returns autori as $$
 	select * from progetto_db.autori;
$$ language plpgsql;
//////////////////////////////////////////////////////////////////////////////////
create or replace function inserisci_libro_genere_trama(t text, tr text, ge text, us int) returns int as $$
declare
	i int;
begin
  insert into progetto_db.libri (titolo,trama, genere, id_user) values (t, tr, ge, us) returning id into i;
	return i;
end;
$$ language plpgsql;

create or replace function inserisci_libro_trama(t text, tr text, us int) returns int as $$
declare
	i int;
begin
  insert into progetto_db.libri (titolo,trama, id_user) values (t, tr, us) returning id  into i;
	return i;
end;
$$ language plpgsql;

create or replace function inserisci_libro_genere(t text, ge text, us int) returns int as $$
declare
	i int;
begin
  insert into progetto_db.libri (titolo, genere, id_user) values (t, ge, us) returning id into i;
	return i;
end;
$$ language plpgsql;

create or replace function inserisci_libro(t text, us int) returns int as $$
declare
	i int;
begin
  insert into progetto_db.libri (titolo, id_user) values (t, us) returning id into i;
	return i;
end;
$$ language plpgsql;

drop function inserisci_libro(text, int);
drop function inserisci_libro_genere(text, text, int);
drop function inserisci_libro_genere_trama(text, text, text, int);
drop function inserisci_libro_trama(text, text, int);
//////////////////////////////////////////////////////////////////////////////////////////////////////////
create or replace function inserisci_autore_nazionalita_data(n text, c text, na text, da date, us int) returns void as $$
  insert into progetto_db.autori (nome,cognome, nazionalita, data_nascita, aggiunto_da) values (n, c, na, da, us);
$$ language sql;

create or replace function inserisci_autore_data(n text, c text, da date, us int) returns void as $$
  insert into progetto_db.autori (nome,cognome, data_nascita, aggiunto_da) values (n, c, da, us);
$$ language sql;

create or replace function inserisci_autore_nazionalita(n text, c text, na text, us int) returns void as $$
  insert into progetto_db.autori (nome, cognome, nazionalita, aggiunto_da) values (n, c, na, us);
$$ language sql;

create or replace function inserisci_autore(n text, c text, us int) returns void as $$
  insert into progetto_db.autori (nome, cognome, aggiunto_da) values (n, c, us);
$$ language sql;


//TRIGGER PER AGIGORNAMENTO MEDIA LIBRO
create or replace function aggiorna_media_libro() returns trigger as $$
declare
  idl int = new.id_libro;
begin
	update progetto_db.libri set valutazione = (SELECT avg(voto) FROM progetto_db.recensioni where id_libro=idl) WHERE id=idl;
return new;
end
$$ language plpgsql;

drop trigger if exists trigger_aggiorna_media_libro on progetto_db.recensioni;
create trigger trigger_aggiorna_media_libro after insert on progetto_db.recensioni
  for each row execute procedure aggiorna_media_libro();

//TRIGGER PER AGGIORNAMENTO VOTO RECENSIONE
create or replace function aggiorna_voti_recensione() returns trigger as $$
declare
	idr int = new.id_recensione;
begin
		update progetto_db.recensioni set punteggio = 
						(select coalesce((select sum(voto) 
							from progetto_db.voti_recensioni 
								where id_recensione=idr),0))
		WHERE id_recensione=idr;
	return new;
end
$$ language plpgsql;


drop trigger if exists trigger_aggiorna_voti_recensione on progetto_db.voti_recensioni;
create trigger trigger_aggiorna_voti_recensione after insert on progetto_db.voti_recensioni
  for each row execute procedure aggiorna_voti_recensione();

create or replace function aggiorna_voti_recensione_after_delete() returns trigger as $$
declare
	idr int = old.id_recensione;
begin
		update progetto_db.recensioni set punteggio = 
						(select coalesce((select sum(voto) 
							from progetto_db.voti_recensioni 
								where id_recensione=idr),0))
		where id_recensione=idr;
	return old;
end
$$ language plpgsql;

drop trigger if exists trigger_aggiorna_voti_recensione_after_delete on progetto_db.voti_recensioni;
create trigger trigger_aggiorna_voti_recensione_after_delete after delete on progetto_db.voti_recensioni
  for each row execute procedure aggiorna_voti_recensione_after_delete();
	
//TRIGGER PER AGIGORNAMENTO VOTO COMNENTO

create or replace function aggiorna_voti_commento() returns trigger as $$
declare
	idc int = new.id_commento;
begin
		update progetto_db.commenti set punteggio = 
        (select coalesce((select sum(voto) 
        	from progetto_db.voti_commenti 
          	where id_commento=idc),0))
        	where id_commento=idc;
	return new;
end
$$ language plpgsql;


drop trigger if exists trigger_aggiorna_voti_commento on progetto_db.voti_commenti;
create trigger trigger_aggiorna_voti_commento after insert on progetto_db.voti_commenti
  for each row execute procedure aggiorna_voti_commento();

create or replace function aggiorna_voti_commento_after_delete() returns trigger as $$
declare
	idc int = old.id_commento;
begin
		update progetto_db.commenti set punteggio = 
        (select coalesce((select sum(voto) 
        	from progetto_db.voti_commenti 
          	where id_commento=idc),0))
        	where id_commento=idc;
	return old;
end
$$ language plpgsql;


drop trigger if exists trigger_aggiorna_voti_commento_after_delete on progetto_db.voti_commenti;
create trigger trigger_aggiorna_voti_commento_after_delete after delete on progetto_db.voti_commenti
  for each row execute procedure aggiorna_voti_commento_after_delete();
