<?php
	session_start();

	$conn = new mysqli('localhost', 'root', '', 'my_trackergps');
	$data = date("d/m/Y");
    
    if (isset($_REQUEST['id'])){
    	$query = 'SELECT Veicoli.* FROM Veicoli WHERE idVeicolo = '.$_REQUEST['id'].''; //Prendo la macchina con l'id che mi è staro passato
        $result = $conn->query($query)->fetch_assoc();
    }else{
    	header('location: index.php');
    }
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Tracker GPS - Dettaglio</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
		<link rel="stylesheet" href="assets/css/main.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=[GOOGLE_API_KEY]"></script>
        <script>
        	function CustomOverlay(position, map, text) { //Funzione che genera il pop-up dei Km/h
              this.position = position;
              this.map = map;
              this.text = text;
              this.div = null;
              this.setMap(map);
            }

            CustomOverlay.prototype = new google.maps.OverlayView();

            CustomOverlay.prototype.onAdd = function() { //Modifica lo stile del pop-up
              var div = document.createElement("div");
              div.style.position = "absolute";
              div.style.color = "black";
              div.style.fontWeight = "bold";
              div.style.backgroundColor = "white";
              div.style.padding = "5px";
              div.style.borderRadius = "5px";
              div.style.boxShadow = "0px 2px 6px rgba(0, 0, 0, 0.3)";
              div.innerText = this.text;
              this.div = div;

              var panes = this.getPanes();
              panes.overlayLayer.appendChild(div);
            };

            CustomOverlay.prototype.draw = function() {
              var overlayProjection = this.getProjection();
              var position = overlayProjection.fromLatLngToDivPixel(this.position);

              var div = this.div;
              div.style.left = position.x + "px"; //Posiziona il pop-up sulla mappa
              div.style.top = position.y + "px";
            };

            CustomOverlay.prototype.onRemove = function() {
              this.div.parentNode.removeChild(this.div);
              this.div = null;
            };
          
          	$(document).ready(function() {

				focalizzaPunto();
            
				function initMap() {
					if (navigator.geolocation) { //Creo la mappa
						navigator.geolocation.getCurrentPosition(function(position) {
							var latitude = position.coords.latitude;
							var longitude = position.coords.longitude;
							map(latitude, longitude);
						});
					}
				}

				function focalizzaPunto(){ //Prendo le coordinate su dove si trova il marker
					$.ajax({
						url: 'update_singleCoords.php?id=<?php echo($_REQUEST['id']) ?>', 
						type: 'POST',
						success: function(response) {
							var data = JSON.parse(response);
							for (var i = 0; i < data.length; i++) {
								var row = data[i];
								map(parseFloat(row.latitudine), parseFloat(row.longitudine))
							}
						}
					});
				}

				function map(latitude, longitude) { //Stampo la mappa
					var map = new google.maps.Map(document.getElementById('mappa'), {
						zoom: 16,
						center: { //La centro sul marker
							lat: latitude,
							lng: longitude
						}
					});
						
					var markers = [];
                    var customOverlay = null;
                
                    
					function Coords(map) { //Funzione che aggiorna il marker
							for (var i = 0; i < markers.length; i++) { //Elimino tutti i markr
								markers[i].setMap(null);
                                customOverlay.setMap(null);
							}
							
							$.ajax({
								url: 'update_singleCoords.php?id=<?php echo($_REQUEST['id']) ?>', //Prendo le coordinate del marker da un file PHP esterno
								type: 'POST',
								success: function(response) {
									var data = JSON.parse(response);
									for (var i = 0; i < data.length; i++) { //Stampo tutti i marker
										var row = data[i];
										var marker = new google.maps.Marker({
											position: {
												lat: parseFloat(row.latitudine),
												lng: parseFloat(row.longitudine)
											},
											map: map,
											icon: {
												path: google.maps.SymbolPath.CIRCLE,
												fillColor: row.colore,
												fillOpacity: 1,
												strokeColor: 'white',
												strokeWeight: 1,
												scale: 10
											}
										});
                                        
                                        markers.push(marker); //Inserisco i marker in un array
                                        
                                       	var markerPosition = new google.maps.LatLng(row.latitudine, row.longitudine);
										var overlayText = row.velocita+" Km/h";

										customOverlay = new CustomOverlay(markerPosition, map, overlayText); //Richiamo la funzione per generare i pop-up
                                        
										
									}
								}
							});
					}

					Coords(map);
					setInterval(function() {
						Coords(map); //Rigenero i marker ogni secondo richiamando la funzione
					}, 1000);
				}

            	initMap();
          	});
        </script>
	</head>
	<body class="single is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="index.php">Home</a></h1>
						<nav class="links">
							<ul>
								<li><a href="updateCar.php">Modifica Macchina</a></li>
								<li><a href="uploadImage.php">Caricamento Media</a></li>
							</ul>
						</nav>
						<nav class="main">
							<ul>
								<li class="search">
									<a class="fa-search" href="#search">Search</a>
									<form id="search" method="get" action="#">
										<input type="text" name="query" placeholder="Search" />
									</form>
								</li>
								<li class="menu">
									<a class="fa-bars" href="#menu">Menu</a>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Menu -->
					<section id="menu">

						<!-- Search -->
							<section>
								<form class="search" method="get" action="#">
									<input type="text" name="query" placeholder="Cerca" />
								</form>
							</section>

						<!-- Links -->

						<!-- Actions -->
							<section>
								<ul class="actions stacked">
                                	 <li><a href="updateCar.php" class="button large fit">Modifica Macchina</a></li>
                                     <li><a href="uploadImage.php" class="button large fit">Caricamento Media</a></li>
								</ul>
                                <hr>
								<ul class="actions stacked">
                                	<?php 
                                    	if(isset($_SESSION['user'])){
                                        	echo('
												<li><a href="login.php" class="button large fit">Logout</a></li>
                                              	');
                                    	}else{
                                        	echo('
												<li><a href="login.php" class="button large fit">Log In</a></li>
                                              	');
                                        }
                                     ?>
								</ul>
							</section>

					</section>

				<!-- Main -->
					<div id="main">

						<!-- Post -->
							<article class="post">
								<header>
									<div class="title">
										<h2><a href="#"><?php echo($result['nome']); ?></a></h2>
										<p><?php echo($result['proprietario']); ?></p>
									</div>
									<div class="meta">
										<time class="published" datetime="2015-11-01"><? echo($data) ?></time>
										<?php 
                                        	if(isset($_SESSION['user'])){
                                            	echo('<a class="author"><span class="name">'.$_SESSION['user'].'</span><img src="images/avatar.jpg" alt="" /></a>');
                                            }else{
                                            	echo('<a href="login.php" class="author"><span class="name">Non loggato</span><img src="images/avatar.jpg" alt="" /></a>');
                                            }
										?>
                                        </div>
								</header>
                                <div id="mappa" style="width:100%; height:400px; margin-bottom:40px">
                                
                                </div>
							</article>

					</div>

				<!-- Footer -->
					<section id="footer">
						<ul class="icons">
							<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon solid fa-rss"><span class="label">RSS</span></a></li>
							<li><a href="#" class="icon solid fa-envelope"><span class="label">Email</span></a></li>
						</ul>
						<p class="copyright">&copy; Design: Lorenzo Marianini & Andrea Bianucci</p>
					</section>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
<?php
	$conn->close();
?>
