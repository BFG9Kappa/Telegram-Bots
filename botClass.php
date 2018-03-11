<?php

/**
* Clase Bot.
*
*/
class Bot
{

    /**
     * Url de la Api de Telegram.
     *
     * @var string $url.
     */
	protected $url = 'https://api.telegram.org/bot';


    /**
     * Tiempo de Expiración de Petición a la API.
     *
     * @var int $telegramtimeout.
     */
	protected $telegramtimeout = 20;


	/**
     * Token del Bot (generado con
     *  BotFather desde Telegram).
     *
     * @var string $token
     */
    protected $token;


    /**
     * Administrador del Bot.
     *
     * @var int $admin
     */
    protected $admin;


    /**
     * Objeto cURL.
     *
     * @var cURL type.
     */
    protected $curl;



    /**
     * Constructor del Bot.
     *
     * @param string (Token Bot).
     */
    public function __construct($token,$admin)
    {
    	$this->curl = curl_init();

    	$this->token = $token;
    	$this->admin = $admin;
    }


    /**
     * Cerrando cURL.
     */
    public function __destruct()
    {
        $this->curl && curl_close($this->curl);
    }


    /**
     *  Envia un Mensaje al Usuario de Telegram.
     *
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
	public function enviarMensaje($chat_id,$text,$parse_mode,$disable_web_page_preview=null,
		$disable_notification=null,$reply_to_message_id=null,$reply_markup=null)
	{
		$opciones = [

            CURLOPT_URL => $this->url . $this->token . '/sendMessage',
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

		return ($this->lanzarcURL($opciones)) ? true : false; 
	}


     /**
     *  Envia un Mensaje al Usuario de Telegram.
     *
     * @param objeto json (Estructura del Mensaje Recibido).
     *
     * @return bool (Correcto || Error)     
     */
	public function procesarMensaje($respuesta)
	{
		try{

			// Decodificando la Respuesta del JSON.
			$mensaje = self::validarRespuestaJSON($respuesta);

	    	// Verificando si es el Administrador el remitente.
	    	if ($mensaje->message->from->id == $this->admin) {

	    		$contestar = ($mensaje->message->text == 'ping') ? 'pong' : (($mensaje->message->text == 'pong') ? 'ping' : '404');

	    		$this->enviarMensaje($this->admin,$contestar,'Markdown',null,null,null,null);

	    		$fp = fopen('datos.txt', 'a+');
      			fwrite($fp, 'Admin envia: '.$mensaje->message->text);
      			fclose($fp);

	    		return $mensaje;
	    	}

		}catch(Exception $e){
			echo 'Excepción ocurrida en procesarMensaje: ',  $e->getMessage(), '\n';
			return false;

		}
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
     * .
     *
     * @param int (Identificador de Usuario Telegram).
     *
     * @return bool (Correcto || Error).
     *
	private function verificarUsuario($userid)
	{
		try{



		}catch(Exception $e){
			echo 'Excepción ocurrida en verificarUsuario: ',  $e->getMessage(), '\n';
			return false;
		}
	}*/



    /**
     * Enviando Petición API.
     *
     * @param array (Opciones cURL).
     *
     * @return bool (Correcto || Error).
     */
	private function lanzarcURL($opciones)
	{
		try{

			curl_setopt_array($this->curl, $opciones);
			$respuesta = curl_exec($this->curl);

			// Verificando la Respuesta de la API.
			if (self::validarRespuestaJSON($respuesta)){
				return $respuesta;
			} else {
				throw new Exception('La API nos devuelve error al mensaje.');
			}

		}catch(Exception $e){
			echo 'Excepción ocurrida en lanzarcURL: ',  $e->getMessage(), '\n';
			return false;
		}
	}


    /**
     * Verificador de la Respuesta de la API.
     *
     * @param objeto json.
     *
     * @return bool (Correcto || Error).
     */
    public static function validarRespuestaJSON($json)
    {
    	$respuesta = json_decode($json);

    	// Verificando que se ha podido decodificar el JSON.
    	if ($respuesta == null) {
    		throw new Exception('Error Decodificando el JSON.');
    	} else {
    		return $respuesta;
    	}
    }




} // Fin Clase Bot.


?>