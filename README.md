Para el sistema se utilizo Laravel 9.19 y Mysql 8

## Caso de uso "Registra entrada"

Para acceder a este caso de uso se utiliza los siguientes datos:
* endpoint: **api/entrada**,
* method: **POST**,
* body: parametro tipo string nombre (**placa**)

## Caso de uso "Registra salida"

Para acceder a este caso de uso se utiliza los siguientes datos:
* endpoint: **api/salida**,
* method: **POST**,
* body: parametro tipo string nombre (**placa**)

## Caso de uso "Listado de clientes #1"

Para acceder a este caso de uso se utiliza los siguientes datos:
* endpoint: **api/informe**,
* method: **POST**

Este ejercicios lo dividi en 2 ya que la pregunta es algo ambigua, la primera son los 3 clientes que mas utilizan el estacionamiento por los minutos estacionados, la segunda opción serian los 3 clientes que mas utilizan el estacionamiento por la cantidad de veces que accedio a este. Ya que por ejemplo un cliente que haya accedido 3 veces tiene un tiempo de estacionamiento en minutos que puede ser mayor o menor al tiempo en minutos de cualquier otro cliente que haya estacionado 1 vez.

## Caso de uso "Listado de clientes #2 (Mayor cantidad de minutos estacionados)"

Para acceder a este caso de uso se utiliza los siguientes datos:
* endpoint: **api/tiempodeuso**,
* method: **POST**

## Caso de uso "Listado de clientes #2 (Mayor cantidad de veces que accedió al estacionamiento)"

Para acceder a este caso de uso se utiliza los siguientes datos:
* endpoint: **api/mayoruso**,
* method: **POST**