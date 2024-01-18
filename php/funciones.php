<?php

function registrar_historial_logs()
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_HISTORIAL_LOGS;

    try {
        $sql = "INSERT INTO {$tabla} (referencia, fecha) VALUES ('CorteABM', NOW())";
        $consulta = mysqli_query($conexion, $sql);
        $id_insert_historial = mysqli_insert_id($conexion);
    } catch (Exception $e) {
        LogDB("Registrar_Error_BD", $sql, $e);
    }

    return $id_insert_historial;
}


function registrar_logs_correctos($consulta, $id_historial_logs)
{
    $conexion = connection(DB_BD_EMISION);
    $tabla1 = TABLA_LOGS_CORRECTOS;

    try {
        $query_recomillada = reemplazar_comillas_simples($consulta);

        $sql = "INSERT INTO {$tabla1} (consulta, id_historial_logs) VALUES ('{$query_recomillada}', '{$id_historial_logs}')";

        $ejecutar_sql = mysqli_query($conexion, $sql);
    } catch (Exception $e) {
        LogDB("Registrar_Correcto_BD", $sql, $e);
    }
}


function registrar_logs_errores($referencia, $consulta, $error, $id_historial_logs)
{
    $conexion = connection(DB_BD_EMISION);
    $tabla1 = TABLA_LOGS_ERRORES;

    try {
        $query_recomillada = reemplazar_comillas_simples($consulta);
        $error_recomillado = reemplazar_comillas_simples($error);

        $sql = "INSERT INTO {$tabla1} (referencia, consulta, error, id_historial_logs) VALUES ('{$referencia}', '{$query_recomillada}', '{$error_recomillado}', '{$id_historial_logs}')";

        $ejecutar_sql = mysqli_query($conexion, $sql);
    } catch (Exception $e) {
        LogDB("Registrar_Error_BD", $sql, $e);
    }
}


function reemplazar_comillas_simples($valor)
{
    return str_replace("'", '"', $valor);
}


function verificar_usuario($id = null, $usuario, $password, $hash = null, $activo = null, $verificar_sesion = false)
{
    $conexion = connection(DB_BD_EMISION);
    $tabla = TABLA_USUARIOS;

    if ($id === null && $hash === null && $activo === null && $verificar_sesion === false) {
        $sql = "SELECT * FROM {$tabla} WHERE BINARY usuario = '{$usuario}' AND BINARY `password` = '{$password}' AND activo = 1";
    } else {
        $sql = "SELECT * FROM {$tabla} WHERE id = '{$id}' AND BINARY usuario = '{$usuario}' AND BINARY `hash` = '{$hash}' AND BINARY `password` = '{$password}' AND activo = '{$activo}'";
    }

    $consulta = mysqli_query($conexion, $sql);
    $datos = mysqli_fetch_assoc($consulta);

    $activo = $datos['activo'];
    $respuesta = $activo === null ? false : $datos;

    return $respuesta;
}
