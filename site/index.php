<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>

<?php
	include "functions.php";
?>


<html>
	<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
	<body>
		<div class="body">
			<?php cabecalho(1); ?>
			<div class="main">
				<p> MAIN BODY HERE </p>
				<p> blablabla </p>
				<p> blablabla </p>
				<p style="font-size: 0.6em;">
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