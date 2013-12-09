<?php
	include "functions.php";
	
	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
	
		//ligar_BD();
		$query_user = "SELECT * FROM utilizador 	
			WHERE id = \"".$_SESSION['user_id']."\"";
		$result_user = mysql_query($query_user);	//para poder mostrar os dados pessoais na barra lateral
		
		$query_concelho = "SELECT * FROM concelho order by `nome` ASC";
		$result_concelho = mysql_query($query_concelho);

		$result_freguesia = NULL;
		$result_local = NULL;
		
		if(isset($_GET['c']) && is_numeric($_GET['c']))
		{
			$query_freguesia = "SELECT * FROM freguesia JOIN concelho ON concelho.id = freguesia.concelho_id
									WHERE concelho.id = ".$_GET['c'];
			$result_freguesia = mysql_query($query_freguesia);
		}
		
		if(isset($_GET['c']) && is_numeric($_GET['c']))
		{
			$query_local = "SELECT * FROM local JOIN freguesia ON freguesia.id = local.freguesia_id
									WHERE freguesia.id = ".$_GET['f'];
			$result_local = mysql_query($query_local);
		}
?>
		
		<html>
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<?php
						if($_SESSION['tipo'] == "Condutor" || $_SESSION['tipo'] == "Condutor e Passageiro") 
						{
					?>
					<div class="main">
					
					<div class="local_paragem">
						ISto é o que devo fazer
						
					</div>
					<div class="local_paragem" >
					
					<?php
					if($_SESSION['tipo'] == "Passageiro") 
						{
					?>
						Adicionar local de espera
						
					<?php } ?>
					</div>

					</div>
					<?php
						}?>
					<?php dadosPessoais(0, $result_user); ?>
					<?php rodape(); ?>
				</div>
			</body>
		</html>
<?php
	} 
	else
		header("LOCATION: erro_sessao.php");
?>