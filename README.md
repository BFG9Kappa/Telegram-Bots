
# Telegram-Bots.


## :warning: Disclaimer.

Antes de empezar, sólo unas breves palabras, porque con lo que se os viene me vais a odiar con la
parrafada:

``` 
Este documento lo escribí porque creo que toda persona, que vive en la sociedad actual,
debe conocer lo que es un bot, saber crear uno y poder aprovechar todo el potencial que
estos ofrecen. Definitivamente, es un ahorro de tiempo y trabajo, gracias a lo anterior
podremos dedicarnos a cosas más importantes como observar el vuelo alocado de la mosca
que planea sobre mi habitación.
```

> Los bots son al mundo lo que la automatización es a la informática. - Secury.

Esto es la primera parte, espero que de muchas, acerca del tema de Bots. 

Cualquier duda que surga, por favor, abrir un :exclamation: **issue**, he pensado que esta es la mejor forma de recopilar dudas y errores que puedan surgir, les agradezco mucho la atención.

Bueno... Empecemos!


## 1. ¿Qué es un Bot? (¿eso se come?)


```
Un bot es un programa que realiza una o varias actividades concretas de forma automática
y que mantiene una vía de comunicación y recibimiento de órdenes (generalmente) a través de Internet.
```

Independientemente del lenguaje de programación con el que se haya desarrollado, la idea del Bot, y por la cuál se diferencia con un programa convencional, es que dicho bot, recibe una serie de "**acciones**" o "**tareas**" a cumplir, y cuando finaliza dicha actividad, **informa de los resultados** obtenidos. 

*La idea principal es esa comunicación y toma de decisiones sobre que actividad completar.*

(Hay casos y casos y hacer una definición genérica no es para nada fácil, la clave reside en la práctica, ya que nos dará la definición que buscamos, pero creo que la anterior citada puede ser válida).



## 2. Algunos ejemplos de Bot.

