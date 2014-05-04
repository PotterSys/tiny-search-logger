<?php

class Pslabs_Search_Log_Admin{
    public function get_current_count(){
        global $wpdb;
        
        return $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->search_log" );
    }
    
    public function get_max_logs_per_page(){
        // TODO: add this to an Option page
        return 20;
    }

    public function get_data( $current_page, $count_per_page){
        global $wpdb;
        
        $current_page = absint( $current_page ) > 1 ? absint($current_page) : 1;
        $count_per_page = absint( $count_per_page ) > 0 ? absint($count_per_page) : 0;
        
        $lower_limit = ($current_page - 1 ) * $count_per_page;
        $upper_limit = $count_per_page;
        
        $query = $wpdb->prepare( "SELECT query_term, timestamp FROM $wpdb->search_log ORDER BY timestamp DESC LIMIT %d, %d", $lower_limit, $upper_limit);
        
        return $wpdb->get_results( $query );
    }
    
    public function pager( $current_page, $max_page, $amount_per_page = 20, $item_count){
        
        $path_for_pager = admin_url('admin.php?page=pslabs-search-log');
        
        $result = "";
        
        $result .= sprintf( '<span class="displaying-num">%s</span>', sprintf( __('%s items', 'pslabs-tiny-search-logger'), $item_count) );
        
        if( $item_count == 0 )
            return;
        
        
        $result .= ("<span class='pagination-links'>");
        
        $disabled_first = $current_page > 1 ? '' : ' disabled';
        $disabled_last = $current_page < $max_page ? '' : ' disabled';
        
        $result .= ( sprintf("<a class='first-page%s' title='%s' href='%s'>&laquo;</a>",  $disabled_first, __('Go to the first page','pslabs-tiny-search-logger'), $path_for_pager) );
        
        if( $current_page > 1 ){
            $path_paged .= $path_for_pager . "&paged=". ($current_page - 1);
            $result .= sprintf("<a class='prev-page%s' title='%s' href='%s'>&lsaquo;</a>",  $disabled_first, __('Go to the previous page','pslabs-tiny-search-logger'), $path_paged );
        }
        
        $result .= ( sprintf("<span class='paging-input'>&nbsp; <span class='current-page'>%s</span> / <span class='total-pages'>%s</span> &nbsp;</span>", $current_page, $max_page) );
        
        if( $current_page < $max_page ){
            $path_paged = $path_for_pager . "&paged=". ($current_page + 1);
            $result .= sprintf("<a class='next-page%s' title='%s' href='%s'>&rsaquo;</a>",  $disabled_last, __('Go to the next page','pslabs-tiny-search-logger'), $path_paged );
        }
        
        $result .= ( sprintf("<a class='last-page%s' title='%s' href='%s'>&raquo;</a>",  $disabled_last, __('Go to the last page', 'pslabs-tiny-search-logger'), $path_for_pager .'&paged='. $max_page) );
        
        return $result;
    }
}