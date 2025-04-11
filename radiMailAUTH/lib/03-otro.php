<?php

require_once './vendor/autoload.php';

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Authentication\TokenCredentialAuthProvider;

// Credenciales de autenticación para Azure
$clientId = "TU_CLIENT_ID";
$clientSecret = "TU_CLIENT_SECRET";
$tenantId = "TU_TENANT_ID";

// Crear instancia de proveedor de autenticación de credenciales de token
$authProvider = new TokenCredentialAuthProvider($clientId, $clientSecret, $tenantId);

// Crear instancia de objeto Graph con el proveedor de autenticación
$graph = new Graph();
$graph->setAuthProvider($authProvider);

?>
