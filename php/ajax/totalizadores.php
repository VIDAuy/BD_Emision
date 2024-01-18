<?php

include '../configuraciones.php';

/** Errores de consultas
 * 800 Error en consulta de cantidad en padron datos socios
 * 801 Error en consulta de cantidad en padron productos socios
 * 802 Error en consulta de cantidad altas de socios del cierre actual
 * 803 Error en consulta de cantidad altas de productos del cierre actual
 */

$cantidad_socios = cantidad_socios();
$cantidad_productos = cantidad_productos();
$cantidad_altas_socios_cierre_actual = cantidad_altas_socios_cierre_actual();
$cantidad_altas_productos_cierre_actual = cantidad_altas_productos_cierre_actual();


if (is_numeric($cantidad_socios) === false && $cantidad_socios <= 0) {
    $respuesta['error'] = true;
    $respuesta['mensaje'] = "Ha ocurrido un error, notifique de error 800";
    die(json_encode($respuesta));
}

if (is_numeric($cantidad_productos) === false && $cantidad_productos <= 0) {
    $respuesta['error'] = true;
    $respuesta['mensaje'] = "Ha ocurrido un error, notifique de error 801";
    die(json_encode($respuesta));
}

if (is_numeric($cantidad_altas_socios_cierre_actual) === false && $cantidad_altas_socios_cierre_actual <= 0) {
    $respuesta['error'] = true;
    $respuesta['mensaje'] = "Ha ocurrido un error, notifique de error 802";
    die(json_encode($respuesta));
}

if (is_numeric($cantidad_altas_productos_cierre_actual) === false && $cantidad_altas_productos_cierre_actual <= 0) {
    $respuesta['error'] = true;
    $respuesta['mensaje'] = "Ha ocurrido un error, notifique de error 803";
    die(json_encode($respuesta));
}


$respuesta['error'] = false;
$respuesta['cantidades'] = [
    ["totalizador1", $cantidad_socios],
    ["totalizador2", $cantidad_productos],
    ["totalizador3", $cantidad_altas_socios_cierre_actual],
    ["totalizador4", $cantidad_altas_productos_cierre_actual]
];




echo json_encode($respuesta);




function cantidad_socios()
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_DATOS_SOCIO;

    $sql = "SELECT COUNT(id) AS cantidad FROM {$tabla}";
    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta)['cantidad'];
}

function cantidad_productos()
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_PRODUCTOS_SOCIO;

    $sql = "SELECT COUNT(id) AS cantidad FROM {$tabla}";
    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta)['cantidad'];
}

function cantidad_altas_socios_cierre_actual()
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_DATOS_SOCIO;

    $sql = "SELECT COUNT(id) AS cantidad FROM {$tabla} WHERE abmactual = 1 AND abm = 'ALTA'";
    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta)['cantidad'];
}

function cantidad_altas_productos_cierre_actual()
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_PRODUCTOS_SOCIO;

    $sql = "SELECT COUNT(id) AS cantidad FROM {$tabla} WHERE abmactual = 1 AND abm = 'ALTA'";
    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta)['cantidad'];
}
