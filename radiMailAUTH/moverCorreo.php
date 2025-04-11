<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
require_once __DIR__ . "/cotes/vendor/autoload.php";
require_once __DIR__ . "/cotes/config.php";
*/

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Exception\ClientException;

/*
$graph = new Graph();
$myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);
*/

echo "<br>messageId:" . $messageId;

// ID de la carpeta de destino
$destinationFolderId = 'AAMkADc5YzAxNTIzLWMwMGUtNDJkMC1hYTIwLWQ0ODE3MGQ4MGM5MAAuAAAAAABYhkKK4gHIS7avtF9IMt6CAQBfHGhAvlUfQYZy9hGHyOddAAYf7NHYAAA=';

// Crear el objeto JSON con los datos de la solicitud
$requestBody = new \stdClass();
$requestBody->destinationId = $destinationFolderId;

// Enviar la solicitud HTTP POST
$response = $graph->createRequest('POST', "/users/".USER_ID."/messages/$messageId/move")
    ->attachBody($requestBody)
    ->execute();

// Verificar que el correo electrónico ha sido movido a la carpeta de destino

echo "<br>estatus:" . $response->getStatus();
if ($response->getStatus() === 200 || $response->getStatus() === 201  ) {
    echo 'El correo electrónico ha sido movido exitosamente.';
} else {
    echo 'Ha ocurrido un error al intentar mover el correo electrónico.';
}


?>