<script type="text/javascript" src="sha512.js"> </script>
<script type="text/javascript" src="forms.js">	</script>

<?php include "functions.php"; ?>

<html>
    <link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
	<body>
    	<div class="body">
			<?php cabecalho(1); ?>
			<div class="main">
                <form name="registo" id="registo" method="post" action="submit_registo.php">
					<div class="registo_item"><label class="registo">*Nome:</label> <input type="text" name="nome" id="nome" onBlur="verificar_registo()" autofocus=true/>
	                    <div id="erro_nome" class="erro_registo" hidden="true">Nome inválido !</div>
                    </div>

					<div class="registo_item"><label class="registo">*E-mail :</label>	<input type="text" name="mail" 	id="mail" onBlur="verificar_registo()"/>
						<div id="erro_mail" class="erro_registo" hidden="true">E-mail inválido !</div>
                    </div>

					<div class="registo_item"><label class="registo">*Telem&oacute;vel: </label>	<input type="text" name="telemovel" id="telemovel" onKeyUp="verificar_registo()"/>
						<div id="erro_telemovel" class="erro_registo" hidden="true">Número de telémovel inválido !</div>
                    </div>

					<div class="registo_item"><label class="registo">Morada:</label> <input type="text" name="morada" id="morada" /></div>

                    <div class="registo_item"><label class="registo">Tipo de utilizador:</label>
                    	<div>
							<input type="checkbox" name="passageiro" id="chk_passageiro" value="1" checked onChange="verificar_registo()"><label>Passageiro</label>
	                        <input type="checkbox" name="condutor" id="chk_condutor" value="1" onChange="verificar_registo()"><label>Condutor</label>
                        </div>
					</div>

					<div class="registo_item"><label class="registo">*Password:</label> <input type="password" name="password" id="password" onBlur="verificar_registo()"/>
                    	<div id="erro_password" class="erro_registo" hidden="true">Password inválida! (min 4 carateres)</div>
                    </div>

					<div class="registo_item"><label class="registo">*Confirma&ccedil;&atilde;o: </label>
                    			<input type="password" name="confirmacao" 	id="confirmacao" onKeyUp="verificar_registo()"/>
						<div id="erro_confirmacao" class="erro_registo" hidden="true">Passwords não coincidem !</div>
        			</div>

                    <div class="registo_item"><input class="login" id="s1" type="button" name="s1" value="Registar" onClick="formhash(this.form, this.form.password);" disabled="true"/></div>
                </form>
				<?php
					if(isset($_GET['erro']) && $_GET['erro'] == 1)
						echo "<div class=\"erro_registo\">E-mail j&aacute; registado</div>";
					else if(isset($_GET['erro']) && $_GET['erro'] == 2)
						echo "<div class=\"erro_registo\">Telem&oacute;vel j&aacute; registado</div>";
					else if(isset($_GET['erro']) && $_GET['erro'] == 3)
						echo "<div class=\"erro_registo\">Nome inv&aacute;lido.</div>";
					else if(isset($_GET['erro']) && $_GET['erro'] == 4)
						echo "<div class=\"erro_registo\">Email inv&aacute;lido.</div>";
				?>
			</div>
			<?php dadosPessoais(2, NULL); ?>
			<?php rodape(); ?>
        </div>
	</body>
</html>
