<?php
/**
* PNR All Notifications
*/

$page_no =1;
if (isset($_GET['page_no'])) {
    $page_no = $_GET['page_no'];
}

$total_notifications = pnrGetTotalNotifications();
$per_page = 10;
$total_pages = ceil($total_notifications/$per_page);
$offset = ($page_no-1)* $per_page;
$notifications_info = pnrGetAllNotifications($per_page,$offset);

?>

<div class="pnr-show-all-notifications" style="padding-top:10px">
    <h2 class="screen-reader-text">Filter Notifications List</h2>
    <ul class="subsubsub">
        <li class="all"><?php _e('Total Notifications','wp-send-notifications-extended');?>
            <span class="count">(<?php echo $notifications_info->total_count; ?>)</span>
        </li>
    </ul>
    <form method="post">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <select name="delete_selected" disabled>
                    <option value="0"><?php _e('Bulk actions','wp-send-notifications-extended');?></option>
                    <option value="1"><?php _e('Delete','wp-send-notifications-extended');?></option>
                </select>
                <input type="submit" name="wpsne_apply_bulk_action" class="button action" value="Apply" disabled>
            </div>

            <h2 class="screen-reader-text"><?php _e('Notifications list navigation','wp-send-notifications-extended');?>
            </h2>
            <div class="tablenav-pages"><span class="displaying-num"><?php echo $notifications_info->total_count; ?>
                    <?php _e('items','wp-send-notifications-extended');?> </span>
                <span class="pagination-links">
                    <a class="next-page button" href="<?php echo WPSNE_ADMIN_URL; ?>&tab=notifications&page_no=1"><span
                            class="screen-reader-text">Next page</span><span aria-hidden="true">«</span></a>

                    <a class="last-page button"
                        href="<?php echo WPSNE_ADMIN_URL; ?>&tab=notifications&page_no=<?php if ($page_no >=2) {echo $page_no-1;}else{echo '1';}?>"><span
                            class="screen-reader-text">Last page</span><span aria-hidden="true">‹</span></a>

                    <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current
                            Page</label><input class="current-page" id="current-page-selector" type="text" name="paged"
                            value="<?php _e($page_no,'wp-send-notifications-extended');?>" size="1"
                            aria-describedby="table-paging">

                    <span class="tablenav-paging-text"> <?php _e('of ','wp-send-notifications-extended');?><span
                                class="total-pages"><?php echo $total_pages; ?></span></span></span>

                    <a class="next-page button"
                        href="<?php echo WPSNE_ADMIN_URL; ?>&tab=notifications&page_no=<?php if ($page_no >=1 && $page_no <$total_pages) { echo $page_no+1;} else { echo $page_no; }?>"><span
                            class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>

                    <a class="last-page button"
                        href="<?php echo WPSNE_ADMIN_URL; ?>&tab=notifications&page_no=<?php echo $total_pages; ?>"><span
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
                        <?php _e('Message ','wp-send-notifications-extended');?>
                    </th>
                    <th scope="col" id="delivered" class="manage-column column-delivered">
                        <?php _e('Delivered At ','wp-send-notifications-extended');?></th>
                    <th scope="col" id="first-delivered" class="manage-column column-first-delivered">
                        <?php _e('Delivered','wp-send-notifications-extended');?></th>
                    <th scope="col" id="last-clicked" class="manage-column column-last-clicked">
                        <?php _e('Clicked','wp-send-notifications-extended');?></th>
                    <th scope="col" id="session-segments" class="manage-column column-segments">
                        <?php _e('Segments','wp-send-notifications-extended');?></th>
                </tr>
            </thead>

            <tbody id="the-list">

                <?php
                if ($notifications_info->total_count > 0) {
                    foreach ($notifications_info->notifications as $key) {
                    ?>
                <tr class="">
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="post[]" value="userid">
                    </th>
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                        <strong><?php _e($key->contents->en,'wp-send-notifications-extended');?></strong>
                        <div class="row-actions">
                            <!-- <span class="edit"><a href="#"><?php _e('Edit ','wp-send-notifications-extended');?></a> | </span>
                            <span class="trash"><a href="#" class="submitdelete"><?php _e('Trash ','wp-send-notifications-extended');?></a> |</span> -->
                        </div>
                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more
                                details</span></button>
                    </td>
                    <td data-colname="Delivered At">
                        <?php _e(date('d-m-Y h:i:s a', $key->completed_at),'wp-send-notifications-extended');?></td>
                    <td data-colname="Delivered">
                        <?php _e($key->platform_delivery_stats->chrome_web_push->successful,'wp-send-notifications-extended');?>
                    </td>
                    <td data-colname="Clicked">
                        <?php _e($key->platform_delivery_stats->chrome_web_push->converted,'wp-send-notifications-extended');?>
                    </td>
                    <td data-colname="Segments">
                        <?php _e(implode(" ",$key->included_segments),'wp-send-notifications-extended');?> </td>
                </tr>
                <?php
                    }
                }
                else
                {
                ?>
                <tr class="iedit ">
                    <th></th>
                    <td><?php _e('No Notifications','wp-send-notifications-extended');?> </td>
                </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </form>
</div>