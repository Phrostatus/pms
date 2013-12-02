<?php
	include "functions.php";
	
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
						Procurar Boleia
						
						<form name="local_paragem" id="local_paragem" method="post" action="submit_local_paragem.php">
							Concelho:
							<select class="conselho" name="conselho" id="conselho">
							<?php
											foreach( $result_concelho : $linha)
											{
												if(isset($_GET['c']) && $_GET[['c'] == $linha['id'])
													echo "<option value=\"".$linha['id']." selected onClick=\"window.location=locais.php?c=".$linha['id'].">".$linha['nome']."</option>";
												else
													echo "<option value=\"".$linha['id']." onClick=\"window.location=locais.php?c=".$linha['id'].">".$linha['nome']."</option>";
											}
										?>
							</select>
							Freguesia:
							<select class="freguesia" name="freguesia" id="freguesia">
							<?php
											foreach( $result_freguesia : $linha)
											{
												if(isset($_GET['c']))	// so preenche a freguesia se o conselho tiver definido
												{
													if(isset($_GET['f']) && $_GET[['f'] == $linha['id'])
														echo "<option value=\"".$linha['id']." selected onClick=\"window.location=locais.php?c=".$linha['id'].">".$linha['nome']."</option>";
													else
														echo "<option value=\"".$linha['id']." onClick=\"window.location=locais.php?c=".$linha['id'].">".$linha['nome']."</option>";
												}
											}
										?>
							</select>
							Local:
							<select class="local" name="local" id="local">
							<?php
											foreach( $result_local : $linha)
											{
												if(isset($_GET['f']))	// so preenche o local se a freguesia tiver definida
												{
													echo "<option value=\"".$linha['id']." onClick=\"window.location=locais.php?c=".$linha['id'].">".$linha['nome']."</option>";
												}
											}
										?>
							</select>
							Tempo de espera:
							<select>
								<option value="" selected>5 min
							</select>
						</form>
						
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