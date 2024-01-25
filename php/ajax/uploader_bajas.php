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


$mover_archivo = guardar_archivo_servidor($file, $nuevo_nombre);

if ($mover_archivo === false) {
    die(json_encode(["success" => false, "mensaje" => "Ocurrieron errores al subir el archivo"], JSON_PRETTY_PRINT));
}


$nombre_archivo = "./excel_cargados/$nuevo_nombre";
$documento = IOFactory::load($nombre_archivo); //Cargamos el archivo excel
$total_hojas = $documento->getSheetCount(); //Obtenemos la cantidad de hojas


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


$reemplazar_archivo_duplicado = reemplazar_archivo_duplicado($nombre_archivo, $archivo);

if ($reemplazar_archivo_duplicado === true) {
    die(json_encode(["success" => false, "mensaje" => "Ocurrieron errores al reemplazar el archivo"], JSON_PRETTY_PRINT));
}


$id_insert_historial = registrar_historial_carga_excel($cantidad_registros, $nuevo_nombre);

if ($id_insert_historial === true) {
    die(json_encode(["success" => false, "mensaje" => "Ocurrieron errores al registrar la carga del archivo"], JSON_PRETTY_PRINT));
}



//die(json_encode($array_cedulas));



$vaciar_tabla_bajas_morosidad = vaciar_tabla_bajas_morosidad();

if ($vaciar_tabla_bajas_morosidad === true) {
    die(json_encode(["success" => false, "mensaje" => "Ocurrieron errores al vaciar la tabla bajas morosidad"], JSON_PRETTY_PRINT));
}




$errores = 0;
$array_insert = [];
foreach ($array_cedulas as $cedula) {

    $id_historial_carga_bajas = registrar_historial_carga_bajas($cedula);

    if ($id_historial_carga_bajas === true) {
        $errores++;
    }


    $dar_baja_socio = dar_baja_socio($cedula);

    if ($dar_baja_socio === true) {
        $errores++;
    }


    $dar_baja_productos_socio = dar_baja_productos_socio($cedula);

    if ($dar_baja_productos_socio === true) {
        $errores++;
    }


    $datos_padron_para_insert_de_bajas = datos_padron_para_insert_de_bajas($cedula);

    if ($datos_padron_para_insert_de_bajas === true) {
        $errores++;
    }


    $registrar_en_bajas = registrar_en_bajas($datos_padron_para_insert_de_bajas, $cedula);

    if ($registrar_en_bajas === true) {
        $errores++;
    }


    $dejar_registro_crm = registrar_en_crm($datos_padron_para_insert_de_bajas, $cedula);

    if ($dejar_registro_crm === true) {
        $errores++;
    }


    $datos_padron_socios = obtener_todos_datos_socios($cedula);

    if ($datos_padron_socios === true) {
        $errores++;
    }


    $registrar_en_bajas_morosidad = registrar_en_bajas_morosidad($datos_padron_socios, $id_historial_carga_bajas);

    if ($registrar_en_bajas_morosidad === true) {
        $errores++;
    }
}








die(json_encode(
    [
        "success" => $errores > 0 ? false : true,
        "mensaje" => $errores > 0 ? "Hubo errores al guardar la información" : "Se guardaron los datos con éxito"
    ],
    JSON_PRETTY_PRINT
));






function guardar_archivo_servidor($file, $nuevo_nombre)
{
    $error = false;

    if (move_uploaded_file($file["tmp_name"], "./excel_cargados/$nuevo_nombre") == false) {
        die(json_encode(["success" => false, "mensaje" => "Hubo errores al guardar la información"], JSON_PRETTY_PRINT));
        $error = true;
    } else {
        $error = false;
    }

    return $error;
}

