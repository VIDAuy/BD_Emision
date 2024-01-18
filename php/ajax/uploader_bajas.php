<?php
include_once "../configuraciones.php";

include_once './lib/PHPExcel-1.8/Classes/PHPExcel.php';


ini_set('memory_limit', '-1');
php_ini_loaded_file();
$file = $_FILES["file"];
$archivo = $_FILES["file"]["name"];

if (move_uploaded_file($file["tmp_name"], "./{$archivo}") == false) {
    die(json_encode(
        [
            "success" => false,
            "mensaje" => "Hubo errores al guardar la información",
        ],
        JSON_PRETTY_PRINT
    ));
}



$conexion = connection(DB_BD_EMISION);
//$conexion2 = connection(DB);
$tabla = TABLA_REGISTRAR_BAJAS;
$tabla1 = TABLA_HISTORIAL_CARGA_BAJAS;
$tabla2 = TABLA_PADRON_DATOS_SOCIO;
$tabla3 = TABLA_PADRON_PRODUCTOS_SOCIO;
$tabla4 = TABLA_BAJAS;


try {
    $consulta = "SELECT * FROM {$tabla} ";
    $cedulasAnteriores = mysqli_query($conexion, $consulta);
    $cedulasAnteriores = mysqli_num_rows($cedulasAnteriores) > 0 ? mysqli_fetch_array($cedulasAnteriores) : [];
} catch (Exception $e) {
    LogDB("Select_Bajas_Anteriores_BD_Emision", $consulta, $e);
}


$reader = PHPExcel_IOFactory::createReaderForFile($archivo);
$excel = $reader->load($archivo);
$worksheet = $excel->getSheet('0');
$lastRow = $worksheet->getHighestRow();
$colString = $worksheet->getHighestDataColumn();
$comienzo = 1;
$celda_cedula = 'A';


for ($i = 0; $i <= $lastRow; $i++) {
    $celda = trim($worksheet->getCell("{$celda_cedula}{$i}")->getValue());

    if (strtoupper($celda) == 'CEDULA' || strtoupper($celda) == 'cedula' || strtoupper($celda) == 'Cedula') {
        $comienzo = $i + 1;
    }
}

$errores = 0;


$array_cedulas = [];
for ($i = $comienzo; $i <= $lastRow; $i++) {
    $cedula =  trim($worksheet->getCell("{$celda_cedula}{$i}")->getValue());
    array_push($array_cedulas, $cedula);
}


$cantidad_registros = count($array_cedulas);

if (file_exists($archivo)) {
    try {
        rename($archivo, "./{$archivo}");
    } catch (Exception $e) {
        LogDB('Mover planilla', ' ', $e);
    }
}


//INSERT DE HISTORIAL DE CARGA DEL EXCEL
try {
    $consulta = "INSERT INTO {$tabla1}(fecha_subida, cantidad_registros, nombre_archivo) VALUES(NOW(), {$cantidad_registros}, '{$archivo}')";
    $insert = mysqli_query($conexion, $consulta);
    $id_insert_historial = mysqli_insert_id($conexion);
    $errores = $insert ? $errores : $errores++;
} catch (Exception $e) {
    LogDB("Insert_historial_carga_bajas_bd_emision", $consulta, $e);
}


