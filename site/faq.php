<?php
	include "functions.php";
	
	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
		ligar_BD();
		$query = "SELECT * FROM utilizador 	
		WHERE id = \"".$_SESSION['user_id']."\"";
		$result = mysql_query($query);				//só para poder mostrar os dados pessoais na barra lateral
	}
?>
	<html>
	<head>
		<title>CarPool-MAD</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
		<script type="text/javascript" src="sha512.js"> </script>
		<script type="text/javascript" src="forms.js">	</script>
	</head>
		<body>
			<div class="body">
				<?php 
				if(login_check() == true)
					cabecalho(2); 
				else
					cabecalho(1);
				?>
				<div class="main">
					<p>FAQ</p>
					<table style="border:1px solid white">
						<tr><td>P: O que é isto ?</td></tr>
						<tr><td>R: Isto é um site de carpool ....</td></tr>
					</table>
				</div>
				<?php
					if(login_check() == true)
						dadosPessoais(0, $result); 
					else
						dadosPessoais(1, NULL); 
				?>
				<?php rodape(); ?>
			</div>
		</body>
	</html>