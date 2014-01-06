<?php
	function listarItinerarios()
	{
		mysql_set_charset("utf8");
		$query_itinerario = 'SELECT * FROM itinerario WHERE condutor_utilizador_id="'.$_SESSION['user_id'].'" order by nome';
		$result_itinerario = mysql_query($query_itinerario);
		
		if(mysql_num_rows($result_itinerario) == 0)
		{
			echo 'Não existem itinerários';
			return;
		}
		echo '<table class="locais_itinerario">';
			echo "<tr>";
				echo '<th>Itinerário</th>';
				echo '<th>Dia</th>';
				echo '<th>Vagas</th>';
			echo '</tr>';
			while($row = mysql_fetch_array($result_itinerario))
			{
				$query_viagem = 'SELECT id FROM viagem WHERE viagem.itinerario_id = "'.$row['id'].'"';
				$result_viagem = mysql_query($query_viagem);

				if(mysql_num_rows($result_viagem) == 0)
					$tem_viagens = 0;
				else
					$tem_viagens = 1;
			
				$query_local = 'SELECT local.id FROM local JOIN itinerario_has_local ON local.id = itinerario_has_local.local_id
													JOIN itinerario ON itinerario.id = itinerario_has_local.itinerario_id
													WHERE itinerario.id = "'.$row['id'].'"';
				$result_local = mysql_query($query_local);	//so para saber se ja tem pelo menos 2 locais no itinerario
				if(isset($_REQUEST['i']) && $row['id'] == $_REQUEST['i']) 
					echo '<tr class="itinerario_selecionado">';
				else if(mysql_num_rows($result_local) < 2)
					echo '<tr class="itinerario_incompleto"> ';
				else 
					echo '<tr class="itinerario_completo">';
				?>
						<td style="width : 173px;" onclick="window.location='locais.php?i=<?=$row['id']?>'"><?php if($tem_viagens) echo "*"; echo $row["nome"];?></td>
						<td style="width : 55px;" onclick="window.location='locais.php?i=<?=$row['id']?>'"><?=$row["dia"]?></td>
						<td style="width : 1px;" onclick="window.location='locais.php?i=<?=$row['id']?>'"><?=$row["lugares_livres"]?></td>
						<td style="width : 1px;">
							<form method="POST" action="apagar_itinerario.php">
								<input type="hidden" name="itinerario_id" value="<?=$row['id']?>">
							<?php if($tem_viagens) { ?>
								<input type="button" class="apagar_local" value="X" onClick="if(confirm('Itinerário com viagens marcadas.O seu rating baixará em 10 pontos. Apagar mesmo assim ?')) {this.form.submit();}">
							<?php }else { ?>
								<input type="button" class="apagar_local" value="X" onClick="if(confirm('Tem a certeza que pretende apagar este itinerário ?')) {this.form.submit();}">
							<?php } ?>
							</form>
						</td>
					</tr>
	  <?php }
		echo '</table>';
		echo '<p align="left">* Já tem viagens marcadas</p>';
	}

	function listarLocais()
	{
		if(isset($_REQUEST['i']) && is_numeric($_REQUEST['i']))
		{
			echo '<center><p>Locais do itinerário selecionado</p></center>';
			
			$query_local = 'SELECT itinerario_has_local.local_id AS local_id, itinerario_has_local.itinerario_id AS itinerario_id, local.nome AS local, freguesia.nome AS freguesia, concelho.nome as concelho, itinerario_has_local.hora, itinerario_has_local.tolerancia
									FROM local 
									JOIN freguesia ON local.freguesia_id = freguesia.id
									JOIN concelho ON freguesia.concelho_id = concelho.id
									JOIN itinerario_has_local ON local.id = itinerario_has_local.local_id
									JOIN itinerario ON itinerario.id = itinerario_has_local.itinerario_id
										WHERE itinerario.id = "'.$_REQUEST['i'].'" 
										AND itinerario.condutor_utilizador_id="'.$_SESSION['user_id'].'" 
										ORDER BY hora';
			$result_local = mysql_query($query_local);
			
			$query_viagem = 'SELECT id FROM viagem WHERE viagem.itinerario_id = "'.$_REQUEST['i'].'"';
			$result_viagem = mysql_query($query_viagem);

			if(mysql_num_rows($result_viagem) == 0)
				$tem_viagens = 0;
			else
				$tem_viagens = 1;
			
			if(mysql_num_rows($result_local) == 0)
			{
				echo 'Não existem locais.';
				return true;
			} ?>
				<table class="locais_local">
					<tr>
						<th>Concelho</th>
						<th>Freguesia</th>
						<th>Local</th>
						<th>Hora</th>
						<th>Espera(min)</th>
					</tr>
				<?php
				while($row = mysql_fetch_array($result_local))
				{  ?>
					<tr>
						<td style="width : 100px;"><?=$row["concelho"]?></td>
						<td style="width : 100px;"><?=$row["freguesia"]?></td>
						<td style="width : 100px;"><?=$row["local"]?></td>
						<td style="width : 1px;"><?=substr($row["hora"],0,5)?></td>
						<td style="width : 1px;"><?=$row["tolerancia"]?></td>
						<?php if(!$tem_viagens){	?>
							<td style="width : 1px;">
								<form method="POST" action="apagar_local.php">
									<input type="hidden" name="itinerario_id" value="<?=$row['itinerario_id']?>">
									<input type="hidden" name="local_id" value="<?=$row['local_id']?>">
									<input type="button" class="apagar_local" value="X" onClick="if(confirm('Tem a certeza que pretende apagar este local deste itinerário ?')) this.form.submit();">
								</form>
							</td>
						<?php } ?>
					</tr>
		  <?php }
			echo '</table>';
			if($tem_viagens)
			{
				echo '<p align="left">Não pode apagar locais porque existem viagens marcadas</p>';
			}
			if(mysql_num_rows($result_local) < 2)
				echo '<p align="left">O itinerário precisa de ter pelo menos 2 locais</p>';
			return true;
		}
		else
		{
			echo "<center>Selecione um itinerário</center>";
			return false;
		}
	}


	include "functions.php";
	
	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
	
		//ligar_BD();
		$query_user = "SELECT * FROM utilizador 	
			WHERE id = \"".$_SESSION['user_id']."\"";
		$result_user = mysql_query($query_user);	//para poder mostrar os dados pessoais na barra lateral
