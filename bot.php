<?php

/*
 *  bot.php
 *
 *  Bot de Telegram utilizando un Wrapper Nativo Propio.
 *
 *  Autor: Secu <secury@stationx11.es>
 *
 *  Simple Programa que precisa de la Clase
 *  donde estan definidos todos los métodos
 *  imprescindibles de la API de Telegram.
 */


/**
 * Datos de Configuración para el Bot.
 *
 * @const string (Clave de identificación).
 * @const string (Dirección de nuestro Bot).
 * @const string (Token concedido por BotFather).
 * @const int (ID del Administrador).
 */
define('KEY', '');
define('REFERENCIA', ''.KEY);
define('TOKEN', '');
define('ADMIN',);


require "botClass.php";
require "validateClass.php";
require "processClass.php";


// Si recibe el parámetro Key, significa que el mensaje viene de Telegram.
if (isset($_GET['key'])) {
	
	// Si no me coinciden las Claves, significa que alguien ha descubierto la ruta.
	if ($_GET['key'] === KEY) {

		// Obteniendo los datos retornados por la API en formato JSON.
		$datos = file_get_contents('php://input');

		// Obteniendo a partir del JSON el array respuesta.
		$datos = Validaciones::validarRespuestaJSON($datos);


		// Comprobando si hay un Nuevo Usuario en el Chat.
		if (($name = Process::esNuevoMiembro($datos))!=null) {

			// Comprobando que el Usuario no es un Bot.
			if (!Process::esUnBot($datos)) {

				Bot::enviarMensaje(TOKEN,ADMIN,'El Usuario: '.$name.' se ha unido al grupo','Markdown',null,null,null,null);
			} else {
				
				// Eliminando al Nuevo Usuario que es un Bot.
				Bot::eliminarMiembro(TOKEN,$datos['message']['chat']['id'],$datos['message']['new_chat_participant']['id']);
			}
		} else if (($name = Process::seFueUsuario($datos))!=null) {

			Bot::enviarMensaje(TOKEN,ADMIN,'El Usuario: '.$name.' ha abandonado el grupo','Markdown',null,null,null,null);

		} else {

			//$pasarle = print_r($datos,true);
			//Bot::enviarMensaje(TOKEN,ADMIN,$pasarle,'Markdown',null,null,null,null);

			// Comprobando si el Mensaje es un Sticker o GIF.
			if (Process::esUnSticker($datos)){
				Bot::eliminarMensaje(TOKEN,$datos['message']['chat']['id'],$datos['message']['message_id']);
			} else if (Process::esUnGif($datos)){
				Bot::eliminarMensaje(TOKEN,$datos['message']['chat']['id'],$datos['message']['message_id']);
			}

			Bot::enviarMensaje(TOKEN,ADMIN,'Nuevo Mensaje Normal','Markdown',null,null,null,null);
		}

	} else {
		
		Bot::enviarMensaje(TOKEN,ADMIN,'Se han equivocado con la KEY xD','Markdown',null,null,null,null);

		echo '{"Error" : "8================D"}';
	}


// Para establecer un WebHook con la API.
} else if (isset($_GET['setHook'])) {

	// Estableciendo el Webhook a la API.
	$ret = Bot::establecerWebhook(TOKEN,REFERENCIA);

// Para eliminar un WebHook con la API.
} else if (isset($_GET['setHook'])) {

	// Eliminando el Webhook de la API.
	$ret = Bot::removerWebhook(TOKEN);

} else {
	
	Bot::enviarMensaje(TOKEN,ADMIN,'Nuevo Mensaje sin KEY xD','Markdown',null,null,null,null);
	echo '{"Error" : "8====D"}';
}

?>
