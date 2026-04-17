<?php
	session_start();

    if (isset($_SESSION['user'])){
   		$conn = new mysqli('localhost', 'root', '', 'my_trackergps');
        $data = date("d/m/Y");
        if (isset($_REQUEST['macchina']) && $_REQUEST['macchina']!=""){ //Verifico che sia specificata una macchina 
          $query = 'SELECT COUNT(*) AS numero FROM Veicoli WHERE Veicoli.idVeicolo = '.$_REQUEST['macchina'].'';
          $numero = $conn->query($query)->fetch_assoc()['numero']; //Controllo se il veicolo è già presente nella tabella
		  
          if (isset($_REQUEST['name']) && $_REQUEST['name']!=""){//Verifico che sia stato specificato il nome del veicolo
            if($numero!=0){ 
                $query = 'UPDATE Veicoli SET nome = "'.$_REQUEST['name'].'" WHERE idVeicolo = '.$_REQUEST['macchina'].''; //Se il veicolo è già presente aggiorno il nome
                $conn->query($query);
            }else{
                $query = 'INSERT INTO Veicoli (idVeicolo, nome) VALUES ('.$_REQUEST['macchina'].' , "'.$_REQUEST['name'].'")'; //Se il veicolo non è presente lo aggiungo e specifico il nome
                $conn->query($query);
                $numero++;
            }
          }
          if (isset($_REQUEST['proprietario']) && $_REQUEST['proprietario']!=""){//Verifico che sia stato specificato il nome del proprietario
            if($numero!=0){
                $query = 'UPDATE Veicoli SET proprietario = "'.$_REQUEST['proprietario'].'" WHERE idVeicolo = '.$_REQUEST['macchina'].''; //Se il veicolo è già presente aggiorno il proprietario
                $conn->query($query);
            }else{
                $query = 'INSERT INTO Veicoli (idVeicolo, proprietario) VALUES ('.$_REQUEST['macchina'].' , "'.$_REQUEST['proprietario'].'")'; //Se il veicolo non è presente lo aggiungo e specifico il nome del proprietario
                $conn->query($query);
                $numero++;
            }
          }
          if (isset($_REQUEST['colore']) && $_REQUEST['colore']!=""){//Verifico che sia stato specificato il colore
            if($numero!=0){
                $query = 'UPDATE Veicoli SET colore = "'.$_REQUEST['colore'].'" WHERE idVeicolo = '.$_REQUEST['macchina'].''; //Se il veicolo è già presente aggiorno il colore
                $conn->query($query);
            }else{
                $query = 'INSERT INTO Veicoli (idVeicolo, colore) VALUES ('.$_REQUEST['macchina'].' , "'.$_REQUEST['colore'].'")'; //Se il veicolo non è presente lo aggiungo e specifico il colore
                $conn->query($query);
                $numero++;
            }
          }
          if (isset($_REQUEST['immagine']) && $_REQUEST['immagine']!=""){ //Verifico che sia stata specificata l'immagine
          	$query = "SELECT id FROM Immagini WHERE Immagini.immagine = '".$_REQUEST['immagine']."'"; //Prendo l'id dell'immagine selezionata dalla tabella immagini
            $id = $conn->query($query)->fetch_assoc()['id'];
            
            if($numero!=0){
                $query = 'UPDATE Veicoli SET immagine = '.$id.' WHERE idVeicolo = '.$_REQUEST['macchina'].''; //Se il veicolo è già presente aggiorno l'immagine
                $conn->query($query);
            }else{
                $query = 'INSERT INTO Veicoli (idVeicolo, immagine) VALUES ('.$_REQUEST['macchina'].' , '.$id.')'; //Se il veicolo non è presente lo aggiungo e specifico l'immagine
                $conn->query($query);
                $numero++;
            }
          }
        }

        $query = 'SELECT ESP.idVettura, Veicoli.nome, Veicoli.proprietario, Veicoli.immagine, Veicoli.colore FROM ESP LEFT OUTER JOIN Veicoli ON ESP.idVettura=Veicoli.idVeicolo;'; //Mi prendo tutti i GPS Attivi dalla tabella ESP
        $result = $conn->query($query);
    }else{
    	header('location: login.php');
    }
	
    
    
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Tracker GPS - Modifica Macchina</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
		<link rel="stylesheet" href="assets/css/main.css" />
        <script>
        	function aggiorna(){ //Funzione che, al cambio della macchina selezionata, modifica i dati sulla sinistra
            	
            	var id = parseInt(document.getElementById('macchina').value); //Prendo la macchina selezionata
                switch(id){
                	<?php 
                    	while($row = $result->fetch_assoc()){ //Mi genero il numero di case in base al numero di GPS Attivi nella tabella ESP
                          if ($row['colore']==null){
                          		echo('
                                  case '.$row['idVettura'].':
                                    document.getElementById("name1").value = "'.$row['nome'].'";
                                    document.getElementById("proprietario1").value = "'.$row['proprietario'].'";
                                    document.getElementById("colore1").style.backgroundColor = "black"; 
                                    break;
                                ');
                          }else{
                          		echo('
                                  case '.$row['idVettura'].':
                                    document.getElementById("name1").value = "'.$row['nome'].'";
                                    document.getElementById("proprietario1").value = "'.$row['proprietario'].'";
                                    document.getElementById("colore1").style.backgroundColor = "'.$row['colore'].'";
                                    break;
                                ');
                          }
                        }
                        
                        $result = $conn->query($query);
                   	?>
                }
            }
            
            function modificaImmagine(){ //Funzione che selezionata l'immagine, modifica l'anteprima sulla sinistra
            	var immagine = document.getElementById('img');
                var select = document.getElementById('immagine').value;
                
                immagine.src = ""+select+"";
            }
            
            function abilita(){ //Funzione che abilita l'invio del colore solo se questo è stato modificato
            	document.getElementById('colore2').name = "colore";
            }
        </script>
    </head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="index.php">HOME</a></h1>
						<nav class="links">
							<ul>
								<li><a href="#">Modifica Macchina</a></li>
								<li><a href="uploadImage.php">Caricamento Media</a></li>
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
										<h2><a href="single.html">Modifica Vettura</a></h2>
										<p>Modifica i dati di una macchina</p>
									</div>
									<div class="meta">
										<time class="published" datetime="2015-11-01"><? echo($data) ?></time>
										<?php 
                                           echo('<a class="author"><span class="name">'.$_SESSION['user'].'</span><img src="images/avatar.jpg" alt="" /></a>');   
										?>	
                                    </div>
								</header>
							</article>
							<article class="post">
								<section>
									<h3>ESEGUI LE MODIFICHE</h3>
									<form method="POST" action="updateCar.php">
										<div class="row gtr-uniform">
                                        	<div class="col-12">
                                              <select name="macchina" id="macchina" onchange="aggiorna()">
                                              		<option value="">- Scegli una macchina -</option>
                                              <?php 
                                                  while($row=$result->fetch_assoc()){
                                                  	  if ($row['nome']==null){
                                                      		echo('
                                                              <option value="'.$row['idVettura'].'">'.$row['idVettura'].' - Unknown</option>
                                                            ');
                                                      }else{
                                                            echo('
                                                              <option value="'.$row['idVettura'].'">'.$row['idVettura'].' - '.$row['nome'].'</option>
                                                            ');
                                                      }
                                                  }
                                              ?>
                                              </select>
											</div>
											<div class="col-6 col-12-xsmall">
                                            	<b>Nome Attuale:</b>
												<input type="text" id="name1" value="" readonly/>
											</div>
											<div class="col-6 col-12-xsmall">
                                            	<b>Nuovo Nome:</b>
												<input type="text" name="name" id="name2" value="" placeholder="Nome..." />
											</div>
                                            <div class="col-6 col-12-xsmall">
                                            	<b>Prorpietario Attuale:</b>
												<input type="text" id="proprietario1" value="" readonly/>
											</div>
											<div class="col-6 col-12-xsmall">
                                            	<b>Nuovo proprietario:</b>
												<input type="text" name="proprietario" id="proprietario2" value="" placeholder="Proprietario..." />
											</div>
                                            <div class="col-6 col-12-xsmall">
                                            	<b>Colore attuale: </b>
												<div id="colore1" style="width:30px; height:30px; border-radius:50px; background-color:white; border:1px solid black"></div>
											</div>
                                            <div class="col-6 col-12-xsmall">
                                            	<b>Nuovo colore: </b><br>
												<input type="color" id="colore2" value="transparent" onChange='abilita()'/>
											</div>
                                            <div class="col-6 col-12-xsmall">
                                            	<b>Immagine selezionata: </b><br>
												<img src="" id="img" alt="" style="width:50%"/>
											</div>
                                            <div class="col-6 col-12-xsmall">
                                            	<b>Seleziona immagine: </b><br>
                                                <select name="immagine" id="immagine" onchange="modificaImmagine()">
                                                        <option value="">- Scegli una macchina -</option>
                                                  <?php
                                                  	  $query = "SELECT * FROM Immagini"; //Prendo tutte le immagini disponibili all'interno della tabella nel DB
                                                      $result = $conn->query($query);
                                                      while($row=$result->fetch_assoc()){
                                                        echo('
                                                           <option value="'.$row['immagine'].'">'.$row['nome'].'</option>
                                                        ');
                                                      }
                                                  ?>
                                                </select>
                                              </div>
                                              <div class="col-6 col-12-xsmall">
												<ul class="actions">
													<li><input type="submit" value="Applica Modifiche" /></li>
												</ul>
											  </div>
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

	</body>
</html>
<?php
	$conn->close();
?>