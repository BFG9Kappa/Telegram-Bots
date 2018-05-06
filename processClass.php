<?php

/**
 *  processClass.php
 *
 *  Clase Procesamiento de Mensajes.
 *
 *  Autor: Secu <secury@stationx11.es>
 *
 *  Requerido para procesar cada uno de
 *	los comandos enviados por el usuario,
 *	y para las rutinas de interconexión
 *	entre la Clase Validaciones y la Clase
 *	del Bot de Telegram.
 *
 */

class Process
{


	/**
	 * 	ARREGLAR ESTO.
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
			return $argumentos[1];
		} else {
			throw new Exception("Error Comando");
		}
	}


	/**
	 * Función para detectar un Nuevo Miembro en el chat,
	 * en caso de suceder, devuelve su nombre. 
	 *
	 * @param array (mensaje Api Telegram).
	 *
	 * @return string (nombre ).
	 */
	public static function esNuevoMiembro($datos)
	{
		// Verificando si existe el elemento de Nuevo Miembro.
		if (isset($datos['message']['new_chat_participant'])) {

			// Obteniendo el alias del Usuario.
			if (isset($datos['message']['new_chat_participant']['username'])) {
				return $datos['message']['new_chat_participant']['username'];
			} else if (isset($datos['message']['new_chat_participant']['first_name'])){
				return $datos['message']['new_chat_participant']['first_name'];	
			} else {
				return '{Sin Nombre}';
			}

		} else {
			return null;
		}
	}


	/**
	 * Función para detectar si un Usuario
	 * ha abandona el Grupo. 
	 *
	 * @param array (mensaje Api Telegram).
	 *
	 * @return string (nombre del Usuario).
	 */
	public static function seFueUsuario($datos)
	{
		// Verificando si existe el elemento de Nuevo Miembro.
		if (isset($datos['message']['left_chat_participant'])) {

			// Obteniendo el alias del Usuario.
			if (isset($datos['message']['left_chat_participant']['username'])) {
				return $datos['message']['left_chat_participant']['username'];
			} else if (isset($datos['message']['left_chat_participant']['first_name'])){
				return $datos['message']['left_chat_participant']['first_name'];
			} else {
				return strval($datos['message']['left_chat_participant']['id']);
			}

		} else {
			return null;
		}
	}


	/**
	 * Función para detectar si el Nuevo Miembro
	 * es un Bot o no. 
	 *
	 * @param array (mensaje Api Telegram).
	 *
	 * @return bool.
	 */
	public static function esUnBot($datos)
	{
		return (isset($datos['message']['new_chat_participant']['is_bot'])) ? true : false;
	}


	/**
	 * Función para detectar si el Nuevo Mensaje
	 * es un Sticker o no. 
	 *
	 * @param array (mensaje Api Telegram).
	 *
	 * @return bool.
	 */
	public static function esUnSticker($datos)
	{
		return (isset($datos['message']['sticker'])) ? true : false;
	}


	/**
	 * Función para detectar si el Nuevo Mensaje
	 * es un Gif o no. 
	 *
	 * @param array (mensaje Api Telegram).
	 *
	 * @return bool.
	 */
	public static function esUnGif($datos)
	{
		return (isset($datos['message']['document']['mime_type']) &&
		$datos['message']['document']['mime_type']==='video/mp4') ? true : false;
	}



} // End Process Class.

?>