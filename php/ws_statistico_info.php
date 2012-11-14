<?php
global $wpdb;

////////////////////////////////////PAGINATION////////////////////////////

$ws_query = "SELECT COUNT(*) as cnt FROM " . $wpdb->prefix . "ws_statistico_agent";
$ws_results = $wpdb->get_results($ws_query, ARRAY_A);

$ws_rows_total_count = $ws_results[0]['cnt'];

$ws_rows_limit = 20;

if (isset($_GET['ws_statistico_agent_page'])) {
    $ws_page = $_GET['ws_statistico_agent_page'];
}

if ((!$ws_page) || (is_numeric($ws_page) == false) || ($ws_page < 0) || ($ws_page > $ws_rows_total_count)) {
    $ws_page = 1; //default (current page)
}

$ws_total_pages = ceil($ws_rows_total_count / $ws_rows_limit);
$ws_set_limit = $ws_page * $ws_rows_limit - ($ws_rows_limit);

//////////////////////////////////END PAGINATION//////////////////////////

$ws_query = "SELECT * FROM " . $wpdb->prefix . "ws_statistico_agent ORDER BY ws_agent_count DESC LIMIT " . $ws_set_limit . "," . $ws_rows_limit;
$ws_results = $wpdb->get_results($ws_query, OBJECT);
?>
<div class="wrap">

    <h2>Browser Info</h2>

    <table class="widefat">
        <thead>
            <tr>
                <th>Browser Agent</th>
                <th>Operating System</th>
                <th>Count</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Browser Agent</th>
                <th>Operating System</th>
                <th>Count</th>
            </tr>
        </tfoot>
        <tbody>

            <?php
            $ws_result_count = 0;

            foreach ($ws_results as $ws_result) {
                echo '<tr>
            <td>' . $ws_result->ws_statistico_name . ' ' . $ws_result->ws_statistico_version . '</td>
            <td>' . $ws_result->ws_os . '</td>
            <td>' . $ws_result->ws_agent_count . '</td>
         </tr>';
                $ws_result_count++;
            }

            if ($ws_result_count == 0) {
                echo '<tr>
            <td>No entries yet</td>
            <td></td>
            <td></td>
         </tr>';
            }
            ?>
        </tbody>
    </table>
    <?php
    if ($ws_total_pages != 1) {//is pagination needed?
        ?>
        <div class="tablenav bottom">

            <div class="tablenav-pages"><span class="displaying-num"><?php echo $ws_rows_total_count; ?> items</span>
                <span class="pagination-links">
                    <a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=ws_statistico_info&ws_statistico_agent_page=1' ?>" title="Go to the first page" class="first-page <?php if ($ws_page == 1) {
        echo "disabled";
    } ?>">«</a>
                    <a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=ws_statistico_info&ws_statistico_agent_page=' . ($ws_page - 1); ?>" title="Go to the previous page" class="prev-page <?php if ($ws_page == 1) {
        echo "disabled";
    } ?>">‹</a>
                </span><!--pagination-links-->

                <span class="paging-input"><?php echo $ws_page; ?> of <span class="total-pages"><?php echo $ws_total_pages; ?></span>
                    <a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=ws_statistico_info&ws_statistico_agent_page=' . ($ws_page + 1); ?>" title="Go to the next page" class="next-page <?php if ($ws_page == $ws_total_pages) {
        echo "disabled";
    } ?>">›</a>
                    <a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=ws_statistico_info&ws_statistico_agent_page=' . $ws_total_pages; ?>" title="Go to the last page" class="last-page <?php if ($ws_page == $ws_total_pages) {
        echo "disabled";
    } ?>">»</a>
                </span><!--paging-input-->

            </div><!--tablenav-pages-->

            <br class="clear">

        </div><!--tablenav bottom-->
    <?
}//Check if pagination is needed
?>

</div><!--wrap-->