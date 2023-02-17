<?php

//save/update api and app id to table
if (isset($_POST['wpsne-save-api'])) {

    if (  ! wp_verify_nonce( $_POST['wpsne_app_and_api_id'], 'save_wpsne_app_and_api_id' ) ) {
        wp_die( 'Security Verification Failed' );
    }

    $apiKey = sanitize_text_field(stripslashes($_POST['wpsne_api_key']));
    $appId = sanitize_text_field(stripslashes($_POST['wpsne_app_id']));

    if ( empty($apiKey) ||  empty($appId)){
        $error = 'Both filed must be filled';
    }elseif(strlen($apiKey) != 48 ){
        $error = ' API Key length not exceed 48 character';
    }elseif(strlen($appId) != 36){
        $error = ' APP ID length length not exceed 36 character';
    }else{

        //  ready to save/update in db
        update_option('wpsne_api_key',$apiKey);
        update_option('wpsne_app_id',$appId );
    }
}

//update auto notification settings
$postPublish ='';
$postUpdate ='';
$pagePublish ='';
$pageUpdate ='';

if (isset($_POST['update_auto_notifications'])) {

    if (  ! wp_verify_nonce( $_POST['wpsne_auto_notification'], 'wpsne_update_auto_notification' ) ) {
        wp_die( 'Security Verification Failed' );
    }

    if(isset($_POST['auto-post-publish'])){
        $postPublish = 'on';
    }

    if(isset($_POST['auto-post-update'])){
        $postUpdate = 'on';
    }

    if(isset($_POST['auto-page-publish'])){
        $pagePublish = 'on';
    }

    if(isset($_POST['auto-page-update'])){
        $pageUpdate = 'on';
    }

    update_option('wpsne_auto_post_publish',$postPublish);
    update_option('wpsne_auto_post_update',$postUpdate);
    update_option('wpsne_auto_page_publish',$pagePublish);
    update_option('wpsne_auto_page_update',$pageUpdate);
}

//update post types
if (isset($_POST['update_post_types'])) {
    if (  ! wp_verify_nonce( $_POST['wpsne_all_post_type'], 'wpsne_update_post_type' ) ) {
        wp_die( 'Security Verification Failed' );
    }
    update_option( 'wpsne_post_type',$_POST['post_type'] );
}

//update post visibility
if (isset($_POST['update_wpsne_post_visibility'])) {

    if (  ! wp_verify_nonce( $_POST['wpsne_all_post_visibility'], 'wpsne_update_all_post_visibility' ) ) {
        wp_die( 'Security Verification Failed' );
    }
    update_option( 'wpsne_post_visibility',$_POST['wpsne_post_visibility'] );
}

//save notification image
if (isset($_POST['save_wpsne_img'])) {

    if (  ! wp_verify_nonce( $_POST['notification_default_image'], 'update_notification_default_image' ) ) {
        wp_die( 'Security Verification Failed' );
    }
    if (isset($_POST['wpsne_img_url'])) {
        if (!empty($_POST['wpsne_img_url'])) {
            update_option( 'wpsne_custom_image',$_POST['wpsne_img_url'] );
        }
    }
}

//update notification default image
if (isset($_POST['update_wpsne_custom_image'])) {

    if (  ! wp_verify_nonce( $_POST['wpsne_custome_image'], 'wpsne_update_custome_image' ) ) {
        wp_die( 'Security Verification Failed' );
    }

    if(!empty($_POST['wpsne_notification_image'])){
        update_option( 'wpsne_default_image',sanitize_text_field($_POST['wpsne_notification_image']));
    }
}
//header page
include_once(WPSNE_INCLUDE_PATH.'/wpsne-dashboard.php');
?>

