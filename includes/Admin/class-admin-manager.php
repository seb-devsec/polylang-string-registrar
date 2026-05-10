<?php
namespace CTS\Admin;
/**
 * Displays admin notices
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Custom Translation Strings
 * @subpackage Custom Translation Strings\Admin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Manager {

    protected $strings_page;
    protected $form_page;

    public function __construct( Render_Registered_Strings $strings_page, Render_Translation_Page $form_page ) {
        $this->strings_page = $strings_page;
        $this->form_page    = $form_page;
    }


        public function register_menu() {
            add_menu_page(
                esc_html__( 'Register Translations', 'cts' ),
                esc_html__( 'Register Translations', 'cts' ),
                'manage_options',
                'register-translation',
                [ $this->form_page, 'render_translation_page' ],
                'dashicons-translation',
                180
            );

            add_submenu_page(
                'register-translation',
                esc_html__( 'Add New Translation', 'cts' ),
                esc_html__( 'Add New', 'cts' ),
                'manage_options',
                'register-translation',
                [ $this->form_page, 'render_translation_page' ]
            );

            add_submenu_page(
                'register-translation',
                esc_html__( 'Settings', 'cts' ),
                esc_html__( 'Settings', 'cts' ),
                'manage_options',
                'cts-settings',
                [ $this, 'render_cts_settings' ]
            );

            add_submenu_page(
                'register-translation',
                esc_html__( 'Registered Translations', 'cts' ),
                esc_html__( 'Registered', 'cts' ),
                'manage_options',
                'registered-strings',
                [ $this->strings_page, 'render_registered_strings' ]
            );

            add_submenu_page(
                null,
                esc_html__( 'Edit Translation', 'cts' ),
                '',
                'manage_options',
                'edit-translation',
                [ $this->form_page, 'render_edit_translation_page' ]
            );
        }



    public function register_settings() {
        register_setting('cts_settings_group', 'cts_delete_on_uninstall');
        register_setting('cts_settings_group', 'cts_per_page');
    }


    public function render_cts_settings() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Settings', 'cts' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'cts_settings_group' );
            do_settings_sections( 'cts_settings_group' );
            $checked = get_option( 'cts_delete_on_uninstall' ) ? 'checked' : '';
            $per_page = (int) get_option( 'cts_per_page', 10 );
            ?>
            <label>
                <input type="checkbox" name="cts_delete_on_uninstall" value="1" <?php echo $checked; ?>>
                <?php esc_html_e( 'Delete plugin settings upon uninstallation', 'cts' ); ?>
            </label><br><br>

            <label for="cts_per_page"><?php esc_html_e( 'Translations per page:', 'cts' ); ?></label>
            <small> <?php echo '(' . esc_html__( '10 if empty', 'cts' ) . ')'; ?> </small>
            <input type="number" name="cts_per_page" id="cts_per_page" value="<?php echo esc_attr( $per_page ); ?>" min="1" max="100" style="width: 80px;"><br><br>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

    
      public function enqueue_admin_scripts() {

        wp_enqueue_script(
        'cts-admin-script',
        plugins_url( 'assets/js/cts-admin.js', dirname( __FILE__, 2 ) ),
        [],
        null,
        true
    );

    wp_enqueue_style(
        'cts-admin-style',
        plugins_url( 'assets/css/admin.css', dirname( __FILE__, 2 ) ),
        [],
        null
    );
      }


      public function cts_delete_all_translations() {
            if (
                !current_user_can('manage_options') ||
                !isset($_POST['cts_delete_all_translations_nonce']) ||
                !wp_verify_nonce($_POST['cts_delete_all_translations_nonce'], 'cts_delete_all_translations')
            ) {
                wp_die( esc_html__( 'Access denied.', 'cts' ) );
            }

            global $wpdb;
            $table = $wpdb->prefix . 'cts_custom_strings';
            $wpdb->query("DELETE FROM $table");

            wp_redirect(admin_url('admin.php?page=registered-strings&deleted_all=1'));
            exit;
        }



           public function cts_delete_single_translation() {

                if (!isset($_GET['id']) || !current_user_can('manage_options')) {
                   wp_die( esc_html__( 'Access denied.', 'cts' ) );
                }

                $id = intval($_GET['id']);
                check_admin_referer('delete_translation_' . $id);

                global $wpdb;
                $table = $wpdb->prefix . 'cts_custom_strings';
                $wpdb->delete($table, ['id' => $id]);

                wp_redirect(admin_url('admin.php?page=registered-strings&deleted=1'));
                exit;
            }



}
