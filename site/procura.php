<?php

function imprimirDados($concelho,$freguesia,$local,$dia,$hora,$minutos)
{
	$hora_procurar=$hora.":".$minutos.":10";
	
	$listarViagens =mysql_query("
	SELECT 
	utilizador.nome as NOME , utilizador.mail  as EMAIL, utilizador.telemovel as TELEMOVEL ,
	itinerario_has_local.hora as HORA , itinerario_has_local.tolerancia as TOLERANCIA 
	FROM utilizador
	JOIN condutor
	JOIN viagem
	JOIN itinerario
	JOIN itinerario_has_local
	JOIN local
	JOIN concelho
	JOIN freguesia
	JOIN ponto
	WHERE utilizador.id = condutor.utilizador_id
	AND itinerario.dia='$dia'
	AND itinerario_has_local.itinerario_condutor_utilizador_id=itinerario.condutor_utilizador_id
	AND itinerario_has_local.hora='$hora_procurar'
	AND local.id = itinerario_has_local.local_id
	AND concelho.id = '$concelho'
	AND freguesia.id = '$freguesia'
	AND ponto.id = '$local'"
	);
	echo mysql_error();
	if(!mysql_error())
	{
	while ($row = mysql_fetch_array($listarViagens))
			{
				//if($row['lugares']!=0)
				//{
				echo $row['NOME'];
				echo "<br>";
				echo $row['TELEMOVEL'];
				//}
			}
	}
	else
	echo "Houve um erro na visualização de viagens! Por favor tente novamente.";
}
	include "functions.php";
	
	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
	
		ligar_BD();
		$query = "SELECT * FROM utilizador 	
		WHERE id = \"".$_SESSION['user_id']."\"";
		$result = mysql_query($query);		

		
	$concelho = $_REQUEST['concelho'];
	$freguesia = $_REQUEST['freguesia'];
	$local = $_REQUEST['local'];
	$dia= $_REQUEST['dia'];
	$hora=$_REQUEST['hora'];
	$minutos=$_REQUEST['minutos'];
		
		//só para poder mostrar os dados pessoais na barra lateral
?>
		<html>
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<div class="main">
					<?php
					
					imprimirDados($concelho,$freguesia,$local,$dia,$hora,$minutos);
					
					?>
					</div>
					<?php dadosPessoais(0, $result); ?>
					<?php rodape(); ?>
				</div>
			</body>
		</html>
<?php
	} 
	else
		header("LOCATION: erro_sessao.php");
?>