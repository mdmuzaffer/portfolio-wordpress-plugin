<?php
/**
 * @package Portfolio
 */
/*
Plugin Name: Muzaffer portfolio
Plugin URI: https://test.com/
Description: Used by millions, portfolio is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: activate the Portfolio plugin and then go to your Portfolio Settings page to set up your API key.
Version: 4.1.9
Author: Automattic
Author URI: https://automattic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: Portfolio
*/

//stop to update plugin
function disable_plugin_updates( $value ) {
   unset( $value->response['portfolio/portfolio.php'] );
   return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

define( 'PORTFOLIO_VERSION', '1.1.0' );
define( 'PORTFOLIO_MINIMUM_WP_VERSION', '4.0' );
define( 'PORTFOLIO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PORTFOLIO__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PORTFOLIO_DELETE_LIMIT', 100000 );

//Create table hooks
register_activation_hook( __FILE__, 'create_portfolio_database_table' );
// delete database when plugin deactive
register_deactivation_hook( __FILE__, 'portfolio_remove_database' );

// Add css and js file 
function portfolio_enqueue_scripts() {
	wp_enqueue_style( 'bootstrapmainCSS', PORTFOLIO__PLUGIN_URL. 'asset/css/bootstrap.min.css',array(),'all', null, true);
    wp_enqueue_script( 'bootstrapmainJS', PORTFOLIO__PLUGIN_URL. 'asset/js/bootstrap.min.js',array(),'all', null, true);
    wp_enqueue_script( 'frontendAjax', PORTFOLIO__PLUGIN_URL. 'asset/js/jquery.min.js',array(),'all', null, true);
    wp_enqueue_script( 'portfolio_ajax', PORTFOLIO__PLUGIN_URL. 'asset/js/developer.js', null, false);
    wp_localize_script( 'portfolio_ajax', 'portfolio_ajax_object',
        array( 
            'adminajaxUrl' => admin_url( 'admin-ajax.php' ),
            'author' => 'Muzaffer',
            'name' => 'Tahridh',
        )
    );
    wp_enqueue_media();
}
add_action( 'admin_print_styles', 'portfolio_enqueue_scripts' );



//added menu page title
add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
add_menu_page('Portfolio', 'Portfolio', 'manage_options','portfolio-list','portfolio_list','dashicons-format-gallery', 10);   
  
add_submenu_page(
    'portfolio-list',               // parent slug
    'Portfolio List',              // page title
    'Portfolio List',             // menu title
    'manage_options',            // capability
    'portfolio-list',           // slug
    'portfolio_list' 			// callback
); 


add_submenu_page(
    'portfolio-list',               // parent slug
    'Category',                     // page title
    'Create category',                    // menu title
    'manage_options',             // capability
    'portfolio-category',         // slug
    'create_category'             // callback
); 
 
add_submenu_page(
    'portfolio-list',          // parent slug
    'Gallery',               // page title
    'Upload images',               // menu title
    'manage_options',              // capability
    'portfolio-gallery',   // slug
    'upload_images'        // callback
);
add_submenu_page(
    'portfolio-list',          // parent slug
    'shortcode',               // page title
    'short Code',               // menu title
    'manage_options',              // capability
    'portfolio-shortcode',   // slug
    'shortcode'        // callback
);

add_submenu_page(
	null,          // parent slug
    'delete page',               // page title
    'delete page',               // menu title
    'manage_options',              // capability
    'portfolio-delete',   // slug
    'portfolio_delete'        // callback
);

}


 function portfolio_list(){
 	global $wpdb;
	
	$pending_reservations = "SELECT `wp_portfolio_images`.id, `wp_portfolio_images`.category_id, `wp_portfolio_images`.images, wp_portfolio_images.status, `wp_portfolio_category`.category FROM `wp_portfolio_images` INNER JOIN `wp_portfolio_category` ON `wp_portfolio_images`.category_id = `wp_portfolio_category`.id";
	$portfolioData = $wpdb->get_results($pending_reservations);
   ob_start();
	include( PORTFOLIO__PLUGIN_DIR . 'template/portfolio-list.php');
	$temp_content = ob_get_contents();
	ob_end_clean();
	echo $temp_content;
}


function create_category(){
	
	global $wpdb;
 	$table = $wpdb->prefix . 'portfolio_category';
 	$category = $wpdb->get_results($wpdb->prepare("select id, category,status,created_at from ". $table, ""));

	include( PORTFOLIO__PLUGIN_DIR . 'template/portfolio-category.php');
}

function upload_images(){
	global $wpdb;
 	$table = $wpdb->prefix . 'portfolio_category';
 	$category = $wpdb->get_results($wpdb->prepare("select id, category from ". $table, ""));
 	/*echo"<pre>";
 	print_r($category);
 	die;*/
 	ob_start();
	include( PORTFOLIO__PLUGIN_DIR . 'template/portfolio-image.php');
	$temp_content = ob_get_contents();
	ob_end_clean();
	echo $temp_content;
}


//Ajax request function

