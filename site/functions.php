<?php
	function sec_session_start() {
			$session_name = 'sec_session_id'; // Set a custom session name
			$secure = false; // Set to true if using https.
			$httponly = true; // This stops javascript being able to access the session id.

			ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.
			$cookieParams = session_get_cookie_params(); // Gets current cookies params.
			session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
			session_name($session_name); // Sets the session name to the one set above.
			session_start(); // Start the php session
			session_regenerate_id(); // regenerated the session, delete the old one.
	}

	function login_check()
	{
		ligar_BD();

		$query = "SELECT id, mail, password FROM utilizador
			WHERE id = '".$_SESSION['user_id']."'";

		$result = mysql_query($query);

		//if($result === FALSE)			//se tiver comentado nao mostra o erro ao user protegendo as variaveis e o codigo ao exterior
		//{
		//	die(mysql_error());
		//}
		//-------------------------------------------------------

	   // Check if all session variables are set
	   if(isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['login_string']))
	   {
			$session_user_id = $_SESSION['user_id'];
			$session_login_string = $_SESSION['login_string'];
			$session_username = $_SESSION['username'];

			$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

			$password = mysql_result($result, 0, "password");
			$user_id = preg_replace("/[^0-9]+/", "", mysql_result($result, 0, "id"));	 // XSS protection as we might print this value
			$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", mysql_result($result, 0, "mail")); // XSS protection as we might print this value
			$login_check = hash('SHA512', $password.$user_browser);

			if($login_check == $session_login_string && $session_user_id == $user_id && $session_username == $username)	  // Logged In!!!!
			{
				return true;
			}
			else	  // Not logged in
			{
				return false;
			}
		}
		else		// Not logged in
		{
			return false;
		}
	}


	function ligar_BD()
	{
		$host="localhost:localhost";
		$user_name="root";
		$database_name="pms-carpool";

		$db=mysql_connect($host, $user_name);

		if (mysql_error() > "") print mysql_error() . "<br>";

		mysql_select_db($database_name, $db);
		if (mysql_error() > "") print mysql_error() . "<br>";
	}

	function cabecalho($pagina_inicial)
	{
		if($pagina_inicial == 1)
		{
			echo "<a href='index.php'>
					<div class='header_div'>
						CAR Pooling MAD
					</div>
				</a>
				<div class='menu'>
					<nav>
						<ul class='menu'>
							<a href='index.php'><li class='menu'>P&aacute;gina Inicial</li></a>
							<a href='registo.php'><li class='menu'>Registo</li></a>
							<a href='sobre.php'><li class='menu'>Sobre</li></a>
							<a href='faq.php'><li class='menu'>FAQ</li></a>
						</ul>
					</nav>
				</div>";
		}
		else
		{
			echo 	"<a href='start_page.php'>
						<div class='header_div'>
							CAR Pooling MAD
						</div>
					</a>
					<div class='menu'>
						<nav>
							<ul class='menu'>
								<a href='start_page.php'><li class='menu'> Página Inicial </li></a>";
								if($_SESSION['tipo'] == "Condutor" || $_SESSION['tipo'] == "Condutor e Passageiro")
									echo "<a href='locais.php'><li class='menu'> Meus Locais </li></a>";
								if($_SESSION['tipo'] == "Passageiro" || $_SESSION['tipo'] == "Condutor e Passageiro")
									echo "<a href='procurar_boleia.php'><li class='menu'> Procurar Boleia </li></a>";
								echo "<a href='viagens.php'><li class='menu'> Viagens Marcadas </li></a>
							</ul>
						</nav>
					</div>";
		}
	}

	function rodape()
	{
		echo "<div class='rodape'>
				Miguel Ribeiro, Francisco Barros, Juan Macedo, Pedro Camacho, Miguel Casaca<br>
				Otimizado para: Google Chrome, Internet Explorer 
			  </div>";
	}

	function dadosPessoais($pagina_inicial, $result)
	{
		if($pagina_inicial == 1)			//   pagina inicial
		{
			echo "<div class='account_div'>
				<p align='center'> <a href='registo.php'> Registo </a> </p>
				<p> Login </p>";
					if(isset($_GET['erro']))   // if id index exists
					{
						if($_GET['erro'] == 1)
							echo "<p class='erro'>LOGIN ERRADO </p>";
					}
			echo	"<form name='login' id='login' method='post' action='submit_login.php'>
					<p> <input type='text' name='mail'	   id='mail' placeholder='mail@host.com' autofocus=true/> </p>
					<p> <input type='password' name='password' id='password'  placeholder='password' /> </p>

					<p>	<input class='login' type='button' name='login' value='Login' onClick='formhash(this.form, this.form.password);' /> </p>
				</form>
			</div>";
		}
		else if($pagina_inicial == 2)		 //registo (tira o autofocus do input pk dos inputs do registo)
		{
			echo "<div class='account_div'>
				<p align='center'> <a href='registo.php'> Registo </a> </p>
				<p> Login </p>";
					if(isset($_GET['erro']))   // if id index exists
					{
						if($_GET['erro'] == 1)
							echo "<p class='erro'>LOGIN ERRADO </p>";
					}
			echo	"<form name='login' id='login' method='post' action='submit_login.php'>
					<p> <input type='text' name='mail'	   id='mail' placeholder='mail@host.com'> </p>
					<p> <input type='password' name='password' id='password' placeholder='password'> </p>

					<p>	<input class='login' type='button' name='login' value='Login' onClick='formhash(this.form, this.form.password);'> </p>
				</form>
			</div>";
		}
		else				// o resto das paginas dpx d fazer login
		{
			$query_notificacao = 'SELECT * FROM notificacao WHERE recetor_id = "'.$_SESSION['user_id'].'" AND lida="0"';
			$result_notificacao = mysql_query($query_notificacao);
			$nao_lidas = mysql_num_rows($result_notificacao);
			
			if($_SESSION['tipo'] == "Condutor")	//descobrir o rating
			{
				$result_rating = mysql_query('SELECT rating FROM condutor WHERE utilizador_id = "'.$_SESSION['user_id'].'"');
				$rating = mysql_fetch_array($result_rating)['rating'];
			}
			else if($_SESSION['tipo'] == "Passageiro")
			{
				$result_rating = mysql_query('SELECT rating FROM passageiro WHERE utilizador_id = "'.$_SESSION['user_id'].'"');
				$rating = mysql_fetch_array($result_rating)['rating'];
			}
			else
			{
				$result_rating = mysql_query('SELECT rating FROM condutor WHERE utilizador_id = "'.$_SESSION['user_id'].'"');
				$rating = mysql_fetch_array($result_rating)['rating'];
				$result_rating = mysql_query('SELECT rating FROM passageiro WHERE utilizador_id = "'.$_SESSION['user_id'].'"');
				$rating = $rating + mysql_fetch_array($result_rating)['rating'];
				$rating = $rating / 2;
			}
				
			
			echo'<div class="account_div">
					<p align="center"> Sessão Iniciada </p>
					<p align="left"> Tipo: '.$_SESSION['tipo'].'</p>
					<p align="left"> Utilizador:'.
						mysql_result($result, 0, "mail").
					'</p>
					<p align="left">Rating: '.$rating.'</p>
					<p> &nbsp </p>';
					
					if($nao_lidas == 0)
						echo '<a href="notificacao.php"><p>Não tem mensagens novas</p></a>';
					else if($nao_lidas == 1)
						echo '<a href="notificacao.php"><p>Tem 1 mensagem nova</p></a>';
					else
						echo '<a href="notificacao.php"><p>Tem '.$nao_lidas.' mensagens novas</p></a>';
					
					
				echo '<p> &nbsp </p>
					<p> &nbsp </p>
					<p align="center"> <a href="logout.php"> Logout </a></p>
				</div>';
		}
	}

	function selectConcelho($valor)
	{
		mysql_set_charset("utf8");
		$query_concelho = "SELECT * from concelho order by nome";
		
		$result_concelho = mysql_query($query_concelho);
		$i = 0;
		while($i < mysql_num_rows($result_concelho))
		{
			$linha_id = mysql_result($result_concelho, $i, "id");
			$linha_nome = mysql_result($result_concelho, $i, "nome");
			if($valor=="origem")
			{
				if(isset($_REQUEST['co']) && $_REQUEST['co'] == $linha_id)
					echo "<option value='$linha_id' selected>$linha_nome</option>";
				else
					echo "<option value='$linha_id'>$linha_nome</option>";
			}
			else
			{
				if(isset($_REQUEST['cd']) && $_REQUEST['cd'] == $linha_id)
					echo "<option value='$linha_id' selected>$linha_nome</option>";
				else
					echo "<option value='$linha_id'>$linha_nome</option>";
			}
			$i++;
		}
	}

	function selectFreguesia($valor)
	{
		mysql_set_charset("utf8");
		if(isset($_REQUEST['cd']) ||isset($_REQUEST['co']))
		{
			if($valor=="origem")
			$query_freguesia = "SELECT * from freguesia WHERE freguesia.concelho_id =\"".$_REQUEST['co']."\" order by nome";
			else
			$query_freguesia = "SELECT * from freguesia WHERE freguesia.concelho_id =\"".$_REQUEST['cd']."\" order by nome";
			
			$result_freguesia = mysql_query($query_freguesia);
			$i = 0;
			while($i < mysql_num_rows($result_freguesia))
			{
				$linha_id = mysql_result($result_freguesia, $i, "id");
				$linha_nome = mysql_result($result_freguesia, $i, "nome");
				
				if($valor=="origem")
				{
					if(isset($_REQUEST['fo']) && $_REQUEST['fo'] == $linha_id)
						echo "<option value='$linha_id' selected>$linha_nome</option>";
					else
						echo "<option value='$linha_id'>$linha_nome</option>";
				}
				else
				{
					if(isset($_REQUEST['fd']) && $_REQUEST['fd'] == $linha_id)
						echo "<option value='$linha_id' selected>$linha_nome</option>";
					else
						echo "<option value='$linha_id'>$linha_nome</option>";
				}
				$i++;
			}
		}
	}
		
	function selectLocal($valor)
	{
		mysql_set_charset("utf8");
		if(isset($_REQUEST['fd']) || isset($_REQUEST['fo']))
		{
			if($valor=="origem")
				$query_local = "SELECT * from local WHERE local.freguesia_id =\"".$_REQUEST['fo']."\" order by nome";
			else
				$query_local = "SELECT * from local WHERE local.freguesia_id =\"".$_REQUEST['fd']."\" order by nome";
			
			$result_local = mysql_query($query_local);
			$i = 0;
			while($i < mysql_num_rows($result_local))
			{
				$linha_id = mysql_result($result_local, $i, "id");
				$linha_nome = mysql_result($result_local, $i, "nome");
				
				if($valor=="origem")
				{
					if(isset($_REQUEST['lo']) && $_REQUEST['lo'] == $linha_id)
						echo "<option value='$linha_id' selected>$linha_nome</option>";
					else
						echo "<option value='$linha_id'>$linha_nome</option>";
				}
				else
				{
					if(isset($_REQUEST['ld']) && $_REQUEST['ld'] == $linha_id)
						echo "<option value='$linha_id' selected>$linha_nome</option>";
					else
						echo "<option value='$linha_id'>$linha_nome</option>";
				}
				$i++;	
			}
		}
	}
?>
