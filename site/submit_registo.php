<?php
	include "functions.php";

	$nome = trim($_POST['nome']);
	$mail = $_POST['mail'];
	$telemovel = $_POST['telemovel'];
	$morada = $_POST['morada'];

	if(isset($_POST['passageiro']) && $_POST['passageiro'] == '1')
    	$utilizador_passageiro = 1;
	else
    	$utilizador_passageiro = 0;

	if(isset($_POST['condutor']) && $_POST['condutor'] == '1')
    	$utilizador_condutor = 1;
	else
    	$utilizador_condutor = 0;

	$password = $_POST['p'];	//� gerado um input p oculto onde � guardada a hash da pass

	ligar_BD();

	// verifica��o do nome
	if(preg_match("#[a-zA-Z ]+#", $nome, $reg_match) != 1 || $reg_match[0] != $nome)
	{
		header("LOCATION: registo.php?erro=$reg_match[0]");
		die();
	}

	// verifica��o do email
	if(preg_match("#[a-zA-Z0-9_]+[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)+#", $mail, $reg_match) != 1 || $reg_match[0] != $mail ||
		preg_match("#[@]#", $mail, $reg_match) == 1 && count($reg_match) != 1)
	{
		header("LOCATION: registo.php?erro=4");
		die();
	}

	$mail_query = "SELECT * FROM utilizador WHERE mail = \"".$mail."\"";
	$telemovel_query = "SELECT * FROM utilizador WHERE telemovel = \"".$telemovel."\"";
	/* � preciso os escapes para as aspas na query acima*/

	$mail_result = mysql_query($mail_query);
	//if($result === FALSE)
	//		die(mysql_error());

	if(mysql_num_rows($mail_result) != 0)
	{
		header("LOCATION: registo.php?erro=1");
		die();
	}

	$telemovel_result = mysql_query($telemovel_query);

	if(mysql_num_rows($telemovel_result) != 0)
	{
		header("LOCATION: registo.php?erro=2");
		die();
	}

	//NAO EXISTE NENHUM REGISTO IGUAL;

	//PREPARACAO DA HASH E SALT DA PALAVRA PASSE

	//$salt = mcrypt_create_iv(64, MCRYPT_DEV_URANDOM);  // isto seria otimo mas da carateres bue eskisitos k dpx nao passam bem em utf8 e fica difentes e n da pa comparar direito
	$salt = substr(sha1(rand()), 0, 128);

	$salted_password = $password.$salt;	//adiciona salt � hash
	$salted_hash = hash('SHA512', $salted_password);

	//INSER��O DOS DADOS NA BASE DE DADOS
	$inserir_utilizador = "INSERT INTO `utilizador` (`password`, `salt`, `nome`, `mail`, `telemovel`, `morada`)
											VALUES (\"".$salted_hash."\", \"".$salt."\", \"".$nome."\", \"".$mail."\", \"".$telemovel."\", \"".$morada."\")";

	mysql_query($inserir_utilizador);

	$id_atribuido = mysql_result(mysql_query("SELECT id FROM `utilizador` WHERE mail = \"".$mail."\""), 0 ,"id");

	if($utilizador_passageiro == 1)
	{
		$inserir_passageiro = "INSERT INTO `passageiro` (`utilizador_id`) VALUES (\"".$id_atribuido."\")";
		mysql_query($inserir_passageiro);
	}

	if($utilizador_condutor == 1)
	{
		$inserir_condutor = "INSERT INTO `condutor` (`utilizador_id`) VAlUES (\"".$id_atribuido."\")";

		mysql_query($inserir_condutor);
	}

	header("LOCATION: index.php");
// [:alnum:]*@[:alnum:]*.[:alnum:]*
?>


