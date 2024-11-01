<?php
/**
 * Plugin Name: WM Pin-It
 * Plugin URI: http://chalktalk.wisemago.com/wm-pin-it
 * Description: A very simple way for adding Pin-it capability to your WP site. Just use [wmpin] [/wmpin] 
 * Author: WiseMago
 * Author URI: wisemago.com
 * Version: 1.1.1.1
 */

class WMPinterest {
	
    //initialize the plugin
	function __construct(){
		$_file = "wm-pinterest/" . basename(__FILE__);
		//register_activation_hook($_file,array(&$this,'activateWMPin'));
		//register_deactivation_hook($_file,array(&$this,'deactivateWMPin'));
	
		add_filter('the_posts', array(&$this,'conditinalStyleJsWMPin')); // the_posts gets triggered before wp_head
		add_shortcode('wmpin', array($this, 'shortcodeWMPin'));  
	}

	function shortcodeWMPin( $atts, $content = null ){
	
		$id= "pin".rand(0,time());
		
		$str .= "<span id='$id'>".$content."</span>";
		
		$str .= "<script type='text/javascript'>
		jQuery(function() {
			
			jQuery('span#".$id." img').pinit();
			
			});
		</script>";
		
		return $str;
	}
	
	function conditinalStyleJsWMPin($posts){
		if (empty($posts)) return $posts;
	 
		$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
		foreach ($posts as $post) {
			if (stripos($post->post_content, '[wmpin') !== false) {
				$shortcode_found = true; // bingo!
				break;
			}
		}
	 
		if ($shortcode_found && !is_admin()) {
			// enqueue here
			wp_enqueue_style('wmpinmaincss',plugins_url('css/jquery.pinit.css',__FILE__));
			wp_enqueue_script("jquery");
			wp_enqueue_script('wmpinmainjs',plugins_url('js/jquery.pinit.js',__FILE__)); 
		}
	 
		return $posts;
	}
}
new WMPinterest();
?>