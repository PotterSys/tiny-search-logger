<?php
global $wpdb;

$ps_search_admin = new Pslabs_Search_Log_Admin();

$count_result = $ps_search_admin->get_current_count();

$current_page = isset($_GET['paged']) ? absint( $_GET['paged'] ) : 1;
$count_per_page = $ps_search_admin->get_max_logs_per_page();

$max_pages = ceil( $count_result / $count_per_page );

$list_results = $ps_search_admin->get_data( $current_page, $count_per_page);
?>
<div class="wrap">
    <h2><?php _e('Searches in this site', 'pslabs-tiny-search-logger'); ?></h2>
 
<div class="tablenav">
    <div class="tablenav-pages">
        <?php echo $ps_search_admin->pager( $current_page, $max_pages, $count_per_page, $count_result); ?>
    </div>
</div>
 
<table class="widefat">
    <thead>
        <tr>
            <th><?php _e('Keyword', 'pslabs-tiny-search-logger'); ?></th>
            <th><?php _e('Timestamp', 'pslabs-tiny-search-logger'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if( count($list_results) > 0 ):
        foreach( $list_results as $row ): ?>
        <tr>
            <td><?php esc_html_e( $row->query_term ); ?></td>
            <td><?php esc_html_e( $row->timestamp ); ?></td>
        </tr>
        <?php endforeach;
        else: ?>
        <tr>
            <td><?php _e('There are no searches logged yet', 'pslabs-tiny-search-logger'); ?></td>
        <tr>
        <?php endif; ?>
    </tbody>
</table>
</div>