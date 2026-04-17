<?php
  // Connessione al database
  $conn = new mysqli('localhost', 'root', '', 'my_trackergps');

  $query = 'SELECT latitudine, longitudine, velocita, colore FROM ESP LEFT OUTER JOIN Veicoli ON Veicoli.idVeicolo = ESP.idVettura;'; //Seleziono le coordinate e la velocità di tutti i GPS
  $result = $conn->query($query);

  $data = array(); //Butto i dati in un'array 
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  echo json_encode($data); //Codifico l'array per poterlo utilizzare con JS
?>