<?php
	include "functions.php";
	
	sec_session_start();
	ligar_BD();
	mysql_set_charset("utf8");
	
	$itinerario_id = $_REQUEST['itinerario_id'];
	$local_id = $_REQUEST['lo'];
	$hora = $_REQUEST['hora'].":".$_REQUEST['min'].":00";
	$espera = $_REQUEST['espera'];
	
	if($itinerario_id == "" || $local_id == "")
	{
		header('LOCATION: locais.php?erro_l=1');
		die();
	}
		
	$query_repetido = 'SELECT local_id FROM itinerario_has_local WHERE local_id="'.$local_id.'" AND itinerario_id="'.$itinerario_id.'"';
	$result_repetido = mysql_query($query_repetido);
	
	if(mysql_num_rows($result_repetido) != 0)
	{
		header('LOCATION: locais.php?erro_l=2');
		die();
	}
	
	$query_inserir = 'INSERT INTO `itinerario_has_local` (`itinerario_id`, `local_id`, `hora`, `tolerancia`)
									 VALUES ("'.$itinerario_id.'","'.$local_id.'","'.$hora.'","'.$espera.'")';
	mysql_query($query_inserir);
	
	//    para mostrar os locais com o ID do itinerário igual ao acabado de inserir
	$query_select = 'SELECT id FROM itinerario WHERE itinerario.id ="'.$itinerario_id.'"';
	$result_select = mysql_query($query_select);
	
	$row = mysql_fetch_array($result_select);	
	header('LOCATION: locais.php?i='.$row['id']);
?>