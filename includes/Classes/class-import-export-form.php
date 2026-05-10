<?php
namespace CTS\Classes; 
/**
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Import_Export_Form {

    public function render_csv_import(): void {
        echo '<h2>' . esc_html__( 'Import Translations (CSV)', 'cts' ) . '</h2>';
        echo '<form method="post" enctype="multipart/form-data" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
        echo '<input type="hidden" name="action" value="import_cts_translations">';
        wp_nonce_field( 'import_cts_translations', 'import_cts_nonce' );
        echo '<input type="file" name="csv_file" accept=".csv" required>';
        echo '&nbsp;';
        echo '<input type="submit" class="button button-primary" value="' . esc_attr__( 'Import', 'cts' ) . '">';
        echo '</form>';
    }

    public function render_sql_import(): void {
        echo '<h2>' . esc_html__( 'Import Translations (SQL)', 'cts' ) . '</h2>';
        echo '<form method="post" enctype="multipart/form-data" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
        echo '<input type="hidden" name="action" value="import_sql_file">';
        wp_nonce_field( 'import_sql_nonce', 'import_sql_nonce' );
        echo '<input type="file" name="sql_file" accept=".sql" required><br><br>';
        echo '<input type="submit" class="button button-primary" value="' . esc_attr__( 'Import SQL', 'cts' ) . '">';
        echo '</form>';
    }

    public function render_export_buttons(): void {
        echo '<br>';
        echo '<a href="' . esc_url( admin_url( 'admin-post.php?action=export_cts_translations' ) ) . '" class="button">' . esc_html__( 'Export CSV', 'cts' ) . '</a>';
        echo '&nbsp;';
        echo '<a href="' . esc_url( admin_url( 'admin-post.php?action=export_cts_sql' ) ) . '" class="button">' . esc_html__( 'Export SQL', 'cts' ) . '</a>';
        echo '<br><br>';
    }

    public function render_delete_all_button(): void {
        $confirm_msg = esc_js( __( 'Are you sure you want to delete all translations? This action cannot be undone.', 'cts' ) );
        
        echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" onsubmit="return confirm(\'' . $confirm_msg . '\')">';
        echo '<input type="hidden" name="action" value="cts_delete_all_translations">';
        wp_nonce_field( 'cts_delete_all_translations', 'cts_delete_all_translations_nonce' );
        echo '<input type="submit" class="button button-link-delete" style="color: #d63638; text-decoration: none;" value="' . esc_attr__( 'Delete All Translations', 'cts' ) . '">';
        echo '</form><br>';
    }

}