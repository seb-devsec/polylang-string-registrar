<?php
namespace CTS;
/**
 * The main class used to run the plugin
 *
 * @link       
 * @since      1.0.0
 * @package    Custom Translation Strings
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Run {

	public function __construct() {

        add_action('init', [$this, 'load_textdomain']);
		
    	if (is_admin()) {
        
        $notice_renderer = new \CTS\Admin\Notice_Renderer();
        $form_renderer = new \CTS\Classes\Import_Export_Form();

        $form_page    = new \CTS\Admin\Render_Translation_Page();
        $strings_page = new \CTS\Admin\Render_Registered_Strings($notice_renderer, $form_renderer);

        $admin_manager = new \CTS\Admin\Admin_Manager($strings_page, $form_page);
        add_action('admin_menu', [ $admin_manager, 'register_menu' ]);
        add_action('admin_enqueue_scripts', [ $admin_manager, 'enqueue_admin_scripts' ]);
        add_action('admin_init', [ $admin_manager, 'register_settings' ]);
        add_action('admin_post_cts_delete_all_translations', [ $admin_manager, 'cts_delete_all_translations' ]);
        add_action('admin_post_cts_delete_translation',  [ $admin_manager, 'cts_delete_single_translation' ]);

        $translation_controller = new \CTS\Classes\Translation_Handler();
        add_action('admin_post_register_translation', [ $translation_controller, 'handle_translation_registration' ]);

        $import_export = new \CTS\Classes\Translation_Importer_Exporter();
        add_action('admin_post_export_cts_translations', [ $import_export, 'handle_export' ]);
        add_action('admin_post_import_cts_translations', [ $import_export, 'handle_import' ]);
        add_action('admin_post_export_cts_sql', [ $import_export, 'handle_export_sql' ]);
        add_action('admin_post_import_sql_file', [ $import_export, 'handle_sql_import' ]);
    }

    $custom_strings = new \CTS\Classes\Register_Custom_Strings();
    add_action('init', [ $custom_strings, 'register_custom_strings_for_translation' ]);

	}

    public function load_textdomain() {
        // Używamy zdefiniowanej przez Ciebie stałej CTS_PLUGIN_PATH
        load_plugin_textdomain(
            'cts', 
            false, 
            dirname( plugin_basename( CTS_PLUGIN_PATH . 'index.php' ) ) . '/languages' 
        );
    }
}


