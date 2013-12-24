<?php
	function imprimirDados($concelho_o,$freguesia_o,$local_o,$horas_o,$concelho_d,$freguesia_d,$local_d,$horas_d,$dia)
	{
		mysql_set_charset("utf8");
		$itinerariosDia = mysql_query("SELECT itinerario.condutor_utilizador_id as CONDUTOR, itinerario.id as ID_ITINERARIO, itinerario.lugares_livres AS LIVRES , itinerario.nome as NOME  from itinerario  where itinerario.dia='$dia'");
		;
		
		if(!mysql_error())
		{
			$numeroviagens=0;
			echo "<table><td>NOME ITINERARIO</td><td>DIA</td><td>CHEGADA ORIGEM</td><td>PARTIDA</td><td>CHEGADA DESTINO</td><td>LIMITE</td>";
			while ($rowItinerariosDia = mysql_fetch_array($itinerariosDia))
			{
				if(!($rowItinerariosDia['LIVRES']==0))
				{	
					$itinerario=$rowItinerariosDia['ID_ITINERARIO'];
					$itinerarioLocais=mysql_query(" SELECT itinerario_has_local.hora AS TIME_SEM_TOLERANCIA,SEC_TO_TIME( TIME_TO_SEC( itinerario_has_local.hora ) + ( itinerario_has_local.tolerancia *60 ) ) AS TIME_COM_TOLERANCIA, itinerario_has_local.hora AS HORAS ,
												local.id AS LOCAL_ID from
												itinerario 
												JOIN itinerario_has_local ON itinerario.id=itinerario_has_local.itinerario_id 
												JOIN LOCAL ON itinerario_has_local.local_id = local.id  
												WHERE itinerario.id='$itinerario'");
					echo mysql_error();
					$horaInicio=false;
					$horaFinal=false;
					$imprimirDadosOrigem="";
					$imprimirDadosDestino="";
					while ($verificarHoras = mysql_fetch_array($itinerarioLocais))
					{
						$hora_origem = strtotime($horas_o);
						$hora_destino = strtotime($horas_d);
						$hora_tolerancia = strtotime($verificarHoras['TIME_COM_TOLERANCIA']);
						$hora_sem_tolerancia = strtotime($verificarHoras['TIME_SEM_TOLERANCIA']);


						if(!$horaInicio)
						{
							if($hora_origem >= $hora_sem_tolerancia && $hora_origem <= $hora_tolerancia)
							{
								if($verificarHoras['LOCAL_ID'] == $local_o)
								{
								
									$imprimirDadosOrigem="<td>$dia</td><td><center>".
									$verificarHoras['TIME_SEM_TOLERANCIA']."<td><center>".$verificarHoras['TIME_COM_TOLERANCIA']."</center></td>";
									
								}			
								$horaInicio=true;
							}
						}
						
						if(!$horaFinal)
						{
							if($hora_destino >= $hora_sem_tolerancia && $hora_destino <= $hora_tolerancia)
							{
								if($verificarHoras['LOCAL_ID'] == $local_d)
								{
									$imprimirDadosDestino="<td><center>".
									$verificarHoras['TIME_SEM_TOLERANCIA']."<td><center>".$verificarHoras['TIME_COM_TOLERANCIA']."</center></td>";
									
								}			
								$horaFinal=true;
							}
						}
						
					}
					if($imprimirDadosOrigem!="" && $imprimirDadosDestino!="" )
					{
						$numeroviagens=$numeroviagens+1;
						echo "<tr><td>".$rowItinerariosDia['NOME']."</td>".$imprimirDadosOrigem.$imprimirDadosDestino."<td>";	
						
						$condutor=mysql_query("SELECT condutor.utilizador_id as ID FROM condutor join itinerario ON condutor.utilizador_id=itinerario.condutor_utilizador_id where itinerario.id=".$rowItinerariosDia['ID_ITINERARIO']."");
						if(mysql_error())
							{
							die();
							echo "Não foi possivel marcar viagem";
							}
						$condutor_id =mysql_fetch_array($condutor)['ID'];
					?>
					<form method="POST" action="marcar_viagem.php">
						<input type="hidden" name="itinerario_id" value="<?=$rowItinerariosDia['ID_ITINERARIO']?>">
						<input type="hidden" name="nome_itinerario" value="<?=$rowItinerariosDia['NOME']?>">
						<input type="hidden" name="inicio" value="<?=$local_o?>">
						<input type="hidden" name="final" value="<?=$local_d?>">
						<input type="hidden" name="condutor" value="<?=$condutor_id?>">
						<input type="button" class="apagar_local" value="M" onClick="if(confirm('Tem a certeza que quer marcar viagem ?')) {this.form.submit();}">
				
					</form>
					<?php
					echo "</td></tr>";
					}
				}	
			}
			
			echo "</table>";
		}	
		else
			echo "Houve um erro na visualização de viagens! Por favor tente novamente.";
		if($numeroviagens==0)
			echo "Não existem viagens no horario inserido.";
	}

function associarPassageiro()
{

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
												<option value="Segunda">Segunda</option>
												<option value="Terça">Terça</option>
												<option value="Quarta">Quarta</option>
												<option value="Quinta">Quinta</option>
												<option value="Sexta">Sexta</option>
												<option value="Sábado">Sábado</option>
												<option value="Domingo">Domingo</option>
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
									<tr><td><input type="submit" value="Procurar"></td></tr>
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
						if(isset($_REQUEST['estado']) && $_REQUEST['estado']=="sucesso")
						{
							echo "<br><br><center><h2>Viagem Marcada com Sucesso</h2></center>";
						}
						if(isset($_REQUEST['estado']) && $_REQUEST['estado']=="marcado")
						{
							echo "<br><br><center><h2>Já tem marcada esta viagem!!</h2></center>";
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