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

class Translation_Importer_Exporter {

 
    public function handle_export() {
        if (!current_user_can('manage_options')) {

            wp_die( esc_html__( 'Access denied.', 'cts' ) );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'cts_custom_strings';
        $rows = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=cts_translations_export.csv');


        $output = fopen('php://output', 'w');
          echo "\xEF\xBB\xBF";

                fputcsv($output, ['string_identifier', 'string_value', 'text_domain'], ';', '"');

                foreach ($rows as $row) {
                    fputcsv($output, [
                        $row['string_identifier'],
                        $row['string_value'],
                        $row['text_domain']
                    ], ';', '"');
                }

            fclose($output);
                    exit;
    }

        
        public function handle_import(): void {

            if (
                !current_user_can('manage_options') ||
                !isset($_FILES['csv_file']) ||
                !wp_verify_nonce($_POST['import_cts_nonce'], 'import_cts_translations')
            ) {
                wp_die( esc_html__( 'Access denied or invalid request.', 'cts' ) );
            }

            $file = $_FILES['csv_file']['tmp_name'];

            if ( !file_exists($file) ) {
                wp_die( esc_html__( 'File not found.', 'cts' ) );
            }


            global $wpdb;
            $table = $wpdb->prefix . 'cts_custom_strings';

            $imported = 0;
            $updated  = 0;
            $skipped  = 0;

            $handle = fopen($file, 'r');
            if ($handle) {
                fgetcsv($handle, 0, ';', '"');

                while (($data = fgetcsv($handle, 0, ';', '"')) !== false) {
                    if (count($data) !== 3) {
                        $skipped++;
                        continue;
                    }

                    list($identifier, $value, $domain) = array_map(
                        fn($item) => sanitize_text_field(trim($item)),
                        $data
                    );

                    if (empty($identifier) || empty($value)) {
                        $skipped++;
                        continue;
                    }

                    $exists = $wpdb->get_var($wpdb->prepare(
                        "SELECT id FROM $table WHERE string_identifier = %s AND text_domain = %s",
                        $identifier,
                        $domain
                    ));

                    if ($exists) {
                        $wpdb->update($table, ['string_value' => $value], ['id' => $exists]);
                        $updated++;
                    } else {
                        $wpdb->insert($table, [
                            'string_identifier' => $identifier,
                            'string_value'      => $value,
                            'text_domain'       => $domain
                        ]);
                        $imported++;
                    }
                }

                fclose($handle);
            }

            wp_redirect(admin_url("admin.php?page=registered-strings&imported=$imported&updated=$updated&skipped=$skipped"));
            exit;
        }




            public function handle_export_sql() {
                if (!current_user_can('manage_options')) {

               wp_die( esc_html__( 'Access denied.', 'cts' ) );
                }

                global $wpdb;
                $table = $wpdb->prefix . 'cts_custom_strings';
                $results = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);

                header('Content-Type: application/sql; charset=utf-8');
                header('Content-Disposition: attachment; filename=cts_custom_strings.sql');

                echo "-- SQL dump for $table\n\n";
                echo "DELETE FROM `$table`;\n";

                foreach ($results as $row) {
                    $escaped_values = array_map(function($val) {
                        return "'" . esc_sql($val) . "'";
                    }, array_values($row));

                    echo "INSERT INTO `$table` (`id`, `string_identifier`, `string_value`, `text_domain`) VALUES (" . implode(", ", $escaped_values) . ");\n";
                }

                exit;
            }



        public function handle_sql_import(): void {

            if (
                    !current_user_can('manage_options') ||
                    !isset($_FILES['sql_file']) ||
                    !wp_verify_nonce($_POST['import_sql_nonce'], 'import_sql_nonce')
                ) {
                    wp_die( esc_html__( 'Access denied or invalid request.', 'cts' ) );
                }

                $file = $_FILES['sql_file']['tmp_name'];
                if ( !file_exists($file) ) {
                    wp_die( esc_html__( 'File not found.', 'cts' ) );
                }

                $sql = file_get_contents($file);
                if ( !$sql ) {
                    wp_die( esc_html__( 'Could not read the file.', 'cts' ) );
                }

            global $wpdb;
            $table = $wpdb->prefix . 'cts_custom_strings';

            $queries = preg_split('/;\s*[\r\n]+/', $sql);

            $inserted = 0;
            $updated = 0;
            $skipped = 0;

            foreach ($queries as $query) {
                $query = trim($query);
                if (empty($query)) continue;

                if (stripos($query, 'DELETE FROM') === 0) {
                    $wpdb->query($query);
                    continue;
                }

                if (preg_match("/INSERT INTO.*VALUES\s*\(\s*'(.+?)'\s*,\s*'(.+?)'\s*,\s*'(.+?)'\s*,\s*'(.+?)'\s*\)/is", $query, $matches)) {
                    $id         = stripslashes($matches[1]);
                    $identifier = stripslashes($matches[2]);
                    $value      = stripslashes($matches[3]);
                    $domain     = stripslashes($matches[4]);

                    $exists = $wpdb->get_var($wpdb->prepare(
                        "SELECT id FROM $table WHERE string_identifier = %s AND text_domain = %s",
                        $identifier,
                        $domain
                    ));

                    if ($exists) {
                        $wpdb->update($table, ['string_value' => $value], ['id' => $exists]);
                        $updated++;
                    } else {
                        $wpdb->insert($table, [
                            'id'                => $id,
                            'string_identifier' => $identifier,
                            'string_value'      => $value,
                            'text_domain'       => $domain
                        ]);
                        $inserted++;
                    }
                } else {
                    $skipped++;
                }
            }

            wp_redirect(admin_url("admin.php?page=registered-strings&imported=1&added=$inserted&updated=$updated&skipped=$skipped"));
            exit;
        }



            public function render_sql_import_form() {
                ?>
                <div class="wrap">
                    <h1><?php esc_html_e( 'Import SQL File', 'cts' ); ?></h1>

                    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <input type="hidden" name="action" value="import_sql_file">
                        <?php wp_nonce_field( 'import_sql_nonce', 'import_sql_nonce' ); ?>

                        <input type="file" name="sql_file" accept=".sql" required>
                        <br><br>
                        <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Import SQL', 'cts' ); ?>">
                    </form>
                </div>
                <?php
            }

}