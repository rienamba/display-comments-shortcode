<?php /*
Plugin Name: Display Comments Shortcode
Description: Adds a [display-comments]shortcode that can put the comments section anywhere the shortcodes are accepted.
Author: Rie
Version: 1.0
Author URI: http://ctlt.ubc.ca
*/

 

/**
 * CTLT_Display_Comments_Shortcode class.
 */
class CTLT_Display_Comments_Shortcode {
	
	static public $counter  = 0;

	/**
	 * init function.
	 *
	 * @access public 
	 * @return void
	 */
	public function init() {
		
		add_action( 'init', array(__CLASS__, 'register_shortcode') );
	 
	}
	/**
	 * register_shortcode function.
	 * 
	 * @access public
	 * @return void
	 */
	function register_shortcode(){
		self::add_shortcode( 'display-comments',  'comment_shortcode' );
	}

	/**
	 * has_shortcode function.
	 * 
	 * @access public
	 * @param mixed $shortcode
	 * @return void
	 */
	function has_shortcode( $shortcode ){
		global $shortcode_tags;
		/* don't do anything if the shortcode exists already */
		return ( in_array( $shortcode, array_keys( $shortcode_tags ) ) ? true : false );
	}


	/**
	 * add_shortcode function.
	 * 
	 * @access public
	 * @param mixed $shortcode
	 * @param mixed $shortcode_function
	 * @return void
	 */
	function add_shortcode( $shortcode, $shortcode_function ){

		if( !self::has_shortcode( $shortcode ) )
			add_shortcode( $shortcode, array( __CLASS__, $shortcode_function ) );

	}

	
	/**
	 * comment_shortcode function.
	 * It will add comment section when you use  [comment]. It adds a div to differentiate from the default comment section in Wordpress. 
	 * @access public
	 * @static
	 * @param mixed $content
	 * @return void
	 */
	public static function comment_shortcode( $content ) {
		remove_filter( 'comments_template', array(__CLASS__, 'remove_template'), 10 );
		
		// work better with the clf-advanced theme
		remove_filter( 'comments_template','clf_base_disable_comments', 10 );
		
		// work better with UBC Collab Theme
		remove_filter( 'comments_template', array( 'UBC_Collab_Display_Options', 'disable_page_comments' ), 10 );
		
		$html =  "<div class=\"display-comments-shortcode\">";
		
		ob_start();
		comments_template();
		$html .= ob_get_contents();
		ob_end_clean();
		
		$html .="</div>";
		
		add_filter( 'comments_template', array(__CLASS__, 'remove_template') );
		
		return $html;
	}
	
	/**
	 * remove_template function.
	 * 
	 * @access public
	 * @param mixed $file
	 * @return void
	 */
	function remove_template( $file ){
		
		return plugin_dir_path( __FILE__ )."/empty.php";
	} 
	
}


CTLT_Display_Comments_Shortcode::init();