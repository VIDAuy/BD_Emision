<?php
include '../configuraciones.php';

$usuario = $_REQUEST['usuario'];
$password = $_REQUEST['password'];


if (!$usuario) {
    $response['error'] = true;
    $response['mensaje'] = "Debe ingresar su usuario!";
    die(json_encode($response));
}

if (!$password) {
    $response['error'] = true;
    $response['mensaje'] = "Debe ingresar una contraseña!";
    die(json_encode($response));
}


$validar = verificar_usuario(null, $usuario, $password, null, null, false);

if ($validar === false) {
    $response['error'] = true;
    $response['mensaje'] = "Las credenciales ingresadas no coinciden!";
    die(json_encode($response));
}



$response['error'] = false;
$response['mensaje'] = "Ha ingresado con éxito!";
$response['datos'] = $validar;
echo json_encode($response);
