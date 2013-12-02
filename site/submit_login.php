<?php
	include "functions.php";
	sec_session_start();

	$mail = $_POST['mail'];
	$password = $_POST['p'];	//é gerado um input "p" oculto onde é guardada a hash da pass

	ligar_BD();
	
	$query = "SELECT * FROM utilizador 	
		WHERE mail = \"".$mail."\"";
	/* é preciso os escapes para as aspas na query acima*/
	$result = mysql_query($query);
	if($result == FALSE)
	{
		die(mysql_error());
	}
	
	$user_id = mysql_result($result, 0, "id");
	$salted_password = $password.mysql_result($result, 0, "salt");	//recupera salt da base de dados e adiciona a hash
	$salted_hash = hash('SHA512', $salted_password);

	if(mysql_result($result, 0, "password") == $salted_hash)		//compara a pass do server com a pass calculada com a hash
	{
		//guardar na sessao o tipo de utilizador
			$query_passageiro = "SELECT id FROM utilizador JOIN passageiro ON utilizador.id = passageiro.utilizador_id
										WHERE utilizador.id = ".$user_id;
			$query_condutor = "SELECT id FROM utilizador JOIN condutor ON utilizador.id = condutor.utilizador_id
										WHERE utilizador.id = ".$user_id;
			$result_passageiro = mysql_query($query_passageiro);
			$result_condutor = mysql_query($query_condutor);
			
			if(mysql_num_rows($result_passageiro) == 1 && mysql_num_rows($result_condutor) == 1)
				$_SESSION['tipo'] = "Condutor e Passageiro";
			else if(mysql_num_rows($result_passageiro) == 1)
				$_SESSION['tipo'] = "Passageiro";
			else
				$_SESSION['tipo'] = "Condutor";
		//----------
		$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
	 
		$user_id = preg_replace("/[^0-9]+/", "", mysql_result($result, 0, "id")); // XSS protection as we might print this value
		$_SESSION['user_id'] = $user_id; 
		$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", mysql_result($result, 0, "mail")); // XSS protection as we might print this value
		$_SESSION['username'] = $username;
		$_SESSION['login_string'] = hash('SHA512', $salted_hash.$user_browser);
	   // Login successful.

		header("LOCATION: start_page.php");
	}
	else
		header("LOCATION: index.php?erro=1");
?>