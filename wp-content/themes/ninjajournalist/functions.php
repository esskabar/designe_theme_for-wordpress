<?php

define( 'TEMPLATE_DIRECTORY_URI', get_stylesheet_directory_uri() );
define( 'TEMPLATE_DIRECTORY', get_stylesheet_directory() );


if ( ! function_exists( 'ninjajournalist_setup' ) ) :
	function ninjajournalist_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Sixteen, use a find and replace
		 * to change 'ninjajournalist' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'ninjajournalist', TEMPLATE_DIRECTORY . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'nj-theme-main-thumbnail', 389, 222, true );
		add_image_size( 'nj-theme-main_wide-thumbnail', 770, 305, true );
		add_image_size( 'nj-theme-main-top_big-thumbnail', 1170, 430, true );
		add_image_size( 'nj-theme-main_small-thumbnail', 93, 67, true );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary' => __( 'Top NinjaJournalist Menu', 'ninjajournalist' ),
			'second' => __( 'Second NinjaJournalist Menu', 'ninjajournalist' ),
			'footer' => __( 'Footer NinjaJournalist Menu', 'ninjajournalist' )
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

	}
endif; // ninjajournalist_setup
add_action( 'after_setup_theme', 'ninjajournalist_setup' );

/**
 * Enqueues scripts and styles.
 */
function ninjajournalist_scripts() {

	// Theme stylesheet.
	wp_enqueue_style( 'ninjajournalist-style', get_stylesheet_uri(), array('ninjajournalist-style-main') );
	wp_enqueue_style( 'ninjajournalist-style-main', TEMPLATE_DIRECTORY_URI.'/styles/main.css');




	wp_enqueue_script( 'ninjajournalist-vendor', TEMPLATE_DIRECTORY_URI.'/scripts/vendor.js', array(), false, true );
	wp_enqueue_script( 'ninjajournalist-modernizr', TEMPLATE_DIRECTORY_URI.'/scripts/vendor/modernizr.js', array(), false, false );
	wp_enqueue_script( 'ninjajournalist-plugins', TEMPLATE_DIRECTORY_URI.'/scripts/plugins.js', array('ninjajournalist-vendor'), false, true );
	wp_enqueue_script( 'ninjajournalist-main', TEMPLATE_DIRECTORY_URI.'/scripts/main.js', array('ninjajournalist-vendor', 'ninjajournalist-plugins' ), false, true );


}
add_action( 'wp_enqueue_scripts', 'ninjajournalist_scripts' );

// Register Custom Bootstrap Navigation Walker
require_once('lib/wp_bootstrap_navwalker.php');

// Disable emojicons
function disable_wp_emojicons() {

	// all actions related to emojis
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	// filter to remove TinyMCE emojis
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
add_action( 'init', 'disable_wp_emojicons' );

// page option
function ninjajournalist_menu() {
	add_theme_page('Banner Top', 'Banner Top', 'edit_theme_options', 'ninjajournalist-main-top', 'ninjajournalist_options_main_top');
	add_theme_page('Three Posts block', 'Three Posts block', 'edit_theme_options', 'ninjajournalist-options-three-posts-block', 'ninjajournalist_options_three_posts_block');
	add_theme_page('Three block', 'Three block', 'edit_theme_options', 'ninjajournalist-options-three-post', 'ninjajournalist_options_three_post');
	add_theme_page('With wide post', 'With wide post', 'edit_theme_options', 'ninjajournalist-options-wide-post', 'ninjajournalist_options_wide_post');
	add_theme_page('Theme options', 'Theme options', 'edit_theme_options', 'ninjajournalist-theme-options', 'ninjajournalist_theme_options');
}
add_action('admin_menu', 'ninjajournalist_menu');

//theme options
function include_admin_scripts() {
//add script for upload image
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}

	wp_enqueue_script( 'admin_scripts', get_stylesheet_directory_uri() . '/scripts/admin.js', array('jquery'), null, false );
}
add_action( 'admin_enqueue_scripts', 'include_admin_scripts' );

