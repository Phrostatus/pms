<?php
	include "functions.php";

	ligar_BD();
	
	$query_apagar = 'DELETE FROM `itinerario_has_local`
							WHERE itinerario_has_local.itinerario_id = "'.$_REQUEST['itinerario_id'].'"
							AND itinerario_has_local.local_id = "'.$_REQUEST['local_id'].'"';
							
	$result_apagar = mysql_query($query_apagar);
	
	if($result_apagar != true)
		echo "Ocorreu um erro ao apagar";
	else
		header('LOCATION: locais.php?i='.$_REQUEST['itinerario_id']);
?>