function saveCategory() {
    $_POST['category_name'];

    //print_r($_REQUEST);
    $category = isset($_REQUEST['category_name']) ? $_REQUEST['category_name'] : "";

    global $wpdb;
 	$table = $wpdb->prefix . 'portfolio_category';
	$wpdb->insert($table, array(
		'category' => $category, 
		'status' =>1
	));

	if($wpdb->insert_id >0){

		echo json_encode(array(
			"status" =>1,
			"message"=>"Your category created successfully"
		));
	}else{

	echo json_encode(array(
			"status" =>0,
			"message"=>"Your category fail to create"
		));
	}

    wp_die();
} 
add_action( 'wp_ajax_save_category', 'saveCategory' );


// Save category images

function saveCategoryImages() {
 	$category = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : "";
	$image = isset($_REQUEST['image']) ? $_REQUEST['image'] : "";


  	global $wpdb;
 	$table = $wpdb->prefix . 'portfolio_images';

 	$rowcount = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE category_id = '$category'"));
 	$count = count( $rowcount );
 	$dbimages = $rowcount[0]->images;

if($count >0){
	$dbimage = $dbimages.$image;
	$update1 = $wpdb->query($wpdb->prepare("UPDATE $table SET images='$dbimage' WHERE category_id= '$category'"));
	echo json_encode(array(
				"status" =>1,
				"message"=>"Your category images update successfully"
			));

}else{

		$wpdb->insert($table, array(
			'category_id' => $category, 
			'images' => $image, 
			'status' =>1
		));

		if($wpdb->insert_id >0){

			echo json_encode(array(
				"status" =>1,
				"message"=>"Your category images saved successfully"
			));
		}else{

		echo json_encode(array(
				"status" =>0,
				"message"=>"Your category image fail to save"
			));
		}
	}

wp_die();
}
add_action( 'wp_ajax_save_category_images', 'saveCategoryImages' );


//Short code


 function portfolio_plugin_front($atts) {
 	global $wpdb;
 	$pending_reservations = "SELECT `wp_portfolio_images`.id, `wp_portfolio_images`.category_id, `wp_portfolio_images`.images, wp_portfolio_images.status, `wp_portfolio_category`.category FROM `wp_portfolio_images` INNER JOIN `wp_portfolio_category` ON `wp_portfolio_images`.category_id = `wp_portfolio_category`.id";
	$portfolioData = $wpdb->get_results($pending_reservations);
	
 $content ='<div class="container">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <h2>Dynamic Pills</h2>

  <ul class="nav nav-pills">';
  	$count=0;
    foreach ($portfolioData as $key => $cat) {
    	if($count == 0){
    	$content.= '<li class="active"><a data-toggle="pill" href="#'.$cat->category.'">'.$cat->category.'</a></li>';
    }else{

    	$content.= '<li><a data-toggle="pill" href="#'.$cat->category.'">'.$cat->category.'</a></li>';
    }
    	$count++;
    
    }

    $content.= '</ul><div class="tab-content">';
 
	 $count=0;
     foreach ($portfolioData as $key => $cat) {

     	$last_pos = strripos("$cat->images","@");
     	$all_iamges = substr($cat->images, 0, $last_pos);
      	$images = explode("@",$all_iamges);
      if($count == 0){
      $content.='<div id="'.$cat->category.'" class="tab-pane fade active in"><h3>'.$cat->category.'</h3>';
     }else{
     	$content.='<div id="'.$cat->category.'" class="tab-pane fade"><h3>'.$cat->category.'</h3>';
     }

      foreach ( $images as $imagesval ) {
      $content.= '<p><img style="height:100px; width:100px;float:left;padding: 10px;" src="'.$imagesval.'"></p>';
      	}
      	$content.='</div>';
      	$count++;
  	}
	 
    return $content;
}

add_shortcode('portfolio-front', 'portfolio_plugin_front');

function shortcode(){
	include( PORTFOLIO__PLUGIN_DIR . 'template/shortcode.php');
}

function portfolio_delete(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'portfolio_category';
	$id = $_GET['id'];
	$wpdb->query( "DELETE  FROM {$table_name} WHERE id = '{$id}'" );
	create_category();
}


//Create table when Plugin active
function create_portfolio_database_table() {
 global $wpdb;
 $table_name = $wpdb->prefix . 'portfolio_category';
 $table_image = $wpdb->prefix . 'portfolio_images';

	if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {
	 $sql = "CREATE TABLE $table_name (
	  `id` int(150) NOT NULL AUTO_INCREMENT,
	 `category` varchar(100) NOT NULL,
	 `status` tinyint(11) NOT NULL,
	 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
	 `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	 dbDelta( $sql );

	}

	if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_image}'") != $table_image ) {

	$sql2 = "CREATE TABLE $table_image (
		 `id` int(150) NOT NULL AUTO_INCREMENT,
		 `category_id` int(100) NOT NULL,
		 `images` text NOT NULL,
		 `status` tinyint(11) NOT NULL,
		 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
		 `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
		 PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	 	dbDelta( $sql2 );

		}
}


function portfolio_remove_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'portfolio_category';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);

     $table_image = $wpdb->prefix . 'portfolio_images';
     $sql2 = "DROP TABLE IF EXISTS $table_image";
     $wpdb->query($sql2);
} 
