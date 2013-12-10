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
								<a href='start_page.php'><li class='menu'> P&aacute;gina Inicial </li></a>";
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
				Miguel Ribeiro, Francisco Barros, Juan Macedo, Pedro Camacho, Miguel Casaca
			  </div>";
	}

	function dadosPessoais($pagina_inicial, $result)
	{
		if($pagina_inicial == 1)
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
		else if($pagina_inicial == 2) //registo
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
					<p> <input type='text' name='mail'	   id='mail' placeholder='mail@host.com'/> </p>
					<p> <input type='password' name='password' id='password' placeholder='password'/> </p>

					<p>	<input class='login' type='button' name='login' value='Login' onClick='formhash(this.form, this.form.password);' /> </p>
				</form>
			</div>";
		}
		else
		{


			echo	"<div class='account_div'>
						<p align='center'> Sess&atilde;o Iniciada </p>
						<p align='left'> Tipo: ".$_SESSION['tipo']."</p>
						<p align='left'> Utilizador:\n".
							mysql_result($result, 0, "mail").
						"</p>
						<p> &nbsp </p>
						<p> &nbsp </p>
						<p> &nbsp </p>
						<p> &nbsp </p>
						<p align='center'> <a href='logout.php'> Logout </a></p>
					</div>";
		}
	}






?>
