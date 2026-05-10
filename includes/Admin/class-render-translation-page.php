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


class Render_Translation_Page {


    public function render_translation_page(): void {
        $success = isset($_GET['success']) && $_GET['success'] === 'true';
        $error   = isset($_GET['error']) ? sanitize_text_field($_GET['error']) : '';

        if ($error === 'duplicate') {
            echo '<div class="notice notice-error"><p>' . esc_html__( 'This identifier already exists in this group!', 'cts' ) . '</p></div>';
        } elseif ($error === 'db_error') {
            echo '<div class="notice notice-error"><p>' . esc_html__( 'Database error. Please check the table structure.', 'cts' ) . '</p></div>';
        } elseif ($error === 'empty_fields') {
            echo '<div class="notice notice-error"><p>' . esc_html__( 'The Identifier and Value fields are required.', 'cts' ) . '</p></div>';
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Add New Translation', 'cts' ); ?></h1>

            <?php if ($success): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e( 'Translation has been registered.', 'cts' ); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="register_translation">

                <label for="string_value"><strong><?php _e( 'Text to translate', 'cts' ); ?></strong></label><br>
                <input type="text" id="string_value" name="string_value" required class="regular-text"><br><br>

                <label for="string_identifier"><strong><?php _e( 'Identifier (translation key)', 'cts' ); ?></strong></label><br>
                <input type="text" id="string_identifier" name="string_identifier" required class="regular-text">
                <p class="description"><?php _e( 'This is the text identifier, e.g., <code>welcome_message</code>.', 'cts' ); ?></p><br>

                <label for="text_domain"><strong><?php _e( 'Group (text domain)', 'cts' ); ?></strong></label><br>
                <input type="text" id="text_domain" name="text_domain" class="regular-text"><br><br>

                <?php wp_nonce_field( 'register_translation_nonce', 'register_translation_nonce' ); ?>

                <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e( 'Register Translation', 'cts' ); ?>">
            </form>
        </div>
        <?php
    }

    public function render_edit_translation_page(): void {
        $success = isset($_GET['success']) && $_GET['success'] === 'true';
        $edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

        if ( ! $edit_id ) {
            wp_die( esc_html__( 'Invalid translation identifier.', 'cts' ) );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'cts_custom_strings';
        $row   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $edit_id ), ARRAY_A );

        if ( ! $row ) {
            wp_die( esc_html__( 'Translation not found.', 'cts' ) );
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Edit Translation', 'cts' ); ?></h1>

            <?php if ($success): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e( 'Translation has been updated.', 'cts' ); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="register_translation">
                <input type="hidden" name="edit_id" value="<?php echo esc_attr( $edit_id ); ?>">

                <label for="string_value"><strong><?php _e( 'Text to translate', 'cts' ); ?></strong></label><br>
                <input type="text" id="string_value" name="string_value" value="<?php echo esc_attr( $row['string_value'] ); ?>" required class="regular-text"><br><br>

                <label for="string_identifier"><strong><?php _e( 'Identifier (translation key)', 'cts' ); ?></strong></label><br>
                <input type="text" id="string_identifier" name="string_identifier" value="<?php echo esc_attr( $row['string_identifier'] ); ?>" class="regular-text" readonly><br><br>

                <label for="text_domain"><strong><?php _e( 'Group (text domain)', 'cts' ); ?></strong></label><br>
                <input type="text" id="text_domain" name="text_domain" value="<?php echo esc_attr( $row['text_domain'] ); ?>" class="regular-text" readonly><br><br>

                <?php wp_nonce_field( 'register_translation_nonce', 'register_translation_nonce' ); ?>

                <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'cts' ); ?>">
            </form>
        </div>
        <?php
    }
}
