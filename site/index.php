<?php
	include "functions.php";
?>

<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>



<html>
<head>
	<title>CarPool-MAD</title>
</head>
	<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
	<body>
		<div class="body">
			<?php cabecalho(1); ?>
			<div class="main">
				<p align=center> CAR Pooling MAD</p>
				<p>&nbsp;</p>
				<p align=center>
					<img align=center src="imagens/uma_logo.jpg">
				</p>
				<p>&nbsp;</p>
				<p style="font-size: 1em;">
					Este sistema pretende a criação facilitada de horários de carpoolling , isto é, a partilha de veículos individuais com outros utilizadores.  Isto é feito através de um base de dados onde o utilizador pode inserir a sua disponibilidade ou necessidade de ir de um ponto A para um ponto B e horários disponíveis (janela temporal). O sistema então iria apresentar sugestões para possíveis parceiros e como poder contacta-los (troca de mensagens ). Também permitiria depois o agendamento de trajeto e o possível contacto entre os membros pertencentes,  em caso de imprevistos ou mudanças. Avaliação da assiduidade e pontualidade após a conclusão do trajeto em causa. A interface implementada através de um website interativo.
                </p>
			</div>	
			<?php dadosPessoais(1,NULL); ?>
			<?php rodape(); ?>
		</div>
	</body>
</html>