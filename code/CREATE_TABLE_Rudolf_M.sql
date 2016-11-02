CREATE TABLE adresse(		/* TABLE DES ADRESSES */
	id SERIAL PRIMARY KEY,	/* identifiant */
	id_adresse integer,	/* identifiant de l adresse */
	numero integer,		/* numero */
	voie text,		/* type de voie */
	code_postal integer,	/* code postal */
	ville text,		/* ville */
	pays text		/* pays */
);

CREATE TABLE geocodage(		/* TABLE DES DIFFERENTS GEOCODAGES */
	id SERIAL PRIMARY KEY,	/* identifiant */
	id_adresse integer,	/* identifiant de l adresse */
	nom_service text,	/* nom du service de geocodage */
	latitude real,		/* latitude selon le geocodage */
	longitude real		/* longitude selon le geocodage */
)