function reemplazar_archivo_duplicado($nombre_archivo, $archivo)
{
    $error = false;

    if (file_exists($nombre_archivo)) {
        try {
            rename($archivo, $nombre_archivo);
            registrar_logs_correctos("Mover planilla - " . $nombre_archivo, "");
            $error = false;
        } catch (Exception $e) {
            registrar_logs_errores("Mover planilla", $nombre_archivo, $e, "");
            $error = true;
        }
    }

    return $error;
}

function registrar_historial_carga_excel($cantidad_registros, $nuevo_nombre)
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_HISTORIAL_CARGA_BAJAS;

    $error = false;

    try {
        $sql = "INSERT INTO {$tabla}(fecha_subida, cantidad_registros, nombre_archivo) VALUES(NOW(), {$cantidad_registros}, '{$nuevo_nombre}')";
        $consulta = mysqli_query($conexion, $sql);
        $id_insert_historial = mysqli_insert_id($conexion);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_historial_carga_bajas", $sql, $e, "");
        $error = true;
    }

    return $error == true ? true : $id_insert_historial;
}

function vaciar_tabla_bajas_morosidad()
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_BAJAS_MOROSIDAD;
    global $id_insert_historial;

    $error = false;

    try {
        $sql = "TRUNCATE TABLE {$tabla}";
        $consulta = mysqli_query($conexion, $sql);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "vaciarTablaBajasMorosidad", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error;
}

function registrar_historial_carga_bajas($cedula)
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_REGISTRAR_BAJAS;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "INSERT INTO {$tabla}(cedula, id_historial_carga_bajas) VALUES('{$cedula}', {$id_insert_historial})";
        $consulta = mysqli_query($conexion, $sql);
        $id_historial_registro_baja = mysqli_insert_id($conexion);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_registrar_bajas", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error == true ? true : $id_historial_registro_baja;
}

function dar_baja_socio($cedula)
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_DATOS_SOCIO;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "UPDATE {$tabla} SET abm='baja', abmactual=1 WHERE cedula={$cedula}";
        $consulta = mysqli_query($conexion, $sql);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Update_padron_socios", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error;
}

function dar_baja_productos_socio($cedula)
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_PRODUCTOS_SOCIO;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "UPDATE {$tabla} SET abm='baja', abmactual=1 WHERE cedula={$cedula}";
        $consulta = mysqli_query($conexion, $sql);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Update_padron_productos", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error;
}

function datos_padron_para_insert_de_bajas($cedula)
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_PADRON_PRODUCTOS_SOCIO;
    $tabla2 = TABLA_PADRON_DATOS_SOCIO;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "SELECT 
            pps.idrelacion, 
            pps.servicio 'servicio_contratado', 
            pps.hora AS 'horas_contratadas', 
            pps.importe, 
            pds.nombre AS 'nombre_socio', 
            pds.sucursal AS 'filial_socio', 
            pds.tel AS 'telefono'
        FROM 
            {$tabla} pps 
            INNER JOIN {$tabla2} pds ON pps.cedula = pds.cedula 
        WHERE 
            pps.cedula = {$cedula} 
        ORDER BY 
            pps.id DESC";
        $consulta = mysqli_query($conexion, $sql);


        if (mysqli_num_rows($consulta) > 0) {
            registrar_logs_correctos($sql, $id_insert_historial);
            $error = false;
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Select_padron_productos_y_socios", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error == true ? true : $consulta;
}

