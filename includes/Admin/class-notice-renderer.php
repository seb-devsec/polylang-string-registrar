<?php
namespace CTS\Admin; 
/**
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

    class Notice_Renderer {

    public function render_notices(): void {
        if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
            $this->render_success(__( 'Translation has been deleted.', 'cts' ));
        }

        if (isset($_GET['deleted_all'])) {
            $this->render_success(__( 'All translations have been deleted.', 'cts' ));
        }

        if (isset($_GET['imported']) || isset($_GET['updated'])) {
            $this->render_success(sprintf(
                __( 'Import completed: %d added, %d updated, %d skipped.', 'cts' ),
                (int) ($_GET['imported'] ?? 0),
                (int) ($_GET['updated'] ?? 0),
                (int) ($_GET['skipped'] ?? 0)
            ));
        }

        if (isset($_GET['sql_imported'])) {
            $this->render_success(sprintf(
                __( 'SQL Import completed: %d queries executed.', 'cts' ),
                (int) $_GET['sql_imported']
            ));
        }
    }

    private function render_success(string $message): void {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
    }
}