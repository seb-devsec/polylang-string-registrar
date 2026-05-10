<?php

spl_autoload_register( 'CustomTranslationStrings_autoloader' );
function CustomTranslationStrings_autoloader( $class_name ) {
	$namespace_root = 'CTS\\';
	$base_dir = plugin_dir_path( __FILE__ ) . 'includes/';

	// Only load classes from our namespace
	if ( strpos( $class_name, $namespace_root ) !== 0 ) {
		return;
	}

	// Remove namespace root and convert namespace to path
	$relative_class = substr( $class_name, strlen( $namespace_root ) );
	$path_parts = explode( '\\', $relative_class );

	// Filename convention: class-[lowercase].php
	$class_file = 'class-' . str_replace( '_', '-', strtolower( array_pop( $path_parts ) ) );
	$sub_path = implode( DIRECTORY_SEPARATOR, $path_parts );

	// Final file path
	$full_path = $base_dir . ( $sub_path ? $sub_path . DIRECTORY_SEPARATOR : '' ) . $class_file . '.php';

	if ( file_exists( $full_path ) ) {
		require_once $full_path;

		// Optional debug
		// if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		// 	error_log( '[Autoloader] Loaded: ' . $full_path );
		// }
	}
}
