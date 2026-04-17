<?php
  // Connessione al database
  $conn = new mysqli('localhost', 'root', '', 'my_trackergps');

  $query = 'SELECT latitudine, longitudine, velocita, colore FROM ESP INNER JOIN Veicoli ON Veicoli.idVeicolo = ESP.idVettura WHERE idVettura='.$_REQUEST['id'].''; //PRendo le coordinate del GPS selezionato
  $result = $conn->query($query);

  $data = array(); //Butto i dati in un array
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  echo json_encode($data); //Lo codifico per poterlo utilizzare in JS
?>