function registrar_en_bajas($datos_padron_para_insert_de_bajas, $cedula)
{
    $id_relacion = "";
    $nombre_socio = "";
    $filial_socio = "";
    $servicio = "";
    $horas = "";
    $importe = "";
    $telefono_contacto = "";

    while ($result = mysqli_fetch_assoc($datos_padron_para_insert_de_bajas)) {
        $id_relacion = $result['idrelacion'];
        $nombre_socio = $result['nombre_socio'];
        $filial_socio = $result['filial_socio'];
        $servicio = $servicio == "" ? $result['servicio_contratado'] : $servicio . ", " . $result['servicio_contratado'];
        $horas = $horas == "" ? $result['horas_contratadas'] : $horas . ", " . $result['horas_contratadas'];
        $importe = $importe == "" ? $result['importe'] : $importe . ", " . $result['importe'];
        $telefono_contacto = $result['telefono'];
    }


    $conexion = connection(DB_CRM);
    $tabla = TABLA_BAJAS;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "INSERT INTO {$tabla}(idrelacion, fecha_ingreso_baja, filial_solicitud, nombre_funcionario, observaciones, nombre_socio, cedula_socio, filial_socio, servicio_contratado, horas_contratadas, importe, motivo_baja, nombre_contacto, apellido_contacto, telefono_contacto, celular_contacto, fecha_inicio_gestion, estado, nombre_funcionario_final, motivo_no_otorgada, observacion_final, area_fin_gestion, fecha_fin_gestion, activo
        ) VALUES('$id_relacion', NOW(), 0, '', 'Baja por moroso', '$nombre_socio', '$cedula', '$filial_socio', '$servicio', '$horas', '$importe', 'Moroso', '', '', '$telefono_contacto', '', NOW(), 'Otorgada', 'Sistema', '', 'Baja por moroso', 'Bajas', NOW(), 0)";
        $consulta = mysqli_query($conexion, $sql);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
            $error = false;
        }
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_bajas", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error;
}

function registrar_en_crm($datos_padron_para_insert_de_bajas, $cedula)
{
    $nombre_socio = "";
    $telefono_contacto = "";

    while ($result = mysqli_fetch_assoc($datos_padron_para_insert_de_bajas)) {
        $nombre_socio = $result['nombre_socio'];
        $telefono_contacto = $result['telefono'];
    }

    $conexion = connection(DB_CRM);
    $tabla = TABLA_REGISTROS;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "INSERT INTO {$tabla} (cedula, nombre, telefono, fecha_registro, sector, observaciones, socio, baja) VALUES ('$cedula', '$nombre_socio', '$telefono_contacto', NOW(), 'Morosos', 'Baja por Moroso', 0, 1)";
        $consulta = mysqli_query($conexion, $sql);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_registro_crm", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error;
}

function obtener_todos_datos_socios($cedula)
{
    $conexion = connection(DB);
    $tabla = TABLA_PADRON_DATOS_SOCIO;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "SELECT * FROM {$tabla} WHERE cedula = {$cedula} ORDER BY id DESC";
        $consulta = mysqli_query($conexion, $sql);


        if (mysqli_num_rows($consulta) > 0) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Select_padron_datos_socios", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error == true ? true : $consulta;
}

