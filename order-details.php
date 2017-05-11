<?php
/**
 * Plugin Name: Order Item Details
 * Plugin URI: http://plife.se
 * Description: It shows order items on order page after woocommerce 3.0.x update
 * Version: 1.0
 * Author: Deniz
 * Author URI: http://plife.se
 * License: GPL2
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column',11);

function custom_shop_order_column($columns)
{
    ?>
    <style>
        .container {
            width:90%;
            border:1px solid #d3d3d3;
        }
        .container div {
            width:100%;
            text-align: center;
        }
        .container .header {
            background-color:#d3d3d3;
            padding: 2px;
            cursor: pointer;
            font-weight: bold;
        }
        .container .content {
            display: none;
            padding : 5px;
        }
        .order-details-cover {
            position:relative;
            display: flex;
            width: 95% !important;
            background-color: #f3f3f3;
            margin-bottom: 4px;
        }
        .order-details-inline {
            width: 30% !important;%;
            margin-bottom: 5px;
            margin-left: 5px;
        }
        .qntInline{
            margin-top: 10px;
        }
        .nameInline {
            width: 47% !important;
        }
    </style>
    <script>
        jQuery(document).ready(function($){
            $(".header").click(function () {
                $header = $(this);
                //getting the next element
                $content = $header.next();
                //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
                $content.slideToggle(100, function () {
                    //execute this after slideToggle is done
                    //change text of header based on visibility of content div
                    $header.text(function () {
                        //change text based on condition
                        return $content.is(":visible") ? "Collapse" : "Expand";
                    });
                });
            })
        })
    </script>
    <?php
    //add columns
    $columns['order-column1'] = __( 'Details','theme_slug');
    return $columns;
}

// adding the data for each orders by column (example)
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 10, 20 );
function custom_orders_list_column_content( $column )
{
    global $post, $woocommerce, $the_order;
    $order = new WC_Order($post->ID);

//to escape # from order id

    $order_id = trim(str_replace('#', '', $order->get_order_number()));
    //custom_order_option_cb($order_id);
    $order = new WC_Order( $order_id );
    $items = $order->get_items();
    $product_id=array();
    $product_name=array();
    $product_qnt=array();
    $product_parent=array();
    foreach ( $items as $item ) {
        $product_name[] = $item['name'];
        $product_id[] = $item['product_id'];
        $product_qnt[]=$item['qty'];
        if ( $meta_data = $item->get_formatted_meta_data( '' ) ) {
            foreach ( $meta_data as $meta_id => $meta ) {
                $product_parent[]=wp_kses_post( force_balance_tags( $meta->display_value ) );
            }
        }
    }
    switch ( $column )
    {
        case 'order-column1' :
            ?>
            <div class="container">
                <div class="header"><span>Expand</span>
                </div>
                <div class="content">
                    <?php
                       $i=0;
                       while($i<count($product_name)) {
                          echo '<div class="order-details-cover">';
                          echo '<div class="order-details-inline"><img width="50px" src="'.getImage($product_id[$i]).'"></div>';
                          echo '<div class="order-details-inline qntInline">'.$product_qnt[$i].'x</div>';
                          echo '<div class="order-details-inline nameInline">'.$product_name[$i].'</div>';
                          if($product_parent[$i]) {
                              echo '<div class="order-details-inline">'.$product_parent[$i].'</div>';
                          }
                          echo '</div>';
                          $i++;
                       }
                    ?>
                </div>
            </div>
            <?php
         break;
    }
}
function getImage($id) {
  return  wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' )[0];
}