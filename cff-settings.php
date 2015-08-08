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
            wp_register_style('plugin_css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', null, CFF_VERSION);
            
            // register scripts
            /* wp_register_script('FeedEk', CFF_URL . 'js/FeedEk.js', array('jquery'), '1.0.0', true); */
        }

        /* Calling Style */
        function cff_styles() {
            wp_enqueue_style('plugin_css');
        }// END public function CFF_styles()

        /* Calling Script*/
        function cff_scripts() {
            /*wp_enqueue_script('FeedEk');*/
        }// END public function cff_scripts()

        // Add Shortcode code
        function render_shortcode($atts){
            // Attributes
            extract( shortcode_atts(
                array(
                   'username'   => '',
                   'user_id'    => '',
                   'post_limit' => '10',
                ), $atts )
             );

            $userName  = $username;
            $userId    = $user_id;
            $postLimit = $post_limit;
            //Set default Access Token
            $access_token_array = array(
                '1489500477999288|KFys5ppNi3sreihdreqPkU2ChIE',
                '859332767418162|BR-YU8zjzvonNrszlll_1a4y_xE',
                '360558880785446|4jyruti_VkxxK7gS7JeyX-EuSXs',
                '1487072591579718|0KQzP-O2E4mvFCPxTLWP1b87I4Q',
                '640861236031365|2rENQzxtWtG12DtlZwqfZ6Vu6BE',
                '334487440086538|hI_NNy1NvxQiQxm-TtXsrmoCVaE',
                '755471677869105|Jxv8xVDad7vUUTauk8K2o71wG2w',
                '518353204973067|dA7YTe-k8eSvgZ8lqa51xSm16DA',
                '444286039063163|5qkYu2qxpERWO3gcs2f3nxeqhpg',
                '944793728885704|XJ6QqKK8Ldsssr4n5Qrs2tVr7rs',
                '1444667452511509|wU7tzWiuj6NadfpHfgkIGLGO86o',
                '1574171666165548|ZL9tXNXxpnCdAvdUjCX5HtRnsR8'
            );
            
            $options = get_option('cff_setting');            
            $access_token = $options['cff_fb_app_id']."|".$options['cff_fb_app_secret_key'];

            if (empty($access_token)) {
                $access_token = $access_token_array[rand(0, 11)];
            }

            if($userName){
                $graph_url    = "https://graph.facebook.com/".$userName."?fields=posts.limit(".$postLimit."){id,name,message,full_picture,updated_time,description,likes,comments,shares}&access_token=". $access_token;
                $page_posts   = json_decode(file_get_contents($graph_url), true);
                $page_posts   = $page_posts['posts']['data'];
            }
            else if($userId){
                $graph_url    = "https://graph.facebook.com/".$userId."?fields=posts.limit(".$postLimit."){id,name,message,full_picture,updated_time,description,likes,comments,shares}&access_token=". $access_token;
                $page_posts   = json_decode(file_get_contents($graph_url), true);
                $page_posts   = $page_posts['posts']['data'];
            }

            $out = "<div class='cff_wrapper'>";
            foreach ($page_posts as $post_feed) {

                $feed_link  =  "http://www.facebook.com/".$post_feed['id'];
                $feed_title =  $post_feed['name'];
                $feed_des   =  $post_feed['description'];
                $feed_img   =  $post_feed['full_picture'];
                $feed_time  =  strtotime($post_feed['updated_time']);
                $like_count =  count($post_feed['likes']['data']);
                $cmt_count  =  count($post_feed['likes']['data']);
                $shr_count  =  $post_feed['shares']['count'];
                
                $out .=  "<div class='cff_feed item-".$post_feed['id']."'>";
                $out .=  "<div class='feed_head'><h1><a href='".$feed_link."' title='".$feed_title."' target='_blank'>".$feed_title."</a></h1></div>";
                $out .=  "<div class='feed_content'>";
                $out .=  "<div class='feed_img feed_left'><img src='".$feed_img."' alt='Feed Image' title='".$feed_title."' /></div>";
                $out .=  "<div class='feed_content feed_right'>".$feed_des."</div>";
                $out .=  "</div>";
                $out .=  "<div class='feed_meta'>";
                $out .=  "<div class='feed_social feed_left'>";
                $out .=  "<span title='Date'> <i class='fa fa-calendar'></i> ".date("F j, Y",$feed_time)."|</span>";
                $out .=  "<span title='Likes'> <i class='fa fa-thumbs-o-up'></i> ".$like_count." |</span>";
                $out .=  "<span title='Comments'> <i class='fa fa-comments'></i> ".$cmt_count." |</span></dov>";
                $out .=  "<span title='Shares'> <i class='fa fa-share '></i> ".((empty($shr_count)) ? 0 : $shr_count)." </span>";
                $out .=  "</div>";
                $out .=  "<div class='feed_read_more feed_right'><a href='".$feed_link."' target='_blank' class='read-more-btn' >Read More</a></div>";
                $out .=  "</div>";
                $out .=  "<div class='feed_clear feed_divider'></div>";
                $out .=  "</div>";
            }

            $out .= "</div>";
            return $out;
        }
       
        /* add a menu */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_menu_page('Classic Facebook Feed', 'Classic FB Feed', 'manage_options', 'classic_facebook_feed', array(&$this, 'cff_htu_page'));
            add_submenu_page( 'classic_facebook_feed', 'Classic Facebook Feed Settings',  'CFF Settings',  'manage_options',  'cff_setting', array(&$this, 'cff_setting_option_page'));
            add_submenu_page( 'classic_facebook_feed', 'Classic Facebook Feed Customize Layout', 'Layout Setting',  'manage_options',  'cff_layout_setting', array(&$this, 'cff_layout_setting_option_page'));
        } // END public function add_menu() 


        /* Menu Callback */     
        public function cff_htu_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            include(sprintf("%s/inc/cff-setting-about.php", dirname(__FILE__)));
        } // END public function cff_htu_page()

        public function cff_setting_option_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            include(sprintf("%s/inc/cff-settion-option.php", dirname(__FILE__)));
        }// END public function cff_htu_page()

        public function cff_layout_setting_option_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            include(sprintf("%s/inc/css-layout-setting.php", dirname(__FILE__)));
        }// END public function cff_htu_page()

    } // END class classic_facebook_feed_settings
} // END if(!class_exists('classic_facebook_feed_settings'))