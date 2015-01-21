<?php
if(!class_exists('classic_facebook_feed_settings'))
{
	class classic_facebook_feed_settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('init', array($this, 'localize_plugin'));
        	add_action('admin_menu', array(&$this, 'add_menu'));

            // Add style and script
             add_action('wp_print_styles', array($this, 'cff_styles'));
             add_action('wp_print_scripts', array($this, 'cff_scripts'));

            // Create shortcode
            add_shortcode('classic-facebook-feed', array($this, 'render_shortcode'));

            // activate shortcode in text widgets
             add_filter('widget_text', 'shortcode_unautop');
             add_filter('widget_text', 'do_shortcode');

		} // END public function __construct
		
        function localize_plugin(){
            // register styles
            wp_register_style('plugin_css', CFF_URL . 'css/style.css', null, CFF_VERSION);
            
            // register scripts
            wp_register_script('FeedEk', CFF_URL . 'js/FeedEk.js', array('jquery'), '1.0.0', true);
        }

        /* Calling Style */
        function cff_styles() {
            wp_enqueue_style('plugin_css');
        }// END public function CFF_styles()

        /* Calling Script*/
        function cff_scripts() {
            wp_enqueue_script('FeedEk');
        }// END public function cff_scripts()

        // Add Shortcode code
        function render_shortcode($atts){
            // Attributes
            extract( shortcode_atts(
                array(
                   'username' => '',
                   'user_id' => '',
                   'post_limit' => '10',
                ), $atts )
             );

            $userName = $username;
            $userId = $user_id;
            $postLimit  = $post_limit;

            if ($userName){
                $fburl = "http://graph.facebook.com/".$userName;
                $pageContent = file_get_contents($fburl);
                $parsedJson  = json_decode($pageContent);
                $userId = $parsedJson->id;
            }

            $out  = "<script type='text/javascript'>\n";
            $out .= "jQuery(document).ready(function($){\n";
            $out .= "$('#newsFeed').FeedEk({\n";
            $out .= "FeedUrl: 'http://www.facebook.com/feeds/page.php?format=rss20&id={$userId}',\n";
            $out .= "MaxCount: {$postLimit},\n";
            $out .= "ShowDesc: true,\n";
            $out .= "ShowPubDate: true\n";
            $out .= "});\n";
            $out .= "});\n";
            $out .= "</script>\n";
           
            $out .= "<div id='newsFeed'></div>";

            return $out;
        }
       
        /* add a menu */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page('Classic Facebook Feed Settings', 'Classic Facebook Feed', 'manage_options', 'classic_facebook_feed', array(&$this, 'plugin_settings_page'));
        } // END public function add_menu()
    
        /* Menu Callback */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}

	        include(sprintf("%s/setting-temp.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class classic_facebook_feed_settings
} // END if(!class_exists('classic_facebook_feed_settings'))