Los ejemplos más típicos son bots de [**trading y apuestas**](https://gunbot.trading/), bots [**crawlers de webs**](https://www.diffbot.com/products/crawlbot/), bots [**escáners de servidores**](https://www.distilnetworks.com/bot-directory/category/vulnerability-scanner/), [**buscadores de ofertas**](https://www.chollobot.com/) en tiendas online, bots que aprenden (usando metodologías propias de la [**inteligencia artificial**](http://chatterbot.readthedocs.io/en/stable/tutorial.html) como el machoine Learning) de conversaciones, etc. 

Y estoy seguro que me quedo muy corto, ya que para cada actividad concreta o no, que quiera realizarse sin tener que estar pendiente de ella, existe un bot que se encargará.

<p align="center">
<img widht="400px" height="400px" src="https://github.com/secu77/Telegram-Bots/blob/master/images/Bot_drunk.jpeg?raw=true" />
</p>


## 3. La API de Telegram y sus Bots.

Como hemos dicho antes, un bot no depende estrictamente de una plataforma o lenguaje de programación,
no esta atado a ninguno de lo anterior, al tomar la definición del punto 1 nos damos cuenta que es algo
**genérico**, y que no necesitamos nada en concreto para engendrarlo. `Tan sólo el programa y la via de comunicación con el mismo.`


Pero apesar de la definición, para empezar, esta bien apoyarse en una plataforma que te asegure la parte, quizás, más complicada del proceso, y es esa vía de comunicación con tu bot (que no deja de ser un programa). Pensemos que esa vía de comunicación tiene que ser sencilla y limpia, y sobretodo tiene que mantenerse activa, porque sino no podremos comunicarle a nuestro bot lo que tiene que hacer.


Dicho esto, para este ejemplo utilizaré Telegram, la App de Mensajería, ya que proporciona una [API](https://core.telegram.org/bots/api) para "crear" y controlar bots (Y esto puede parecer muy enrevesado o complejo, pero en realidad el funcionamiento es bastante simple). 

<img src="https://github.com/secu77/Telegram-Bots/blob/master/images/Bot_tel.jpeg?raw=true" />

```
Telegram actúa como pasarela entre los clientes de su plataforma (Cualquier usuario de Telegram)y los 
bots (que no dejan de ser programas desarrollados en el lenguaje de programación que más gusto te de). 
```

Por tanto, el funcionamiento será: Nuestro programa o bot, manda un mensaje a la API de Telegram (que no es más que hacer una petición a una URL), y Telegram procesa esa petición (puede ser un mensaje para un usuario, puede ser eliminar una foto, salirse de un grupo, o simplemente obtener actualizaciones, etc), y devuelve una respuesta.


## 4. Bot Father.

Pero si esto se pudiera hacer sin control supondría un problema, por tanto, Telegram necesita establecer unos límites y validaciones.

Cada bot es identificado por un **"Token" único**, dicho Token no se asigna a la ligera, para generar un Token se tiene que utilizar **"Bot Father"** que es un usuario de Telegram (bueno en realidad es un bot xD) que de forma muy sencilla te permite generar un máximo de 20 Tokens al mismo tiempo (o lo que es lo mismo 20 bots simultáneamente). Para ello seguiremos los mismos procedimientos que aquí:

<p align="center">
<img src="https://github.com/secu77/Telegram-Bots/blob/master/images/Demo_BotFather.gif?raw=true" />
</p>


## 5. Long Polling y Webhooking.


Una vez generado el token, necesitaremos definir las dos posibles vías de obtención de mensajes, dependiendo de la tarea que desempeñará el bot, se utilizará uno y otro:



  - El método de **Long Polling** es una *variación del polling tradicional*. El Cliente (nuestro bot) hace una petición al Servidor
  (Api Telegram) con el fin de obtener actualizaciones, **el Servidor puede no responder en el acto**, pongamos un ejemplo:
  
  El Bot solicita nuevas actualizaciones, pero el Servidor no tiene dichas actualizaciones, pues... en lugar de devolver una respuesta vacía y cerrar la conexión, lo que hace es **mantener la conexión abierta (un tiempo determinado), y esperar hasta tener los datos solicitados**. Una vez tiene dichos datos, el Servidor los manda al Cliente completando así la solicitud. Y cuando el Cliente recibe esta información, normalmente vuelve a solicitar nuevos datos de la misma forma, consiguiendo de esta forma reducir la latencia entre la primera respuesta y la nueva solicitud.

  Para hacer uso de esta metodología por nuestro bot, la [**documentación de la api de telegram**](https://core.telegram.org/bots/api#getupdates) nos proporciona unos campos adicionales a la hora de solicitar nuevas actualizaciones con el método `/getUpdates`. Dicho método posee 4 parámetros, ```todos opcionales```, pero no nos detendremos a explicar más que 1, que es el que nos interesa para completar dicho método, y es: **'timeout'**.

  Dice así en este parámetro:

  > 'El tiempo de espera en segundos para el Long Polling. Por defecto se establece 0 (polling corto). Debe ser un valor positivo, y el polling corto sólo debe ser usado para pruebas.'

  ¿Qué nos dice con esto? 

  Primero que **establezcamos un timeout**, nada nuevo, pero cabe destacar una palabra clave de importancia que es "short polling", donde nos dice "se establece por defecto un timeout de 0", es decir, una petición completamente instantánea, y cuyo método recomienda no usar por defecto, tan sólo exclusivamente en casos puntuales de desarrollo y pruebas, puesto que dicho método satura el servidor a peticiones reiteradas. Visto esto, procederemos a lo siguiente:

  	-En el bot se establecerá un timeout (dicho valor es opcional, como marca la api) a la hora de hacer la petición al /getUpdates,
  	en el caso de usarla, en nuestro ejemplo de Webhooks no es necesario puesto que como se puede ver no hacemos uso de dicho método, ya que las nuevas actualizaciones nos vendrán directamente a nuestro bot. Cosa que explicaremos en el siguiente método.


<p align="center">
<img widht="450px" height="450px" src="https://github.com/secu77/Telegram-Bots/blob/master/images/Bot_Longpolling.jpeg?raw=true" />
</p>

  - En cambio en método **Webhooking**, es completamente lo contrario, el bot le manda al servidor la dirección IP de donde estará escuchando, y le dice algo así:  `"Cuando tengas notificaciones, mándamelas aquí vale?".` Entonces Telegram cada vez que reciba una nueva notificación que afecta a nuestro bot, ya sea un mensaje u otra cosa, le mandará un **JSON** con el mensaje (ese único mensaje) a través de la API al bot. **Importante**: para utilizar este método, Telegram nos obliga a establecer un canal seguro, por tanto, nos pide que nuestro servidor (o lugar donde mantengamos el bot) vaya bajo SSL.

<p align="center">
<img widht="450px" height="450px" src="https://github.com/secu77/Telegram-Bots/blob/master/images/Bot_Webhooking.jpeg?raw=true" />
</p>


## 6. Mensajes Json Api y acceso.

Y como habíamos dicho antes, tanto para recibir las notificaciones que afectan a nuestro bot, como para validar la respuesta de la api a una petición que enviamos, lo haremos mediante un mensaje JSON. Un mensaje formateado como Objeto Json, sigue la siguiente estructura de nombre y valor:

<p align="center">
<img alt="https://www.json.org/" src="https://github.com/secu77/Telegram-Bots/blob/master/images/json.png?raw=true" />
</p>

Y para acceder a cada uno de sus valores, hemos de transformar esta estructura de mensaje en 'algo' más cómodo para trabajar. Como puede ser por ejemplo un **array asociativo**, con el que podremos acceder fácilmente a cada uno de los elementos (y por tanto a sus valores, que son lo que nos interesan).

Aquí os voy a mostrar alguna forma básica de procesar un mensaje JSON y poder acceder a sus valores, pero cabe recordar que esta forma depende del lenguaje de programación que usemos para programar nuestro bot, e incluso, dentro del lenguaje,podemos utilizar distintas formas.


```php
<?php

$mensajejson = '{"update_id":123,"message":{"message_id":123,"from":{"id":123,"is_bot":false,"first_name":"Secu","username":"Secury","language_code":"es"},"chat":{"id":123,"first_name":"Secu","username":"Secury","type":"private"},"date":123,"text":"Hey"}}';

// Con 'json_decode', pasándole true como 2 parámetro, obtenemos un array asociativo del JSON.
$arrayasociativo = json_decode($mensajejson, true);

// Accediendo a un par de elementos:
echo $arrayasociativo['update_id'] . PHP_EOL;
echo $arrayasociativo['message']['from']['username'] . ' says ' . $arrayasociativo['message']['text'] . PHP_EOL;

?>
```

Para no extender demasiado, simplemente buscar las funciones correspondientes para decodificar el mensaje
JSON en el lenguaje de programación que se desee. Y visitar la página oficial de JSON https://www.json.org/


## 7. Métodos Api Telegram.


Otro apunte con mucha relevancia es que, dependiendo, del suceso que incumba a nuestro bot, y el cual nos notifica Telegram, la estructura del mensaje cambiará (en muy poco pero cambiará), y por tanto, `idear una función o método genérico/general para procesar cada notificación que nos puede enviar, es una locura,` puesto que es más sencillo contemplar cada caso en el orden correcto y de forma correcta (esto nos evitará muchos errores y el rompernos la cabeza). 

Para ello, he preparado, de forma personal, y con el simple objetivo de profundizar un poco más en la API de Telegram (y de compartir ese conocimiento) haciendo una **"Clase"** (no os preocupeis, todo métodos estáticos, muerte a la POO) **con algunos métodos para parsear notificaciones concretas que nos manda la API**, **todo en lenguaje PHP**, y que explicaré brevemente un caso para que nos podamos hacer a la idea de como funciona.

(Pero cabe recordar, que la lista de todas estas acciones las proporciona la documentación de la API de Telegram en su [**página web oficial**](https://core.telegram.org/bots/api#available-methods))

Y por último, simplemente decir que lo que aquí habéis visto (y el conjunto de clases y ficheros que os dejo), se denomina **"wrapper"**, que es como una capa por encima de la API (atendiendo a la definición, 'como para envolverla' en su totalidad) para tratar esta **de forma sencilla** (en vez de tener que hacer cada petición como hemos visto antes de forma manual, **utilizamos las funciones que harán eso mismo por nosotros**).

Cada función esta explicada en la [**Clase**](https://github.com/secu77/Telegram-Bots/blob/master/botClass.php) y se apoya de un [**ejemplo**](https://github.com/secu77/Telegram-Bots/blob/master/bot.php) sencillo de funcionamiento.

Para empezar a utilizar el Bot, nos situamos en el archivo [**bot.php**](https://github.com/secu77/Telegram-Bots/blob/master/bot.php), y previsualizamos los siguientes campos:

<img src="https://github.com/secu77/Telegram-Bots/blob/master/images/botfile.png?raw=true" />

  - El campo **KEY** contendrá una clave que necesitamos establecer: ¿Por qué? Porque nuestro Bot va a ser accesible por
la API de Telegram, al igual que lo será por cualquier otro, y para no procesar mensajes entrantes de cualquier usuario,
y sólo aceptar los verificados de la API de Telegram, usaremos esa key junto con nuestra referencia (sitio web donde se aloja el bot), para mandarlo a la API y que así, siempre que nos notifique algún suceso, venga con dicha clave, y por tanto, sabremos que ese mensaje será realmente de la API.

  - El campo **Referencia** debe ser el nombre de dominio o dirección IP donde corre nuestro bot.<br />Ex: https://stationx11.es/bot.php (recordar que bajo SSL).

  - El campo **TOKEN** corresponde al TOKEN generado por Bot Father, que antes hemos citado.

  - Y el campo **ADMIN** se refiere al ID (de Telegram) de nuestro usuario (o del usuario, del cual el bot procesará los mensajes que reciba), al ser una prueba, esta diseñado para que sólo responda a esta persona, pero se podría omitir esta condición facilmente y el bot respondería a todo el mundo. El ID lo podemos conseguir preguntandole en Telegram al bot @myidbot (poniendo el comando **/getid**).


Cabe destacar que esta Clase esta muy lejos de tener implementados cada uno de los métodos que la API
utiliza. Por ahora estan algunos importantes e interesantes para trastear un poco. Conforme vaya teniendo tiempo iré añadiendo tanto los métodos restantes como ejemplos de programas o bots, que puedan despertar la inspiración del lector, y puedan ser usados por todos.


Hay muchos wrappers, bastante brutales y más currados que este en **Github**, y para distintos lenguajes, algunos muy importantes son:

  - [**Lenguaje PHP**](https://github.com/php-telegram-bot/core)
  - [**Lenguaje Python**](https://github.com/python-telegram-bot/python-telegram-bot)
  - [**Lenguaje Nodejs**](https://github.com/telegraf/telegraf)
  - [**Lenguaje Java**](https://github.com/rubenlagus/TelegramBots)
  - [**Lenguaje C++**](https://github.com/reo7sp/tgbot-cpp)
  - [**Lenguaje Erlang**](https://github.com/seriyps/pe4kin)
  - [**Lenguaje Go**](https://github.com/go-telegram-bot-api/telegram-bot-api)
  - [**Lenguaje C#**](https://github.com/MrRoundRobin/telegram.bot)
  - [**Lenguaje Ruby**](https://github.com/eljojo/telegram_bot)
  - [**Lenguaje Lua**](https://github.com/wrxck/telegram-bot-lua)
  - [**Lenguaje Haskell**](https://github.com/klappvisor/haskell-telegram-api)
  - [**Lenguaje Scala**](https://github.com/mukel/telegrambot4s)
  - [**Lenguaje Swift**](https://github.com/FabrizioBrancati/SwiftyBot)
  - [**Lenguaje Asm x86_64**](https://github.com/StefanoBelli/x86_64-asm-tgbot)


### Referencias:

  - https://core.telegram.org/bots/api
  - http://php.net/
  - https://www.json.org/
  - https://www.pubnub.com/blog/2014-12-01-http-long-polling/
  - https://en.wikipedia.org/wiki/Push_technology#Long_polling
  - @DevPGSV


Dedicaciones:<br />
A Miriam, por verte otra vez.
