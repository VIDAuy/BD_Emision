<?php
include_once "../configuraciones.php";
include_once './lib/PhpSpreadSheet/vendor/autoload.php';


/** Cargamos la librería **/

use PhpOffice\PhpSpreadsheet\IOFactory;


ini_set('memory_limit', '-1');
php_ini_loaded_file();
$file = $_FILES["file"];
$archivo = $_FILES["file"]["name"];
$nuevo_nombre = generarHash(50) . ".xlsx";
if (move_uploaded_file($file["tmp_name"], "./excel_cargados/$nuevo_nombre") == false) {
    die(json_encode(
        [
            "success" => false,
            "mensaje" => "Hubo errores al guardar la información",
        ],
        JSON_PRETTY_PRINT
    ));
}



$conexion = connection(DB_BD_EMISION);
$conexion2 = connection(DB_CRM);
//$conexion2 = connection(DB);
$tabla = TABLA_REGISTRAR_BAJAS;
$tabla1 = TABLA_HISTORIAL_CARGA_BAJAS;
$tabla2 = TABLA_PADRON_DATOS_SOCIO;
$tabla3 = TABLA_PADRON_PRODUCTOS_SOCIO;
$tabla4 = TABLA_BAJAS;
$tabla5 = TABLA_REGISTROS;


$nombre_archivo = "./excel_cargados/$nuevo_nombre";
$documento = IOFactory::load($nombre_archivo); //Cargamos el archivo excel
$total_hojas = $documento->getSheetCount(); //Obtenemos la cantidad de hojas
$errores = 0;


$array_cedulas = [];
for ($indice_hoja = 0; $indice_hoja < $total_hojas; $indice_hoja++) {
    //Establecemos la hoja que va a analizar
    $hoja_actual = $documento->getSheet($indice_hoja);

    //Obtenemos la cantidad de filas que contienen información
    $numero_filas = $hoja_actual->getHighestDataRow();

    //Obtenemos la cantidad de columnas que contienen información
    $letra = $hoja_actual->getHighestColumn();

    for ($indice_fila = 2; $indice_fila <= $numero_filas; $indice_fila++) {
        $cedula = $hoja_actual->getCell("A" . $indice_fila)->getValue();
        array_push($array_cedulas, $cedula);
    }
}


$cantidad_registros = count($array_cedulas);

if (file_exists($nombre_archivo)) {
    try {
        rename($archivo, $nombre_archivo);
        registrar_logs_correctos("Mover planilla - " . $nombre_archivo, "");
    } catch (Exception $e) {
        registrar_logs_errores("Mover planilla", $nombre_archivo, $e, "");
    }
}


//INSERT DE HISTORIAL DE CARGA DEL EXCEL
try {
    $consulta = "INSERT INTO {$tabla1}(fecha_subida, cantidad_registros, nombre_archivo) VALUES(NOW(), {$cantidad_registros}, '{$nuevo_nombre}')";
    $insert = mysqli_query($conexion, $consulta);
    $id_insert_historial = mysqli_insert_id($conexion);
    $errores = $insert ? $errores : $errores++;

    if ($insert === true) {
        registrar_logs_correctos($consulta, $id_insert_historial);
    }
} catch (Exception $e) {
    registrar_logs_errores("uploaderBajas_" . "Insert_historial_carga_bajas", $consulta, $e, $id_historial_logs);
}



//die(json_encode($array_cedulas));



