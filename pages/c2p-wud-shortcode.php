<?php
/* 
=== Category to Pages WUD shortcodes===
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//==============================================================================//
	
//Shortcode to show categories anywhere LIST
if(!function_exists('cattopage_short_code_cat_list')){
	function cattopage_short_code_cat_list($atts) {
		$ctp_show = "categories";
		if(get_option('cattopage_wud_unique')=="0"){
			$ctp_show = "category";
		}
		$ctp_title = get_option('cattopage_wud_widget_title1');
		$result = NULL;
		
		$categories = get_categories( array(
			'orderby' => 'name',
			'order'   => 'ASC',
			'post_type' => array('page','post'),
			'taxonomy' => $ctp_show
		) );
		$result .= "<strong>".$ctp_title."</strong>";
		foreach( $categories as $category ) {
			$category_link = sprintf( 
				'<a href="%1$s" alt="%2$s">%3$s</a>',
				esc_url( get_category_link( $category->term_id ) ),
				esc_attr( sprintf( '%s', $category->name ) ),
				esc_html( $category->name )
			);		 
			$result .= '<br>' . sprintf( '%s', $category_link ) . ' ('. $category->count.') ';
		} 
		return $result."<br><br>";
	}
}
//Shortcode to show categories anywhere DROP
if(!function_exists('cattopage_short_code_cat_drop')){
	function cattopage_short_code_cat_drop($atts) {
		$ctp_show = "categories";
		if(get_option('cattopage_wud_unique')=="0"){
			$ctp_show = "category";
		}	
		$ctp_title = get_option('cattopage_wud_widget_title1');
		$result = NULL;
		
		$result .= "<strong>".$ctp_title."</strong><br>";	
		$result .= '<select name="event-dropdown" onchange=\'document.location.href=this.options[this.selectedIndex].value;\'>'; 
			$categories = get_categories( array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'post_type' => array('page','post'),
				'taxonomy' => $ctp_show
			) ); 
			foreach ($categories as $category) {
				$result .= '<option value="'.get_option('home').'/categories/'.$category->slug.'">';
				$result .= $category->cat_name;
				$result .= ' ('.$category->category_count.')';
				$result .= '</option>';
			}

		$result .= '</select>';
		return $result."<br><br>";
	}
}

//Shortcode to show tags anywhere LIST
if(!function_exists('cattopage_short_code_tag_list')){
	function cattopage_short_code_tag_list($atts) {
		$ctp_show = "tags";
		if(get_option('cattopage_wud_unique')=="0"){
			$ctp_show = "post_tag";
		}	
		$ctp_title = get_option('cattopage_wud_widget_title2');
		$result = NULL;
		
		$categories = get_categories( array(
			'orderby' => 'name',
			'order'   => 'ASC',
			'post_type' => array('page','post'),
			'taxonomy' => $ctp_show
		) );
		$result .= "<strong>".$ctp_title."</strong>";
		foreach( $categories as $category ) {
			$category_link = sprintf( 
				'<a href="%1$s" alt="%2$s">%3$s</a>',
				esc_url( get_category_link( $category->term_id ) ),
				esc_attr( sprintf( '%s', $category->name ) ),
				esc_html( $category->name )
			);		 
			$result .= '<br>' . sprintf( '%s', $category_link ) . ' ('. $category->count.') ';
		} 
		return $result."<br><br>";
	}
}

//Shortcode to show tags anywhere DROP
if(!function_exists('cattopage_short_code_tag_drop')){
	function cattopage_short_code_tag_drop($atts) {
		$ctp_show = "tags";
		if(get_option('cattopage_wud_unique')=="0"){
			$ctp_show = "post_tag";
		}	
		$ctp_title = get_option('cattopage_wud_widget_title2');
		$result = NULL;

		$result .= "<strong>".$ctp_title."</strong><br>";	
		$result .= '<select name="event-dropdown" onchange=\'document.location.href=this.options[this.selectedIndex].value;\'>'; 
			$categories = get_categories( array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'post_type' => array('page','post'),
				'taxonomy' => $ctp_show
			) ); 
			foreach ($categories as $category) {
				$result .= '<option value="'.get_option('home').'/tags/'.$category->slug.'">';
				$result .= $category->cat_name;
				$result .= ' ('.$category->category_count.')';
				$result .= '</option>';
			}

		$result .= '</select>';
		return $result."<br><br>";	
	}
}

?>
