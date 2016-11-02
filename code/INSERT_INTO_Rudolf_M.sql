INSERT INTO adresse (id_adresse,numero,voie,code_postal,ville,pays) /* insert des echantillons d adresse */
VALUES 	('1','15','Boulevard Copernic','77420','CHAMPS-SUR-MARNE','FRANCE'),
	('2','4','Allée Buissonnière','77186','NOISIEL','FRANCE'),
	('3','6','Avenue Blaise Pascal','77420','CHAMPS-SUR-MARNE','FRANCE'),
	('4','83','Cours des Roches','77186','NOSIEL','FRANCE'),
	('5','5','Avenue des Pyramides','77420','CHAMPS-SUR-MARNE','FRANCE'),
	('6','7','Boulevard Pierre Carle','77186','NOISIEL','FRANCE');

INSERT INTO geocodage (id_adresse,nom_service,latitude,longitude) /* insert des echantillons de geocodage */
VALUES 	('1','Google','48.840438','2.586477'),
	('1','Bing','48.8407','2.58612'),
	('2','Google','48.843175','2.622332'),
	('2','Bing','48.84354','2.62291'),
	('2','OSM','48.8433817','2.6229902'),
	('3','Google','48.840593','2.587247'),
	('3','Bing','48.84058','2.58697'),
	('4','Google','48.844872','2.616269'),
	('4','Bing','48.8439','2.61549'),
	('4','OSM','48.8437252','2.6153751'),
	('5','Google','48.853581','2.584531'),
	('5','Bing','48.85364','2.58407'),
	('6','Google','48.856864','2.624098'),
	('6','Bing','48.85631','2.62386'),
	('6','OSM','48.8561663','2.6248955');