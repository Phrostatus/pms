<?php
function imprimirDados($concelho,$freguesia,$local,$dia,$hora,$minutos)
{
	
	$hora_procura=$hora.":".$minutos.":00";
	$query_listarViagens ="
	SELECT itinerario_has_local.hora as TIME_SEM_TOLERANCIA ,SEC_TO_TIME( TIME_TO_SEC(itinerario_has_local.hora )+ (itinerario_has_local.tolerancia*60)) as TIME_COM_TOLERANCIA ,itinerario.id AS ID_ITINERARIO, itinerario.nome AS NOME, itinerario.dia AS DIA, 
	itinerario.lugares_livres as LIVRES, itinerario_has_local.tolerancia as TOLERANCIA , itinerario_has_local.hora as HORAS
	FROM condutor
	JOIN itinerario ON itinerario.dia='$dia'
	JOIN itinerario_has_local 
	";
	
	
	
	
	$listar_resto=mysql_query("
	SELECT itinerario_has_local.dia as DIA, itinerario_has_local.nome as NOME ,itinerario_has_local.tolerancia as TOLERANCIA 
	condutor
	JOIN itinerario
	JOIN itinerario_has_local  ON itinerario_has_local.itinerario_condutor_utilizador_id=condutor.id 
	JOIN local ON local.id = itinerario_has_local.local_id 
	JOIN concelho concelho.id = '$concelho' 
	JOIN freguesia freguesia.id = '$freguesia'
	JOIN ponto ponto.id = '$local'
	");

	$result_listarViagens=mysql_query($query_listarViagens);
	
	echo mysql_error();
	if(!mysql_error())
	{
	echo "<table><tr><td>NOME</td><td>DIA</td><td>HORA</td><tr>";
	while ($row = mysql_fetch_array($result_listarViagens))
			{
				$hora_procurar=strtotime($hora_procura);
				$hora_limite_superior=strtotime($row['TIME_COM_TOLERANCIA']);
				$hora_limite_inferior=strtotime($row['TIME_SEM_TOLERANCIA']);
				echo "<tr>";
				if($hora_procurar>=$hora_limite_inferior && $hora_procurar<=$hora_limite_superior)
				{
					echo "<td>".$row['NOME']."</td>";
					echo "<td>".$row['DIA']."</td>";
					echo "<td>".$row['TIME_SEM_TOLERANCIA']."</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
	}
	else
	echo "Houve um erro na visualização de viagens! Por favor tente novamente.";
}

	function selectConcelho()
	{
		$query_concelho = "SELECT * from concelho order by nome";
		$result_concelho = mysql_query($query_concelho);
		$i = 0;
		while($i < mysql_num_rows($result_concelho))
		{
			$linha_id = mysql_result($result_concelho, $i, "id");
			$linha_nome = mysql_result($result_concelho, $i, "nome");
			if(isset($_GET['concelho']) && $_GET['concelho'] == $linha_id)
				echo "<option value='$linha_id' selected>$linha_nome</option>";
			else
				echo "<option value='$linha_id'>$linha_nome</option>";
			$i++;
		}
	}

	function selectFreguesia()
	{
		if(isset($_GET['concelho']))
		{
			$query_freguesia = "SELECT * from freguesia WHERE freguesia.concelho_id =\"".$_GET['concelho']."\" order by nome";
			$result_freguesia = mysql_query($query_freguesia);
			$i = 0;
			while($i < mysql_num_rows($result_freguesia))
			{
				$linha_id = mysql_result($result_freguesia, $i, "id");
				$linha_nome = mysql_result($result_freguesia, $i, "nome");
				if(isset($_GET['freguesia']) && $_GET['freguesia'] == $linha_id)
					echo "<option value='$linha_id' selected>$linha_nome</option>";
				else
					echo "<option value='$linha_id'>$linha_nome</option>";
				$i++;
			}
		}
	}
	
	function selectLocal()
	{
		if(isset($_GET['freguesia']))
		{
			$query_local = "SELECT * from local WHERE local.freguesia_id =\"".$_GET['freguesia']."\" order by nome";
			$result_local = mysql_query($query_local);
			$i = 0;
			while($i < mysql_num_rows($result_local))
			{
				$linha_id = mysql_result($result_local, $i, "id");
				$linha_nome = mysql_result($result_local, $i, "nome");
				if(isset($_GET['local']) && $_GET['local'] == $linha_id)
					echo "<option value=\"".$linha_id."\" selected>".$linha_nome."</option>";
				else
					echo "<option value=\"".$linha_id."\">".$linha_nome."</option>";
				$i++;
			}
		}
	}
	
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
			<script type="text/javascript" src="forms.js">	</script>
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<div class="main">
						<p>Procurar Boleia</p>
								<table class="procurar_boleia_inserir" method="POST">
									<tr>
										<th>Concelho:</th>
										<th>Freguesia:</th>
										<th>Local:</th>
										<th>Dia:</th>
										<th>Hora:</th>
									</tr>
									
									<form name="pesquisa_local" id="pesquisa_local" method="POST">
									<tr>	
										<td>
											<select class="procurar_boleia"  name="origem_concelho" id="origem_concelho" onChange="selelcionarConcelho()" style="width: 125px">
												<option> </option>
												<?php selectConcelho(); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="origem_freguesia" id="origem_freguesia" onChange="selelcionarFreguesia()" style="width: 130px">
												<option> </option>
												<?php selectFreguesia(); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="origem_local" id="origem_local" onChange="selelcionarLocal()" style="width: 130px">
												<option> </option>
												<?php 
												selectLocal(); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia_dia" name="dia" id="dia">
												<option> </option>
												<option value="segunda">Segunda</option>
												<option value="terca">Ter&ccedil;a</option>
												<option value="quarta">Quarta</option>
												<option value="quinta">Quinta</option>
												<option value="sexta">Sexta</option>
												<option value="sabado">S&aacute;bado</option>
												<option value="domingo">Domingo</option>
											</select>
										</td>
										<td>
											<select class="procurar_boleia_hora" name="origem_hora" id="origem_hora">
												<option> </option>
												<?php for($i=0;$i<24;$i++)
												{ 
													if($i < 10) 
														echo "<option value=\"".$i."\">0".$i."</option>";
													else
														echo "<option value=\"".$i."\">".$i."</option>";
												} ?>
											</select>
											:
											<select class="procurar_boleia_hora" name="origem_minutos" id="origem_minutos">
												<option> </option>
												<option value="00"> 00 </option>
												<option value="05"> 05 </option>
												<?php for($i=10;$i<60;$i+=5){ ?>
													<option value="<?=$i ?>" > <?php echo $i; ?> </option>
												<?php } ?>
											</select>
										</td>
									</tr>
									
									<!--Local DEstino-->
									
									<tr>	
										<td>
											<select class="procurar_boleia"  name="destino_concelho" id="destino_concelho" onChange="selelcionarConcelho()" style="width: 125px">
												<option> </option>
												<?php selectConcelho(); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="destino_freguesia" id="destino_freguesia" onChange="selelcionarFreguesia()" style="width: 130px">
												<option> </option>
												<?php selectFreguesia(); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="destino_destino_local" id="destino_destino_local" onChange="selelcionarLocal()" style="width: 130px">
												<option> </option>
												<?php 
												selectLocal(); ?>
											</select>
										</td>
										<td>
											<select class="destino_procurar_boleia_dia" name="dia" id="dia">
												<option> </option>
												<option value="segunda">Segunda</option>
												<option value="terca">Ter&ccedil;a</option>
												<option value="quarta">Quarta</option>
												<option value="quinta">Quinta</option>
												<option value="sexta">Sexta</option>
												<option value="sabado">S&aacute;bado</option>
												<option value="domingo">Domingo</option>
											</select>
										</td>
										<td>
											<select class="procurar_boleia_hora" name="destino_hora" id="destino_hora">
												<option> </option>
												<?php for($i=0;$i<24;$i++)
												{ 
													if($i < 10) 
														echo "<option value=\"".$i."\">0".$i."</option>";
													else
														echo "<option value=\"".$i."\">".$i."</option>";
												} ?>
											</select>
											:
											<select class="procurar_boleia_hora" name="destino_minutos" id="destino_minutos">
												<option> </option>
												<option value="00"> 00 </option>
												<option value="05"> 05 </option>
												<?php for($i=10;$i<60;$i+=5){ ?>
													<option value="<?=$i ?>" > <?php echo $i; ?> </option>
												<?php } ?>
											</select>
										</td>
									</tr>
									
									<input type="hidden" name="estado" value="resultados"  >
									<tr><input type="submit" value="Procurar"  ></tr>
								</table>
						</form>
					
					<div class="menuEsquerdo">
					<?php
					
					if (isset($_REQUEST['estado'])&&$_REQUEST['estado']=="resultados")
					{
						$concelho = $_REQUEST['concelho'];
						$freguesia = $_REQUEST['freguesia'];
						$local = $_REQUEST['local'];
						$dia= $_REQUEST['dia'];
						$hora=$_REQUEST['hora'];
						$minutos=$_REQUEST['minutos'];
						imprimirDados($concelho,$freguesia,$local,$dia,$hora,$minutos);
					
					?>
					</div>
					<div class="menuDireito">
					<?php
					
						$concelho = $_REQUEST['concelho'];
						$freguesia = $_REQUEST['freguesia'];
						$local = $_REQUEST['local'];
						$dia= $_REQUEST['dia'];
						$hora=$_REQUEST['hora'];
						$minutos=$_REQUEST['minutos'];
						imprimirDados($concelho,$freguesia,$local,$dia,$hora,$minutos);
					}
					?>
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