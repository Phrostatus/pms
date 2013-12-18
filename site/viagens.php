<?php
	include "functions.php";
	
	sec_session_start();
				
	if(login_check() == true)
	{			// PROTECTED PAGE HERE
	
		ligar_BD();
		$query = "SELECT * FROM utilizador 	
		WHERE id = \"".$_SESSION['user_id']."\"";
		$result = mysql_query($query);				//são para poder mostrar os dados pessoais na barra lateral
		
		
	function mostrarViagens()
{
	$id_utilizador=$_SESSION['user_id'];
	$verificar_c= mysql_query("SELECT * FROM condutor where utilizador_id='$id_utilizador'");
	$passageiro_t=mysql_num_rows($verificar_c);
	
	if($passageiro_t!=1)
	{
		$query_viagens = "select itinerario.nome as NOME_ITINERARIO, itinerario.id as ITINERARIO_ID , viagem.id as VIAGEM_ID,
		viagem.condutor_utilizador_id as CONDUTOR, 
		viagem.fim as FIM, viagem.inicio as INICIO
		from viagem JOIN viagem_passageiro on viagem.id=viagem_passageiro.viagem_id
		JOIN passageiro  on viagem_passageiro.passageiro_utilizador_id = passageiro.utilizador_id
		JOIN itinerario on itinerario.id=viagem.itinerario_id
		where passageiro.utilizador_id='$id_utilizador'";
	}
	else
	{
		$query_viagens = "SELECT itinerario.nome AS NOME_ITINERARIO, itinerario.id AS ITINERARIO_ID, viagem.id as VIAGEM_ID,
		viagem.fim as FIM, viagem.inicio as INICIO
		FROM viagem
		JOIN itinerario ON itinerario.id = viagem.itinerario_id
		WHERE viagem.condutor_utilizador_id =1
		GROUP BY itinerario.nome";
	}
	
	mysql_set_charset("utf8");
	$result_viagens =mysql_query($query_viagens);
	if(!mysql_error())
	{
		$contar=0;
		$numero_viagens=mysql_num_rows($result_viagens);
		if(!($numero_viagens==0))
		{
			while($row_viagens = mysql_fetch_array($result_viagens))
			{
				$itinerario_nome=$row_viagens['NOME_ITINERARIO'];
				$itinerario_id=$row_viagens['ITINERARIO_ID'];
				
					
				if($passageiro_t!=1)
				{
					
					//HORAS VIAGENS!
					$hora_inicio=mysql_query("select itinerario_has_local.hora as HORA, itinerario.dia as DIA  from itinerario_has_local 
					join itinerario on itinerario_id=itinerario_has_local.itinerario_id where itinerario.id=$itinerario_id and itinerario_has_local.local_id=\"".$row_viagens['INICIO']."\"");
					while($row_hora_inicio = mysql_fetch_array($hora_inicio))
					{
					$hora_inic=$row_hora_inicio['HORA'];
					$dia=$row_hora_inicio['DIA'];
					}
					
					$hora_fim=mysql_query("select itinerario_has_local.hora as HORA  from itinerario_has_local 
					where itinerario_has_local.itinerario_id=$itinerario_id and itinerario_has_local.local_id=\"".$row_viagens['FIM']."\"");
					$hora_fi=mysql_fetch_array($hora_fim)['HORA'];
					
					//LOCAIS NOMES
					$local_inicio=mysql_query("select nome as NOME from local where local.id=\"".$row_viagens['INICIO']."\"");
					$local_fim=mysql_query("select nome as NOME from local where local.id=\"".$row_viagens['FIM']."\"");
					$local_in =mysql_fetch_array($local_inicio)['NOME'];
					$local_fi =mysql_fetch_array($local_fim)['NOME'];
					$id_condutor=$row_viagens['CONDUTOR'];
					
					//DADOS CONDUTOR
					$dados_condutor=mysql_query("select nome as NOME, mail as EMAIL, telemovel as TELEMOVEL 
					from utilizador where utilizador.id='$id_condutor'");
					while($dados = mysql_fetch_array($dados_condutor))
					{
						$nome_condutor=$dados['NOME'];
						$telemovel_condutor=$dados['TELEMOVEL'];
						$email_condutor=$dados['EMAIL'];
					}	
					
					if ($contar==0)
					{
					echo "<table><tr><td>ITINERARIO</td><td>CONDUTOR</td><td>TELEMOVEL</td><td>EMAIL</td><td>DIA</td><td>INICIO</td></td><td>HORA</td><td>FIM</td></td><td>HORA</td></tr>";
					$contar=1;
					}
					?>
					<tr>
					<td><?php echo $itinerario_nome?></td>
					<td><?php echo  $nome_condutor ?></td>
					<td><?php echo  $telemovel_condutor?></td>
					<td><?php echo  $email_condutor?></td>
					<td><?php echo  $dia?></td>
					<td><?php echo  $local_in?></td>
					<td><?php echo  $hora_inic?></td>
					<td><?php echo  $local_fi?></td>
					<td><?php echo  $hora_fi?></td>
					
					<td><form method="POST" action="cancelar_viagem.php">
					<input type="hidden" name="itinerario_id" value="<?=$row_viagens['ITINERARIO_ID']?>">
					<input type="hidden" name="viagem_id" value="<?=$row_viagens['VIAGEM_ID']?>">
					<input type="button"  value="Cancelar" onClick="if(confirm('Tem a certeza que quer cancelar viagem ?')) {this.form.submit();}">
					</form>
					<td>
					<?php
					echo"</tr>";
				}
				else
				{
					if ($contar==0)
					{
					echo "<table class='locais_itinerario'><tr><td>ITINERARIO</td><td>HORA</td><td>INICIO</td><td>HORA</td><td>FIM</td><td>HORA</td><td>PASSAGEIRO</td><td>TELEMOVEL</td><td>EMAIL</td></tr>";
					$contar=1;
					}
					
					$selecionar_passageiros=mysql_query("SELECT viagem.id AS VIAGEM_ID, viagem.inicio AS INICIO, viagem.fim AS FIM
														FROM viagem
														JOIN viagem_passageiro ON viagem.id = viagem_passageiro.viagem_id
														WHERE viagem.itinerario_id ='$itinerario_id'
														 ");
					
					$num_passageiros_itinerario=mysql_num_rows($selecionar_passageiros);
					
					echo "<tr><td rowspan='$num_passageiros_itinerario'>$itinerario_nome</td>";
					$query_passageiros_restrito=mysql_query("SELECT viagem.id AS VIAGEM_ID, viagem.inicio AS INICIO, viagem.fim AS FIM
														FROM viagem
														JOIN viagem_passageiro ON viagem.id = viagem_passageiro.viagem_id
														WHERE viagem.itinerario_id ='$itinerario_id' group by viagem.id");
	
					while($row_selecionar_passageiros = mysql_fetch_array($query_passageiros_restrito))
					{
							//HORAS VIAGENS!
						$hora_inicio=mysql_query("select itinerario_has_local.hora as HORA, itinerario.dia as DIA  from itinerario_has_local 
						join itinerario on itinerario_id=itinerario_has_local.itinerario_id where itinerario.id=$itinerario_id and itinerario_has_local.local_id=\"".$row_selecionar_passageiros['INICIO']."\"");
						while($row_hora_inicio = mysql_fetch_array($hora_inicio))
						{
						$hora_inic=$row_hora_inicio['HORA'];
						$dia=$row_hora_inicio['DIA'];
						}
						
						$hora_fim=mysql_query("select itinerario_has_local.hora as HORA  from itinerario_has_local 
						where itinerario_has_local.itinerario_id=$itinerario_id and itinerario_has_local.local_id=\"".$row_selecionar_passageiros['FIM']."\"");
						$hora_fi=mysql_fetch_array($hora_fim)['HORA'];
						
						//LOCAIS
						$local_inicio=mysql_query("select nome as NOME from local where local.id=\"".$row_selecionar_passageiros['INICIO']."\"");
						$local_fim=mysql_query("select nome as NOME from local where local.id=\"".$row_selecionar_passageiros['FIM']."\"");
						$local_in =mysql_fetch_array($local_inicio)['NOME'];
						$local_fi =mysql_fetch_array($local_fim)['NOME'];
					
						$procurar_locais="SELECT * FROM `viagem_passageiro` WHERE viagem_id=\"".$row_selecionar_passageiros['VIAGEM_ID']."\" ";
						$total_locais=mysql_query($procurar_locais);
						$numero_de_locais=mysql_num_rows($total_locais);
						echo "<td rowspan='$numero_de_locais'>$dia</td>";
						echo "<td rowspan='$numero_de_locais'>$local_in</td>";
						echo "<td rowspan='$numero_de_locais'>$hora_inic</td>";
						echo "<td rowspan='$numero_de_locais'>$local_fi</td>";
						echo "<td rowspan='$numero_de_locais'>$hora_fi</td>";
						
						$procurar_locais_restrito=$procurar_locais."group by viagem_id";

						$total_locais_pesquisar=mysql_query($procurar_locais_restrito);
						
						while($row_total_locais = mysql_fetch_array($total_locais_pesquisar))
						{
							$local_viagem=mysql_query("SELECT utilizador.nome AS NOME, utilizador.mail AS EMAIL, utilizador.telemovel AS TELEMOVEL
										FROM viagem_passageiro
										JOIN passageiro ON passageiro.utilizador_id = viagem_passageiro.passageiro_utilizador_id
										JOIN utilizador ON passageiro.utilizador_id = utilizador.id
										WHERE viagem_passageiro.viagem_id =\"".$row_selecionar_passageiros['VIAGEM_ID']."\" GROUP BY utilizador.id ");
								$numero_s=mysql_num_rows($local_viagem);
							while($row_local_viagem = mysql_fetch_array($local_viagem))
							{
								echo "<td>".$row_local_viagem['NOME']."</td>";
								echo "<td>".$row_local_viagem['TELEMOVEL']."</td>";
								echo "<td>".$row_local_viagem['EMAIL']."</td>";
								
								
								if($numero_s>1)
								echo "</tr>";
								
								
							}
						echo "</tr>";
						}
						
									
					}
					
				}
			}
			echo "</table>";
		}
		else 
			echo "<center><h3>Não tem viagens marcadas</h3></center>";
	}
	else
		echo "Houve um erro na visualização de viagens! Por favor tente novamente.";
}
?>
		<html>
			<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
			<body>
				<div class="body">
					<?php cabecalho(2); ?>
					<div class="main">
						<?php 
						mostrarViagens();
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