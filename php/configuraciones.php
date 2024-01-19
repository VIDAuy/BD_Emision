<?php

if (session_status() !== PHP_SESSION_ACTIVE)    session_start();

date_default_timezone_set('America/Montevideo');

define("PATH_APP", __DIR__);

const PRODUCCION = false; // para definir si es test o produccion la APP

error_reporting(PRODUCCION ? 0 : E_ALL);

//HEADERS
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, POST');
//header('Access-Control-Allow-Origin: *');

const PATH_FUNCIONEs = "modelos/";
const PATH_LIB = "lib/";

//DB Conexiones
include_once PATH_APP . "/db.php";

//Lib
include_once PATH_LIB . "monolog/monolog.php";
include_once PATH_LIB . "validate.php";
//include_once PATH_LIB . "PHPExcel-1.8/Classes/PHPExcel.php";


//LOGS
const LOGS_DIR = PATH_APP . "/logs";

//Utils
//Functions
include_once "funciones.php";
include_once "utils.php";


//DB PROD
const DB_ABMMOD_PROD         = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "abmmod");
const DB_CRM_PROD            = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "crm");
const DB_BD_EMISION_PROD     = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "bd_emision");
//const DB_COORDINACION_PROD = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinacion");

//DB TEST
const DB_ABMMOD_TEST         = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "abmmod");
const DB_CRM_TEST            = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "crm");
const DB_BD_EMISION_TEST     = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "bd_emision");
//const DB_COORDINACION_TEST = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinacion");

//DB PROD O TEST
const DB                = PRODUCCION ? DB_ABMMOD_PROD       : DB_ABMMOD_TEST;
const DB_CRM            = PRODUCCION ? DB_CRM_PROD          : DB_CRM_TEST;
const DB_BD_EMISION     = PRODUCCION ? DB_BD_EMISION_PROD   : DB_BD_EMISION_TEST;
//const DB_COORDINACION = PRODUCCION ? DB_COORDINACION_PROD : DB_COORDINACION_TEST;


//TABLAS BD

//Server 250
const TABLA_USUARIOS                 = "usuarios";
const TABLA_PADRON_DATOS_SOCIO       = "padron_datos_socio";
const TABLA_PADRON_PRODUCTOS_SOCIO   = "padron_producto_socio";
const TABLA_SERVICIOS_CODIGOS        = "servicios_codigos";
const TABLA_PROMO                    = "promo";
const VISTA_SOCIOS_PRODUCTOS         = "abmActual_con_socios_y_productos";
const TABLA_HISTORIAL_CARGA_BAJAS    = "historial_carga_bajas";
const TABLA_REGISTRAR_BAJAS          = "registrar_bajas";
const TABLA_BAJAS                    = "bajas";
const TABLA_HISTORIAL_LOGS           = "historial_logs";
const TABLA_LOGS_CORRECTOS           = "logs_correctos";
const TABLA_LOGS_ERRORES             = "logs_errores";
const TABLA_REGISTROS                = "registros";




//MENESAJES 
const ERROR_PERMISOS =  ["success" => false, "mensaje" => "Usted no cuenta con dichos permisos para ejecutar la operación", "permisos" => false];
const ERROR_LOGIN = "Error de usuario o contraseña";
const ERROR_SESSION_USUARIO = "Error al verificar tu sesión , cierra la sesión y vuelve a ingresar";
const ERROR_GENERAL = "Ocurrio un error, contacte con el administrador";

const ERROR_CONSULTA = "Ocurrio un error al intentar insertar el registro";
const EXITO_CONSULTA = "Se inserto el registro con exito";
const ERROR_USUARIO_INEXISTENTE = "Error, el usuario ingresado no existe";
const ERROR_TABLA_TEMPORAL = "Error al crear la tabla temporal";
const ERROR_INSERT_TABLA_TEMPORAL = "Error al insertar en la tabla temporal";
