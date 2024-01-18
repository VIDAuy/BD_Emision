<?php

include './configuraciones.php';

$hash = $_GET['hash'];

$verificarHash = comprobarHash($hash);



if (mysqli_num_rows($verificarHash) <= 0) {

    $response['error'] = true;
    $response['mensaje'] = "El hash no existe";
    die(json_encode($response));
}




$response['error'] = false;
$response['mensaje'] = "El hash existe y no esta abonado";




echo json_encode($response);




function comprobarHash($hash)
{
    $conexion = connection(DB);
    $tabla = TABLA_REGISTROS;

    $consulta = "SELECT * FROM {$tabla} WHERE hash_pago = '$hash' AND abonado = 0";
    $respuesta = mysqli_query($conexion, $consulta);

    return $respuesta;
}
