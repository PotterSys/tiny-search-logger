<?php
/*
Plugin Name: Tiny Search Logger
Plugin URI: http://www.pslabs.cl/
Description: Logs searches made by end users. Use wisely
Author: Juan Correa
Version: 1
Author URI: http://www.pslabs.cl/
Text Domain: pslabs-tiny-search-logger
License: GNU GPL v2
*/

/**
 *  Tiny Search Logger
    Copyright (C) 2014 - Juan Correa Poblete

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
    
    Contact in juan *at/@* pslabs.cl
 *
 **/

register_activation_hook( __FILE__, 'ps_log_install_plugin' );

define( 'PS_LOG_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'PS_LOG_VERSION', '0.1');

function ps_log_install_plugin(){
    global $wpdb;
    $table_name = $wpdb->prefix . "search_log";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        if( get_option('ps_log_plugin_version') != PS_LOG_VERSION ){
            include( PS_LOG_PLUGIN_PATH .'inc/install.php' );
            update_option( 'ps_log_plugin_version', PS_LOG_VERSION );
        }
    }
}

class Pslabs_Search_Log {
    public static function add_wpdb_shortcut(){
        global $wpdb;
        $wpdb->search_log = $wpdb->prefix . "search_log";
    }
    
    public function add_search_to_db( ){
        global $wpdb;
        if( is_search() && is_main_query() && !is_admin() ){
            $wpdb->insert( $wpdb->search_log, array( 'query_term' => get_search_query(), 'timestamp' => current_time('mysql') ) );
        }
    }
    
    public function page_admin(){
        require PS_LOG_PLUGIN_PATH . 'search-log-admin.php';
        require PS_LOG_PLUGIN_PATH . 'adm/panel.php';
    }
    
    public function add_menu_page(){
        add_menu_page( __('Searches') , __('Searches'), 'publish_pages', 'pslabs-search-log', array( new Pslabs_Search_Log, 'page_admin' ), "dashicons-search" , 22);
    }
}

$pslog = new Pslabs_Search_Log();

add_action( 'init', Pslabs_Search_Log::add_wpdb_shortcut() );
add_action( 'wp', array( &$pslog, 'add_search_to_db') );
add_action( 'admin_menu', array( &$pslog, 'add_menu_page') );