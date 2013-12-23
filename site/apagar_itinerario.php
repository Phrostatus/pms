<?php
	include "functions.php";

	sec_session_start();
	ligar_BD();
	mysql_set_charset("utf8");
	
	$query_itinerario = 'SELECT id, dia FROM itinerario WHERE itinerario.id = "'.$_REQUEST['itinerario_id'].'"';
	$result_itinerario = mysql_query($query_itinerario);
	$row_itinerario = mysql_fetch_array($result_itinerario);
	
	$query_viagem = 'SELECT id FROM viagem WHERE viagem.itinerario_id = "'.$_REQUEST['itinerario_id'].'"';
	$result_viagem = mysql_query($query_viagem);

	if(mysql_num_rows($result_viagem) > 0)	//manda mensagem a todos os passageiros a dizer que a viagem foi cancelada
	{	
		$query_passageiro = 'SELECT viagem_passageiro.passageiro_utilizador_id, viagem.inicio, viagem.fim, itinerario.dia
								FROM viagem_passageiro
								JOIN viagem ON viagem.id = viagem_passageiro.viagem_id
								JOIN itinerario ON itinerario.id = viagem.itinerario_id
									WHERE itinerario.id = "'.$_REQUEST['itinerario_id'].'"';
									
		$result_passageiro = mysql_query($query_passageiro);				
		
		while($row_passageiro = mysql_fetch_array($result_passageiro))
		{
			$query_viagem_inicio = 'SELECT * FROM itinerario_has_local JOIN local ON local.id = itinerario_has_local.local_id WHERE itinerario_id="'.$_REQUEST['itinerario_id'].'" AND local_id="'.$row_passageiro['inicio'].'"';
			$query_viagem_fim = 'SELECT * FROM itinerario_has_local JOIN local ON local.id = itinerario_has_local.local_id WHERE itinerario_id="'.$_REQUEST['itinerario_id'].'" AND local_id="'.$row_passageiro['fim'].'"';
			$result_viagem_inicio = mysql_query($query_viagem_inicio);
			$result_viagem_fim = mysql_query($query_viagem_fim);
			
			$viagem_inicio = mysql_fetch_array($result_viagem_inicio);
			$viagem_fim = mysql_fetch_array($result_viagem_fim);
			
			$query_mensagem = 'INSERT INTO `notificacao` (`tipo`,`mensagem`,`lida`,`emissor_id`,`recetor_id`)
									VALUES ("2","O condutor cancelou a sua viagem de '.$row_itinerario['dia'].' de '.$viagem_inicio['nome'].' às '.$viagem_inicio['hora'].' para '.$viagem_fim['nome'].' às '.$viagem_fim['hora'].'",
									"0","'.$_SESSION['user_id'].'","'.$row_passageiro['passageiro_utilizador_id'].'")';
			mysql_query($query_mensagem);
		}	
	}

	$query_apagar = 'DELETE FROM `itinerario`
							WHERE itinerario.id = "'.$_REQUEST['itinerario_id'].'"';
	
	$result_apagar = mysql_query($query_apagar);
	
	if($result_apagar != true)
		echo "Ocorreu um erro ao apagar";
	else
		header('LOCATION: locais.php');
?>