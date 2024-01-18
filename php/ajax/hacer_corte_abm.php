<?php
include '../configuraciones.php';

$tabla1 = TABLA_PADRON_DATOS_SOCIO;
$tabla2 = TABLA_PADRON_PRODUCTOS_SOCIO;
$fecha_actual = date("d-m-Y");
$mes_anio = date("my", strtotime($fecha_actual . "+ 1 month"));
$nueva_tabla_PDS = $tabla1 . $mes_anio;
$nueva_tabla_PPS = $tabla2 . $mes_anio;


/** BACKUP PADRÓN **/


$crear_tabla_PDS = backup_tabla($nueva_tabla_PDS, $tabla1);

if ($crear_tabla_PDS === false) {
    $response['error'] = true;
    $response['mensaje'] = "Error al crear la tabla padron datos socio";
    die(json_encode($response));
}

$crear_tabla_PPS = backup_tabla($nueva_tabla_PPS, $tabla2);

if ($crear_tabla_PPS === false) {
    $response['error'] = true;
    $response['mensaje'] = "Error al crear la tabla padron producto socio";
    die(json_encode($response));
}


/** END BACKUP PADRÓN **/


$id_historial_logs = registrar_historial_logs();


$array_query = [
    "UPDATE {$tabla1} SET nomodifica = 1",
    "UPDATE {$tabla2} SET nomodifica = 1",
    "UPDATE {$tabla1} SET nomodifica = 0 WHERE abmactual = 1",
    "UPDATE {$tabla2} SET nomodifica = 0 WHERE abmactual = 1",
    "UPDATE {$tabla1} SET total_importe = 0 WHERE total_importe IS NULL",
    "UPDATE {$tabla1} SET total_importe = 0 WHERE total_importe IS NULL"
];

$array1 = ["06", "07", "08", "10", "11", "13", "14", "15", "17", "18", "19", "20", "21", "22", "23", "24", "25", "28", "29", "30", "31", "32", "33", "34", "35", "36", "39", "40", "41", "44", "45", "47", "49", "50", "52", "53", "54", "55", "56", "57", "58", "59", "62", "64", "66", "67", "70", "72", "73", "74", "75", "76", "77", "79", "80", "81", "83", "84", "85", "86", "87", "88", "89", "93", "94", "95", "96", "97", "98", "100", "101", "102", "103", "104", "105", "106"];

$array2 = ["04"];

$array3 = ["01", "02", "03", "05", "12", "16", "37", "46", "51", "61", "63", "65", "68", "69", "82"];


$cantidad = 1;
foreach ($array_query as $query) {
    $update1 = update(null, null, false, $query, $cantidad);

    if ($update1 === false) {
        $response['error'] = true;
        $response['mensaje'] = "Ocurrio un error, update query $cantidad!";
        die(json_encode($response));
    }

    $cantidad++;
}

foreach ($array1 as $servicio) {
    $update2 = update("servicio", $servicio, false, false, $cantidad);

    if ($update2 === false) {
        $response['error'] = true;
        $response['mensaje'] = "Ocurrio un error, update servicio $servicio!";
        die(json_encode($response));
    }

    $cantidad++;
}

foreach ($array2 as $servicio) {
    $update3 = update("4", $servicio, false, false, $cantidad);

    if ($update3 === false) {
        $response['error'] = true;
        $response['mensaje'] = "Ocurrio un error, update servicio $servicio!";
        die(json_encode($response));
    }

    $cantidad++;
}

foreach ($array3 as $servicio) {
    $update4 = update(null, $servicio, true, false, $cantidad);

    if ($update4 === false) {
        $response['error'] = true;
        $response['mensaje'] = "Ocurrio un error, update servicio $servicio!";
        die(json_encode($response));
    }

    $cantidad++;
}



$response['error'] = false;
$response['mensaje'] = "Se realizó el corte con éxito!";
die(json_encode($response));





/** FUNCTION BACKUP PADRÓN **/
function backup_tabla($nuevaTabla, $tablaOriginal)
{
    $conexion = connection(DB_BD_EMISION);
    $sql1 = "DROP TABLE IF EXISTS $nuevaTabla";
    $sql2 = "CREATE TABLE $nuevaTabla SELECT * FROM $tablaOriginal";
    $consulta1 = mysqli_query($conexion, $sql1);
    $consulta2 = mysqli_query($conexion, $sql2);

    return $consulta1 === true && $consulta2 === true ? true : false;
}
/** END FUNCTION BACKUP PADRÓN **/


/** FUNCTION UPDATE **/
function update($servdecod = null, $servicio = null, $concat = false, $query = false, $cantidad)
{
    global $id_historial_logs;
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_PADRON_PRODUCTOS_SOCIO;

    $errores = false;
    try {
        if ($servdecod != null && $servicio != null && $concat === false && $query === false) {
            $sql = "UPDATE {$tabla} SET servdecod = '{$servdecod}' WHERE servicio = $servicio";
        } else if ($servdecod === null && $servicio != null && $concat != false && $query === false) {
            $sql = "UPDATE {$tabla} SET servdecod = CONCAT(servicio, hora) WHERE servicio = $servicio";
        } else if ($servdecod === null && $servicio === null && $concat === false && $query != false) {
            $sql = $query;
        }

        $consulta = mysqli_query($conexion, $sql);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_historial_logs);
        }

        $errores = $consulta;
    } catch (Exception $e) {
        $reference_error = $servicio === null ? $cantidad : "servicio_" . $servicio;
        registrar_logs_errores("CorteABM_update_" . $reference_error, $sql, $e, $id_historial_logs);
    }

    return $errores;
}
/** END FUNCTION UPDATE **/
