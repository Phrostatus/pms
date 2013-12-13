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
				echo '<th>Nr Lugares</th>';
			echo '</tr>';
			while($row = mysql_fetch_array($result_itinerario))
			{
				$query_local = 'SELECT local.id FROM local JOIN itinerario_has_local ON local.id = itinerario_has_local.local_id
													JOIN itinerario ON itinerario.id = itinerario_has_local.itinerario_id
													WHERE itinerario.id = "'.$row['id'].'"';
				$result_local = mysql_query($query_local);	//so para saber se ja tem pelo menos 2 locais no itinerario
				if(mysql_num_rows($result_local) < 2) { ?>
					<tr class="itinerario_incompleto" onclick="window.location='locais.php?i=<?=$row['id']?>'"> <?php }
				else { ?>
					<tr class="itinerario_completo" onclick="window.location='locais.php?i=<?=$row['id']?>'"> <?php }
						echo '<td style="width : 150px;">'.$row["nome"].'</td>';
						echo '<td style="width : 55px;">'.$row["dia"].'</td>';
						echo '<td style="width : 1px;">'.$row["lugares_livres"].'</td>';
					echo '</tr>';
			}
		echo '</table>';
	}

	function listarLocais()
	{
		if(isset($_REQUEST['i']) && is_numeric($_REQUEST['i']))
		{
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
			
			if(mysql_num_rows($result_local) == 0)
			{
				echo 'Não existem locais';
				return;
			} ?>
				<table class="locais_itinerario">
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
						<td style="width : 1px;">
							<form method="POST" action="apagar_local.php">
								<input type="hidden" name="itinerario_id" value="<?=$row['itinerario_id']?>">
								<input type="hidden" name="local_id" value="<?=$row['local_id']?>">
								<input type="submit" value="X" onClick="confirm('Tem a certeza que pretende apagar este local deste itinerário ?')">
							</form>
						</td>
					</tr>
		  <?php }
			echo '</table>';
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
			<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<?php
						if($_SESSION['tipo'] == "Condutor" || $_SESSION['tipo'] == "Condutor e Passageiro") 
						{
					?>
					<div class="main">
						<div class="menuEsquerdo">
							<?php listarItinerarios(); ?>  <!-- listar os itinerários -->
							<br><br>
							<form method="POST" action="adicionar_itinerario.php">
								<fieldset>
								<legend>Adicionar novo itinerário</legend>
								<table>
									<tr>
										<td>Nome</td>
										<td>Dia</td>
										<td>Nr Lugares</td>
									</tr>
									<tr>
										<td><input type="text" name="nome_itinerario" id="nome_itinerario" style="width:103px;"></td>
										<td>
											<select name="dia_itinerario">
												<option></option>
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
										<td colspan=3 align="center"><input type="submit"></td>
									</tr>
									</table>
								</fieldset>
							</form>
							<?php
							if(isset($_GET['erro']) && $_GET['erro'] == 1)
								echo '<div><p class="erro">ERRO: Itinerário já existe</p></div>';
							?>
						</div>
						<div class="menuDireito" >
							<?php listarLocais(); ?>  <!-- listar os locais do itinerario selecionado -->
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