<?php
	session_start();
    
    if (isset($_SESSION['user'])){
    	if(isset($_REQUEST['base64']) && $_REQUEST['base64']!='' && isset($_REQUEST['nome']) && $_REQUEST['nome']!=''){
        	$conn = new mysqli('localhost', 'root', '', 'my_trackergps');
            
            $query = "INSERT INTO Immagini (nome, immagine) VALUES ('".$_REQUEST['nome']."', '".$_REQUEST['base64']."')"; //Inserisco l'immagine caricata nel DB
            $conn->query($query);
        }
    }else{
    	header("location:login.php"); //Se non sono loggato rimando alla pagina 'login.php'
    }
    
	$data = date("d/m/Y");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Tracker GPS - Importa un'immagine</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
		<link rel="stylesheet" href="assets/css/main.css" />
    </head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="index.php">HOME</a></h1>
						<nav class="links">
							<ul>
								<li><a href="updateCar.php">Modifica Macchina</a></li>
								<li><a href="#">Caricamento Media</a></li>
							</ul>
						</nav>
						<nav class="main">
							<ul>
								<li class="search">
									<a class="fa-search" href="#search">Cerca</a>
									<form id="search" method="get" action="#">
										<input type="text" name="query" placeholder="Cerca" />
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
								<form class="search" method="POST" action="#">
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
										<h2><a href="#">Importa un'immagine</a></h2>
										<p>Seleziona l'immagne che preferisci dal tuo computer</p>
									</div>
									<div class="meta">
										<time class="published"><? echo($data) ?></time>
										<?php 
                                           echo('<a class="author"><span class="name">'.$_SESSION['user'].'</span><img src="images/avatar.jpg" alt="" /></a>');   
										?>	
                                    </div>
								</header>
							</article>
							<article class="post">
								<section>
									<h3>IMPORTA UN'IMMAGINE</h3>
									<form method="POST" action="uploadImage.php">
                                        <div class="row gtr-uniform">
                                            <div class="col-6 col-12-xsmall">
                                            	<b>Immagine selezionata: </b><br>
												<img src="images/uploadImage.jpg" id="preview-image" alt="" style="width:50%"/>
											</div>
                                            <div class="col-6 col-12-xsmall">
                                            	<b>Carica un'immagine: </b><br>
                                                <br>
                                                <input type="file" id="file-input" accept="image/*" required="required">
                                                <input type="text" style="display:none" id="base64" name="base64" required="required">
                                              </div>
                                         </div>
                                         <div class="col-12">
                                            	<b>Descrizione immagine:</b>
												<input type="text" id="nome" name="nome" placeholder="Es. Fiat Punto" required="required"/>
										 </div>
                                         <br>
                                         <div class="col-6 col-12-xsmall">
												<ul class="actions">
													<li><input type="submit" value="Carica Immagine" /></li>
												</ul>
										 </div>
									</form>
								</section>
							</article>

						<!-- Pagination -->

					</div>
			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
            <script>
              const fileInput = document.getElementById('file-input'); //Prendo l'input dell'immagine
              const previewImage = document.getElementById('preview-image'); //Prendo la casella di output
              const input = document.getElementById('base64'); //Casella di testo nascosta in cui inserisco la stringa base64
              fileInput.addEventListener('change', function() {
                // Legge il contenuto dell'immagine come una stringa Base64
                var file = fileInput.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                  var base64String = e.target.result.split(',')[1];
                  previewImage.src = 'data:image/jpeg;base64,'+base64String; //Inserisco la stringa Base64 nella casella di output dell'immagine
                  input.value = 'data:image/jpeg;base64,'+base64String; //Insersco la stringa Base64 nella casella di testo nascosta
                };
                reader.readAsDataURL(file);
              });
            </script>
	</body>
</html>
<?php
	$conn->close();
?>
