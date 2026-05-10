<?php
namespace CTS\Core; 
/**
 * \CTS\Core::init(__FILE__);
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


final class Core {

    public static string $path;
    public static string $url;

    public static function init(string $main_file_path) {

        self::$path = plugin_dir_path( $main_file_path ); 
        self::$url  = plugin_dir_url( $main_file_path );  
    }
}


$template = \CTS\Core::$path . 'templates/form.php';
$img_url  = \CTS\Core::$url . 'assets/img/icon.svg';

