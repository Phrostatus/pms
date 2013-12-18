<?php
	include "functions.php";
	sec_session_start();
	ligar_BD();
	$itinerario=$_REQUEST['itinerario_id'];
	$viagem_id=$_REQUEST['viagem_id'];
	$passageiro=$_SESSION['user_id'];

	$verificar_local_igual= mysql_query("select * from viagem_passageiro where viagem_id='$viagem_id' ");
	$possivel_apagar=mysql_num_rows($verificar_local_igual);

		//Actualizar o valor dos lugares livres
	$query_lugares= mysql_query("select lugares_livres as LUGARES from itinerario where itinerario.id='$itinerario'");	
	$num_lugares_cancelar =(mysql_fetch_array($query_lugares)['LUGARES']) +1;
	mysql_query("UPDATE `itinerario` SET `lugares_livres`='$num_lugares_cancelar' WHERE itinerario.id='$itinerario'");


	if(!($possivel_apagar==1))
	{
		//Não se pode apagar pois existe mais gajos
		mysql_query("DELETE FROM `viagem_passageiro` WHERE passageiro_utilizador_id='$passageiro' and viagem_id='$viagem_id'");
	}
	else
	{
		//possivel apagar
		$query_cancelar = "DELETE FROM `viagem` WHERE id='$viagem_id'";
		$result_cancelar = mysql_query($query_cancelar);
	}

	header("LOCATION: viagens.php");
	
			
?>