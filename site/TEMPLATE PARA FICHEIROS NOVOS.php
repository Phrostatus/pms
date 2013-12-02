<?php
	include "functions.php";
	
	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
	
		ligar_BD();
		$query = "SELECT * FROM utilizador 	
		WHERE id = \"".$_SESSION['user_id']."\"";
		$result = mysql_query($query);				//só para poder mostrar os dados pessoais na barra lateral
?>
		<html>
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<div class="main">
						
						// ###################################################
						// NOVOS DADOS FICAM AKI !!!!!!!!!!!!!!!!!!!!!!!!!!!! 
						//
							para mudar algo mudem nas funcoes.php, ou no estilos.css como o float das novas divs e display:block; etc....
						//
						// ###################################################
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