<?php
namespace CTS\Admin; 
use WP_List_Table;

/**
 * 
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Translation_List_Table extends \WP_List_Table {

        public function __construct() {
            parent::__construct([
                'singular' => 'translation',
                'plural'   => 'translations',
                'ajax'     => false
            ]);
        }

        public function get_columns() {
            return [
                'id'                => __( 'ID', 'cts' ),
                'string_identifier' => __( 'Identifier', 'cts' ),
                'string_value'      => __( 'Text', 'cts' ),
                'text_domain'       => __( 'Group', 'cts' ),
                'actions'           => __( 'Actions', 'cts' ),
            ];
        }

    public function get_sortable_columns() {
        return [];
    }

    public function no_items() {
        esc_html_e( 'No registered translations found.', 'cts' );
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cts_custom_strings';

        $per_page = (int) get_option('cts_per_page', 10);
        if ($per_page < 1) $per_page = 10;

        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        $total_items = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        $this->items = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d", $per_page, $offset),
            ARRAY_A
        );

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }

   

    public function column_default($item, $column_name) {
        if ($column_name === 'actions') {
            $id = intval($item['id']); 
            
            $edit_url   = admin_url('admin.php?page=edit-translation&edit=' . $id);
            
            $delete_url = admin_url('admin-post.php?action=cts_delete_translation&id=' . $id);
  
            $delete_url = wp_nonce_url($delete_url, 'delete_translation_' . $id);

           return sprintf(
                '<a href="%1$s">%2$s</a> | <a href="%3$s" onclick="return confirm(\'%4$s\')">%5$s</a>',
                esc_url( $edit_url ),
                esc_html__( 'Edit', 'cts' ),
                esc_url( $delete_url ),
                esc_js( __( 'Are you sure you want to delete this translation?', 'cts' ) ),
                esc_html__( 'Delete', 'cts' )
            );
        }

        return esc_html($item[$column_name] ?? '');
    }
      


    public function get_column_info() {
    $columns = $this->get_columns();
    $hidden = get_hidden_columns($this->screen);
    $sortable = $this->get_sortable_columns();

    return [ $columns, $hidden, $sortable, 'string_identifier' ];
}

}

