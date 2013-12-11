<?php
	function listarItinerarios()
	{
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
				$result_local = mysql_query($query_local);
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
							<br><br><p>Adicionar novo itinerário</p>
							<form action="">
								<input type="text" name="nome_itinerario" id="nome_itinerario">
								<input type="submit">
							</form>
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