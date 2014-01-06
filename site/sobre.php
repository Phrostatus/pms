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
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen">
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
					<p>Sobre</p>
					<p>Com crescente preços de combustível e transporte público, este já uma regalia de custo considerável nesta localização, e espaço de estacionamento a diminuir, possuir transporte privado e livre fica cada vez mais necessário mas de díficil obtenção.</p>
  <p>CarPool-MAD compromete-se a minimizar este problema através da cooperação entre a população usando carpooling: a partilha de transporte privado, preenchendo lugares que estariam outraem vazios.</p>
  <p>Este portal servirá para correr este serviço online, auxiliando a descoberta de possíveis condutores com horários semelhantes, ou ofereçer como um, e trocar mensagems e informação.</p>
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