<?php

/**
 * Plugin Name: WP Send Notifications Extended
 * Version: 1.0
 * Requires at least: 4.9
 * Requires PHP: 5.6
 * Plugin URI: https://developernoob.in/
 * Description: Send notifications on post/page publish,update etc
 * Author: Mr. Kanhu
 * Author URI: https://developernoob.in/
 * Text Domain: wp-send-notifications-extended
 * Domain Path: /languages
 */

//denied direct access of the file
defined('ABSPATH') || die("Permission Denied");

//define plugin constants
define('WPSNE_URL', plugin_dir_url(__FILE__));
define('WPSNE_PATH', plugin_dir_path(__FILE__));
define('WPSNE_ASSETS_URL', plugin_dir_url(__FILE__) . 'assets');
define('WPSNE_INCLUDE_PATH', plugin_dir_path(__FILE__) . 'includes');
define('WPSNE_ADMIN_URL', admin_url('admin.php?page=wp-send-notifications-extended'));

//load textdomain
add_action('plugins_loaded', 'wpsne_text_domain');
function wpsne_text_domain()
{
    load_plugin_textdomain('wp-send-notifications-extended', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}


function wpsne_get_active_menu_url()
{
    if (isset($_GET['tab'])) {
        return $_GET['tab'];
    }
}


register_activation_hook(__FILE__, 'wpsne_on_activation');

register_uninstall_hook(__FILE__, 'wpsne_on_uninstall');


// added to the for js and css files
add_action('admin_init', 'wpsne_css_js');
function wpsne_css_js()
{
    if (isset($_GET['page']) && $_GET['page'] == 'wp-send-notifications-extended') {

        wp_enqueue_script("wpsne-custom-js", WPSNE_ASSETS_URL . "/js/wpsne-js.js", array('jquery'), '1.0', false);

        wp_enqueue_style("wpsne-custom-css", WPSNE_ASSETS_URL . '/css/wpsne-css.css');

        // add ajax url
        wp_localize_script('wpsne-custom-js', 'pnr', array('ajaxurl' => admin_url('admin-ajax.php')));

        // for media uploader
        wp_enqueue_media();
    }
}


add_action('wp_ajax_wpsne_send_manual_send_notification', 'wpsne_send_manual_send_notification');
function wpsne_send_manual_send_notification()
{
    include_once(WPSNE_PATH . 'api/wpsne-one-signal-api.php');

    // for admin send manual notification by the help of ajax
    if (isset($_POST['action']) && $_POST['action'] == 'wpsne_send_manual_send_notification') {

        $msg = array(
            'success' => false,
            'message' => '',
        );
        if (empty($_POST['wpsne_ntfc_heading'])) {
            $msg['message'] = " Heading should not be empty";
        } elseif (empty($_POST['wpsne_ntfc_content'])) {
            $msg['message'] = " Content should not be empty";
        } else {
            $heading = sanitize_text_field(stripslashes($_POST['wpsne_ntfc_heading']));
            $content = sanitize_text_field(stripslashes($_POST['wpsne_ntfc_content']));

            if ($_POST['sent_to'] == 'all') {
                if ($_POST['wpsne_add_link'] == 'yes') {
                    $addLink = 'yes';
                    if (empty($_POST['wpsne_ntfc_btn_text'])) {
                        $msg['message'] = 'Button text not be empty';
                    } elseif (empty($_POST['wpsne_ntfc_btn_link'])) {
                        $msg['message'] = 'Button link not be empty';
                    } else {
                        $btnText = sanitize_text_field(stripslashes($_POST['wpsne_ntfc_btn_text']));
                        $btnLink = sanitize_text_field(stripslashes($_POST['wpsne_ntfc_btn_link']));

                        //api call with button link
                        $msg['message']  = json_encode(wpsne_send_message($heading, $content, 'all', true, $btnText, $btnLink, ''));
                    }
                } else {
                    //api call without button link
                    $msg['message']  = wpsne_send_message($heading, $content, 'all', false, '', '', '');

                    // print_r(json_decode($mhhh));
                }
            } else {
                if (empty($_POST['wpsne_unique_id'])) {
                    $msg['message'] =  " unique not be empty";
                } else {
                    $userUniqueId = sanitize_text_field(stripslashes($_POST['wpsne_unique_id']));
                    if ($_POST['wpsne_add_link'] == 'yes') {
                        $addLink = 'yes';
                        if (empty($_POST['wpsne_ntfc_btn_text'])) {
                            $msg['message'] = 'Button text not be empty';
                        } elseif (empty($_POST['wpsne_ntfc_btn_link'])) {
                            $msg['message'] =  'Button link not be empty';
                        } else {
                            $btnText = sanitize_text_field(stripslashes($_POST['wpsne_ntfc_btn_text']));
                            $btnLink = sanitize_text_field(stripslashes($_POST['wpsne_ntfc_btn_link']));
                            // send all with button link
                            $msg['message'] = json_encode(wpsne_send_message($heading, $content, 'unique', true, $btnText, $btnLink, $userUniqueId));
                        }
                    } else {
                        // send to all without button link
                        $msg['message'] = json_encode(wpsne_send_message($heading, $content, 'unique', false, '', '', $userUniqueId));
                    }
                }
            }
        }

        // print_r($msg['message']);

        echo json_encode($msg);
        // echo $msg;
    }

    wp_die();
}

//save default settings for on plugin activate
function wpsne_on_activation()
{
    add_option('wpsne_auto_post_publish', 'on');
    add_option('wpsne_auto_post_update', 'on');
    add_option('wpsne_auto_page_publish', 'on');
    add_option('wpsne_auto_page_update', 'on');

    add_option('wpsne_api_key', '');
    add_option('wpsne_app_id', '');

    add_option('wpsne_post_type', array('post', 'page'));
    add_option('wpsne_post_visibility', array('public', 'private'));
    add_option('wpsne_default_image', 'no');
    add_option('wpsne_custom_image', '');
}

function wpsne_on_uninstall()
{
    delete_option('wpsne_auto_post_publish');
    delete_option('wpsne_auto_post_update');
    delete_option('wpsne_auto_page_publish');
    delete_option('wpsne_auto_page_update');
    delete_option('wpsne_post_type');
    delete_option('wpsne_post_visibility');
    delete_option('wpsne_default_image');
    delete_option('wpsne_custom_image');
}


//for one signal verification
add_action('wp_head', 'onesignal_js_header');
function onesignal_js_header()
{

    if (!empty(get_option('wpsne_api_key'))  || !empty(get_option('wpsne_app_id'))) {

?>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
        <script>
            window.OneSignal = window.OneSignal || [];
            OneSignal.push(function() {
                OneSignal.init({
                    appId: "<?php echo get_option('wpsne_app_id'); ?>",
                });
            });

            OneSignal.push(function() {
                OneSignal.SERVICE_WORKER_PARAM = {
                    scope: '/wp-content/plugins/wp-send-notifications-extended/sdk_files/'
                };
                OneSignal.SERVICE_WORKER_PATH =
                    'wp-content/plugins/wp-send-notifications-extended/sdk_files/OneSignalSDKWorker.js'
                OneSignal.SERVICE_WORKER_UPDATER_PATH =
                    'wp-content/plugins/wp-send-notifications-extended/sdk_files/OneSignalSDKUpdaterWorker.js'
                OneSignal.setDefaultNotificationUrl("<?php echo site_url(); ?>");
                if (OneSignal.isPushNotificationsSupported()) {
                    console.log('Push Notification Supported');
                    OneSignal.getUserId(function(userId) {
                        console.log("User ID:", userId);
                    });
                    OneSignal.push(function() {
                        OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                            if (isEnabled) {
                                console.log("Push notifications are enabled!");
                            } else {
                                console.log("Push notifications are not enabled yet.");
                                OneSignal.push(function() {
                                    OneSignal.showSlidedownPrompt();
                                });
                            }
                        });
                    });
                } else {
                    console.log('Not Supported');
                }
            });
        </script>
    <?php
    }
}


