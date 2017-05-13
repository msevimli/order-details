<?php
class wcOrderItemShowerClass {

  public function __construct() {
   	return;       
  }
  function custom_orders_list_column_content($column) {
    global $post, $woocommerce, $the_order;
    $order = new WC_Order($post->ID);
 	$order_id = trim(str_replace('#', '', $order->get_order_number()));
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
                <div class="header" data="<?php echo array_sum($product_qnt); ?>"><span>Expand <span class="qntOrder">( 	<?php echo array_sum($product_qnt); ?> )</span></span></div>
                 <div class="content">
           		    <?php
       		        	$i=0;
                        while($i<count($product_name)) {
           		        	echo '<div class="order-details-cover">';
           		        	echo '<div class="order-details-inline orderImg"><div class="orderDetailsImgCover"><img width="50px" src="'.$this->getImageOrderDetails($product_id[$i]).'"></div></div>';
           			        echo '<div class="order-details-inline qntInline">'.$product_qnt[$i].'x</div>';
           			        echo '<div class="order-details-inline nameInline">'.$product_name[$i].'</div>';
           		        	if($i<count($product_parent)) {
           			            echo '<div class="order-details-inline parentItem">'.$product_parent[$i].'</div>';
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
 	function getImageOrderDetails($id) {
    	if(wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' )[0]) {
        	return  wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' )[0];
    	}
    	else {
        	return null;
    	}
	}	
}