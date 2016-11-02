CREATE TABLE geocodages
(
  id_adresse integer,
  id_service integer,
  latitude real,
  longitude real
)

CREATE TABLE services
(
  id integer,
  numero integer,
  voie text,
  code_postal integer,
  ville text
)

CREATE TABLE adresse
(
  numero integer,
  id integer,
  voie text,
  code_postal integer,
  ville text,
  latitude real,
  longitude real,
)