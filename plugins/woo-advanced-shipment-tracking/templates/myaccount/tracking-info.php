<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * View Order: Tracking information
 *
 * Shows tracking numbers view order page
 *
 * @author  WooThemes
 * @package WooCommerce Shipment Tracking/templates/myaccount
 * @version 1.6.4
 */

if ( $tracking_items ) :
	$wcast_customizer_settings = new wcast_initialise_customizer_settings();
	$table_header_font_size = '';
	$table_header_font_color = '';
	$table_content_font_size = '';
	$table_content_font_color = '';
	$email_border_color = '';
	$email_border_size = '';
	$shipment_tracking_header_text = '';
	$email_table_backgroud_color = '';
	$tracking_link_font_color = '';
	$tracking_link_bg_color = '';
	$table_content_line_height = '';
	$table_content_font_weight = '';
	
	$ast = new WC_Advanced_Shipment_Tracking_Actions;
	
	$upload_dir   = wp_upload_dir();	
	$ast_directory = $upload_dir['baseurl'] . '/ast-shipping-providers/';
	$ast_base_directory = $upload_dir['basedir'] . '/ast-shipping-providers/';
	
	$tracking_info_settings = get_option('tracking_info_settings');
		
	$select_tracking_template = $ast->get_option_value_from_array('tracking_info_settings','select_tracking_template',$wcast_customizer_settings->defaults['select_tracking_template']);
	
	$show_provider_th = 1;
	$colspan = 1;
	
	$display_thumbnail = $ast->get_option_value_from_array('tracking_info_settings','display_shipment_provider_image','');

	$display_shipping_provider_name = $ast->get_option_value_from_array('tracking_info_settings','display_shipment_provider_name','');
	
	$tracking_number_link = $ast->get_option_value_from_array('tracking_info_settings','tracking_number_link','');
		
	if($display_shipping_provider_name == 1 && $display_thumbnail == 1){
		$show_provider_th = 1;
		$colspan = 2;
	} else if($display_shipping_provider_name != 1 && $display_thumbnail == 1){
		$show_provider_th = 1;
		$colspan = 1;
	} else if($display_shipping_provider_name == 1 && $display_thumbnail != 1){
		$show_provider_th = 1;
		$colspan = 1;
	} else if($display_shipping_provider_name != 1 && $display_thumbnail != 1){
		$show_provider_th = 0;
		$colspan = 1;
	} else{
		$show_provider_th = 0;		
	} 
	
	
	$email_border_color = $ast->get_option_value_from_array('tracking_info_settings','table_border_color',$wcast_customizer_settings->defaults['table_border_color']);
	
	if(get_theme_mod('table_border_size')){ $email_border_size = get_theme_mod('table_border_size'); } else{ $email_border_size = "1"; }
	
	$hide_trackig_header = $ast->get_option_value_from_array('tracking_info_settings','hide_trackig_header','');
	
	$shipment_tracking_header = $ast->get_option_value_from_array('tracking_info_settings','header_text_change','Tracking Information');
	
	$shipment_tracking_header_text = $ast->get_option_value_from_array('tracking_info_settings','additional_header_text','');
	
	$email_table_backgroud_color = $ast->get_option_value_from_array('tracking_info_settings','table_bg_color',$wcast_customizer_settings->defaults['table_bg_color']);
	
	$table_content_line_height = $ast->get_option_value_from_array('tracking_info_settings','table_content_line_height',$wcast_customizer_settings->defaults['table_content_line_height']);
	
	$table_content_font_weight = $ast->get_option_value_from_array('tracking_info_settings','table_content_font_weight',$wcast_customizer_settings->defaults['table_content_font_weight']);
		
	$table_header_font_size = $ast->get_option_value_from_array('tracking_info_settings','table_header_font_size',$wcast_customizer_settings->defaults['table_header_font_size']);
	
	$table_header_font_color = $ast->get_option_value_from_array('tracking_info_settings','table_header_font_color',$wcast_customizer_settings->defaults['table_header_font_color']);
	
	$table_content_font_size = $ast->get_option_value_from_array('tracking_info_settings','table_content_font_size',$wcast_customizer_settings->defaults['table_content_font_size']);
	
	$table_content_font_color = $ast->get_option_value_from_array('tracking_info_settings','table_content_font_color',$wcast_customizer_settings->defaults['table_content_font_color']);
	
	$tracking_link_font_color = $ast->get_option_value_from_array('tracking_info_settings','tracking_link_font_color',$wcast_customizer_settings->defaults['tracking_link_font_color']);
	
	$tracking_link_bg_color = $ast->get_option_value_from_array('tracking_info_settings','tracking_link_bg_color',$wcast_customizer_settings->defaults['tracking_link_bg_color']);
	
	$th_column_style = "text-align: center; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;font-size:".$table_header_font_size."px; color: ".$table_header_font_color." ; border: ".$email_border_size."px solid ".$email_border_color."; padding: 12px;";
	
	$td_column_style = "text-align: center; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size:".$table_content_font_size."px;font-weight:".$table_content_font_weight."; color: ".$table_content_font_color." ; border: ".$email_border_size."px solid ".$email_border_color."; padding: 12px;";
	
	$tracking_link_style = "color: ".$tracking_link_font_color." ;background:".$tracking_link_bg_color.";padding: 10px;";
	
	$hide_table_header = $ast->get_option_value_from_array('tracking_info_settings','hide_table_header','');
	
	$remove_date_from_tracking_info = $ast->get_option_value_from_array('tracking_info_settings','remove_date_from_tracking',$wcast_customizer_settings->defaults['remove_date_from_tracking']);
	
	$show_track_label = $ast->get_option_value_from_array('tracking_info_settings','show_track_label',$wcast_customizer_settings->defaults['show_track_label']);
	
	$provider_header_text = $ast->get_option_value_from_array('tracking_info_settings','provider_header_text',$wcast_customizer_settings->defaults['provider_header_text']);
	
	$tracking_number_header_text = $ast->get_option_value_from_array('tracking_info_settings','tracking_number_header_text',$wcast_customizer_settings->defaults['tracking_number_header_text']);
	
	$shipped_date_header_text = $ast->get_option_value_from_array('tracking_info_settings','shipped_date_header_text',$wcast_customizer_settings->defaults['shipped_date_header_text']);
	
	$track_header_text = $ast->get_option_value_from_array('tracking_info_settings','track_header_text',$wcast_customizer_settings->defaults['track_header_text']);
	
	$simple_layout_content = $ast->get_option_value_from_array('tracking_info_settings','simple_layout_content',$wcast_customizer_settings->defaults['simple_layout_content']);
	
	$simple_provider_font_size = $ast->get_option_value_from_array('tracking_info_settings','simple_provider_font_size',$wcast_customizer_settings->defaults['simple_provider_font_size']);
	
	$simple_provider_font_color = $ast->get_option_value_from_array('tracking_info_settings','simple_provider_font_color',$wcast_customizer_settings->defaults['simple_provider_font_color']);
	
	$show_provider_border = $ast->get_option_value_from_array('tracking_info_settings','show_provider_border',$wcast_customizer_settings->defaults['show_provider_border']);
	
	$provider_border_color = $ast->get_option_value_from_array('tracking_info_settings','provider_border_color',$wcast_customizer_settings->defaults['provider_border_color']);			
 ?>

	<?php if($hide_trackig_header != 1){ ?>
		<h2><?php echo apply_filters( 'woocommerce_shipment_tracking_my_orders_title', __( $shipment_tracking_header, 'woo-advanced-shipment-tracking' ) ); ?></h2>
	<?php } ?>
	
	<p><?php echo $shipment_tracking_header_text; ?></p>
	
	<?php if($select_tracking_template == 'simple_list'){ ?>
	<div class="tracking_info">
		<ul class="tracking_list">
			<?php foreach ( $tracking_items as $tracking_item ) {
			$date_shipped = date("Y-m-d");
			if(isset($tracking_item['date_shipped'])){
				$date_shipped = $tracking_item['date_shipped'];
			}	
			
			global $wpdb;
			$results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woo_shippment_provider WHERE ts_slug = %s", $tracking_item['tracking_provider'] ) );
			$tracking_provider = $results->provider_name;
			$custom_provider_name = $results->custom_provider_name;			
			
			if($custom_provider_name != NULL){
				$provider_name = $custom_provider_name;	
			} else{
				$provider_name = $tracking_provider;	
			}			
			
			$url = str_replace('%number%',$tracking_item['tracking_number'],$tracking_item['formatted_tracking_link']);	
			$simple_layout_content_updated = '';
			?>	
				<li class="tracking_list_li">
					<div class="tracking_list_div" style="font-size:<?php echo $simple_provider_font_size; ?>px;color:<?php echo $simple_provider_font_color; ?>;border-bottom:<?php echo $show_provider_border; ?>px solid <?php echo $provider_border_color; ?>">
						<?php 
						$formatted_tracking_provider = apply_filters( 'ast_provider_title', esc_html( $provider_name ));
						
						$simple_layout_content_updated = str_replace('{ship_date}',date_i18n( get_option( 'date_format' ), $date_shipped ),$simple_layout_content);
						
						$simple_layout_content_updated = str_replace('{shipping_provider}',$formatted_tracking_provider,$simple_layout_content_updated);
						
						$tracking_number_link = '<a target="_blank" href="'.esc_url( $url ).'">'.$tracking_item['tracking_number'].'</a>';
						
						$simple_layout_content_updated = str_replace('{tracking_number_link}',$tracking_number_link,$simple_layout_content_updated);
						
						echo $simple_layout_content_updated; ?>						
					</div>
					<?php do_action("ast_tracking_simple_list_email_body", $order_id,$tracking_item); ?>
				</li>
			<?php } ?>			
		</ul>
	</div>
	<?php } else{ ?>
	<table class="shop_table shop_table_responsive my_account_tracking" style="width: 100%;border-collapse: collapse;line-height:<?php echo $table_content_line_height; ?>px;background:<?php echo $email_table_backgroud_color; ?>">
		<?php if($hide_table_header != 1){ ?>
		<thead>
			<tr>
				<?php if($show_provider_th){ ?>
				<th class="tracking-provider" colspan="<?php echo $colspan; ?>" style="<?php echo $th_column_style; ?>">
					<?php _e( $provider_header_text, 'woo-advanced-shipment-tracking' ); ?>
				</th>
				<?php } ?>
				<?php do_action("ast_tracking_my_acoount_header", $order_id, $th_column_style); ?>
				<th class="" style="<?php echo $th_column_style; ?>"><?php _e( $tracking_number_header_text, 'woo-advanced-shipment-tracking' ); ?></th>
				<?php if($remove_date_from_tracking_info != 1){ ?>
				<th class="date-shipped" style="<?php echo $th_column_style; ?>"><?php _e( $shipped_date_header_text, 'woo-advanced-shipment-tracking' ); ?></th>
				<?php } 
				if(!$tracking_number_link){
				?>
				<th class="order-actions" style="<?php echo $th_column_style; ?>"><?php if($show_track_label == 1) { _e( $track_header_text, 'woo-advanced-shipment-tracking' ); }?></th>
				<?php } ?>
			</tr>
		</thead>
		<?php } ?>
		<tbody><?php
		foreach ( $tracking_items as $tracking_item ) {							
				global $wpdb;
				$results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woo_shippment_provider WHERE ts_slug = %s", $tracking_item['tracking_provider'] ) );
				$tracking_provider = $results->provider_name;
				$custom_provider_name = $results->custom_provider_name;			
				
				if($custom_provider_name != NULL){
					$provider_name = $custom_provider_name;	
				} else{
					$provider_name = $tracking_provider;	
				}
		
				?><tr class="tracking">
					<?php if($display_thumbnail == 1){ ?>
						<td class="tracking-provider" style="<?php echo $td_column_style; ?>" data-title="<?php _e( 'Provider', 'woo-advanced-shipment-tracking' ); ?>">
							<?php 						
								global $wpdb;		
								$woo_shippment_table_name = wc_advanced_shipment_tracking()->table;	
								$shippment_provider = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $woo_shippment_table_name WHERE provider_name=%s",$tracking_item['formatted_tracking_provider'] ) );
								$custom_thumb_id = $shippment_provider['0']->custom_thumb_id;								
								if($custom_thumb_id == 0 && $shippment_provider['0']->shipping_default == 1){
									if(!file_exists($ast_base_directory.''.sanitize_title($tracking_item['formatted_tracking_provider']).'.png')){
										$src = wc_advanced_shipment_tracking()->plugin_dir_url().'assets/shipment-provider-img/'.sanitize_title($tracking_item['formatted_tracking_provider']).'.png?v='.wc_advanced_shipment_tracking()->version;
									} else{
										$src = $ast_directory.''.sanitize_title($tracking_item['formatted_tracking_provider']).'.png?v='.wc_advanced_shipment_tracking()->version;
									}
								} else{
									$image_attributes = wp_get_attachment_image_src( $custom_thumb_id , array('60','60') );
									if($image_attributes[0]){
										$src = $image_attributes[0];	
									} else{
										$src = wc_advanced_shipment_tracking()->plugin_dir_url()."assets/images/icon-default.png";	
									}							
								}						
							?>
							<img style="width: 50px;margin-right: 5px;vertical-align: middle;" src="<?php echo $src; ?>">
						</td>
					<?php } ?>
					<?php if($display_shipping_provider_name == 1){ ?>
					<td class="tracking-provider" style="<?php echo $td_column_style; ?>" data-title="<?php _e( 'Provider Name', 'woo-advanced-shipment-tracking' ); ?>">
						<?php echo apply_filters( 'ast_provider_title', esc_html( $provider_name )); ?>
					</td>
					<?php } ?>
					<?php do_action("ast_tracking_my_account_body", $order_id,$tracking_item, $td_column_style); ?>
					<td class="tracking-number" style="<?php echo $td_column_style; ?>" data-title="<?php _e( 'Tracking Number', 'woo-advanced-shipment-tracking' ); ?>">
						<?php if('' !== $tracking_item['formatted_tracking_link'] && $tracking_number_link){
							$url = str_replace('%number%',$tracking_item['tracking_number'],$tracking_item['formatted_tracking_link']);
							?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" style="<?php echo $tracking_link_style; ?>"><?php echo esc_html( $tracking_item['tracking_number'] ); ?></a>
							<?php
						} else{
							 echo esc_html( $tracking_item['tracking_number'] );	
						} 
						 ?>
					</td>
					<?php if($remove_date_from_tracking_info != 1){ ?>
					<td class="date-shipped" style="<?php echo $td_column_style; ?>" data-title="<?php _e( 'Date', 'woocommerce' ); ?>" style="text-align:left; white-space:nowrap;">
						<time datetime="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>" title="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>"><?php echo date_i18n( get_option( 'date_format' ), $tracking_item['date_shipped'] ); ?></time>
					</td>
					<?php } if(!$tracking_number_link){ ?>
					<td class="order-actions" style="<?php echo $td_column_style; ?>;text-align:center;">
							<?php if ( '' !== $tracking_item['formatted_tracking_link'] ) { 
							$url = str_replace('%number%',$tracking_item['tracking_number'],$tracking_item['formatted_tracking_link']);
							?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" class="button" style="<?php echo $tracking_link_style; ?>"><?php _e( 'Track', 'woo-advanced-shipment-tracking' ); ?></a>
							<?php } ?>
					</td>
					<?php } ?>
				</tr><?php
		}
		?></tbody>
	</table>
<?php
	}
endif;