<?php
	function imprimirDados($concelho_o,$freguesia_o,$local_o,$horas_o,$concelho_d,$freguesia_d,$local_d,$horas_d,$dia)
	{
		$listar_Origem =mysql_query("
		SELECT itinerario_has_local.hora AS TIME_SEM_TOLERANCIA,
		SEC_TO_TIME( TIME_TO_SEC( itinerario_has_local.hora ) + ( itinerario_has_local.tolerancia *60 ) ) AS TIME_COM_TOLERANCIA,
		itinerario.id AS ID_ITINERARIO, 
		itinerario.lugares_livres AS LIVRES, 
		itinerario_has_local.hora AS HORAS, 
		local.id AS LOCAL_ID, 
		local.nome AS NOME_LOCAL
		FROM condutor
		JOIN itinerario ON itinerario.dia =  '$dia'
		JOIN itinerario_has_local ON itinerario.id = itinerario_has_local.itinerario_id
		JOIN LOCAL ON itinerario_has_local.local_id = local.id
		");
		
		echo mysql_error();

		if(!mysql_error())
		{
			echo "<table>
			<tr> 
				<th>DIA</th> 
				<th>ORIGEM</th>
				<th>CHEGADA</th>
				<th>PARTIDA</th>
			<tr>";
			while ($row = mysql_fetch_array($listar_Origem))
			{
				$hora_origem = strtotime($horas_o);
				$hora_origem_limite_superior = strtotime($row['TIME_COM_TOLERANCIA']);
				$hora_origem_limite_inferior = strtotime($row['TIME_SEM_TOLERANCIA']);
				
				$hora_destino = strtotime($horas_d);
				$hora_destino_limite_superior = strtotime($row['TIME_COM_TOLERANCIA']);
				$hora_destino_limite_inferior = strtotime($row['TIME_SEM_TOLERANCIA']);
				
				if( ($hora_origem >= $hora_origem_limite_inferior && $hora_origem <= $hora_origem_limite_superior) ||
					($hora_destino >= $hora_destino_limite_inferior && $hora_destino <= $hora_destino_limite_superior))
				{			
					echo "<tr>";
					echo "<td>$dia</td>";
					if($hora_origem >= $hora_origem_limite_inferior && $hora_origem <= $hora_origem_limite_superior)
					{
						if($row['LOCAL_ID'] == $local_o)
						{
							echo "<td><center>".$row['NOME_LOCAL']."</center></td>";
							echo "<td><center>".$row['TIME_SEM_TOLERANCIA']."</center></td>";
							echo "<td><center>".$row['TIME_COM_TOLERANCIA']."</center></td>";
						}
						else 
							echo "<td><center>-</center></td><td><center>-</center></td><td><center>-</center></td>";
						
					}
					else if ($hora_destino >= $hora_destino_limite_inferior && $hora_destino <= $hora_destino_limite_superior)
						echo "</tr>";
				}
				echo "</table>";
			}	
		}
		else
			echo "Houve um erro na visualização de viagens! Por favor tente novamente.";
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
									<th></th>
									<th>Concelho:</th>
									<th>Freguesia:</th>
									<th>Local:</th>
									<th>Hora:</th>
									<th>Dia:</th>
								</tr>
									
								<form name="pesquisa_local" id="pesquisa_local" method="REQUEST">
									<tr>
										<td>Origem</td>										
										<td>
											<select class="procurar_boleia"  name="co" id="co" onChange="selecionarOpcoes()" style="width: 130px">
												<option> </option>
												<?php selectConcelho("origem"); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="fo" id="fo" onChange="selecionarOpcoes()" style="width: 130px">
												<option> </option>
												<?php selectFreguesia("origem"); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="lo" id="lo" style="width: 200px">
												<option> </option>
												<?php selectLocal("origem"); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia_hora" name="hora_o" id="hora_o">
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
											<select class="procurar_boleia_hora" name="min_o" id="min_o">
												<option> </option>
												<option value="00"> 00 </option>
												<option value="05"> 05 </option>
												<?php for($i=10;$i<60;$i+=5){ ?>
													<option value="<?=$i ?>" > <?php echo $i; ?> </option>
												<?php } ?>
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
									</tr>
									
									<!--Local Destino-->
									
									<tr>	
										<td>Destino</td>
										<td>
											<select class="procurar_boleia"  name="cd" id="cd" onChange="selecionarOpcoes()" style="width: 130px">
												<option> </option>
												<?php selectConcelho("destino"); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="fd" id="fd" onChange="selecionarOpcoes()" style="width: 130px">
												<option> </option>
												<?php selectFreguesia("destino"); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia"  name="ld" id="ld" style="width: 200px">
												<option> </option>
												<?php 
												selectLocal("destino"); ?>
											</select>
										</td>
										<td>
											<select class="procurar_boleia_hora" name="hora_d" id="hora_d">
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
											<select class="procurar_boleia_hora" name="min_d" id="min_d">
												<option> </option>
												<option value="00"> 00 </option>
												<option value="05"> 05 </option>
												<?php for($i=10;$i<60;$i+=5){ ?>
													<option value="<?=$i ?>" > <?php echo $i; ?> </option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr><td><input type="submit" value="Procurar"  ></td></tr>
									<input type="hidden" name="estado" value="resultados"  >
								</form>
							</table>
							
						<?php
						if (isset($_REQUEST['estado']) && $_REQUEST['estado'] == "resultados")
						{
							$dia = $_REQUEST['dia'];
							$concelho_o = $_REQUEST['co'];
							$freguesia_o = $_REQUEST['fo'];
							$local_o = $_REQUEST['lo'];
							$hora_o = $_REQUEST['hora_o'];
							$minutos_o = $_REQUEST['min_o'];
							$concelho_d = $_REQUEST['cd'];
							$freguesia_d = $_REQUEST['fd'];
							$local_d = $_REQUEST['ld'];
							$hora_d = $_REQUEST['hora_d'];
							$minutos_d = $_REQUEST['min_d'];
							$horas_o = $hora_o.":".$minutos_o.":00";
							$horas_d = $hora_d.":".$minutos_d.":00";
							imprimirDados($concelho_o,$freguesia_o,$local_o,$horas_o,$concelho_d,$freguesia_d,$local_d,$horas_d,$dia);
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