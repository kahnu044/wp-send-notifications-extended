<div class="notice notice-error  is-dismissible" style="margin:10px 0 10px 0;display:none" id="wpsne_response">
    <p></p>
</div>

<div id="dashboard-widgets-wrap">
    <div id="dashboard-widgets" class="metabox-holder">
        <div id="postbox-container-1" class="postbox-container">
            <div class="meta-box-sortables ui-sortable">
                <div id="dashboard_quick_press" class="postbox " style="">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><span class="hide-if-no-js"><?php _e('Send Notification', 'wp-send-notifications-extended'); ?>
                            </span>
                        </h2>
                    </div>
                    <div class="inside">
                        <form name="post" action="" method="post" id="admin-send-notifications">
                            <div class="input-text-wrap pnr-form-box" id="title-wrap">
                                <label for="title"><?php _e('Notification Heading', 'wp-send-notifications-extended'); ?></label>
                                <input type="text" name="wpsne_ntfc_heading" id="wpsne_ntfc_heading">
                            </div>
                            <div class="input-text-wrap pnr-form-box" id="title-wrap">
                                <label for="title"> <?php _e('Notification Content(Message)', 'wp-send-notifications-extended'); ?></label>
                                <input type="text" name="wpsne_ntfc_content" id="wpsne_ntfc_content">
                            </div>
                            <div>
                                <label for="pnr-app-id"><b><?php _e('Sent To', 'wp-send-notifications-extended'); ?> : </b>
                                    <input class="" type="radio" checked name="sent_to" id="sent_to_all" value="all" onclick="hidePnrField('#show-unique-id-box')"> <?php _e('All', 'wp-send-notifications-extended'); ?>
                                    <input class="" type="radio" name="sent_to" id="sent_to_unique" value="unique" onclick="showPnrField('#show-unique-id-box')">
                                    <?php _e('Unique User Id', 'wp-send-notifications-extended'); ?>
                                </label>
                            </div>
                            <div class="input-text-wrap" id="show-unique-id-box" style="display:none">
                                <input type="text" id="wpsne_unique_id" name="wpsne_unique_id" placeholder="<?php _e('Unique user Id', 'wp-send-notifications-extended'); ?>" required="">
                            </div>
                            <div>
                                <label for="pnr-app-id" class="form-label"><b><?php _e('Add Button Link ', 'wp-send-notifications-extended'); ?>: </b>
                                    <input class="" type="radio" name="wpsne_add_link" id="wpsne_add_link_no" onclick="hidePnrField('#show-button-form')" value="no" checked>
                                    <?php _e('No ', 'wp-send-notifications-extended'); ?>
                                    <input class="" type="radio" name="wpsne_add_link" id="wpsne_add_link_yes" onclick="showPnrField('#show-button-form')" value="yes"> <?php _e('Yes', 'wp-send-notifications-extended'); ?>
                                </label>
                            </div>
                            <div id="show-button-form" style="display:none">
                                <div class="input-text-wrap">
                                    <input type="text" id="wpsne_ntfc_btn_text" name="wpsne_ntfc_btn_text" placeholder="<?php _e('Button Text eg. Visit Site', 'wp-send-notifications-extended'); ?>" required="">
                                </div>
                                <div class="input-text-wrap" style="margin-top:7px">
                                    <input type="text" id="wpsne_ntfc_btn_link" name="wpsne_ntfc_btn_link" placeholder="<?php _e('Button Link eg. https://yoursitename.com', 'wp-send-notifications-extended'); ?>" required="">
                                </div>
                            </div>
                            <p class="submit pnr-submit">
                                <input type="button" name="wpsne_send_notification" id="wpsne_send_notification" class="button button-primary" value="<?php _e('Send', 'wp-send-notifications-extended'); ?>"> <br class="clear">
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>