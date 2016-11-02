<!DOCTYPE html>
<html lang="fr">
	<!-- HEAD -->
	<head>
		<meta charset="UTF-8" />
		<title>Comparateur de web services de géocodage - la consultation</title>
		<!-- CSS -->
		<style>
			table.tbl_bdd{ /* style des tableaux */
				border-collapse: collapse;
				font-weight: normal;
				text-align: center;
				margin-left: auto;
				margin-right: auto;
				width: 50%;
			}
			table.tbl_bdd,th{ /* style des cellules */
				border: 0.5px solid grey;
				height: 25px;
			}
			table.tbl_bdd,td{ /* style des cellules */
				border: 0.5px solid grey;
			}
			div,p.compteur{ /* style des compteurs des lignes des tableaux issus de la BDD */
				font-weight: normal;
			}
			div,p.adr_find{ /* style du resultat de la recherche */
				font-weight: normal;
			}
			div#map{ /* style de la carte */
				width: auto;
				height: 350px;
				border: 0.5px solid grey;
			}
			body{ /* style du body */
				font-family: sans-serif; /* police */
			}
		</style>
		<!-- JAVASCRIPT -->
		<script type="text/javascript" src="js-marker-clusterer-gh-pages/src/markerclusterer.js"></script> <!-- scritp MARKERCLUSTER -->
		<script>
			var markerCluster; // variable du MARKERCLUSTERER
			var markers = []; // tableau des markers pour le MARKERCLUSTERER
			function afficher(){ // signature
				alert("Projet réalisé par Rudolf MILLET dans le cadre du projet Web Mapping (ING2 2015/2016) !");
			}
			var map; // variable pour la carte google
			function initialize(){ // lors du chargement de la page
				for(var i=1;i<=nb_ligne_geo;i++){ // parcours des geocodages
					if(row_geo[i][1]==number){ // si il s agit du geocodage de l adresse
						var latlng = new google.maps.LatLng(parseFloat(row_geo[i][3]),parseFloat(row_geo[i][4])); // extration des coordonnes
						var mapOptions = { // options de la carte
							zoom:17,
							center:latlng
						}
						break; // des qu on a un couple de coordonnees on arrete la boucle for
					}else if(i==nb_ligne_geo){ // sinon on creer la carte de base
						var latlng = new google.maps.LatLng(48.849335,2.607764);
							var mapOptions = {
							zoom:14,
							center:latlng
						}
					}
				}
				map = new google.maps.Map(document.getElementById("map"),mapOptions); // carte Google
				// affichage de toutes les coordonnees de l adresse
				for(var i=1;i<=nb_ligne_geo;i++){ // parcours des geocodages
					if(row_geo[i][1]==number){ // si c est le geocodage de l adresse choisie
						var bulle = row_geo[i][2]; // afficher le service
						console.log(bulle); // affiche l info bulle
						var loc = { lat: parseFloat(row_geo[i][3]), lng: parseFloat(row_geo[i][4]) }; // extraction des coordonnees
						console.log(loc); // affiche la localisation
						addMarker(loc,map,bulle); // ajout du marker
					}			
				}
				markerCluster = new MarkerClusterer(map, markers); // creation du MARKERCLUSTERER
			}
		</script>
	</head>
	<!-- BODY -->
	<body onload='javascript:initialize()'>
		<h2 onclick='javascript:afficher();'><center>Comparateur de web services de géocodage - la consulatation<center></h2> <!-- titre -->
		<div id="map"></div> <!-- carte -->
		<br/>
		<!-- formulaire -->
		<fieldset><center>
			<legend>Recherche dans la BDD</legend><br/>
			<form method="get" action="recherche.php">
				<label>Numéro : <input type="text" name ="numero" value="" /></label>
				<label>Voirie : <input type="text" name ="voirie" value="" /></label>
				<label>Code postal : <input type="text" name ="code_postal" value="" /></label>
				<label>Ville : <input type="text" name ="ville" value="" /></label>
				<label>Pays : <input type="text" name ="pays" value="" /></label>
				<input type="submit" value="Envoyer" />
			</form>
		<center></fieldset>
		<?php
			// phpinfo(); // informations sur le PHP charge
			/*echo "<div><p><center>Chargement de l'extension 'pgsql' : <strong>";
			echo extension_loaded('pgsql') ? 'Yes':'No'; // test by adding this in your index.php
			echo "<strong><center></p></div><br/>";*/
			$menu_choix = extension_loaded('pgsql');
			// SWITCH
			switch ($menu_choix){ // menu en fonction du chargement de l extension
				case true: // si l extension pgsql a ete chargee
					$link = pg_connect("host=localhost port=5432 dbname=adresse_geocodage user=postgres password=''"); // host=localhost port=5432 dbname=adresse_geocodage user=postgres password=''
					if(!$link){ // si la connexion a echouee
						//echo "<div><p><center>Erreur de connexion : <strong>" . print pg_last_error($link) . "<strong><center></p></div><br/>"; // afficher l erreur de connexion
					}else{
					    //print "<div><p><center>Connexion réussie au port : <strong>" . pg_host($link) . "<strong><center></p></div><br/>"; // affiche le succes de la connexion
					    // EXTRACTION de la BDD des adresses
					    $result_adresse = pg_query($link, "SELECT * FROM adresse"); // requete SQL
					    if (!$result_adresse) { // si la requete n a pas aboutie
		  					//echo "<div><p><center><strong>Erreur de requête SQL (adresse) !<strong><center></p></div><br/>"; // affichage du warning
						}
						else{
							/*echo "<div><p><center><strong>Nombre de lignes du résultat de la requête (SELECT * FROM adresse) : ";
							echo pg_num_rows($result_adresse); // nombre de lignes du resultat de la requete
							echo "<strong><center></p></div><br/>";*/
							$nb_ligne_adr = pg_num_rows($result_adresse); // stocke le nombre de lignes du resultat de la requete
							$i = 1;
							/*echo "<table class='tbl_bdd'>";
							echo "<tr><th>Adresse n°</th><th>Numéro</th><th>Voie</th><th>Code postal</th><th>Ville</th><th>Pays</th></tr>"; // entete*/
							while($row = pg_fetch_row($result_adresse)){ // on accede aux valeurs de la ligne par leur indice dans le tableau
								// AFFICHAGE des adresses
								/*echo "<tr>";
								echo "<td>";
								//echo "Adresse n°: ";
								echo $row[1];
								echo "</td><td>";
								//echo "Numéro: ";
								echo $row[2];
								echo "</td><td>";
								//echo "Voie: ";
								echo $row[3];
								echo "</td><td>";
								//echo "Code postal: ";
								echo $row[4];
								echo "</td><td>";
								//echo "Ville: ";
								echo $row[5];
								echo "</td><td>";
								//echo "Pays: ";
								echo $row[6];
								echo "</td>";
								echo "</tr>";*/
								// STOCKAGE des adresses
								$row_adr[$i][1] = $row[1];
								$row_adr[$i][2] = $row[2];
								$row_adr[$i][3] = $row[3];
								$row_adr[$i][4] = $row[4];
								$row_adr[$i][5] = $row[5];
								$row_adr[$i][6] = $row[6];
								$i++;
							}
							/*echo "</table>";
							echo "<br/>";*/
						}
						// EXTRACTION de la BDD des geocodages
					    $result_geocodage = pg_query($link, "SELECT * FROM geocodage"); // requete SQL
					    if (!$result_geocodage) { // si la requete n a pas aboutie
		  					//echo "<div><p><center><strong>Erreur de requête SQL (geocodage) !<strong><center></p></div><br/>"; // affichage du warning
						}
						else{
							/*echo "<div><p><center><strong>Nombre de lignes du résultat de la requête (SELECT * FROM geocodage) : ";
							echo pg_num_rows($result_geocodage); // nombre de lignes du resultat de la requete
							echo "<strong><center></p></div><br/>";*/
							$nb_ligne_geo = pg_num_rows($result_geocodage); // stocke le nombre de lignes du resultat de la requete
							$i = 1;
							/*echo "<table class='tbl_bdd'>";
							echo "<tr><th>Adresse n°</th><th>Service de géocodage</th><th>Latitude</th><th>Longitude</th></tr>"; // entete*/
							while($row = pg_fetch_row($result_geocodage)){ // on accede aux valeurs de la ligne par leur indice dans le tableau
								// AFFICHAGE des geocodages
								/*echo "<tr>";
								echo "<td>";
								//echo "Adresse n°: ";
								echo $row[1];
								echo "</td><td>";
								//echo "Service de géocodage: ";
								echo $row[2];
								echo "</td><td>";
								//echo "Latitude: ";
								echo $row[3];
								echo "</td><td>";
								//echo "Longitude: ";
								echo $row[4];
								echo "</td>";
								echo "</tr>";*/
								// STOCKAGE des geocodages
								$row_geo[$i][1] = $row[1];
								$row_geo[$i][2] = $row[2];
								$row_geo[$i][3] = $row[3];
								$row_geo[$i][4] = $row[4];
								$i++;
							}
							/*echo "</table>";
							echo "<br/>";*/
						}
					}
				    if(!pg_close($link)){ // cette fonction retourne TRUE en cas de succes ou FALSE si une erreur survient 
		       			//print "<div><p><center>Impossible de fermer la connexion au port " . pg_host($link) . ": <strong>" . pg_last_error($link) . "<strong><center></p></div><br/>";
		   			}else{
		        		//print "<div><p><center>Déconnexion de la base de données réussie !<center></p></div><br/>";
		   			}
		   			break;
		   		case false: // si l extension pgsql n a pas ete chargee
		   			// STOCKAGE des adresses
		   			$row_adr[1][1] = 1;
					$row_adr[1][2] = 15;
					$row_adr[1][3] = "Boulevard Copernic";
					$row_adr[1][4] = 77420;
					$row_adr[1][5] = "CHAMPS-SUR-MARNE";
					$row_adr[1][6] = "FRANCE";
					////////////////
					$row_adr[2][1] = 2;
					$row_adr[2][2] = 4;
					$row_adr[2][3] = "Allée Buissonnière";
					$row_adr[2][4] = 77186;
					$row_adr[2][5] = "NOISIEL";
					$row_adr[2][6] = "FRANCE";
					////////////////
					$row_adr[3][1] = 3;
					$row_adr[3][2] = 6;
					$row_adr[3][3] = "Avenue Blaise Pascal";
					$row_adr[3][4] = 77420;
					$row_adr[3][5] = "CHAMPS-SUR-MARNE";
					$row_adr[3][6] = "FRANCE";
					////////////////
					$row_adr[4][1] = 4;
					$row_adr[4][2] = 83;
					$row_adr[4][3] = "Cours des Roches";
					$row_adr[4][4] = 77186;
					$row_adr[4][5] = "NOISIEL";
					$row_adr[4][6] = "FRANCE";
					////////////////
					$row_adr[5][1] = 5;
					$row_adr[5][2] = 5;
					$row_adr[5][3] = "Avenue des Pyramides";
					$row_adr[5][4] = 77420;
					$row_adr[5][5] = "CHAMPS-SUR-MARNE";
					$row_adr[5][6] = "FRANCE";
					////////////////
					$row_adr[6][1] = 6;
					$row_adr[6][2] = 7;
					$row_adr[6][3] = "Boulevard Pierre Carle";
					$row_adr[6][4] = 77186;
					$row_adr[6][5] = "NOISIEL";
					$row_adr[6][6] = "FRANCE";
					////////////////
					$nb_ligne_adr = count($row_adr); // nombre d adresses = 6
					/*echo "<div><p><center><strong>Nombre de lignes : ";
					echo $nb_ligne_adr; // nombre de lignes
					echo "<strong><center></p></div><br/>";*/
					// AFFICHAGE des adresses
					/*echo "<table class='tbl_bdd'>";
					echo "<tr><th>Adresse n°</th><th>Numéro</th><th>Voie</th><th>Code postal</th><th>Ville</th><th>Pays</th></tr>"; // entete
					for($i=1;$i<=$nb_ligne_adr;$i++){
						echo "<tr>";
						for($j=1;$j<=6;$j++){
							echo "<td>";
							echo $row_adr[$i][$j];
							echo "</td>";
						}
						echo "</tr>";
					}
					echo "</table>";
					echo "<br/>";*/
					// STOCKAGE des geocodages
					$row_geo[1][1] = 1;
					$row_geo[1][2] = "Google";
					$row_geo[1][3] = 48.840438;
					$row_geo[1][4] = 2.586477;
					////////////////
					$row_geo[2][1] = 1;
					$row_geo[2][2] = "Bing";
					$row_geo[2][3] = 48.8407;
					$row_geo[2][4] = 2.58612;
					////////////////
					$row_geo[3][1] = 2;
					$row_geo[3][2] = "Google";
					$row_geo[3][3] = 48.843175;
					$row_geo[3][4] = 2.622332;
					////////////////
					$row_geo[4][1] = 2;
					$row_geo[4][2] = "Bing";
					$row_geo[4][3] = 48.84354;
					$row_geo[4][4] = 2.62291;
					////////////////
					$row_geo[5][1] = 2;
					$row_geo[5][2] = "OSM";
					$row_geo[5][3] = 48.8433817;
					$row_geo[5][4] = 2.6229902;
					////////////////
					$row_geo[6][1] = 3;
					$row_geo[6][2] = "Google";
					$row_geo[6][3] = 48.840593;
					$row_geo[6][4] = 2.587247;
					////////////////
					$row_geo[7][1] = 3;
					$row_geo[7][2] = "Bing";
					$row_geo[7][3] = 48.84058;
					$row_geo[7][4] = 2.58697;
					////////////////
					$row_geo[8][1] = 4;
					$row_geo[8][2] = "Google";
					$row_geo[8][3] = 48.844872;
					$row_geo[8][4] = 2.616269;
					////////////////
					$row_geo[9][1] = 4;
					$row_geo[9][2] = "Bing";
					$row_geo[9][3] = 48.8439;
					$row_geo[9][4] = 2.61549;
					////////////////
					$row_geo[10][1] = 4;
					$row_geo[10][2] = "OSM";
					$row_geo[10][3] = 48.8437252;
					$row_geo[10][4] = 2.6153751;
					////////////////
					$row_geo[11][1] = 5;
					$row_geo[11][2] = "Google";
					$row_geo[11][3] = 48.853581;
					$row_geo[11][4] = 2.584531;
					////////////////
					$row_geo[12][1] = 5;
					$row_geo[12][2] = "Bing";
					$row_geo[12][3] = 48.85364;
					$row_geo[12][4] = 2.58407;
					////////////////
					$row_geo[13][1] = 6;
					$row_geo[13][2] = "Google";
					$row_geo[13][3] = 48.856864;
					$row_geo[13][4] = 2.624098;
					////////////////
					$row_geo[14][1] = 6;
					$row_geo[14][2] = "Bing";
					$row_geo[14][3] = 48.85631;
					$row_geo[14][4] = 2.62386;
					////////////////
					$row_geo[15][1] = 6;
					$row_geo[15][2] = "OSM";
					$row_geo[15][3] = 48.8561663;
					$row_geo[15][4] = 2.6248955;
					////////////////
					$nb_ligne_geo = count($row_geo); // nombre de geocodages = 15
					/*echo "<div><p><center><strong>Nombre de lignes : ";
					echo $nb_ligne_geo; // nombre de lignes
					echo "<strong><center></p></div><br/>";*/
					// AFFICHAGE des geocodages
					/*echo "<table class='tbl_bdd'>";
					echo "<tr><th>Adresse n°</th><th>Service de géocodage</th><th>Latitude</th><th>Longitude</th></tr>"; // entete
					for($i=1;$i<=$nb_ligne_geo;$i++){
						echo "<tr>";
						for($j=1;$j<=4;$j++){
							echo "<td>";
							echo $row_geo[$i][$j];
							echo "</td>";
						}
						echo "</tr>";
					}
					echo "</table>";
					echo "<br/>";*/
		   			break;
		   	}
		?>
		<script type="text/javascript">
			// VARIABLE PHP a placer ici pour le bon chargement
			var nb_ligne_geo = <?php echo $nb_ligne_geo; ?>; // nombre de lignes dans la table geocodage
			console.log("Nombre de ligne du tableau des géocodages : " + nb_ligne_geo);
			var row_geo = <?php echo json_encode($row_geo); ?>; // tableau des geocodages
			console.log(row_geo); // row_geo[1][2]
			var number = null; // pour l initialisation de la carte
			console.log(number);
		</script>
		<?php
			// initialisation
		   	$numero = $_GET['numero'];
			$voirie = $_GET['voirie'];
			$code_postal = $_GET['code_postal'];
			$ville = $_GET['ville'];
			$pays = $_GET['pays'];
			$affichage = false; // permission pour afficher le resultat sur la carte
			// affichage de la recherche
			echo "<div><p><center>Votre recherche est : <strong>";
			echo $numero;
			echo " ";
			echo $voirie;
			echo ", ";
			echo $code_postal;
			echo " ";
			echo $ville;
			echo ", ";
			echo $pays;
			echo "<strong><center></p></div>";
			// afficahge de la reponse
			for($i=1;$i<=$nb_ligne_adr;$i++){ // parcours les adresses
				if($row_adr[$i][6]==$pays){ // si le pays existe
					if($row_adr[$i][5]==$ville){ // si la ville existe
						if($row_adr[$i][4]==$code_postal){ // si le code postal existe
							if($row_adr[$i][3]==$voirie){ // si la voirie existe
								if($row_adr[$i][2]==$numero){ // si le numero existe
									echo "<div><p id='adr_find'><center>Adresse n°";
									echo $row_adr[$i][1];
									echo " trouvée : <strong>";
									echo $row_adr[$i][2];
									echo " ";
									echo $row_adr[$i][3];
									echo ", ";
									echo $row_adr[$i][4];
									echo " ";
									echo $row_adr[$i][5];
									echo ", ";
									echo $row_adr[$i][6];
									echo "<strong><center></p></div><br/>";
									$number = $row_adr[$i][1]; // numero de l adresse trouvee
									$affichage = true; // permission pour afficher le resultat sur la carte
									break; // pour le bon chargement des script JS !
								}elseif($i==$nb_ligne_adr){ // sinon si on a fini de parcourir les adresses
									echo "<div><p><center><strong>Adresse introuvable dans la BDD !<strong><center></p></div>";
									break; // pour le bon chargement des script JS !
								}
							}elseif($i==$nb_ligne_adr){ // sinon si on a fini de parcourir les adresses
								echo "<div><p><center><strong>Adresse introuvable dans la BDD !<strong><center></p></div>";
								break; // pour le bon chargement des script JS !
							}
						}elseif($i==$nb_ligne_adr){ // sinon si on a fini de parcourir les adresses
							echo "<div><p><center><strong>Adresse introuvable dans la BDD !<strong><center></p></div>";
							break; // pour le bon chargement des script JS !
						}
					}elseif($i==$nb_ligne_adr){ // sinon si on a fini de parcourir les adresses
						echo "<div><p><center><strong>Adresse introuvable dans la BDD !<strong><center></p></div>";
						break; // pour le bon chargement des script JS !
					}
				}elseif($i==$nb_ligne_adr){ // sinon si on a fini de parcourir les adresses
					echo "<div><p><center><strong>Adresse introuvable dans la BDD !<strong><center></p></div>";
					break; // pour le bon chargement des script JS !
				}
			}
		?>
		<!-- JAVASCRIPT -->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"> // chargement de l API Google
		</script>
		<script type="text/javascript">
			// VARIABLE PHP
			var affichage = <?php echo $affichage; ?>; // permission pour afficher le resultat sur la carte
			if(affichage){ // transfert/copie PHP -> JS
				var number = <?php echo $number; ?>; // converti le numero de l adresse
				console.log(number); // affiche le numero de l adresse
				/*var numero = <?php echo $numero; ?>;
				console.log(numero);
				var voirie = <?php echo $voirie; ?>;
				console.log(voirie);
				var code_postal = <?php echo $code_postal; ?>;
				console.log(code_postal);
				var ville = <?php echo $ville; ?>;
				console.log(ville);
				var pays = <?php echo $pays; ?>;
				console.log(pays);*/
			}
			// AFFICHAGE DE TOUS LES MARKERS
			function addMarker(location,map,bulle){ // Adds a marker to the map.
				// Add the marker at the clicked location, and add the next-available label
				// from the array of alphabetical characters.
				var infowindows = new google.maps.InfoWindow({ // nouvelle info bulle
					content: bulle
				});
				var marker = new google.maps.Marker({ // nouveau marker
					position: location,
					label: "",
					map: map
				});
				markers.push(marker); // ajoute le marker au tableau des markers pour le MARKERCLUSTERER
				console.log("Liste des markers : "+markers); // affichage de la liste des markers du MARKERCLUSTERER
				marker.addListener('click',function(){ // affiche l info bulle au clic
					infowindows.open(map,marker);
				})
			}
		</script>
	</body>
</html>