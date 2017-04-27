<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL Core service
 * @author Howard <howard@realtyna.com>
 * @date 9/28/2015
 * @package WPL
 */
class wpl_service_wpl
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
        // Run WPL delete user function when a user removed from WordPress
        add_action('delete_user', array('wpl_users', 'delete_user'), 10, 1);
        
        // Start Session
        if(!session_id()) session_start();

        // Setting the default timezone of WordPress
        if(get_option('timezone_string')) date_default_timezone_set(get_option('timezone_string'));
        
        // Shutdown WPL objects
        add_action('wp_footer', array('wpl_global', 'wpl_shutdown'), 99);
        add_action('admin_footer', array('wpl_global', 'wpl_shutdown'), 99);
        
        // If we're in an AJAX request don't do the rest
        if(defined('DOING_AJAX') and DOING_AJAX) return;
        
        if(wpl_global::get_client()) $this->backend();
        else $this->frontend();
	}
    
    public function backend()
    {
        // Show update notification in WPL backend
        $available_updates = wpl_global::get_updates_count();
        if($available_updates >= 1 and wpl_users::is_administrator()) wpl_flash::set(sprintf(__('%s update(s) are available for WPL and its addons. Please proceed with update after creating a backup.', 'wpl'), '<strong>'.$available_updates.'</strong>'), 'wpl_gold_msg', 1);
    }
    
    public function frontend()
    {
    }
}