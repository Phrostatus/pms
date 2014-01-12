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
					<p align=center>FAQ</p>
					<table class="faq">
						<tr><td class="p">P: O que é o carpooling?</td></tr>
						<tr><td class="r">R: Carpooling é o acto de partilhar lugares não ocupados em viaturas privadas entre várias entidades, isto é, o dono da viatura mantêm-se igual mas este permite outras pessoas vir juntamente com este em quaisquer lugares vagos. Em palavras simples, boleia.</td></tr>
						<tr class="blank"><td>&nbsp;</tr></td>
						<tr><td class="p">P: Quais são as vantagems de carpooling?</td></tr>
						<tr><td class="r">R: Menor consumo de combustível possível (embora continua-se a conduzir uma viatura, evita-se o uso de várias quando uma bastaria) logo mais amigável para o ambiente, menos tráfego e assim mais espaço de estacionamento, é mais barato no contexto da RAM...</td></tr>
						<tr class="blank"><td>&nbsp;</tr></td>
						<tr><td class="p">P: Como é o carpooling mais barato?</td></tr>
						<tr><td class="r">R: Embora combustíveis não sejam exactamente baratos (e. 2014), transportes públicos na RAM possuem um carácter extremo, especialmente em viagems regulares onde carpooling poderia ser usado.</td></tr>
						<tr class="blank"><td>&nbsp;</tr></td>
						<tr><td class="p">P: O que é a CarPool-MAD?</td></tr>
						<tr><td class="r">R: Somos um serviço que permite a quaisquer pessoas interessadas em carpooling na RAM, permitindo ofereçer os seus serviços ou encontrar outros com horários similares, e contactá-los mais facilmente, em caso de emergência ou necessidade.</td></tr>
						<tr class="blank"><td>&nbsp;</tr></td>
						<tr><td class="p">P: Existe quaisquer custos associados a este serviço?</td></tr>
						<tr><td class="r">R: Não. Donações seriam apreciadas porém.</td></tr>
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