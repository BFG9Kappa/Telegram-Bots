<?php

/**
 *  validateClass.php
 *
 *  Clase Validaciones de Telegram.
 *
 *  Autor: Secu <secury@stationx11.es>
 *
 *  Requerido para las validaciones
 *  pertinentes sobre los mensajes.
 *
 */

class Validaciones
{

	/**
	 * Función que verifica si un argumento
	 * se identifica como un comando válido
	 * para el Bot de Telegram.
	 *
	 * @param string `comando`.
	 *
	 * @return bool.
	 */
	public static function isACommand($argum)
	{
		// Asegura que empieza por '/' y que sólo tiene una '/'.
		return (bool)((isset($argum[0])) && ($argum[0] === '/') 
			&& (self::contarChar($argum,'/') === 1)) ? true : false;
	}



	/**
	 * Función que obtiene el nº de apariciones
	 * de un carácter objetivo, sobre una cadena.
	 *
	 * @param string `cadena`.
	 * @param string `carácter objetivo`.
	 *
	 * @return int `nº de coincidencias`.
	 */
	public static function contarChar($argum,$ch)
	{
		$cont = 0;
		$tam = strlen($argum);
		while ($tam--) {
			$cont += ($argum[$tam] === $ch) ? 1 : 0;
		}

		return $cont;
	}



	/**
	 * Verificador de la Respuesta de la API.
	 *
	 * @param objeto JSON.
	 *
	 * @return string.
	 */
	public static function validarRespuestaJSON($json)
	{
		$respuesta = json_decode($json,true);

		// Verificando que se ha podido decodificar el JSON.
		if ($respuesta == null) {
			throw new Exception('Error Decodificando el JSON.');
		} else {
			return $respuesta;
		}
	}


}	// Fin Clase.

?>