function ninjajournalist_theme_options() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST" enctype="multipart/form-data">
			<?php
			settings_fields( 'theme_options_fields' );
			do_settings_sections( 'ninjajournalist_theme_options' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action('admin_init', 'theme_options_settings');
function theme_options_settings(){

	register_setting( 'theme_options_fields', 'theme_options_id', 'sanitize_callback_theme_options' );


	add_settings_section( 'theme_options_id', 'Customize ninjajournalist Theme', '', 'ninjajournalist_theme_options' );


	add_settings_field('custom_logo', 'Logo image:<br>max size 329x54', 'fill_logo_image', 'ninjajournalist_theme_options', 'theme_options_id' );
}

function fill_logo_image($name, $value = array(), $w = 115, $h = 115){
	$value = get_option('theme_options_id');
	$value = $value['custom_logo'];
	$name="theme_options_id[custom_logo]";
	$default = get_stylesheet_directory_uri() . '/images/no-image.png';
	if( $value ) {
		$image_attributes = wp_get_attachment_image_src( $value, array($w, $h) );
		$src = $image_attributes[0];
	} else {
		$src = $default;
	}
	echo '
	<div>
		<img data-src="' . $default . '" src="' . $src . '" width="' . $w . 'px" height="' . $h . 'px" />
		<div>
			<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			<button type="submit" class="upload_image_button button">Upload</button>
			<button type="submit" class="remove_image_button button">&times;</button>
		</div>
	</div>
	';
}

//main top
function ninjajournalist_options_main_top() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
			settings_fields( 'main_top_post' );
			do_settings_sections( 'main_top_block' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action('admin_init', 'plugin_settings_main_top');
function plugin_settings_main_top(){

	register_setting( 'main_top_post', 'main_top_post_id', 'sanitize_callback' );


	add_settings_section( 'main_top_section_id', 'Customize main top', '', 'main_top_block' );


	add_settings_field('main_top_post_big', 'Show post on main top<br>ONE BIG: (paste ID)', 'fill_main_top_big', 'main_top_block', 'main_top_section_id' );

}
function fill_main_top_big(){
	$val = get_option('main_top_post_id');
	$val = $val['big'];
	?>
	<input type="text" name="main_top_post_id[big]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

//below top
function ninjajournalist_options_below_top_stripe() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
			settings_fields( 'below_top_post_stripe' );
			do_settings_sections( 'below_top_block_stripe' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

//three posts block
function ninjajournalist_options_three_posts_block() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
			settings_fields( 'three_posts_block' );
			do_settings_sections( 'ninjajournalist-options-three-posts-block' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action('admin_init', 'plugin_settings_three_posts_block');
function plugin_settings_three_posts_block(){

	register_setting( 'three_posts_block', 'three_posts_block_id', 'sanitize_callback' );


	add_settings_section( 'three_posts_block_section', 'Customize Block with three posts section', '', 'ninjajournalist-options-three-posts-block' );


	add_settings_field('three_posts_block', 'Show three posts:<br>(paste post ID, comma separated)', 'fill_three_post_input', 'ninjajournalist-options-three-posts-block', 'three_posts_block_section' );
}
function fill_three_post_input(){
	$val = get_option('three_posts_block_id');
	$val = $val;
	?>
	<input type="text" name="three_posts_block_id" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}


//three block
function ninjajournalist_options_three_post() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
			settings_fields( 'three_post' );
			do_settings_sections( 'ninjajournalist-options-three-post' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action('admin_init', 'plugin_settings_three_post');
function plugin_settings_three_post(){

	register_setting( 'three_post', 'three_post_id', 'sanitize_callback' );


	add_settings_section( 'three_post_section', 'Customize Block with three section', '', 'ninjajournalist-options-three-post' );


	add_settings_field('three_post_left', 'Show post in left section:<br>(paste post ID', 'fill_three_post_input_left', 'ninjajournalist-options-three-post', 'three_post_section' );

	add_settings_field('three_post_center', 'Show three post in center:<br>(paste post ID, comma separated)', 'fill_three_post_input_center', 'ninjajournalist-options-three-post', 'three_post_section' );

}
function fill_three_post_input_left(){
	$val = get_option('three_post_id');
	$val = $val['left'];
	?>
	<input type="text" name="three_post_id[left]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

function fill_three_post_input_center(){
	$val = get_option('three_post_id');
	$val = $val['center'];
	?>
	<input type="text" name="three_post_id[center]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

//block with wide post
function ninjajournalist_options_wide_post() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
			settings_fields( 'block_with_wide_post' );
			do_settings_sections( 'ninjajournalist-options-wide-post' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action('admin_init', 'plugin_settings_wide_post');
function plugin_settings_wide_post(){

	register_setting( 'block_with_wide_post', 'block_wide_post', 'sanitize_callback' );


	add_settings_section( 'block_wide_post_section', 'Customize block with wide post', '', 'ninjajournalist-options-wide-post' );


	add_settings_field('wide_post_wide', 'Show post in wide section<br>ONE WIDE: (paste ID)', 'fill_wide_post_id', 'ninjajournalist-options-wide-post', 'block_wide_post_section' );

	add_settings_field('wide_post_four', 'Show post in wide section<br>FOUR THINK: (paste ID, comma separated)', 'fill_post_id_near_wide', 'ninjajournalist-options-wide-post', 'block_wide_post_section' );
}
function fill_wide_post_id(){
	$val = get_option('block_wide_post');
	$val = $val['wide'];
	?>
	<input type="text" name="block_wide_post[wide]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}
function fill_post_id_near_wide(){
	$val = get_option('block_wide_post');
	$val = $val['four'];
	?>
	<input type="text" name="block_wide_post[four]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

## Clear Data
function sanitize_callback( $options ){

	if ( !is_array( $options ) ){
		$options = strip_tags( $options );
	} else {
		foreach( $options as $name => & $val ){
			if( $name == 'input' )
				$val = strip_tags( $val );

			if( $name == 'checkbox' )
				$val = intval( $val );
		}
	}


	//die(print_r( $options )); // Array ( [input] => aaaa [checkbox] => 1 )

	return $options;
}

function sanitize_callback_theme_options( $options ){

	if ( !is_array( $options ) ){
		$options = strip_tags( $options );
	} else {
		foreach( $options as $name => & $val ){
			if( $name == 'input' )
				$val = strip_tags( $val );

			if( $name == 'checkbox' )
				$val = intval( $val );
		}
	}


	return $options;
}

/**
 * Обрезка текста (excerpt). Шоткоды вырезаются. Минимальное значение maxchar может быть 22.
 * version 2.2
 *
 * @param  массив/строка $args аргументы. Смотирте переменную $default.
 * @return строка HTML
 */
function kama_excerpt( $args = '' ){
	global $post;

	$default = array(
		'maxchar'     => 350, // количество символов.
		'text'        => '',  // какой текст обрезать (по умолчанию post_excerpt, если нет post_content.
		// Если есть тег <!--more-->, то maxchar игнорируется и берется все до <!--more--> вместе с HTML
		'save_format' => false, // Сохранять перенос строк или нет. Если в параметр указать теги, то они НЕ будут вырезаться: пр. '<strong><a>'
		'more_text'   => 'Read more...', // текст ссылки читать дальше
		'echo'        => true, // выводить на экран или возвращать (return) для обработки.
	);

	if( is_array($args) )
		$rgs = $args;
	else
		parse_str( $args, $rgs );

	$args = array_merge( $default, $rgs );

	extract( $args );

	if( ! $text ){
		$text = $post->post_excerpt ? $post->post_excerpt : $post->post_content;

		$text = preg_replace ('~\[[^\]]+\]~', '', $text ); // убираем шоткоды, например:[singlepic id=3]
		// $text = strip_shortcodes( $text ); // или можно так обрезать шоткоды, так будет вырезан шоткод и конструкция текста внутри него
		// и только те шоткоды которые зарегистрированы в WordPress. И эта операция быстрая, но она в десятки раз дольше предыдущей
		// (хотя там очень маленькие цифры в пределах одной секунды на 50000 повторений)

		// для тега <!--more-->
		if( ! $post->post_excerpt && strpos( $post->post_content, '<!--more-->') ){
			preg_match ('~(.*)<!--more-->~s', $text, $match );
			$text = trim( $match[1] );
			$text = str_replace("\r", '', $text );
			$text = preg_replace( "~\n\n+~s", "</p><p>", $text );

			$more_text = $more_text ? '<a class="kexc_moretext" href="'. get_permalink( $post->ID ) .'#more-'. $post->ID .'">'. $more_text .'</a>' : '';

			$text = '<p>'. str_replace( "\n", '<br />', $text ) .' '. $more_text .'</p>';

			if( $echo )
				return print $text;

			return $text;
		}
		elseif( ! $post->post_excerpt )
			$text = strip_tags( $text, $save_format );
	}

	// Обрезаем
	if ( mb_strlen( $text ) > $maxchar ){
		$text = mb_substr( $text, 0, $maxchar );
		$text = preg_replace('@(.*)\s[^\s]*$@s', '\\1...', $text ); // убираем последнее слово, оно 99% неполное
	}

	// Сохраняем переносы строк. Упрощенный аналог wpautop()
	if( $save_format ){
		$text = str_replace("\r", '', $text );
		$text = preg_replace("~\n\n+~", "</p><p>", $text );
		$text = "<p>". str_replace ("\n", "<br />", trim( $text ) ) ."</p>";
	}

	//$out = preg_replace('@\*[a-z0-9-_]{0,15}\*@', '', $out); // удалить *some_name-1* - фильтр сммайлов

	if( $echo ) return print $text;

	return $text;
}


if (!function_exists('vw_post_meta')):
	/**
	 * Prints HTML with meta information for the categories, tags.
	 */
	function vw_post_meta() {
		echo '<div class="post-meta-info"><div>';
		if ('post' == get_post_type()) {
			if (is_singular() || is_multi_author()) {
				printf('<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span>%2$s</span></span>, ',
					__('By ', 'ninjajournalist'),
					get_the_author()
				);
			}
		}

		if (in_array(get_post_type(), array('post', 'attachment'))) {
			$time_string = '<time class="entry-date" datetime="%1$s">Published on %2$s</time> ';

			if (get_the_time('U') !== get_the_modified_time('U')) {
				$time_string = '<time class="entry-date" datetime="%1$s">%2$s</time>';
			}

			$time_string = sprintf($time_string,
				esc_attr(get_the_date('c')),
				get_the_date('M j, Y')
			);

			echo $time_string;
		}



		// end of cm-post-meta
		echo '</div></div>';
	}
endif;

// post page title shortcode
add_shortcode("post_page_title", "vw_create_post_page_title");
if (!function_exists('vw_create_post_page_title')):
function vw_create_post_page_title ($attrs, $content = null) {
	return '<div class="page-title"><h2>'.$content.'</h2></div>';
}
	endif;

// remove some shortcode
//add_shortcode( 'sc:default_top_ad', '__return_false' );
//add_shortcode( 'sc:default_lower_ad', '__return_false' );
//add_shortcode( 'sc', '__return_false' );
//add_shortcode( 'sc:Miss_Universe_Makeup_Top_Ad', '__return_false' );
//add_shortcode( 'adsense', '__return_false' );
add_shortcode( 'cm-nav-shortcode', '__return_false' );


/* Alternative wp_link_pages
------------------------------------------------------------------------------ */
function custom_link_pages() {

	global $page, $numpages, $post;
	$paged = (int) $page;
	$max_page = $numpages;

	if($max_page <= 1 ) return false;

	if(empty($paged) || $paged == 0) $paged = 1;



	$out='';
	$out.= "<div class='wp-pagenavi clearfix'>\n";
	if ( $page === 1 ) {
		$l = _wp_link_page(($page+1));
		preg_match('/href=(["\'])([^\1]*)\1/i', $l, $a);
		$out.= '<a class="btn btn-nav-page btn-block" href="'.$a[2].'">' . 'Next <span class="arrow-right-post-1 arrow-post spr"></span>' .'</a>';
	}
	if ( $page === $max_page ) {
		preg_match('/href=(["\'])([^\1]*)\1/i', _wp_link_page(($page-1)), $a);
//		$out.= '<a class="btn btn-nav-page btn-block" href="'.$a[2].'">' . '<span class="arrow-left-post-1 arrow-post spr"></span> Prev' .'</a>';
		preg_match('/href=(["\'])([^\1]*)\1/i', _wp_link_page(($page-1)), $a);
		$out.= '<a class="btn btn-nav-page pull-left col-lg-4" href="'.$a[2].'">' . '<span class="arrow-left-post arrow-post spr"></span> Prev' .'</a>';
		preg_match('/href=(["\'])([^\1]*)\1/i', _wp_link_page(($page+1)), $a);
		$out.= '<a class="btn btn-nav-page pull-right col-lg-4" href="'.'/more-content'.'">' . 'Next <span class="arrow-right-post arrow-post spr"></span>' .'</a>';
	}
	if ( $page > 1 && $page < $max_page ) {
		preg_match('/href=(["\'])([^\1]*)\1/i', _wp_link_page(($page-1)), $a);
		$out.= '<a class="btn btn-nav-page pull-left col-lg-4" href="'.$a[2].'">' . '<span class="arrow-left-post arrow-post spr"></span> Prev' .'</a>';
		preg_match('/href=(["\'])([^\1]*)\1/i', _wp_link_page(($page+1)), $a);
		$out.= '<a class="btn btn-nav-page pull-right col-lg-4" href="'.$a[2].'">' . 'Next <span class="arrow-right-post arrow-post spr"></span>' .'</a>';
	}


	$out.= "</div>";

	return apply_filters( 'clp_pagination', $out, $page, $numpages, $post );
}

// register widgets
function register_my_widgets(){
	register_sidebar( array(
		'name' => 'Center Footer Widget',
		'id' => "center-footer-widget",
		'description' => '',
		'class' => '',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => "</h2>\n",
	) );
	register_sidebar( array(
		'name' => 'Place for 300x250 Ad',
		'id' => "ad-300x250",
		'description' => '',
		'class' => '',
		'before_widget' => '<div id="%1$s" class="%2$s widget tbis_ads hidden-xs">',
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => "</h2>\n",
	) );
	register_sidebar( array(
		'name' => 'Footer Right Widget',
		'id' => "footer-right-widget",
		'description' => '',
		'class' => '',
		'before_widget' => '<div id="%1$s" class="%2$s widget ft_ad hidden-xs">',
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => "</h2>\n",
	) );
	register_sidebar( array(
		'name' => sprintf(__('Aside %s'), 'Popular' ),
		'id' => "aside-popular",
		'description' => '',
		'class' => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s aside-popular">',
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => "</h2>\n",
	) );
	register_sidebar( array(
		'name'          => 'Below Post',
		'id'            => 'below_post_widget',
		'before_widget' => '<div class="cm_below_post_widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name' => 'Place for 728x90 Ad',
		'id' => "ad-728x90",
		'description' => '',
		'class' => '',
		'before_widget' => '<div id="%1$s" class="%2$s banner-widget-center"><div class="ads">',
		'after_widget' => "</div></div>\n",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => "</h2>\n",
	) );
}
add_action( 'widgets_init', 'register_my_widgets' );

//infinity scroll
function ninjajournalist_infinity_scroll(){
	$args = unserialize(stripslashes($_POST['query']));
	$args['paged'] = $_POST['page'] + 1; // next page
	$args['post_status'] = 'publish';
	// The Query
	$query_main_home = new WP_Query( $args );

	// The Loop
	if ( $query_main_home->have_posts() ) {
		while ( $query_main_home->have_posts() ) {
			$query_main_home->the_post();
			?>
			<?php
			if ( has_post_thumbnail() ) {
				$thumb_ = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'tw-theme-main-thumbnail' );
			} else {
				$thumb_ = array();
			} ?>
			<?php
			$post_not_in[]   = $post->ID;
			$category_detail = get_the_category( $post->ID );
			?>
			<div class="main_block_item ajax-loaded">
				<a href="<?php the_permalink(); ?>">
					<div class="mb_img_wrap">
						<div class="mb_img" style="background-image: url('<?php echo $thumb_[0]; ?>')"></div>
					</div>

					<h2 class="mb_head"><?php the_title(); ?></h2>
					<p class="mb_text"><?php kama_excerpt( 'maxchar=150' ); ?></p>
				</a>

			</div>
			<?php
		}
	} else {
		echo 'no posts found';
	}
	// Restore original Post Data
	wp_reset_postdata();
	die();
}


add_action('wp_ajax_loadmore', 'ninjajournalist_infinity_scroll');
add_action('wp_ajax_nopriv_loadmore', 'ninjajournalist_infinity_scroll');

// fix Responsive YouTube or Vimeo embed with Bootstrap or Roots.io in WordPress
function bootstrap_wrap_oembed( $html ){
	if ( preg_match ( '/class="instagram-media"/', $html ) ){
		return'<div class="embed-instagram-responsive">'.$html.'</div>';
	}
	$html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
	return'<div class="embed-responsive embed-responsive-16by9">'.$html.'</div>';
}
add_filter( 'embed_oembed_html','bootstrap_wrap_oembed',10,1);

/* check if user using smaller mobile device */
if ( ! function_exists( 'bz_wp_is_mobile' ) ) {
	function bz_wp_is_mobile() {
		if ( ! class_exists( 'Mobile_Detect' ) ) {
			include_once( TEMPLATE_DIRECTORY . '/lib/Mobile_Detect.php' );
			$detect = new Mobile_Detect;
		} else {
			$detect = new Mobile_Detect;
		}
		if ( $detect->isMobile() && ! $detect->isTablet() ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}


/* check if user using tablet device */
if ( ! function_exists( 'bz_wp_is_tablet' ) ) {
	function bz_wp_is_tablet() {
		if ( ! class_exists( 'Mobile_Detect' ) ) {
			include_once( TEMPLATE_DIRECTORY . '/lib/Mobile_Detect.php' );
			$detect = new Mobile_Detect;
		} else {
			$detect = new Mobile_Detect;
		}
		if ( $detect->isTablet() ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

add_filter('widget_text', 'do_shortcode');