?>
		<html>
			<head>
				<title>CarPool-MAD</title>
			</head>
			<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
			<script type="text/javascript" src="forms.js">	</script>
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<?php
						if($_SESSION['tipo'] == "Condutor" || $_SESSION['tipo'] == "Condutor e Passageiro") 
						{
					?>
					<div class="main">
						<div class="menuEsquerdo">
							<center><p>Lista de Itinerários</p></center>
							<?php listarItinerarios(); ?>  <!-- listar os itinerários -->
							<br><br>
							<form method="POST" action="adicionar_itinerario.php">
								<fieldset>
								<legend>Adicionar novo itinerário</legend>
								<table class="insere_itinerario">
									<tr>
										<td>Nome</td>
										<td>Dia</td>
										<td>Vagas</td>
									</tr>
									<tr>
										<td><input type="text" name="nome_itinerario" id="nome_itinerario" style="width:150px;"></td>
										<td>
											<select name="dia_itinerario">
												<option value="Segunda">Segunda</option>
												<option value="Terça">Terça</option>
												<option value="Quarta">Quarta</option>
												<option value="Quinta">Quinta</option>
												<option value="Sexta">Sexta</option>
												<option value="Sábado">Sábado</option>
												<option value="Domingo">Domingo</option>
											</select>
										</td>
										<td>
											<select name="lugares_livres">
												<option>1</option><option>2</option><option>3</option>
												<option>4</option><option>5</option><option>6</option>
												<option>7</option><option>8</option><option>9</option>
												<option>10</option>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan=3 align="center"><input type="submit" value="Adicionar Itinerário"></td>
									</tr>
									</table>
								</fieldset>
							</form>
							<?php
							if(isset($_GET['erro_i']) && $_GET['erro_i'] == 1)
								echo '<div><p class="erro">ERRO: Itinerário já existe</p></div>';
							?>
						</div>
						<div class="menuDireito">
							<?php 
							if(listarLocais())	// listar os locais do itinerario selecionado retorna false se nao tiver itinerario selecionado
							{	?>  
								<br><br>
								<form name="insere_local" id="pesquisa_local" method="REQUEST" action="adicionar_local.php">
								<fieldset>
									<legend>Adicionar local ao itinerário selecionado</legend>
									<table class="insere_local">
										<tr>
											<td>Concelho</td>
											<td>Freguesia</td>
											<td>Local</td>
											<td>Hora</td>
											<td>Espera</td>
										</tr>
										<tr>
											<td>
												<select class="insere_local"  name="co" id="co" onChange='selecionarOpcoes_locais(<?=$_REQUEST["i"]?>)' style="width: 85px; height:20px;">
													<option> </option>
													<?php selectConcelho("origem"); ?>
												</select>
											</td>
											<td>
												<select class="insere_local"  name="fo" id="fo" onChange="selecionarOpcoes_locais(<?=$_REQUEST["i"]?>)" style="width: 90px; height:20px;">
													<option> </option>
													<?php selectFreguesia("origem"); ?>
												</select>
											</td>
											<td>
												<select class="insere_local"  name="lo" id="lo" style="width: 107px; height:20px">
													<option> </option>
													<?php selectLocal("origem"); ?>
												</select>
											</td>
											<td>
												<select class="insere_local_hora" name="hora" id="hora" style="width:41px;">
													<?php for($i=0;$i<24;$i++)
													{ 
														if($i < 10) 
															echo "<option value=\"".$i."\">0".$i."</option>";
														else
															echo "<option value=\"".$i."\">".$i."</option>";
													} ?>
												</select>
												:
												<select class="insere_local_hora" name="min" id="min" style="width:41px;">
													<option value="00"> 00 </option>
													<option value="05"> 05 </option>
													<?php for($i=10;$i<60;$i+=5){ ?>
														<option value="<?=$i ?>" > <?php echo $i; ?> </option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="insere_local_espera" name="espera" id="espera" style="width: 41px; height:20px;">
													<option value="5">5</option>
													<option value="10">10</option>
													<option value="15">15</option>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="5" align="center">
												<input type="hidden" name="itinerario_id" value='<?=$_REQUEST["i"]?>'>
												<input type="submit" value="Adicionar Local">
											</td>
										</tr>
									</table>
								</fieldset>
								</form>
								<?php
							}
							if(isset($_GET['erro_l']) && $_GET['erro_l'] == 1)
								echo '<div><p class="erro">Erro ao adicionar local<br><br>Selecione um itinerário</p></div>';
							else if(isset($_GET['erro_l']) && $_GET['erro_l'] == 2)
								echo '<div><p class="erro">Local já adicionado a este itinerário<br><br>Selecione um itinerário</p></div>';
							?>
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