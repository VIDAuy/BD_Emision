<?php
include '../configuraciones.php';


$id = $_REQUEST['id'];
$usuario = $_REQUEST['usuario'];
$password = $_REQUEST['password'];
$hash = $_REQUEST['hash'];
$activo = $_REQUEST['activo'];


if (!$id || !$usuario || !$password || !$hash || !$activo) {
    $response['error'] = true;
    $response['mensaje'] = "Usuario no logueado!";
    die(json_encode($response));
}


$validar = verificar_usuario($id, $usuario, $password, $hash, $activo, true);

if ($validar === false) {
    $response['error'] = true;
    $response['mensaje'] = "Credenciales inválidas!";
    die(json_encode($response));
}


$response['error'] = false;
$response['mensaje'] = "¡Bienvenido!";
echo json_encode($response);
