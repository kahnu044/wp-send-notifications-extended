<div id="wpbody " style="padding:0px" role="main">
    <div id="wpbody-content" style="padding-bottom:0px">
        <div class="pnr-menu-view">
            <div class="wrap" style="margin:0">
                <div class="show-pnr-alert">
                    <h4 class="pnr-alert-heading"><?php _e('Welcome to','wp-send-notifications-extended');?>
                        <b><?php _e('Push Notifications Reloaded','wp-send-notifications-extended');?> </b>
                    </h4>
                    <p class="pnr-alert-desc"><?php _e('A simple & easy way to send ','wp-send-notifications-extended');?>
                        <b><?php _e('Push Notifications.','wp-send-notifications-extended');?></b>
                    </p>
                </div>
                <?php
                $setUpdone = false;
                if (empty(get_option('wpsne_api_key')) || empty(get_option('wpsne_app_id')) ) {
                ?>
                <div class="notice notice-error is-dismissible " style="margin:0 0 10px 0">
                    <p> <strong><?php _e('API Not Setup!','wp-send-notifications-extended');?></strong>
                        <?php _e('Setup API','wp-send-notifications-extended');?> <a href="javascript:void(0)"
                            onclick="api_signup_window()"><?php _e('HERE','wp-send-notifications-extended');?></a> ,
                        <?php _e('Please Configure API at','wp-send-notifications-extended');?></a> <a
                            href="admin.php?page=wp-send-notifications-extended&tab=setting">
                            <?php _e('Setting','wp-send-notifications-extended');?></a>
                        <?php _e('Tab','wp-send-notifications-extended');?>.
                    </p>
                </div>
                <?php

                }else{
                    $api_status = wpsne_check_API_APP_Id();
                    if($api_status['error']){
                        $setUpdone = false;
                    ?>
                    <div class="notice notice-error is-dismissible " style="margin:0 0 10px 0">
                        <p><?php _e($api_status['value'],'wp-send-notifications-extended');?></p>
                    </div>

                    <?php
                    }else{
                        $setUpdone = true;
                    }
                }

                $active_menu =  wpsne_get_active_menu_url();

                ?>
                <div class="pnr-tab-menu">
                    <ul>
                        <li><a class="  <?php if ($active_menu == '') { echo 'active';} ?> " aria-current="page"
                                href="<?php echo WPSNE_ADMIN_URL; ?>"><?php _e('Subscribers','wp-send-notifications-extended');?></a>
                        </li>
                        <li>
                            <a class=" <?php if ($active_menu == 'notifications') { echo 'active';} ?> "
                                href="<?php echo WPSNE_ADMIN_URL; ?>&tab=notifications">
                                <?php _e('Notifications','wp-send-notifications-extended');?></a>
                        </li>
                        <li>
                            <a class="  <?php  if ($active_menu == 'send-notifications') { echo 'active';} ?> "
                                href="<?php echo WPSNE_ADMIN_URL; ?>&tab=send-notifications"><?php _e('Send Notifications','wp-send-notifications-extended');?></a>
                        </li>
                        <li>
                            <a class=" <?php if ($active_menu == 'setting') { echo 'active';} ?> "
                                href="<?php echo WPSNE_ADMIN_URL; ?>&tab=setting"><?php _e('Settings','wp-send-notifications-extended');?></a>
                        </li>
                        <li>
                            <a class=" <?php if ($active_menu == 'help') { echo 'active';} ?> "
                                href="<?php echo WPSNE_ADMIN_URL; ?>&tab=help"><?php _e('Help','wp-send-notifications-extended');?></a>
                        </li>
                    </ul>
                </div>
            </div>