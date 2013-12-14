<?php
	include "functions.php";
	
	sec_session_start();
	ligar_BD();
	mysql_set_charset("utf8");
	
	$nome = $_REQUEST['nome_itinerario'];
	$dia = $_REQUEST['dia_itinerario'];
	$lugares_livres = $_REQUEST['lugares_livres'];
	
	$query_repetido = 'SELECT nome FROM itinerario 
								   JOIN condutor ON condutor.utilizador_id = itinerario.condutor_utilizador_id
										WHERE condutor.utilizador_id="'.$_SESSION['user_id'].'"
											AND itinerario.nome="'.$nome.'"';
	$result_repetido = mysql_query($query_repetido);

	if(mysql_num_rows($result_repetido) > 0)
	{
		header('LOCATION: locais.php?erro_i=1');
		die();
	}
			
	$query_inserir = sprintf('INSERT INTO `itinerario` (`condutor_utilizador_id`, `nome`, `dia`, `lugares_livres`)
									 VALUES ("'.$_SESSION['user_id'].'","%s","'.$dia.'","'.$lugares_livres.'")',mysql_real_escape_string($nome));
	mysql_query($query_inserir);
	
	//    para mostrar os locais com o ID do itinerário igual ao acabado de inserir
	$query_select = 'SELECT id FROM itinerario WHERE itinerario.nome ="'.$nome.'"';
	$result_select = mysql_query($query_select);
	
	$row = mysql_fetch_array($result_select);	
	header('LOCATION: locais.php?i='.$row['id']);
?>