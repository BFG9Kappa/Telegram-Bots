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
define('ADMIN', );



// Estableciendo datos iniciales de Tiempo y Memoria.
$tinicial = microtime(true);
$minicial = memory_get_usage()/1024;


/**
 *  Recibiendo la Solicitud y
 *  estableciendo el Webhook.
 *
 */
if (!empty($_GET['setHook'])) {
	
	require 'botClass.php';

	// Estableciendo el Webhook a la API.
	$ret = Bot::establecerWebhook(TOKEN,REFERENCIA);

	var_dump($ret);

	// Mostrando Datos de Tiempo Demorado.
	$tfinal = microtime(true);
	echo "\nTiempo gastado: ";
	echo ($tfinal-$tinicial);
	echo " segundos";

	// Mostrando Datos de la Memoria Consumida
	$mfinal = memory_get_usage()/1024;
	echo "\nMemoria al liberar objeto: ";
	echo $mfinal - $minicial;
	echo "Kb\n";

	exit();


/**
 *  Recibiendo la Solicitud y
 *  eliminando el Webhook.
 *
 */
} else if (!empty($_GET['delHook'])){

	require 'botClass.php';

	// Eliminando el Webhook de la API.
	$ret = Bot::removerWebhook(TOKEN);

	var_dump($ret);

	// Mostrando Datos de Tiempo Demorado.
	$tfinal = microtime(true);
	echo "\nTiempo gastado: ";
	echo ($tfinal-$tinicial);
	echo " segundos";

	// Mostrando Datos de la Memoria Consumida
	$mfinal = memory_get_usage()/1024;
	echo "\nMemoria al liberar objeto: ";
	echo $mfinal - $minicial;
	echo "Kb\n";

	exit();


/**
 *  Recibiendo la Key por GET,
 *  y, en caso de ser auténtica,
 *  procesando el Mensaje recibido
 *  de Telegram.
 *
 */
} else if (!empty($_GET['key'])) {

	require 'botClass.php';

	// Verificando que la KEY recibida coincide con la almacenada y propocionada a la API.
	if ($_GET['key'] == KEY) {

		// Obteniendo los datos retornados por la API en formato JSON.
		$datos = file_get_contents('php://input');

		if (!empty($datos)) {
			
			// Creando el Objeto del Bot.
			$bot = new Bot(TOKEN,ADMIN);

			// Procesando el Mensaje recibido de la API.
			$datos = $bot->procesarMensaje($datos);

			/*$fp = fopen("datos.txt", "a+");
			if ($datos==false) {
				fwrite($fp, 'fail');
			} else {
				fwrite($fp, print_r($datos,true));
			}
			fclose($fp);*/


		} else {
			$fp = fopen("datos.txt", "a+");
			fwrite($fp, "empty");
			fclose($fp);
			exit();
		}
	}

	// Mostrando Datos de Tiempo Demorado.
	$tfinal = microtime(true);
	echo "Tiempo gastado: ";
	echo ($tfinal-$tinicial);
	echo " segundos";

	// Mostrando Datos de la Memoria Consumida
	$mfinal = memory_get_usage()/1024;
	echo "Memoria después de liberar objeto: ";
	echo $mfinal - $minicial;
	echo "Kb\n";

}


?>
