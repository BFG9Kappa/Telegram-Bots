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
	 * Verifica si un argumento se
	 * identifica como un comando 
	 * de Telegram.
	 *
	 * @param string.
	 *
	 * @return bool.
	 */
	public static function isACommand($argum)
	{
		return ((isset($argum[0])) && ($argum[0] == '/') 
			&& (self::contarChar($argum,'/') == 1)) ? true : false;
	}



	/**
	 * Devuelve el nº de coincidencias
	 * con un carácter especificado por
	 * parámetro.
	 *
	 * @param string (palabra).
	 * @param string (carácter clave).
	 *
	 * @return int (nº de coincidencias).
	 */
	public static function contarChar($argum,$ch)
	{
		$cont = 0;
		$tam = strlen($argum);
		while ($tam--) {
			$cont += ($argum[$tam] == $ch) ? 1 : 0;
		}

		return $cont;
	}



	/**
	 * Comprueba entre los comandos
	 * especificados, para retornar
	 * una respuesta.
	 *
	 * @param array (array de argumentos).
	 *
	 * @return string (respuesta al comando).
	 */
	public static function buscarComando($argumentos,$num)
	{
		/*
		 *  Comandos Simples (único argumento).
		 */
		if (($num === 1) && ($argumentos[0] === '/start')) {
			return 'Hola';
		} elseif (($num === 1) && ($argumentos[0] === '/help')) {
			return 'Ayuda';
		} elseif (($num === 1) && ($argumentos[0] === '/ping')) {
			return 'pong';

		/*
		 *  Comando Complejo (argumentos múltiples).
		 */
		} elseif (($num === 2) && ($argumentos[0] === '/di')) {
			return self::sanitizar($argumentos[1]);
		} else {
			throw new Exception("Error Comando");
		}
	}


	/**
	 * Sanitiza un string de 
	 * posibles inyecciones.
	 *
	 * @param string (palabra).
	 *
	 * @return string (palabra sanitizada).
	 */
	public static function sanitizar($cadena)
	{
		return htmlspecialchars(str_replace('\'', '', $cadena),ENT_QUOTES);
	}



}	// Fin Clase.


?>