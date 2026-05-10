<?php
namespace CTS\Admin; 
use CTS\Admin\Translation_List_Table;
use CTS\Admin\Notice_Renderer;
use CTS\Classes\Import_Export_Form;
/**
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Render_Registered_Strings {


    private Notice_Renderer $notice_renderer;
    private Import_Export_Form $form_renderer;

    public function __construct(Notice_Renderer $notice_renderer, Import_Export_Form $form_renderer) {
        $this->notice_renderer = $notice_renderer;
        $this->form_renderer = $form_renderer;
    }

      public function render_registered_strings() {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Added strings for translation', 'cts') . '</h1>';


        $this->notice_renderer->render_notices();

        $this->form_renderer->render_csv_import();
        $this->form_renderer->render_sql_import();
        $this->form_renderer->render_export_buttons();
        $this->form_renderer->render_delete_all_button();

        $table = new Translation_List_Table();
        $table->prepare_items();
        
        echo '<form method="post">';

        // echo '<pre>';
        // print_r($table->items);
        // echo '</pre>';
        $table->display();
        echo '</form>';

        echo '</div>';
    }


}