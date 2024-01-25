<?php
include '../configuraciones.php';

$bajas_padron = obtener_cantidad_bajas_padron();

if (!is_numeric($bajas_padron)) {
    $response['error'] = true;
    $response['mensaje'] = "No se pudo obtener la cantidad de bajas en padrón";
}

$bajas_morosidad = obtener_cantidad_bajas_morosidad();

if (!is_numeric($bajas_morosidad)) {
    $response['error'] = true;
    $response['mensaje'] = "No se pudo obtener la cantidad de bajas de morosidad";
}


$bajas_cargadas_directamente = $bajas_padron > $bajas_morosidad ? $bajas_padron - $bajas_morosidad : $bajas_morosidad - $bajas_padron;


$response['error'] = false;
$response['cantidad'] = [
    "bajas_por_morosidad" => $bajas_morosidad,
    "bajas_cargadas_directamente" => $bajas_cargadas_directamente
];

echo json_encode($response);





function obtener_cantidad_bajas_padron()
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_DATOS_SOCIO;

    $sql = "SELECT 
          COUNT(id) AS 'Cantidad' 
        FROM 
          {$tabla} 
        WHERE 
          abm = 'baja' AND 
          abmactual = 1";
    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta)['Cantidad'];
}

function obtener_cantidad_bajas_morosidad()
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_HISTORIAL_CARGA_BAJAS;
    $tabla2 = TABLA_REGISTRAR_BAJAS;

    $mes = date("m");
    $anio = date("Y");

    $sql = "SELECT 
          COUNT(rb.id) AS 'Cantidad'
        FROM 
          {$tabla} hcb
          INNER JOIN {$tabla2} rb ON rb.id_historial_carga_bajas = hcb.id 
        WHERE 
          MONTH(fecha_subida) = $mes AND 
          YEAR(fecha_subida) = $anio";

    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta)['Cantidad'];
}
