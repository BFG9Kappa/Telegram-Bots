<?php

/**
 *  botClass.php
 *
 *  Clase Bot de Telegram.
 *
 *  Autor: Secu <secury@stationx11.es>
 *
 *  Ideas y metodología extraías del:
 *  Wrapper Nativo de la API de Bots de Telegram.
 *  <https://github.com/TelegramBot/Api>
 */

class Bot
{

	/**
	 * Url de la Api de Telegram.
	 *
	 * @var string $url.
	 */
	static $url = 'https://api.telegram.org/bot';


	/**
	 * Tiempo de Expiración de Petición a la API.
	 *
	 * @var int $telegramtimeout.
	 */
	static $telegramtimeout = 20;



	/**
	 * Enviando Petición API.
	 *
	 * @param array (Opciones cURL).
	 *
	 * @return bool (Correcto || Error).
	 */
	public static function lanzarcURL($opciones)
	{
		$curl = curl_init();

		try{

			curl_setopt_array($curl, $opciones);
			$respuesta = curl_exec($curl);

			// Verificando la Respuesta de la API.
			if (Validaciones::validarRespuestaJSON($respuesta)){
				return $respuesta;
			} else {
				return false;
			}

		}catch(Exception $e){
			echo 'Excepción ocurrida en lanzarcURL: ',  $e->getMessage(), '\n';
			return false;
		}
	}


//========================================================== Funciones API.


	/**
	 *  Envia un Mensaje al Usuario de Telegram.
	 *
	 * @param string (Token del Bot).
	 * @param int (Identificador del Usuario).
	 * @param string (Contenido del Mensaje).
	 * @param string (Formato del Mensaje).
	 * @param string (Deshabilita la previsualización de Links).
	 * @param string (Habilita la notificación silenciosa).
	 * @param string (ID del Usuario si es un Mensaje de Respuesta).
	 * @param string (Para ocultar, mostrar o remover los KeyBoards).
	 *
	 * @return bool (Correcto || Error)     
	 */
	public static function enviarMensaje($token,$chat_id,$text,$parse_mode,$disable_web_page_preview=null,$disable_notification=null,$reply_to_message_id=null,$reply_markup=null)
	{
		$opciones = [

			CURLOPT_URL => self::$url . $token . '/sendMessage',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => [
				'chat_id' => $chat_id,
				'text' => $text,
				'parse_mode' => $parse_mode,
				'disable_web_page_preview' => $disable_web_page_preview,
				'disable_notification' => $disable_notification,
				'reply_to_message_id' => $reply_to_message_id,
				'reply_markup' => $reply_markup,
			]
		];

		return (self::lanzarcURL($opciones)) ? true : false; 
	}


	/**
	 *  Establecer un Webhook a la API de Telegram.
	 *
	 * @param string (Token Bot).
	 * @param string (URL al que enviará Telegram las notificaciones).
	 *
	 * @return objeto json.    
	 */
	public static function establecerWebhook($token,$midireccion)
	{
		$cl = curl_init();
		$opciones = array(
			CURLOPT_URL => 'https://api.telegram.org/bot'.$token.'/setWebhook',
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => [
				'url' => $midireccion,
			]
		);

		curl_setopt_array($cl, $opciones);
		$respuesta = curl_exec($cl);
		
		return $respuesta;
	}


	/**
	 *  Remover un Webhook de la API de Telegram.
	 *
	 * @param string (Token Bot).
	 *
	 * @return objeto json.    
	 */
	public static function removerWebhook($token)
	{
		$cl = curl_init();
		$opciones = array(
			CURLOPT_URL => 'https://api.telegram.org/bot'.$token.'/deleteWebhook',
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => fase,
			CURLOPT_POSTFIELDS => null
		);

		curl_setopt_array($cl, $opciones);
		$respuesta = curl_exec($cl);
		
		return $respuesta;
	}


	/**
	 *  Método para eliminar a un Usuario del Grupo.
	 *
	 * @param string (Token Bot).
	 * @param int (chat id).
	 * @param int (user id).
	 *
	 * @return bool.    
	 */
	public static function eliminarMiembro($token,$chat_id,$user_id)
	{
		$opciones = [

			CURLOPT_URL => self::$url . $token . '/kickChatMember',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => [
				'chat_id' => $chat_id,
				'user_id' => $user_id,
			]
		];

		return (self::lanzarcURL($opciones)) ? true : false;
	}


	/**
	 *  Método para eliminar un Mensaje de un Usuario de un Grupo.
	 *
	 * @param string (Token Bot).
	 * @param int (chat id).
	 * @param int (mensaje id).
	 *
	 * @return bool.    
	 */
	public static function eliminarMensaje($token,$chat_id,$message_id)
	{
		$opciones = [

			CURLOPT_URL => self::$url . $token . '/deleteMessage',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => [
				'chat_id' => $chat_id,
				'message_id' => $message_id,
			]
		];

		return (self::lanzarcURL($opciones)) ? true : false;
	}


} // Fin Clase Bot.

?>