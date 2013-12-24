<?php
	include "functions.php";
	sec_session_start();
	ligar_BD();
	$itinerario=$_REQUEST['itinerario_id'];
	$local_inicio=$_REQUEST['inicio'];
	$local_final=$_REQUEST['final'];
	$passageiro=$_SESSION['user_id'];
	$condutor=$_REQUEST['condutor'];
	$nome_itinerario=$_REQUEST['nome_itinerario'];
	
	$inicio=mysql_query("select nome as NOME from local where local.id='$local_inicio'");
	$fim=mysql_query("select nome as NOME from local where local.id='$local_final'");
	$local_in =mysql_fetch_array($inicio)['NOME'];
	$local_fi =mysql_fetch_array($fim)['NOME'];
	
	$query_lugares= mysql_query("select lugares_livres as LUGARES from itinerario where itinerario.id='$itinerario'");	
	while ($query_lugares_row = mysql_fetch_array($query_lugares))
	{
		$num_lugares=$query_lugares_row['LUGARES']-1;
	}
	$query_marcar = "UPDATE `itinerario` SET `lugares_livres`='$num_lugares' WHERE itinerario.id='$itinerario'";
	$result_marcar = mysql_query($query_marcar);
	//Vereficar se existe viagem ;
	
	$viagem=mysql_query("SELECT viagem.id as ID from viagem where inicio='$local_inicio' and fim='$local_final' and viagem.condutor_utilizador_id='$condutor' ");
	$existe=mysql_num_rows($viagem);

	$mensagem="Marquei viagem no itinerario $nome_itinerario com inicio em $local_in e final em $local_fi";

	if($existe==0)
	{
		mysql_query("INSERT INTO `viagem`(`itinerario_id`, `avaliacao_condutor`, `condutor_utilizador_id`, `inicio`, `fim`) 
		VALUES ('$itinerario','-1','$condutor','$local_inicio','$local_final')");
		$notificacao="INSERT INTO `notificacao`(`tipo`, `mensagem`, `lida`, `emissor_id`, `recetor_id`)
		VALUES ('Viagem Marcada', '$mensagem', '0', '$passageiro', '$condutor')";
		mysql_query($notificacao);
		$viagem_id=mysql_insert_id();
		$query_marcar= mysql_query("INSERT INTO `viagem_passageiro`(`viagem_id`, `passageiro_utilizador_id`, `avaliacao_passageiro`) VALUES ('$viagem_id','$passageiro',-1)");
		header("LOCATION: procurar_boleia.php?estado=sucesso");
	}
	else
	{
		$viagem_id =mysql_fetch_array($viagem)['ID'];
		$notificacao="INSERT INTO `notificacao`(`tipo`, `mensagem`, `lida`, `emissor_id`, `recetor_id`)
		VALUES ('Viagem Marcada', '$mensagem', '0', '$passageiro', '$condutor')";
		mysql_query($notificacao);

		
		$verificar_marcado=mysql_query("SELECT `viagem_id`, `passageiro_utilizador_id`, `avaliacao_passageiro`
		FROM `viagem_passageiro` 
		join `viagem` on viagem.condutor_utilizador_id='$condutor' and viagem.id=viagem_passageiro.viagem_id
		WHERE passageiro_utilizador_id='$passageiro' AND viagem_id=$viagem_id ");
		$marcado=mysql_num_rows($verificar_marcado);

		if($marcado==0)
		{ 
			$query_marcar= mysql_query("INSERT INTO `viagem_passageiro`(`viagem_id`, `passageiro_utilizador_id`, `avaliacao_passageiro`) VALUES ('$viagem_id','$passageiro',-1)");
			header("LOCATION: procurar_boleia.php?estado=sucesso");
		}
		else
		{
			header("LOCATION: procurar_boleia.php?estado=marcado");
		}
	}
	
	
	if($result_marcar != true)
		echo "Ocorreu um erro ao marcar";
			
?>