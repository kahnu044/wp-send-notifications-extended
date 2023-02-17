<?php

//delete subscriber list
if (isset($_POST['wpsne_delete_subscriber'])) {
    if ($_POST['delete_selected'] == 1 ) {
        if (isset($_POST['all_subscribers'])) {
            $subscribers_device_id = $_POST['all_subscribers'];
            foreach ($subscribers_device_id  as $device_id) {
                pnrRemoveSubscribers($device_id);
            }
        }
    }
}

//pagination
$page_no =1;
if (isset($_GET['page_no'])) {
    $page_no = $_GET['page_no'];
}
$total_subscriber = pnrGetAllSubscribers();
$per_page = 10;
$total_pages = ceil($total_subscriber/$per_page);
$offset = ($page_no-1)* $per_page;
$devices_info = pnrGetAllDevices($per_page, $offset);
?>

<div class="pnr-show-all-notifications" style="padding-top:10px">
    <h2 class="screen-reader-text">Filter posts list</h2>
    <ul class="subsubsub">
        <li class="all"><?php _e('Total Subscribers','wp-send-notifications-extended');?>
            <span class="count">(<?php _e($devices_info->total_count,'wp-send-notifications-extended');?>)</span>
        </li>
    </ul>

    <form method="post">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <select name="delete_selected">
                    <option value="0"><?php _e('Bulk actions','wp-send-notifications-extended');?></option>
                    <option value="1"><?php _e('Delete','wp-send-notifications-extended');?></option>
                </select>
                <input type="submit" name="wpsne_delete_subscriber" class="button action" value="Apply">
            </div>

            <h2 class="screen-reader-text"><?php _e('Subscriber lists navigation','wp-send-notifications-extended');?></h2>
            <div class="tablenav-pages"><span class="displaying-num"><?php _e($devices_info->total_count,'wp-send-notifications-extended');?>
            <?php _e('items','wp-send-notifications-extended');?></span>
                <span class="pagination-links">
                    <a class="next-page button" href="<?php echo WPSNE_ADMIN_URL; ?>&page_no=1"><span
                            class="screen-reader-text">Next page</span><span aria-hidden="true">«</span></a>
                    <a class="last-page button"
                        href="<?php echo WPSNE_ADMIN_URL; ?>&page_no=<?php if ($page_no >=2) {echo $page_no-1;}else{echo '1';}?>"><span
                            class="screen-reader-text">Last page</span><span aria-hidden="true">‹</span></a>
                    <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current
                            Page</label><input class="current-page" id="current-page-selector" type="text" name="paged"
                            value="<?php _e($page_no,'wp-send-notifications-extended');?>" size="1" aria-describedby="table-paging">
                        <span class="tablenav-paging-text"> of
                            <span class="total-pages"><?php echo $total_pages; ?></span></span>
                    </span>
                    <a class="next-page button"
                        href="<?php echo WPSNE_ADMIN_URL; ?>&page_no=<?php if ($page_no >=1 && $page_no <$total_pages) { echo $page_no+1;} else { echo $page_no; }?>"><span
                            class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                    <a class="last-page button"
                        href="<?php echo WPSNE_ADMIN_URL; ?>&page_no=<?php echo $total_pages; ?>"><span
                            class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a>
                </span>
            </div>
        </div>

        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1">Select All</label><input
                            id="cb-select-all-1" type="checkbox">
                    </td>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <?php _e('UserID','wp-send-notifications-extended');?>
                    </th>
                    <th scope="col" id="device" class="manage-column column-device">Device</th>
                    <th scope="col" id="first-session" class="manage-column column-first-session"><?php _e('First Session','wp-send-notifications-extended');?></th>
                    <th scope="col" id="last-session" class="manage-column column-last-session"><?php _e('Last Session','wp-send-notifications-extended');?></th>
                    <th scope="col" id="session-count" class="manage-column column-session-count"><?php _e('Session Count','wp-send-notifications-extended');?></th>
                    <th scope="col" id="location" class="manage-column column-location"><?php _e('Location','wp-send-notifications-extended');?></th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php
            if ($devices_info->total_count > 0) {
                foreach ($devices_info->players as $key) {
                ?>
                <tr class="">
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="all_subscribers[]" value="<?php echo $key->id;?>">
                    </th>
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                        <strong><?php _e($key->id,'wp-send-notifications-extended');?></strong>
                        <div class="row-actions">
                            <!-- <span class="edit"><a href="#"><?php _e('Edit','wp-send-notifications-extended');?></a> | </span>
                            <span class="trash"><a href="#" class="submitdelete"><?php _e('Trash','wp-send-notifications-extended');?></a> |</span> -->
                        </div>
                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more
                                details</span></button>
                    </td>
                    <td data-colname="Device"><?php _e($key->device_model.'('.$key->device_os.')','wp-send-notifications-extended');?></td>
                    <td data-colname="First Session"><?php _e(date('d-m-Y h:i:s a', $key->created_at),'wp-send-notifications-extended');?></td>
                    <td data-colname="Last Session"><?php _e(date('d-m-Y h:i:s a', $key->last_active),'wp-send-notifications-extended');?></td>
                    <td data-colname="Session Count"><?php _e($key->session_count,'wp-send-notifications-extended');?></td>
                    <td data-colname="location"><?php _e(pnrGetUserLocation($key->ip),'wp-send-notifications-extended');?></td>
                </tr>
                <?php
                }
            }
            else
            {
            ?>
                <tr class="iedit ">
                    <th></th>
                    <td><?php _e('No Subscriber','wp-send-notifications-extended');?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>