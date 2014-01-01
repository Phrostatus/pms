<?php include "functions.php"; ?>

<script type="text/javascript" src="forms.js">	</script>

<?php

	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
	
		//ligar_BD();
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
					<?php
						if(!isset($_REQUEST['estado']))
						{
							if(isset($_REQUEST['r']))
							{
								$recetor_id = $_REQUEST['r'];
								$query_mail = sprintf('SELECT mail from utilizador WHERE id="%s"', mysql_real_escape_string($recetor_id));
								$recetor_mail = mysql_fetch_array(mysql_query($query_mail))['mail'];
							?>
								<form method="POST" action="">
									<fieldset>
									<legend>Nova mensagem</legend>
										<label class="nova_notificacao">Destinatário</label>
											<label class=""><?=$recetor_mail?></label><br><br>
										<label class="nova_notificacao">Mensagem</label>
										<textarea maxLength="500" class="notificacao" name="mensagem" id="mensagem" onKeyUp="limitarCaracteres(mensagem, 500);"></textarea>
											<br><br><br><br><br><br><br><br><br><br>
										<label class="nova_notificacao">&nbsp;</label>
											<label id="caracteres_restantes">500</label><label> caracteres restantes</label>
										
										<input type="hidden" name="estado" value="inserir">
										<input type="hidden" name="recetor_id" value="<?=$recetor_id?>">
										<p align="center"><input type="submit" value="Enviar"></p>
									</fieldset>						
								</form>
						<?php
							}
							else
							{
								echo '<p class="erro">Destinatário inválido</p>';
								echo '<p></p>';
								echo '<p align=center>Volte à <a href="start_page.php">Página Inicial</a></p>';
							}
						}
						else if(isset($_REQUEST['estado']) && $_REQUEST['estado'] == "inserir")
						{
							$query_inserir = sprintf('INSERT INTO `notificacao` (`tipo`,`mensagem`,`lida`,`emissor_id`,`recetor_id`)
									VALUES("3","%s","0","'.$_SESSION['user_id'].'","'.$_REQUEST['recetor_id'].'")',
										mysql_real_escape_string($_REQUEST['mensagem']));
	
							mysql_query($query_inserir);
							echo '<p align="center">Mensagem Enviada</p>';
							echo '<p align="center"><a href="start_page.php">Página Inicial</a></p>';
						}
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