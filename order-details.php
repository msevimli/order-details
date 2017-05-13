<?php
/**
 * Plugin Name: Woocommerce Order Item Shower
 * Plugin URI: http://plife.se
 * Description: It shows order items on order page after woocommerce 3.0.x update
 * Version: 1.1
 * Author: Deniz
 * Author URI: http://plife.se
 * License: GPL2
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
include_once( plugin_dir_path( __FILE__ ) . 'inc/wc-order-details.php');

add_action( 'admin_enqueue_scripts', 'orderItemShowerLoadFiles' );
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column',11);
add_action( 'manage_shop_order_posts_custom_column' , 'setDetails', 10, 20 );

function custom_shop_order_column($columns) {
     $columns['order-column1'] = __( 'Details','theme_slug');
     return $columns;
}
function setDetails ($columns) {
     $orderItemShower=new wcOrderItemShowerClass();
     $orderItemShower->custom_orders_list_column_content($columns);
}
function orderItemShowerLoadFiles() {
     wp_register_script( 'orderItemShowerJs', plugins_url('js/jslib.js', __FILE__), array('jquery'));
     wp_enqueue_script( 'orderItemShowerJs' );
     wp_register_style( 'orderItemShowerCss', plugins_url('css/style.css',__FILE__) );
     wp_enqueue_style( 'orderItemShowerCss' );
}