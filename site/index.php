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
				<p>&nbsp;</p>
				<p style="font-size: 1em;">
                	©2008 Lorem Ipsum Dolar Lorem ipsum dolor sit amet, consetetur 
                    sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et 
			        dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam 
			        et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata 
			        sanctus est Lorem ipsum dolor sit amet.
                </p>
			</div>	
			<?php dadosPessoais(1,NULL); ?>
			<?php rodape(); ?>
		</div>
	</body>
</html>