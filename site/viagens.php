﻿<?php
	include "functions.php";
	
	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
	
		ligar_BD();
		$query = "SELECT * FROM utilizador 	
		WHERE id = \"".$_SESSION['user_id']."\"";
		$result = mysql_query($query);				//são para poder mostrar os dados pessoais na barra lateral
		
		
	function mostrarViagens()
{
	$id_utilizador=$_SESSION['user_id'];
	$query_viagens = 'SELECT viagem_has_local.tipo as TIPO , concelho.nome as CONSELHO, freguesia.nome as FREGUESIA, local.nome as LOCAL   
		FROM utilizador
		JOIN condutor			ON condutor.utilizador_id = utilizador.id
		JOIN viagem				ON viagem.condutor_utilizador_id = utilizador.id
		JOIN itinerario 	  	ON itinerario.condutor_utilizador_id=utilizador.id
		JOIN viagem_has_local 	ON viagem_has_local.viagem_id=viagem.id
		JOIN local 				ON local.id=viagem_has_local.local_id
		JOIN freguesia 			ON freguesia.id=local.freguesia_id
		JOIN concelho 			ON concelho.id=freguesia.concelho_id
		WHERE utilizador.id="'.$id_utilizador.'"
		GROUP BY viagem_has_local.local_id
		ORDER BY viagem.id';
		/*AND local.id = itinerario_has_local.local_id
		AND concelho.id = '$concelho'
		AND freguesia.id = '$freguesia'
		AND ponto.id = '$local'"*/
	mysql_set_charset("utf8");
	$result_viagens =mysql_query($query_viagens);
		
	if(!mysql_error())
	{
		$contar=0;
		while ($row = mysql_fetch_array($result_viagens))
			{
			if ($contar==0)
			{
			echo "<table><tr><td>TIPO</td><td>CONSELHO</td><td>FREGUESIA</td><td>LOCAL</td></tr>";
			$contar=1;
			}
			?>
			<td><?php echo $row['TIPO'] ?></td>
			<td><?php echo $row['CONSELHO']?></td>
			<td><?php echo $row['FREGUESIA']?></td>
			<td><?php echo $row['LOCAL']?></td>
			<?php
			echo"</tr>";
			}
		echo "</table>";
	}
	else
	echo "Houve um erro na visualização de viagens! Por favor tente novamente.";
}
?>
		<html>
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<div class="main">
						<?php 
						mostrarViagens();
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