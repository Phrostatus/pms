<?php
	include "functions.php";

	ligar_BD();
	
	$query_apagar = 'DELETE FROM `itinerario`
							WHERE itinerario.id = "'.$_REQUEST['itinerario_id'].'"';
	
	$result_apagar = mysql_query($query_apagar);
	
	if($result_apagar != true)
		echo "Ocorreu um erro ao apagar";
	else
		header('LOCATION: locais.php');
?>