<?php

function digitoCedula($cedula_sin_digito)
{
    $mul_7_digitos = [8, 1, 2, 3, 4, 7, 6];
    $mul_6_digitos = [1, 2, 3, 4, 7, 6];
    $suma = 0;
    $length_cedula_sin_digito = strlen($cedula_sin_digito);

    for ($i = 0; $i < $length_cedula_sin_digito; $i++) {
        $mul = $length_cedula_sin_digito === 6 ? $mul_6_digitos : $mul_7_digitos;
        $suma = intval($mul[$i]) * intval(substr($cedula_sin_digito, $i, 1)) + $suma;
    }
    return (int)$suma % 10;
}

function comprobarCI($cedula)
{

    if (strlen($cedula) < 6 || strlen($cedula) > 9) {
        return false;
    }


    $arrCoefs = [2, 9, 8, 7, 6, 3, 4, 1];
    $suma = 0;
    $difCoef = count($arrCoefs) - strlen($cedula);

    for ($i = strlen($cedula) - 1; $i > -1; $i--) {
        $dig = substr($cedula, $i, 1);
        $coef = $arrCoefs[$i + $difCoef];
        $suma = $suma + $dig * $coef;
    }
    if (($suma % 10) == 0) return true;
    else return false;
}

function generarHash($largo = 20)
{
    $caracteres_permitidos = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    return  strtoupper(substr(str_shuffle($caracteres_permitidos), 0, $largo));
}

/**
 * controlarExtension
 *
 * @param  mixed $files array imagenes $_FILES
 * @param  mixed $tipo  JPG, JPEG,PNG,ETC
 * @return void
 */
function controlarExtension($files, $tipo)
{
    $validar_extension = $tipo;
    $valido = 0;
    for ($i = 0; $i < count($files["name"]); $i++) {
        $extension_archivo = strtolower(pathinfo(basename($files["name"][$i]), PATHINFO_EXTENSION));

        if (in_array($extension_archivo, $validar_extension))  $valido++;
        else $valido = 0;
    }
    return $valido;
}


/**
 * subirArchivo
 *
 * @param  mixed $archivo  $_FILES["imagen"];
 * @param  mixed $destino_distinto  :  "../../documentacion/" por defecto null
 */
function subirArchivo($archivo, $destino_distinto = null)
{
    if (controlarExtension($archivo, array("png", "jpeg", "jpg", "doc", "docx", "bmp", "msg", "xls", "xlsx", "pdf")) > 0) {

        for ($i = 0; $i < count($archivo["name"]); $i++) {
            $extension_archivo = strtolower(pathinfo(basename($archivo["name"][$i]), PATHINFO_EXTENSION));
            $nombre_archivo =  generarHash(20) . '.' . $extension_archivo;
            $destino = $destino_distinto != null ? "../../documentacion/" . $nombre_archivo : $destino_distinto . "" . $nombre_archivo;

            if (move_uploaded_file($archivo["tmp_name"][$i], $destino)) return true;
            else return false; //Error al subir al servidor

        }
    } else  return false;    //Error de tipo de archivo

}


/**
 * respuesta
 *
 * @param  mixed $respuesta true or false en json quedaria respuesta :true  ó respuesta: false
 * @param  mixed $mensaje String
 */
function respuesta($status, $text = "")
{
    return json_encode(
        array(
            "success" => $status,
            "text" => $text,
        )
    );
}

function jsonMessage($status = 'success', $icon = '', $text = '', $title = '')
{
    die(json_encode(array(
        'success' => $status,
        'icon' => $icon,
        'title' => $title,
        'text' => $text
    )));
}

function jsonErrorMessage($text, $title = 'Error', $errores = null)
{
    die(json_encode(array(
        'success' => false,
        'icon' => 'error',
        'title' => $title,
        'text' => $text,
        'mensaje' => $text,
        'errores' => $errores,
        'html' => $text,
        'sesion' => false,
    )));
}
function jsonSuccessMessage($text, $title = 'Éxito')
{
    die(json_encode(array(
        'success' => true,
        'icon' => 'success',
        'title' => $title,
        'text' => $text
    )));
}

/**
 * joinMessages
 * 
 * @param array $messages
 * 
 * @return string
 */
function joinMessages($messages)
{
    return join(' ', array_map(function ($error) {
        return "<p>$error</p>";
    }, $messages));
}


/**
 * recortarCampo
 *
 * @param  string | Array $campo  EJ: $_REQUEST["descripcion"] o array de campos o strings
 * @param  mixed $largo 60 ( siendo 60 un "int" para que muestre hasta 60 caracteres)
 */
function recortarString($campo, $largo = 60)
{
    if (is_array($campo)) {
        foreach ($campo as $key => $c) {
            if (!is_array($c)) $campo[$key] = remplazarString($c, $largo);
        }
    } else $campo = remplazarString($campo, $largo);

    return $campo;
}

function remplazarString($campo, $largo)
{
    $largo = intval($largo);
    if (strlen($campo) > $largo) {
        $br  = array("<br />", "<br>", "<br/>");
        $campo = str_ireplace($br, "\r\n", $campo);

        $campo_sin_editar = mb_convert_encoding($campo, 'UTF-8', 'UTF-8');
        $campo = substr($campo, 0, $largo) . " ...<a href='#' onclick='verMasTabla(event,`" . $campo_sin_editar . "`);'> Ver Más</a>";
        $campo = mb_convert_encoding($campo, 'UTF-8', 'UTF-8');
    }
    return $campo;
}



function validarForm($request, $reglas, $array = false)
{

    $validador = new Validate($request, $reglas);

    if (!$validador->exec()) {
        $mensajes = $validador->getMessages();
        if ($array == false) {
            $mensaje_con_errores = '';

            foreach ($mensajes as $mens) {
                foreach ($mens as $m) {
                    $mensaje_con_errores .= '<div style="color:red;">' . $m . '</div><br>';
                }
            }

            die(jsonErrorMessage($mensaje_con_errores));
        } else {
            return $mensajes;
        }
    } else {
        return true;
    }
}