function registrar_en_bajas_morosidad($datos_padron_socios, $id_historial_carga_bajas)
{
    $id = $datos_padron_socios["id"];
    $nombre = $datos_padron_socios["nombre"];
    $tel = $datos_padron_socios["tel"];
    $cedula = $datos_padron_socios["cedula"];
    $direccion = $datos_padron_socios["direccion"];
    $sucursal = $datos_padron_socios["sucursal"];
    $ruta = $datos_padron_socios["ruta"];
    $radio = $datos_padron_socios["radio"];
    $activo = $datos_padron_socios["activo"];
    $fecha_nacimiento = $datos_padron_socios["fecha_nacimiento"];
    $edad = $datos_padron_socios["edad"];
    $tarjeta = $datos_padron_socios["tarjeta"];
    $tipo_tarjeta = $datos_padron_socios["tipo_tarjeta"];
    $numero_tarjeta = $datos_padron_socios["numero_tarjeta"];
    $nombre_titular = $datos_padron_socios["nombre_titular"];
    $cedula_titular = $datos_padron_socios["cedula_titular"];
    $telefono_titular = $datos_padron_socios["telefono_titular"];
    $anio_e = $datos_padron_socios["anio_e"];
    $mes_e = $datos_padron_socios["mes_e"];
    $sucursal_cobranzas = $datos_padron_socios["sucursal_cobranzas"];
    $sucursal_cobranza_num = $datos_padron_socios["sucursal_cobranza_num"];
    $empresa_marca = $datos_padron_socios["empresa_marca"];
    $flag = $datos_padron_socios["flag"];
    $count = $datos_padron_socios["count"];
    $observaciones = $datos_padron_socios["observaciones"];
    $grupo = $datos_padron_socios["grupo"];
    $idrelacion = $datos_padron_socios["idrelacion"];
    $empresa_rut = $datos_padron_socios["empresa_rut"];
    $total_importe = $datos_padron_socios["total_importe"];
    $nactual = $datos_padron_socios["nactual"];
    $version = $datos_padron_socios["version"];
    $flagchange = $datos_padron_socios["flagchange"];
    $rutcentralizado = $datos_padron_socios["rutcentralizado"];
    $PRINT = $datos_padron_socios["PRINT"];
    $EMITIDO = $datos_padron_socios["EMITIDO"];
    $movimientoabm = $datos_padron_socios["movimientoabm"];
    $abm = $datos_padron_socios["abm"];
    $abmactual = $datos_padron_socios["abmactual"];
    $check = $datos_padron_socios["check"];
    $usuario = $datos_padron_socios["usuario"];
    $usuariod = $datos_padron_socios["usuariod"];
    $fechafil = $datos_padron_socios["fechafil"];
    $radioViejo = $datos_padron_socios["radioViejo"];
    $extra = $datos_padron_socios["extra"];
    $nomodifica = $datos_padron_socios["nomodifica"];
    $origenVta = $datos_padron_socios["origenVta"];
    $imp_desc = $datos_padron_socios["imp_desc"];



    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_BAJAS_MOROSIDAD;

    global $id_insert_historial;

    $error = false;

    try {
        $sql = "INSERT INTO {$tabla} (id, nombre, tel, cedula, direccion, sucursal, ruta, radio, activo, fecha_nacimiento, edad, tarjeta, tipo_tarjeta, numero_tarjeta, nombre_titular, cedula_titular, telefono_titular, anio_e, mes_e, sucursal_cobranzas, sucursal_cobranza_num, empresa_marca, flag, count, observaciones, grupo, idrelacion, empresa_rut, total_importe, nactual, `version`, flagchange, rutcentralizado, `PRINT`, EMITIDO, movimientoabm, abm, abmactual, `check`, usuario, usuariod, fechafil, radioViejo, extra, nomodifica, origenVta, imp_desc, id_registrar_bajas) VALUES ('$id', '$nombre', '$tel', '$cedula', '$direccion', '$sucursal', '$ruta', '$radio', '$activo', '$fecha_nacimiento', '$edad', '$tarjeta', '$tipo_tarjeta', '$numero_tarjeta', '$nombre_titular', '$cedula_titular', '$telefono_titular', '$anio_e', '$mes_e', '$sucursal_cobranzas', '$sucursal_cobranza_num', '$empresa_marca', '$flag', '$count', '$observaciones', '$grupo', '$idrelacion', '$empresa_rut', '$total_importe', '$nactual', '$version', '$flagchange', '$rutcentralizado', '$PRINT', '$EMITIDO', '$movimientoabm', '$abm', '$abmactual', '$check', '$usuario', '$usuariod', '$fechafil', '$radioViejo', '$extra', '$nomodifica', '$origenVta', '$imp_desc', '$id_historial_carga_bajas')";
        $consulta = mysqli_query($conexion, $sql);

        if ($consulta === true) {
            registrar_logs_correctos($sql, $id_insert_historial);
        }

        $error = false;
    } catch (Exception $e) {
        registrar_logs_errores("uploaderBajas_" . "Insert_registro_bajas_morosidad", $sql, $e, $id_insert_historial);
        $error = true;
    }

    return $error;
}
