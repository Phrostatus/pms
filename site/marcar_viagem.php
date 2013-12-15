<?php
	include "functions.php";

	ligar_BD();
	$itinerario=$_REQUEST['itinerario_id'];
	
	$query_lugares= mysql_query("select lugares_livres as LUGARES from itinerario where itinerario.id='$itinerario'");	
	while ($query_lugares_row = mysql_fetch_array($query_lugares))
	{
		$num_lugares=$query_lugares_row['LUGARES']-1;
	}
	$query_marcar = "UPDATE `itinerario` SET `lugares_livres`='$num_lugares' WHERE itinerario.id='$itinerario'";
	$result_marcar = mysql_query($query_marcar);
	
	if($result_marcar != true)
		echo "Ocorreu um erro ao marcar";
	else
		header('LOCATION: procurar_boleia.php?estado=sucesso');