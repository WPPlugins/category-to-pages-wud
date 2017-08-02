<?php
/* 
=== Category to Pages WUD Options ===
=> Excerpt for page
=> Cat/Tag in title
=> Cat/Tag in post

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//==============================================================================//


//If 'Use excerpts for pages:' is activated
function my_add_excerpts_to_pages() {
	if(get_option('cattopage_wud_exp_yes')==1){
		add_post_type_support( 'page', 'excerpt' );
	}
}

//If 'Use excerpts for pages:' and if is archive page and if is pages
function cattopage_change_to_excerpt($content) {
	global $post, $excerpt;
	if ( is_archive() && get_option('cattopage_wud_exp_yes')==1 && $post->post_type =="page" ) {
		//Unique page excerpt
		if( $post->post_excerpt && post_type_supports( 'page', 'excerpt' )) {
			$ctp_excerpt = $post->post_excerpt;
			$pattern = '~http(s)?://[^\s]*~i';
			$content = preg_replace($pattern, '', $ctp_excerpt);			
		}
		//Make excerpt from content
		else{
			$ctp_excerpt = strip_shortcodes ( wp_trim_words ( $content, get_option('cattopage_wud_exp_lenght') ) );
			$pattern = '~http(s)?://[^\s]*~i';
			$content = preg_replace($pattern, '', $ctp_excerpt);		
		}
	}
	return $content;
}

//Show Category and ord tag title on pages IN TITLE
function cattopage_wud_titles( $title , $id = null ) {
		global $post;
	
	$cats_title = NULL;
	$tags_title = NULL;
	//Font Size
	$sizect = get_option('cattopage_wud_title_size');
	if(empty($sizect)){$sizect="12";}
	//Line Size
	$sizel=$sizect+1;
	//Font Family
	$fontct = get_option('cattopage_wud_title_font');
	if(empty($fontct)){$fontct="inherit";}

	if(!empty($post)){	
	//If UNIQUE Categories and Tags
		if(get_option('cattopage_wud_unique')=="1"){
				if(get_option('cattopage_wud_cat')=="page"){
					$cats_title = 	get_the_term_list( $post->ID, 'categories', '', ', ' );
				}
				if(get_option('cattopage_wud_tag')=="page"){
					$tags_title = 	get_the_term_list( $post->ID, 'tags', '', ', ' );
				}
		}
	//If WordPress Categories and Tags
		else{	
				if(get_option('cattopage_wud_cat')=="page"){
					$cats_title = 	get_the_term_list( $post->ID, 'category', '', ', ' );
				}
				if(get_option('cattopage_wud_tag')=="page"){
					$tags_title = 	get_the_term_list( $post->ID, 'post_tag', '', ', ' );
				}					
		}
	}	
	//If nothing is in the loop ... return
    if(!in_the_loop()){return $title;}

	//If Oké, display the Title('s)
     if(is_page() && get_option('cattopage_wud_title')=='page' ){
			if(!empty($cats_title)){
				$cats_title = "<p class='ctp-wud-title' style= 'font-family:".$fontct."; font-size: ".$sizect."px; line-height: ".$sizel."px; margin: 0px; margin-top: 4px;'><span class='wudicon wudicon-category' style='font-size: ".$sizect."px;'> </span>".$cats_title."</p>";
			}
			if(!empty($tags_title)){
				$tags_title = "<p class='ctp-wud-title' style= 'font-family:".$fontct."; font-size: ".$sizect."px; line-height: ".$sizel."px; margin: 0px; margin-top: 4px;'><span class='wudicon wudicon-tag' style='font-size: ".$sizect."px;'> </span>".$tags_title."</p>";
			}
		//Build the new Title ...
		$title .= $cats_title.$tags_title;
	} 
    return $title;
}

//Show Category and ord tag title on pages ON TOP OF THE POST
function cattopage_wud_titles_in_page($content) {	
   if(is_page()) {	   
 
		global $post;
	
	$cats_title = NULL;
	$tags_title = NULL;
	//Font Size
	$sizect = get_option('cattopage_wud_title_size');
	if(empty($sizect)){$sizect="12";}
	//Line Size
	$sizel=$sizect+1;
	//Font Family
	$fontct = get_option('cattopage_wud_title_font');
	if(empty($fontct)){$fontct="inherit";}

	if(!empty($post)){	
	//If UNIQUE Categories and Tags
		if(get_option('cattopage_wud_unique')=="1"){
				if(get_option('cattopage_wud_cat')=="page"){
					$cats_title = 	get_the_term_list( $post->ID, 'categories', '', ', ' );
				}
				if(get_option('cattopage_wud_tag')=="page"){
					$tags_title = 	get_the_term_list( $post->ID, 'tags', '', ', ' );
				}
		}
	//If WordPress Categories and Tags
		else{	
				if(get_option('cattopage_wud_cat')=="page"){
					$cats_title = 	get_the_term_list( $post->ID, 'category', '', ', ' );
				}
				if(get_option('cattopage_wud_tag')=="page"){
					$tags_title = 	get_the_term_list( $post->ID, 'post_tag', '', ', ' );
				}					
		}
	}	

	//If Oké, display the Title('s)
     if(is_page() && get_option('cattopage_wud_title')=='page' ){
			if(!empty($cats_title)){
				$cats_title = "<p class='ctp-wud-title' style= 'font-family:".$fontct."; font-size: ".$sizect."px; line-height: ".$sizel."px; margin: 0px; margin-top: 4px;'><span class='wudicon wudicon-category' style='font-size: ".$sizect."px;'> </span>".$cats_title."</p>";
			}
			if(!empty($tags_title)){
				$tags_title = "<p class='ctp-wud-title' style= 'font-family:".$fontct."; font-size: ".$sizect."px; line-height: ".$sizel."px; margin: 0px; margin-top: 4px;'><span class='wudicon wudicon-tag' style='font-size: ".$sizect."px;'> </span>".$tags_title."</p>";
			}
		//Build the new Title ...
		$catstags = '<div style="margin-bottom:20px;">'.$cats_title.$tags_title.'</div>';
	}
	  $content = $catstags.$content;
   }
   return $content;
}

?>