//add settings link to plugin installation page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_wpsne_action_link');
function add_wpsne_action_link($actions)
{
    $mylinks = array(
        '<a href="' . admin_url('admin.php?page=wp-send-notifications-extended') . '">Settings</a>',
    );
    $actions = array_merge($mylinks, $actions);
    return $actions;
}


//add menu
add_action('admin_menu', 'wpsne_menu');
function wpsne_menu()
{
    add_menu_page(
        __('Push Notifications Reloaded', 'wp-send-notifications-extended'),
        __('Push Notifications ', 'wp-send-notifications-extended'),
        'manage_options',
        'wp-send-notifications-extended',
        'wpsne_menu_view',
        'dashicons-bell',
        26
    );
}

function wpsne_menu_view()
{

    include_once(WPSNE_PATH . 'api/wpsne-one-signal-api.php');

    if (wpsne_get_active_menu_url() == 'setting') {
        include_once(WPSNE_INCLUDE_PATH . '/wpsne-settings.php');
    } else {
        include_once(WPSNE_INCLUDE_PATH . '/wpsne-dashboard.php');
    }

    if (wpsne_get_active_menu_url() == 'help') {
        include_once(WPSNE_INCLUDE_PATH . '/wpsne-help.php');
    }

    if ($setUpdone) {

        if (wpsne_get_active_menu_url() == '') {
            include_once(WPSNE_INCLUDE_PATH . '/wpsne-subscribers.php');
        }

        if (wpsne_get_active_menu_url() == 'notifications') {
            include_once(WPSNE_INCLUDE_PATH . '/wpsne-notifications.php');
        }

        if (wpsne_get_active_menu_url() == 'send-notifications') {
            include_once(WPSNE_INCLUDE_PATH . '/wpsne-send-notifications.php');
        }
    }
    echo '</div>
    </div>
</div>';
}


