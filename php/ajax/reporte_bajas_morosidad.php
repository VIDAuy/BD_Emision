<?php

include '../configuraciones.php';


$tabla["data"] = [];



$listado = listado_reporte_bajas();

while ($row = mysqli_fetch_assoc($listado)) {
    $id = $row['id'];
    $cedula = $row['cedula'];
    $fecha_carga = $row['fecha_subida'];

    $tabla["data"][] = [
        "id" => $id,
        "cedula" => $cedula,
        "fecha_carga" => date("d/m/Y H:i:s", strtotime($fecha_carga))
    ];
}





echo json_encode($tabla);



function listado_reporte_bajas()
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_HISTORIAL_CARGA_BAJAS;
    $tabla2 = TABLA_REGISTRAR_BAJAS;

    $sql = "SELECT 
        rb.id,
        rb.cedula,
        hcb.fecha_subida
      FROM 
        {$tabla} hcb
        INNER JOIN {$tabla2} rb ON rb.id_historial_carga_bajas = hcb.id";

    return mysqli_query($conexion, $sql);
}