<div id="dashboard-widgets-wrap">
    <div id="dashboard-widgets" class="metabox-holder">
        <div id="postbox-container-1" class="postbox-container">
            <div class="meta-box-sortables ui-sortable">
                <div id="dashboard_quick_press" class="postbox " style="">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><span class="hide-if-no-js">
                        <?php _e('API Setup','wp-send-notifications-extended');?>
                                </span>
                        </h2>
                    </div>
                    <div class="inside">
                        <form name="post" action="<?php echo WPSNE_ADMIN_URL.'&tab=setting'; ?>" method="post" id="api-form" class="initial-form hide-if-no-js">
                            <?php wp_nonce_field( 'save_wpsne_app_and_api_id', 'wpsne_app_and_api_id' ); ?>
                            <div class="input-text-wrap wpsne-form-box" id="title-wrap">
                                <label for="title">
                                <?php _e('OneSignal App ID ','wp-send-notifications-extended');?>
                                <i class="fa fa-question-circle" aria-hidden="true" title="Go to help for more info"></i></label>
                                <input type="text" class="form-control" name="wpsne_app_id" id="wpsne-app-id"
                                    value="<?php echo get_option('wpsne_app_id');?>">
                            </div>
                            <div class="input-text-wrap wpsne-form-box" id="title-wrap">
                                <label for="title">
                                <?php _e('OneSignal Rest API','wp-send-notifications-extended');?>
                                <i class="fa fa-question-circle" aria-hidden="true" title="Go to help for more info"></i></label>
                                <input type="text" class="form-control" name="wpsne_api_key" id="wpsne-api-key"
                                    value="<?php echo get_option('wpsne_api_key');?>">
                            </div>
                            <p class="submit wpsne-submit">
                                <input type="submit" name="wpsne-save-api" class="button button-primary"
                                    value="<?php _e('Update Setting','wp-send-notifications-extended');?>"> <br class="clear">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        <?php
        if( $setUpdone){ ?>
            <div class="meta-box-sortables ui-sortable wpsne-setting-page">
                <div id="dashboard_quick_press" class="postbox " style="">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><span class="hide-if-no-js"><?php _e('Post Types ','wp-send-notifications-extended');?>
                                </span>
                        </h2>
                    </div>
                    <div class="inside">
                        <form name="post" action="" method="post" class="initial-form hide-if-no-js">
                            <?php
                            wp_nonce_field( 'wpsne_update_post_type','wpsne_all_post_type' );

                            $args = array(
                                'public'   => true,
                            );
                            $post_types = get_post_types( $args , 'names' );

                            //remove attachment from the fetched data
                            unset($post_types['attachment']);
                            foreach ( $post_types as $post_type ) { ?>
                            <div class="wpsne-form-box">
                                <input type="checkbox" name='post_type[]' value="<?php echo $post_type; ?>" <?php
                                        if(is_array(get_option( 'wpsne_post_type' ))){
                                            if(in_array($post_type, get_option( 'wpsne_post_type' ))){  echo 'checked';  }
                                        }
                                    ?>>
                                <label for="title">
                                <?php _e(ucfirst($post_type),'wp-send-notifications-extended');?> </label>

                            </div>
                            <?php
                                }
                            ?>
                            <div class="submit wpsne-submit">
                                <input type="submit" name="update_post_types" class="button button-primary"
                                    value="<?php _e('Update Setting','wp-send-notifications-extended');?>"> <br class="clear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="meta-box-sortables ui-sortable wpsne-setting-page">
                <div id="dashboard_quick_press" class="postbox " style="">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><span class="hide-if-no-js"><?php _e('Default Image ','wp-send-notifications-extended');?>
                                </span>
                        </h2>
                    </div>
                    <div class="inside">
                        <form name="post" action="" method="post" id="quick-press" class="initial-form hide-if-no-js">
                            <?php wp_nonce_field( 'wpsne_update_custome_image','wpsne_custome_image'); ?>
                            <div class="wpsne-form-box">
                                <input type="radio" name="wpsne_notification_image" id="wpsne_notification_featured_img"
                                    value="featured"
                                    <?php if(get_option( 'wpsne_default_image') =='featured'){echo 'checked';} ?>>
                                <label for=""><?php _e('Featured Image','wp-send-notifications-extended');?></label>
                            </div>
                            <div class="wpsne-form-box">
                                <input type="radio" name="wpsne_notification_image" id="wpsne_notification_custom_img"
                                    value="custom"
                                    <?php if(get_option( 'wpsne_default_image') =='custom'){echo 'checked';} ?>>
                                <label for=""><?php _e('Custom Image','wp-send-notifications-extended');?></label>
                            </div>
                            <div class="wpsne-form-box">
                                <input type="radio" name="wpsne_notification_image" id="wpsne_notification_no_img"
                                    value="no" <?php if(get_option( 'wpsne_default_image') =='no'){echo 'checked';} ?>>
                                <label for=""><?php _e('No Image','wp-send-notifications-extended');?></label>
                            </div>
                            <div class="submit wpsne-submit">
                                <input type="submit" name="update_wpsne_custom_image" id="update_wpsne_custom_image"
                                    class="button button-primary" value="<?php _e('Update Setting','wp-send-notifications-extended');?> "> <br class="clear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


        <div id="postbox-container-2" class="postbox-container">
            <div class="meta-box-sortables ui-sortable wpsne-setting-page">
                <div id="dashboard_quick_press" class="postbox " style="">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><span class="hide-if-no-js"><?php _e('Settings','wp-send-notifications-extended');?> </span>
                        </h2>
                    </div>
                    <div class="inside">
                        <form name="post" action="" method="post" id="quick-press" class="initial-form hide-if-no-js">
                            <?php wp_nonce_field('wpsne_update_auto_notification','wpsne_auto_notification' ); ?>
                            <div class="wpsne-form-box">
                                <input type="checkbox" name="auto-post-publish" id="auto-post-publish"
                                    <?php if(get_option( 'wpsne_auto_post_publish') == 'on'){ echo 'checked'; } ?>>
                                <label for="title">
                                <?php _e('Auto Send Notifications On Post Publish','wp-send-notifications-extended');?> </label>
                            </div>
                            <div class="wpsne-form-box">
                                <input type="checkbox" name="auto-post-update" id="auto-post-update"
                                    <?php if(get_option( 'wpsne_auto_post_update') == 'on'){ echo 'checked'; } ?>>
                                <label for="title">
                                <?php _e('Auto Send Notifications On Post Update','wp-send-notifications-extended');?> </label>
                            </div>
                            <div class="wpsne-form-box">
                                <input type="checkbox" name="auto-page-publish" id="auto-page-publish"
                                    <?php if(get_option( 'wpsne_auto_page_publish') == 'on'){ echo 'checked'; } ?>>
                                <label for="title">
                                <?php _e('Auto Send Notifications On Page Publish ','wp-send-notifications-extended');?></label>
                            </div>
                            <div class="wpsne-form-box">
                                <input type="checkbox" name="auto-page-update" id="auto-page-update"
                                    <?php if(get_option( 'wpsne_auto_page_update') == 'on'){ echo 'checked'; } ?>>
                                <label for="title">
                                <?php _e('Auto Send Notifications On Page Update','wp-send-notifications-extended');?> </label>
                            </div>
                            <div class="submit wpsne-submit">
                                <input type="submit" name="update_auto_notifications" class="button button-primary"
                                    value="<?php _e('Update Setting','wp-send-notifications-extended');?>"> <br class="clear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- post visibility -->
            <div class="meta-box-sortables ui-sortable wpsne-setting-page">
                <div id="dashboard_quick_press" class="postbox " style="">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><span class="hide-if-no-js">
                            <?php _e('Post Visibility','wp-send-notifications-extended');?>
                                </span>
                        </h2>
                    </div>
                    <div class="inside">
                        <form name="post" action="" method="post" id="quick-press" class="initial-form hide-if-no-js">
                            <?php wp_nonce_field( 'wpsne_update_all_post_visibility','wpsne_all_post_visibility' ); ?>
                            <div class="wpsne-form-box">
                                <input type="checkbox" name="wpsne_post_visibility[]" id="wpsne_post_visibility"
                                    value="private" <?php
                                if(is_array(get_option( 'wpsne_post_visibility'))){
                                    if(in_array('private', get_option( 'wpsne_post_visibility'))){  echo 'checked';  }
                                }
                                ?>>
                                <label for="title">
                                <?php _e('Private','wp-send-notifications-extended');?></label>
                            </div>
                            <div class="wpsne-form-box">
                                <input type="checkbox" name="wpsne_post_visibility[]" id="wpsne_post_visibility"
                                    value="password protected" <?php
                                if(is_array(get_option( 'wpsne_post_visibility'))){
                                    if(in_array('password protected', get_option( 'wpsne_post_visibility'))){  echo 'checked';  }
                                }
                                ?>>
                                <label for="title">
                                <?php _e('Password Protected','wp-send-notifications-extended');?> </label>
                            </div>
                            <input type="hidden" name="wpsne_post_visibility[]" id="wpsne_post_visibility" value="public">
                            <div class="submit wpsne-submit">
                                <input type="submit" name="update_wpsne_post_visibility" class="button button-primary"
                                    value="<?php _e('Update Setting','wp-send-notifications-extended');?> "> <br class="clear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="meta-box-sortables ui-sortable wpsne-setting-page">
                <div id="dashboard_quick_press" class="postbox " style="">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><span class="hide-if-no-js">
                        <?php _e('Custom Image','wp-send-notifications-extended');?> </span>
                        </h2>
                    </div>
                    <div class="inside">
                        <form name="post" action="" method="post" class="initial-form hide-if-no-js">
                            <?php wp_nonce_field( 'update_notification_default_image','notification_default_image'); ?>
                            <div class="wpsne-show-image">
                                <input id="upload_img-btn" type="button" name="upload-btn" class="button-secondary"
                                    value="<?php _e('Upload Image','wp-send-notifications-extended');?> ">
                                <br /> <br />
                                <input id="delete_img-btn" type="button" name="delete-btn" class="button-secondary"
                                    value="<?php _e('Remove Image','wp-send-notifications-extended');?> " style="display: none;">
                            </div>
                            <div class="wpsne-show-image">
                                <div id="logo_container">
                                </div>
                                <div id="wpsne-custom-img-url">
                                </div>
                            </div>
                            <div class="submit wpsne-submit">
                                <input type="submit" name="save_wpsne_img" class="button button-primary"
                                    value="<?php _e('Update Setting','wp-send-notifications-extended');?>"> <br class="clear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        else
        {
        echo '</div>';
        }
        ?>
    </div>
</div>

<?php
$wpsne_custom_saved_image = get_option( 'wpsne_custom_image' );
if($wpsne_custom_saved_image != ''){ ?>
<script>
jQuery(document).ready(function($) {
    $('#logo_container').append(
        '<img class="logo" id="wpsne-custom-saved-image" src="<?php echo $wpsne_custom_saved_image; ?>" height="100px" width="100px" />'
    );
});
</script>
<?php
}
?>