//adding meta box to each post type for auto notifications
add_action('admin_init', 'wpsne_add_custom_fields');
function wpsne_add_custom_fields()
{
    $screens = get_post_types();
    foreach ($screens as $screen) {
        add_meta_box(
            'wpsne-auto-push-notifications',
            __('Push Notifications Reloaded', 'wp-send-notifications-extended'),
            'wpsne_auto_push_notifications',
            $screen,
            'side',
            'high',
        );
    }
}


function wpsne_auto_push_notifications($post)
{
    $notification_status  = get_post_meta($post->ID, 'wpsne-auto-push-notifications', true);
    if ($post->post_status === 'publish') {
    ?>
        <input type="checkbox" name="auto_push_notifications" id="auto_push_notifications" <?php if ($notification_status == 'on') {
                                                                                                echo 'checked';
                                                                                            } ?>>
    <?php _e(esc_attr('Auto Send Notification On ' . ucfirst($post->post_type) . ' Update'), 'wp-send-notifications-extended');
    } else {
    ?>
        <input type="checkbox" name="auto_push_notifications" id="auto_push_notifications" checked>
        <?php _e(esc_attr('Auto Send Notification On ' . ucfirst($post->post_type) . ' Publish'), 'wp-send-notifications-extended'); ?>
<?php
    }
}


// save the custom meta data
add_action('save_post', 'save_wpsne_metabox_data');
function save_wpsne_metabox_data($post_id)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $auto_notification = sanitize_text_field($_POST['auto_push_notifications']);
        update_post_meta($post_id, 'wpsne-auto-push-notifications', $auto_notification);
    }
}

//send auto push notifications on post/page published / updated
add_action('save_post', 'wpsne_auto_send_notifications');
function wpsne_auto_send_notifications($post_id)
{

    include_once(WPSNE_PATH . 'api/wpsne-one-signal-api.php');
    global $post;
    $post_title = get_the_title($post_id);
    $post_url = get_permalink($post_id);

    $postType = '';
    if (!empty($post->post_type)) {
        $postType = $post->post_type;
    }

    $savedPostType = get_option('wpsne_post_type');

    if (is_array($savedPostType)) {
        if (in_array($postType, $savedPostType)) {

            // 2nd check post meta key enable or disable
            $check_enable_disable  = get_post_meta($post_id, 'wpsne-auto-push-notifications', true);

            if ($check_enable_disable == 'on') {

                //get post status
                $savePostVisibility = get_option('wpsne_post_visibility');

                //check post visibility``
                if (get_post_status($post_id) == 'publish') {
                    if (post_password_required($post_id)) {
                        $postVisibility =  'password protected';
                    } else {
                        $postVisibility = 'public';
                    }
                } else {
                    $postVisibility = 'private';
                }

                if (is_array($savePostVisibility)) {
                    if (in_array($postVisibility, $savePostVisibility)) {
                        $defaultImg = false;
                        // check post will be published or update
                        if (get_option('wpsne_default_image') != 'no') {
                            if (get_option('wpsne_default_image') == 'custom') {
                                $defaultImg = get_option('wpsne_custom_image');
                            } else {
                                $defaultImg = wp_get_attachment_url(get_post_thumbnail_id($post_id), 'thumbnail');
                            }
                        }

                        //check post type is page or post , otherwise send direct notification
                        if (in_array($postType, array('post', 'page'))) {
                            if ($post->post_date !=  $post->post_modified) {

                                //now check update post type on / off
                                $status =  get_option('wpsne_auto_' . $postType . '_update');
                                if ($status == 'on') {
                                    $msg =  'A ' . ucfirst($postType) . ' Updated';
                                    wpsne_auto_send_notifications($msg, $post_title, 'View ' . $postType, $post_url, $defaultImg);
                                }
                            } else {
                                $status =  get_option('wpsne_auto_' . $postType . '_publish');
                                if ($status == 'on') {
                                    //now check update post type on / off
                                    $msg =  'A New ' . ucfirst($postType) . ' Published';
                                    wpsne_auto_send_notifications($msg, $post_title, 'View ' . $postType, $post_url, $defaultImg);
                                }
                            }
                        } else {
                            //direct send notification
                            $msg =  'A New ' . ucfirst($postType) . ' Published';
                            wpsne_auto_send_notifications($msg, $post_title, 'View ' . $postType, $post_url, $defaultImg);
                        }
                    }
                }
            }
        }
    }
}
?>