$array_insert = [];
foreach ($array_cedulas as $cedula) {

    //INSERT DE BAJAS CARGADAS EN EL EXCEL
    try {
        $consulta = "INSERT INTO {$tabla}(cedula, id_historial_carga_bajas) VALUES('{$cedula}', {$id_insert_historial})";
        $insert = mysqli_query($conexion, $consulta);
        $id_insert = mysqli_insert_id($conexion);
        $errores = $insert ? $errores : $errores++;
    } catch (Exception $e) {
        LogDB("Insert_registrar_bajas_bd_emision", $consulta, $e);
    }


    //UPDATE EN PADRÓN DATOS SOCIOS
    try {
        $consulta = "UPDATE {$tabla2} SET abm='baja', abmactual=1 WHERE cedula={$cedula}";
        $update = mysqli_query($conexion, $consulta);
        $errores = $update ? $errores : $errores++;
    } catch (Exception $e) {
        LogDB("Update_padron_socios_bd_emision", $consulta, $e);
    }


    //UPDATE EN PADRÓN PRODUCTO SOCIOS
    try {
        $consulta = "UPDATE {$tabla3} SET abm='baja', abmactual=1 WHERE cedula={$cedula}";
        $update = mysqli_query($conexion, $consulta);
        $errores = $update ? $errores : $errores++;
    } catch (Exception $e) {
        LogDB("Update_padron_productos_bd_emision", $consulta, $e);
    }



    /** OBTENGO LOS DATOS DEL PADRÓN PARA EL INSERT DE BAJAS **/
    try {
        $consulta = "SELECT pps.idrelacion, pps.servicio 'servicio_contratado', pps.hora AS 'horas_contratadas', pps.importe, pds.nombre AS 'nombre_socio', pds.sucursal AS 'filial_socio' FROM padron_producto_socio pps INNER JOIN padron_datos_socio pds ON pps.cedula = pds.cedula WHERE pps.cedula = {$cedula} ORDER BY pps.id DESC";
        $resultados = mysqli_query($conexion, $consulta);


        $id_relacion = "";
        //fecha_ingreso_baja
        $filial_solicitud = 0;
        $nombre_funcionario = "";
        $observaciones = "Baja por moroso";
        $nombre_socio = "";
        $cedula_socio = $cedula;
        $filial_socio = "";
        $servicio = "";
        $horas = "";
        $importe = "";
        $motivo_baja = "Moroso";
        $nombre_contacto = "";
        $apellido_contacto = "";
        $telefono_contacto = "";
        $celular_contacto = "";
        //fecha_inicio_gestion
        $estado = "Otorgada";
        $nombre_funcionario_final = "Sistema";
        $motivo_no_otorgada = "";
        $observacion_final = "Baja por moroso";
        $area_fin_gestion = "Bajas";
        //fecha_fin_gestion
        $activo = 0;

        while ($result = mysqli_fetch_assoc($resultados)) {
            $servicio = $servicio == "" ? $result['servicio_contratado'] : $servicio . ", " . $result['servicio_contratado'];
            $importe = $importe == "" ? $result['importe'] : $importe . ", " . $result['importe'];
            $id_relacion = $result['idrelacion'];
            $horas = $horas == "" ? $result['horas_contratadas'] : $horas . ", " . $result['horas_contratadas'];
            $nombre_socio = $result['nombre_socio'];
            $filial_socio = $result['filial_socio'];
        }
    } catch (Exception $e) {
        LogDB("Select_padron_productos_bd_emision", $consulta, $e);
    }


    /** INSERT REGISTROS DE BAJAS **/
    try {
        $consulta = "INSERT INTO {$tabla4}(idrelacion, fecha_ingreso_baja, filial_solicitud, nombre_funcionario, observaciones, nombre_socio, cedula_socio, filial_socio, servicio_contratado, horas_contratadas, importe, motivo_baja, nombre_contacto, apellido_contacto, telefono_contacto, celular_contacto, fecha_inicio_gestion, estado, nombre_funcionario_final, motivo_no_otorgada, observacion_final, area_fin_gestion, fecha_fin_gestion, activo
        ) VALUES('$id_relacion', NOW(), '$filial_solicitud', '$nombre_funcionario', '$observaciones', '$nombre_socio', '$cedula_socio', '$filial_socio', '$servicio', '$horas', '$importe', '$motivo_baja', '$nombre_contacto', '$apellido_contacto', '$telefono_contacto', '$celular_contacto', NOW(), '$estado', '$nombre_funcionario_final', '$motivo_no_otorgada', '$observacion_final', '$area_fin_gestion', NOW(), '$activo')";

        $insert = mysqli_query($conexion, $consulta);
        $errores = $insert ? $errores : $errores++;
    } catch (Exception $e) {
        LogDB("Insert_bajas_bd_emision", $consulta, $e);
    }
}






mysqli_close($conexion);

die(json_encode(
    [
        "success" => $errores > 0 ? false : true,
        "mensaje" => $errores > 0 ? "Hubo errores al guardar la información" : "Se guardaron los datos con éxito"
    ],
    JSON_PRETTY_PRINT
));
