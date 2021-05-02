<?php
/**
 *
 * @package    PACMEC
 * @category   Settings
 * @version    0.0.1
 */

define('DB_port', '3306');                                   // Base de datos: Puerto de conexion (Def: 3306)
define('DB_driver', 'mysql');                                // Base de datos: Controlador de la conexion (Def: mysql)
define('DB_host', 'localhost');                              // Base de datos: Servidor/Host de conexion (Def: localhost)
define('DB_user', 'pacmec_u');                               // Base de datos: Usuario de conexion
define('DB_pass', 'pacmec_p');                               // Base de datos: Contraseña del usuario
define('DB_database', 'pacmec_dev');                         // Base de datos: Nombre de la base de datos
define('DB_charset', 'utf8mb4');                             // Base de datos: Caracteres def
define('DB_prefix', 'mt_');                                  // Base de datos: Prefijo de las tablas (Opcional)
define('AUTH_KEY_COST', 4);                                  // Nivel de encr: Costo del algoritmo MIN: 4
define('MODE_DEBUG', false);                                 // Modo    Debug: Activar el modo DEBUG
define('PACMEC_SSL', true);                                  // Habilitar SSL Forzado
define('SMTP_CC', false);                                    // Habilitar SSL Forzado
define('SMTP_BCC', false);                                   // Habilitar SSL Forzado
define('PACMEC_DEF_SEPARATOR_PATH', '/');
define('PACMEC_LANG_DEF', 'es-CO');
define('ENCODE_KEY',        'put your unique phrase here');
