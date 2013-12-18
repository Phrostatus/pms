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
		$query_viagens = "select itinerario.nome as NOME_ITINERARIO, itinerario.id as ITINERARIO_ID , viagem.id as VIAGEM_ID,
		viagem.condutor_utilizador_id as CONDUTOR, 
		viagem.fim as FIM, viagem.inicio as INICIO
		from viagem JOIN viagem_passageiro on viagem.id=viagem_passageiro.viagem_id
		JOIN passageiro  on viagem_passageiro.passageiro_utilizador_id = passageiro.utilizador_id
		JOIN itinerario on itinerario.id=viagem.itinerario_id
		where viagem.condutor_utilizador_id='$id_utilizador'";
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
				
				$local_inicio=mysql_query("select nome as NOME from local where local.id=\"".$row_viagens['INICIO']."\"");
				$local_fim=mysql_query("select nome as NOME from local where local.id=\"".$row_viagens['FIM']."\"");
				$local_in =mysql_fetch_array($local_inicio)['NOME'];
				$local_fi =mysql_fetch_array($local_fim)['NOME'];
					
				if($passageiro_t!=1)
				{
					
					$id_condutor=$row_viagens['CONDUTOR'];
					$dados_condutor=mysql_query("select nome as NOME, mail as EMAIL, telemovel as TELEMOVEL from utilizador where utilizador.id='$id_condutor'");
					while($dados = mysql_fetch_array($dados_condutor))
					{
						$nome_condutor=$dados['NOME'];
						$telemovel_condutor=$dados['TELEMOVEL'];
						$email_condutor=$dados['EMAIL'];
					}	
					
					if ($contar==0)
					{
					echo "<table><tr><td>ITINERARIO</td><td>CONDUTOR</td><td>TELEMOVEL</td><td>EMAIL</td><td>INICIO</td><td>FIM</td></tr>";
					$contar=1;
					}
					?>
					<tr>
					<td><?php echo $itinerario_nome?></td>
					<td><?php echo  $nome_condutor ?></td>
					<td><?php echo  $telemovel_condutor?></td>
					<td><?php echo  $email_condutor?></td>
					<td><?php echo  $local_in?></td>
					<td><?php echo  $local_fi?></td>
					
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
					echo "<table><tr><td>ITINERARIO</td><td>PASSAGEIRO</td><td>TELEMOVEL</td><td>EMAIL</td><td>INICIO</td><td>FIM</td></tr>";
					$contar=1;
					}
					
					$viagem_id=$row_viagens['VIAGEM_ID'];
					$selecionar_passageiros=mysql_query("SELECT utilizador.nome as NOME , utilizador.mail as EMAIL, utilizador.telemovel as TELEMOVEL
					from viagem_passageiro join passageiro on viagem_passageiro.passageiro_utilizador_id=passageiro.utilizador_id
					join utilizador on utilizador.id=passageiro.utilizador_id where viagem_passageiro.viagem_id='$viagem_id'");
					
					$num_passageiros=mysql_num_rows($selecionar_passageiros);
					echo "<tr><td rowspan='$num_passageiros'>$itinerario_nome</td>";
					while($row_selecionar_passageiros = mysql_fetch_array($selecionar_passageiros))
					{
						echo "<td>".$row_selecionar_passageiros['NOME']."</td>";
						echo "<td>".$row_selecionar_passageiros['TELEMOVEL']."</td>";
						echo "<td>".$row_selecionar_passageiros['EMAIL']."</td>";
						echo "<td>".$local_in."</td>";
						echo "<td>".$local_fi."</td></tr>";						
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