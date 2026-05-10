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

final class Translation_Handler {

//     public function handle_translation_registration(): void {
//     if (
//         !current_user_can('manage_options') ||
//         !isset($_POST['submit']) ||
//         !wp_verify_nonce($_POST['register_translation_nonce'], 'register_translation_nonce')
//     ) {
//         wp_die('Brak dostępu lub nieprawidłowe żądanie.');
//     }

//     global $wpdb;
//     $table = $wpdb->prefix . 'cts_custom_strings';

//     $id         = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
//     $identifier = sanitize_text_field($_POST['string_identifier'] ?? '');
//     $value      = sanitize_text_field($_POST['string_value'] ?? '');
//     $domain     = sanitize_text_field($_POST['text_domain'] ?? '');

//     if (empty($identifier) || empty($value)) {
//         wp_die(__('Wszystkie pola są wymagane.', 'cts'));
//     }

//     if ($id > 0) {

//         $wpdb->update(
//             $table,
//             [
//                 'string_identifier' => $identifier,
//                 'string_value'      => $value,
//                 'text_domain'       => $domain
//             ],
//             ['id' => $id],
//             ['%s', '%s', '%s'],
//             ['%d']
//         );

//         wp_redirect(admin_url('admin.php?page=edit-translation&edit=' . $id . '&success=true'));
//     } else {

//         $wpdb->insert(
//             $table,
//             [
//                 'string_identifier' => $identifier,
//                 'string_value'      => $value,
//                 'text_domain'       => $domain
//             ],
//             ['%s', '%s', '%s']
//         );

//         wp_redirect(admin_url('admin.php?page=register-translation&success=true'));
//     }

//     exit;
// }


        public function handle_translation_registration(): void {
                    if (
                        !current_user_can('manage_options') ||
                        !isset($_POST['submit']) ||
                        !wp_verify_nonce($_POST['register_translation_nonce'], 'register_translation_nonce')
                    ) {
                        
                        //wp_die( esc_html__( 'Brak dostępu lub nieprawidłowe żądanie.', 'cts' ) );
                      
                        wp_die( esc_html__( 'Access denied or invalid request.', 'cts' ) );

                    }

                    global $wpdb;
                    $table = $wpdb->prefix . 'cts_custom_strings';

                    $id         = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
                    $identifier = sanitize_text_field($_POST['string_identifier'] ?? '');
                    $value      = sanitize_text_field($_POST['string_value'] ?? '');
                    $domain     = sanitize_text_field($_POST['text_domain'] ?? 'default'); 

                    if (empty($identifier) || empty($value)) {
                        wp_redirect(admin_url('admin.php?page=register-translation&error=empty_fields'));
                        exit;
                    }

                    if ($id > 0) {
                   
                        $updated = $wpdb->update(
                            $table,
                            [
                                'string_identifier' => $identifier,
                                'string_value'      => $value,
                                'text_domain'       => $domain
                            ],
                            ['id' => $id],
                            ['%s', '%s', '%s'],
                            ['%d']
                        );

                        wp_redirect(admin_url('admin.php?page=edit-translation&edit=' . $id . '&success=true'));
                    } else {

                        $check_duplicate = $wpdb->get_var($wpdb->prepare(
                            "SELECT COUNT(*) FROM $table WHERE string_identifier = %s AND text_domain = %s",
                            $identifier,
                            $domain
                        ));

                        if ($check_duplicate > 0) {
         
                            wp_redirect(admin_url('admin.php?page=register-translation&error=duplicate'));
                            exit;
                        }

                        $inserted = $wpdb->insert(
                            $table,
                            [
                                'string_identifier' => $identifier,
                                'string_value'      => $value,
                                'text_domain'       => $domain
                            ],
                            ['%s', '%s', '%s']
                        );

                        if ($inserted === false) {
                            wp_redirect(admin_url('admin.php?page=register-translation&error=db_error'));
                        } else {
                            wp_redirect(admin_url('admin.php?page=register-translation&success=true'));
                        }
                    }

                    exit;
                }





    //deprecated
       public function handle_single_translation_registration(): void {
        if (
            isset($_POST['submit']) &&
            wp_verify_nonce($_POST['register_translation_nonce'], 'register_translation_nonce')
        ) {
            global $wpdb;

            $name = sanitize_text_field($_POST['string_identifier']);
            $string = sanitize_text_field($_POST['string_value']);
            $group = sanitize_text_field($_POST['text_domain']);

            if (!empty($name) && !empty($string)) {
                $table_name = $wpdb->prefix . 'cts_custom_strings';

                $wpdb->insert($table_name, [
                    'string_identifier' => $name,
                    'string_value'      => $string,
                    'text_domain'       => $group
                ]);
            }
        }

        wp_redirect(admin_url('admin.php?page=register-translation&success=true'));
        exit;
    }

    

}

