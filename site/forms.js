function formhash(form, password)
{
   // Create a new element input, this will be our hashed password field.
   var p = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden";
   p.value = sha512(password.value);
   // Make sure the plaintext password doesn't get sent.
   password.value = "";
   // Finally submit the form.
   form.submit();
}

function clear_mail(textbox)
{
	if(textbox.value == "mail@host.com")
		textbox.value = "";
}

function restore_mail(textbox)
{
	if(textbox.value == "")
		textbox.value = "mail@host.com";
}

function clear_password(textbox)
{
	if(textbox.value == "password")
	{
		textbox.type = "password";
		textbox.value = "";
	}
	//this.select();
}

function restore_password(textbox)
{
	if(textbox.value == "")
	{
		textbox.type = "text";
		textbox.value = "password";
	}
}

//__ REGIISTO__________________________________________________
function confirmacao_password(password, confirmacao, div)	//true = OK
{
	if(confirmacao.value.length >= password.value.length)
	{
		if(confirmacao.value == password.value)
		{
		   div.hidden = true;
		   return true;
		}
		else
		{
			div.hidden = false;
			return false;
		}
	}
	else
	{
	   div.hidden = true;
	   return false;
	}

}

function verificar_null(textbox, div)
{
	if(textbox.value == "")
		div.hidden = false;
	else
		div.hidden = true;
}

function verificar_telemovel(textbox, div)	  //true = OK
{
	if(textbox.value == "" || !(isNumber(textbox.value)) || textbox.value.length != 9)
	{
		div.hidden = false;
		return false;
	}
	else
	{
		div.hidden = true;
		return true;
	}
}

function verificar_password(textbox, div)	  //true = OK
{
	if(textbox.value == "" || textbox.value.length < 4)
	{
		div.hidden = false;
		return false;
	}
	else
	{
		div.hidden = true;
		return true;
	}
}

function verificar_nome(textbox, div)		//true = OK
{
	verificar_null(textbox, div);
	var ver_nome = textbox.value.match(/[a-zA-Z\u00E0-\u00FC ]+/g);
	if(ver_nome == null || ver_nome.length != 1 || ver_nome[0] != textbox.value)
	{
		div.hidden = false;
		return false;
	}
	else
	{
		div.hidden = true;
		return true;
	}
}

function verificar_mail(textbox, div)
{
	verificar_null(textbox, div);

	if(textbox.value.match(/[a-zA-Z0-9_]+[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)+/g) == null ||
		textbox.value.match(/[.]$/) != null || textbox.value.match(/[@]/g).length > 1)
	{
		div.hidden = false;
		return false;
	}
	else
	{
		div.hidden = true;
		return true;
	}
}

function verificar_registo()
{
	if(    ! verificar_nome(document.getElementById("nome"), document.getElementById("erro_nome"))
		|| ! verificar_mail(document.getElementById("mail"), document.getElementById("erro_mail"))																			  		|| ! verificar_telemovel(document.getElementById("telemovel"), document.getElementById("erro_telemovel"))																			        || (document.getElementById("chk_passageiro").checked == false && document.getElementById("chk_condutor").checked == false)
	    || ! verificar_password(document.getElementById("password"), document.getElementById("erro_password"))
		|| ! confirmacao_password(document.getElementById("password"), document.getElementById("confirmacao"), document.getElementById("erro_confirmacao"))
	)
		document.getElementById("s1").disabled = true;
	else
		document.getElementById("s1").disabled = false;
}

function isNumber(n)
{
  return !isNaN(parseFloat(n)) && isFinite(n);
}



//PROCURAR BOLEIA
function selelcionarConcelho(select_concelho)
{
	var	select_concelho = document.getElementById("concelho");
	window.location= "procurar_boleia.php?co=" + select_concelho[select_concelho.selectedIndex].value;
}


function selelcionarFreguesia(select_freguesia)
{
	var	select_concelho = document.getElementById("origem_concelho");
	var	select_freguesia = document.getElementById("origem_freguesia");

	window.location= "procurar_boleia.php?concelho=" + select_concelho[select_concelho.selectedIndex].value + "fo=" + select_freguesia[select_freguesia.selectedIndex].value;
}

function selecionarOpcoes()
{
	var	select_o_concelho = document.getElementById("co");
	var	select_o_freguesia = document.getElementById("fo");
	var	select_o_local = document.getElementById("lo");
	var	select_d_concelho = document.getElementById("cd");
	var	select_d_freguesia = document.getElementById("fd");
	var	select_d_local = document.getElementById("ld");
	
	window.location= "procurar_boleia.php?co=" +select_o_concelho[select_o_concelho.selectedIndex].value  +
										"&fo=" + select_o_freguesia[select_o_freguesia.selectedIndex].value +
										"&lo="+ select_o_local[select_o_local.selectedIndex].value+
										
										"&cd=" +select_d_concelho[select_d_concelho.selectedIndex].value  +
										"&fd=" + select_d_freguesia[select_d_freguesia.selectedIndex].value +
										"&ld="+ select_d_local[select_d_local.selectedIndex].value;
	
}

function verificar_locais()
{
	if(! verificar_nome(document.getElementById("nome"), document.getElementById("erro_nome")))
		document.getElementById("s1").disabled = true;
	else
		document.getElementById("s1").disabled = false;
}

function selecionarOpcoes_locais(itinerario)
{
	var	select_o_concelho = document.getElementById("co");
	var	select_o_freguesia = document.getElementById("fo");
	var	select_o_local = document.getElementById("lo");
	if(itinerario == null)
		itinerario="";
	window.location= "locais.php?i=" + itinerario + "&co=" + select_o_concelho[select_o_concelho.selectedIndex].value  +
													"&fo=" + select_o_freguesia[select_o_freguesia.selectedIndex].value +
													"&lo=" + select_o_local[select_o_local.selectedIndex].value;	
}

// NOVA MENSAGEM LIMITAR CARATERES
function limitarCaracteres(input, max_char)
{
	if(input.value.length > max_char)
	{
		final_input = slice(input.value(0, input.value.length - 1));
		document.getElementById("caracteres_restantes").value = max_char;
	}
	else
	{
		document.getElementById("caracteres_restantes").innerHTML = max_char - input.value.length;
	}
}