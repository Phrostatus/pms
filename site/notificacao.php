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
						<div class="listaNotificacoes">
						<?php	
							if(isset($_REQUEST['n']) && is_numeric($_REQUEST['n']))
							{
								$query_lida = 'UPDATE notificacao SET lida="1" WHERE notificacao.id = "'.$_REQUEST['n'].'"';
								mysql_query($query_lida);
							}
						
							$query_notificacao = 'SELECT notificacao.id AS "notificacao.id", notificacao.tipo, notificacao.mensagem, notificacao.lida, notificacao.emissor_id, notificacao.recetor_id, notificacao.data, utilizador.mail
													FROM notificacao
													JOIN utilizador ON utilizador.id = notificacao.emissor_id
														WHERE recetor_id = "'.$_SESSION['user_id'].'" order by notificacao.data DESC';
							$result_notificacao = mysql_query($query_notificacao);
				
							$num_notificacao = mysql_num_rows($result_notificacao);
							
							if($num_notificacao == 0)
								echo '<p>Não tem nenhuma mensagem.</p>';
							else
							{ 
							?>
								<table class="notificacao">
								<thead>
									<tr>
										<th>De</th>
										<th>Assunto</th>
										<th>Data</th>
									</tr>
								</thead>
								</tbody>
								<?php
									$i = 0;
									while($row_notificacao = mysql_fetch_array($result_notificacao))
									{ 	
										if(isset($_REQUEST['n']) && $row_notificacao['notificacao.id'] == $_REQUEST['n'])
											echo '<tr class="notificacao_selecionada">';
										else
											echo '<tr>';
									?>
											<td style="width:200px;" onclick="window.location='notificacao.php?n=<?=$row_notificacao['notificacao.id']?>'"><?php if($row_notificacao['lida'] == 0) echo '<b>'; echo $row_notificacao['mail']; ?></b></td>
											<?php
											if($row_notificacao['tipo'] == 1) {
											?>	<td style="width:90px;" onclick="window.location='notificacao.php?n=<?=$row_notificacao['notificacao.id']?>'"><?php if($row_notificacao['lida'] == 0) echo '<b>';?>Viagem marcada</b></td>
									  <?php }else if($row_notificacao['tipo'] == 2){
										?>		<td style="width:90px;" onclick="window.location='notificacao.php?n=<?=$row_notificacao['notificacao.id']?>'"><?php if($row_notificacao['lida'] == 0) echo '<b>';?>Viagem desmarcada</td>
									  <?php	}else if($row_notificacao['tipo'] == 3){
										 ?>		<td style="width:90px;" onclick="window.location='notificacao.php?n=<?=$row_notificacao['notificacao.id']?>'"><?php if($row_notificacao['lida'] == 0) echo '<b>';?>Mensagem pessoal</td>
									  <?php } ?>
											<td style="width:80px;" onclick="window.location='notificacao.php?n=<?=$row_notificacao['notificacao.id']?>'"><?php if($row_notificacao['lida'] == 0) echo '<b>'; echo $row_notificacao['data']; ?></td>
										</tr>
										</b>
							  <?php } ?>
								</tbody>
							</table>
				  	  <?php } ?>
						</div>
						<div class="notificacao">
						<?php
							if(isset($_REQUEST['n']) && is_numeric($_REQUEST['n']))
							{
								$query_lida = 'UPDATE notificacao SET lida="1" WHERE notificacao.id = "'.$_REQUEST['n'].'"';
								mysql_query($query_lida);
							
								$query_notificacao = 'SELECT notificacao.id AS "notificacao.id", notificacao.tipo, notificacao.mensagem, notificacao.lida, notificacao.emissor_id, notificacao.recetor_id, notificacao.data, utilizador.mail
													FROM notificacao
													JOIN utilizador ON utilizador.id = notificacao.emissor_id
														WHERE recetor_id = "'.$_SESSION['user_id'].'"
															AND notificacao.id = "'.$_REQUEST['n'].'"';
								$result_notificacao = mysql_query($query_notificacao);
								$notificacao = mysql_fetch_array($result_notificacao);
								
								if($notificacao == false) //notificacao nao existe para este utilizador
									echo '<p>Selecione uma mensgem</p>';
								else
								{
									echo '<p>Mensagem</p>';
									echo '<p class="notificacao">De: '.$notificacao['mail'].'</p>';
									echo '<p class="notificacao">Data: '.$notificacao['data'].'</p>';
									echo '<p class="notificacao">Assunto: ';
										if($notificacao['tipo'] == 1) echo "Viagem Marcada";
										if($notificacao['tipo'] == 2) echo "Viagem desmarcada pelo condutor";
										if($notificacao['tipo'] == 3) echo "Mensagem Pessoal";
									echo '</p>';
									echo '<hr>';
									echo '<p class="notificacao">Mensagem:</p>';
									echo '<p class="notificacao">'.$notificacao['mensagem'].'</p>';
								}
							}
							else
							{
								echo '<p>Selecione uma mensgem</p>';
							}
						?>
							<label></label>
						</div>
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