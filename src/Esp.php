0<?php
	$conn = new mysqli ('localhost', 'root', '',  'my_trackergps');
    if (isset($_REQUEST['id']) && isset($_REQUEST['latitude']) && isset($_REQUEST['longitude']) && $_REQUEST['latitude']!='' && $_REQUEST['longitude']!=''){
      $latitude = $_REQUEST['latitude'];
      $longitude = $_REQUEST['longitude'];
      $speed = $_REQUEST['speed'];
      if (strlen($latitude)<=10 && strlen($longitude)<=11){
      	 $query = "SELECT COUNT(*) AS numero FROM ESP WHERE ESP.idVettura=".$_REQUEST['id']."";
         $numero = $conn->query($query)->fetch_assoc()['numero'];
         
         if ($numero!=0){
           $query = "DELETE FROM ESP WHERE idVettura = ".$_REQUEST['id']."";
           $conn->query($query);
         }
         
         $latitudef = floatval($latitude);
         $latitude = substr(''.intval($latitudef), 0, 2).'.';
         $latitudef = floatval(substr(''.$latitudef, 2));
         $latitude = $latitude.''.substr(($latitudef/60), 2, 9);
         
         $longitudef = floatval($longitude);
         $longitude = substr(''.intval($longitudef), 0, 2).'.';
         $longitudef = floatval(substr(''.$longitudef, 2));
         $longitude = $longitude.''.substr(($longitudef/60), 2, 9);
         
         if(strlen($speed)>=1){
         	$speed = round(intval($speed)*1.852);
		 }else{
         	$speed = 0;
         }
         
         echo ($latitude.' , '.$longitude.' , '.$speed);
      	 $query = 'INSERT INTO ESP (idVettura, latitudine, longitudine, velocita) VALUES ('.$_REQUEST['id'].', "'.$latitude.'", "'.$longitude.'", '.$speed.')';
      	 $conn->query($query);
      }
    }
    
    $conn->close();
?>