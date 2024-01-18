<?php

include '../configuraciones.php';


$tabla["data"] = [];


$all_data = listado_servicios_codigos(false);
$datos_completos = [];
while ($row = mysqli_fetch_assoc($all_data)) {
    array_push($datos_completos, $row['nro_servicio']);
}


$datos_vista = listado_vista_padron();
$datos_vista_padron = [];
while ($row = mysqli_fetch_assoc($datos_vista)) {
    array_push($datos_vista_padron, $row['servicio']);

    $tabla["data"][] = [
        "id_servicio" => $row['servicio'],
        "servicio" => @utf8_decode($row['tipo_servicio']),
        "cantidad" => $row['cantidad'],
    ];
}


$servicios_no_contratados = array_diff($datos_completos, $datos_vista_padron);


foreach ($servicios_no_contratados as $row) {
    $data = listado_servicios_codigos($row);
    $res = mysqli_fetch_assoc($data);

    $tabla["data"][] = [
        "id_servicio" => $res['nro_servicio'],
        "servicio" => @utf8_decode($res['servicio']),
        "cantidad" => 0,
    ];
}





echo json_encode($tabla);



function listado_servicios_codigos($numero = false)
{
    $conexion = connection(DB);
    $tabla = TABLA_SERVICIOS_CODIGOS;

    $where = $numero == false ? "" : "AND nro_servicio = '$numero'";

    $sql = "SELECT nro_servicio, servicio FROM {$tabla} WHERE activo = 1 $where";

    return mysqli_query($conexion, $sql);
}


function listado_vista_padron()
{
    $conexion = connection(DB);
    $tabla1 = VISTA_SOCIOS_PRODUCTOS;
    $tabla2 = TABLA_SERVICIOS_CODIGOS;

    $sql = "SELECT
	    v.servicio,
	    s.servicio AS 'tipo_servicio',
	    count( v.servicio ) AS 'cantidad' 
    FROM
	    {$tabla1} AS v
	    INNER JOIN {$tabla2} AS s ON v.servicio = s.nro_servicio 
    GROUP BY s.servicio 
    ORDER BY s.servicio ASC;";

    return mysqli_query($conexion, $sql);
}
