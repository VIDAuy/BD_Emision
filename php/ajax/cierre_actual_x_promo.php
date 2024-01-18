<?php

include '../configuraciones.php';


$tabla["data"] = [];


$all_data = listado_servicios_codigos(false);
$datos_completos = [];
while ($row = mysqli_fetch_assoc($all_data)) {
    array_push($datos_completos, $row['cod_promo']);
}


$datos_vista = listado_vista_padron();
$datos_vista_padron = [];
while ($row = mysqli_fetch_assoc($datos_vista)) {
    array_push($datos_vista_padron, $row['cod_promo']);

    $tabla["data"][] = [
        "cod_promo" => $row['cod_promo'],
        "promo" => @utf8_decode($row['promo']),
        "cantidad" => $row['cantidad'],
    ];
}


$servicios_no_contratados = array_diff($datos_completos, $datos_vista_padron);


foreach ($servicios_no_contratados as $row) {
    $data = listado_servicios_codigos($row);
    $res = mysqli_fetch_assoc($data);

    $tabla["data"][] = [
        "cod_promo" => $res['cod_promo'],
        "promo" => @utf8_decode($res['promo']),
        "cantidad" => 0,
    ];
}





echo json_encode($tabla);



function listado_servicios_codigos($numero = false)
{
    $conexion = connection(DB);
    $tabla = TABLA_PROMO;

    $where = $numero == false ? "" : "AND codigoPromo = '$numero'";

    $sql = "SELECT codigoPromo AS 'cod_promo', nombrePromo AS 'promo' FROM {$tabla} WHERE activo = 1 $where ORDER BY codigoPromo ASC";

    return mysqli_query($conexion, $sql);
}


function listado_vista_padron()
{
    $conexion = connection(DB);
    $tabla1 = VISTA_SOCIOS_PRODUCTOS;
    $tabla2 = TABLA_PROMO;

    $sql = "SELECT
	    v.cod_promo,
	    p.nombrePromo AS 'promo',
	    count( p.codigoPromo ) AS 'cantidad' 
    FROM
	    {$tabla1} AS v
	    INNER JOIN {$tabla2} AS p ON v.cod_promo = p.codigoPromo 
    GROUP BY p.codigoPromo 
    ORDER BY p.codigoPromo ASC";

    return mysqli_query($conexion, $sql);
}
