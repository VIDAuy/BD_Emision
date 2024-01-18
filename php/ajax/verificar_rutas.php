<?php

include '../configuraciones.php';


$tabla["data"] = [];

$comprobar_rutas = rutas_comprobadas();


while ($row = mysqli_fetch_assoc($comprobar_rutas)) {

    $cedula = $row["cedula"];
    $cant_digitos = $row["cant_digitos"];
    $ruta = $row['ruta'];

    $tabla["data"][] = [
        "cedula" => $cedula,
        "cant_digitos" => $cant_digitos,
        "ruta" => $ruta != "" ? $ruta : "<span class='text-danger fw-bolder'>Vacía</span>",
    ];
}




echo json_encode($tabla);




function rutas_comprobadas()
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_DATOS_SOCIO;

    $sql = "SELECT
        cedula,
        LENGTH(ruta) AS 'cant_digitos',
        ruta
    FROM
        {$tabla}
    WHERE
        LENGTH(ruta) != 10";

    return mysqli_query($conexion, $sql);
}