$array_insert = [];
foreach ($array_cedulas as $cedula) {

    //INSERT DE BAJAS CARGADAS EN EL EXCEL
    try {
        $consulta = "INSERT INTO {$tabla}(cedula, id_historial_carga_bajas) VALUES('{$cedula}', {$id_insert_historial})";
        $insert = mysqli_query($conexion, $consulta);
        $id_insert = mysqli_insert_id($conexion);
        $errores = $insert ? $errores : $errores++;

        if ($insert === true) {
            registrar_logs_correctos($consulta, $id_insert_historial);
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_registrar_bajas", $consulta, $e, $id_historial_logs);
    }


    //UPDATE EN PADRÓN DATOS SOCIOS
    try {
        $consulta = "UPDATE {$tabla2} SET abm='baja', abmactual=1 WHERE cedula={$cedula}";
        $update = mysqli_query($conexion, $consulta);
        $errores = $update ? $errores : $errores++;

        if ($update === true) {
            registrar_logs_correctos($consulta, $id_insert_historial);
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Update_padron_socios", $consulta, $e, $id_historial_logs);
    }


    //UPDATE EN PADRÓN PRODUCTO SOCIOS
    try {
        $consulta = "UPDATE {$tabla3} SET abm='baja', abmactual=1 WHERE cedula={$cedula}";
        $update = mysqli_query($conexion, $consulta);
        $errores = $update ? $errores : $errores++;

        if ($update === true) {
            registrar_logs_correctos($consulta, $id_insert_historial);
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Update_padron_productos", $consulta, $e, $id_historial_logs);
    }



    /** OBTENGO LOS DATOS DEL PADRÓN PARA EL INSERT DE BAJAS **/
    try {
        $consulta = "SELECT 
            pps.idrelacion, 
            pps.servicio 'servicio_contratado', 
            pps.hora AS 'horas_contratadas', 
            pps.importe, 
            pds.nombre AS 'nombre_socio', 
            pds.sucursal AS 'filial_socio', 
            pds.tel AS 'telefono'
        FROM 
            padron_producto_socio pps 
            INNER JOIN padron_datos_socio pds ON pps.cedula = pds.cedula 
        WHERE 
            pps.cedula = {$cedula} 
        ORDER BY 
            pps.id DESC";
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
            $telefono_contacto = $result['telefono'];
        }

        if (mysqli_num_rows($resultados) > 0) {
            registrar_logs_correctos($consulta, $id_insert_historial);
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Select_padron", $consulta, $e, $id_historial_logs);
    }


    /** INSERT REGISTRO DE BAJA **/
    try {
        $consulta = "INSERT INTO {$tabla4}(idrelacion, fecha_ingreso_baja, filial_solicitud, nombre_funcionario, observaciones, nombre_socio, cedula_socio, filial_socio, servicio_contratado, horas_contratadas, importe, motivo_baja, nombre_contacto, apellido_contacto, telefono_contacto, celular_contacto, fecha_inicio_gestion, estado, nombre_funcionario_final, motivo_no_otorgada, observacion_final, area_fin_gestion, fecha_fin_gestion, activo
        ) VALUES('$id_relacion', NOW(), '$filial_solicitud', '$nombre_funcionario', '$observaciones', '$nombre_socio', '$cedula_socio', '$filial_socio', '$servicio', '$horas', '$importe', '$motivo_baja', '$nombre_contacto', '$apellido_contacto', '$telefono_contacto', '$celular_contacto', NOW(), '$estado', '$nombre_funcionario_final', '$motivo_no_otorgada', '$observacion_final', '$area_fin_gestion', NOW(), '$activo')";

        $insert = mysqli_query($conexion, $consulta);
        $errores = $insert ? $errores : $errores++;

        if ($insert === true) {
            registrar_logs_correctos($consulta, $id_insert_historial);
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_bajas", $consulta, $e, $id_historial_logs);
    }


    /** INSERT REGISTRO EN CRM **/
    try {
        $consulta = "INSERT INTO {$tabla5} (cedula, nombre, telefono, fecha_registro, sector, observaciones, socio, baja) VALUES ('$cedula_socio', '$nombre_socio', '$telefono_contacto', NOW(), 'Morosos', 'Baja por Moroso', 0, 1)";

        $insert = mysqli_query($conexion2, $consulta);
        $errores = $insert ? $errores : $errores++;

        if ($insert === true) {
            registrar_logs_correctos($consulta, $id_insert_historial);
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_registro_crm", $consulta, $e, $id_historial_logs);
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
