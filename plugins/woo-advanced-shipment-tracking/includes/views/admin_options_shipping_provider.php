<?php
/**
 * html code for shipping providers tab
 */
?>
<?php $wc_ast_api_key = get_option('wc_ast_api_key'); 
$upload_dir   = wp_upload_dir();	
$ast_directory = $upload_dir['baseurl'] . '/ast-shipping-providers/';
if(isset($_GET['open']) && $_GET['open'] == 'synch_providers'){ ?>
	<script>
		jQuery( document ).ready(function() {	
			jQuery('.sync_provider_popup').show();
		});
	</script>
<?php }
?>
<section id="content1" class="tab_section">
	<div class="d_table" style="">
		<div class="tab_inner_container">
			<div class="provider_top">	
				<div class="status_filter">
					<a href="javaScript:void(0);" data-status="active" class="active"><?php _e( 'Active', 'woo-advanced-shipment-tracking'); ?></a>
					<a href="javaScript:void(0);" data-status="inactive"><?php _e( 'Inactive', 'woo-advanced-shipment-tracking'); ?></a>
					<a href="javaScript:void(0);" data-status="custom"><?php _e( 'Custom', 'woo-advanced-shipment-tracking'); ?></a>
					<a href="javaScript:void(0);" data-status="all"><?php _e( 'All', 'woocommerce'); ?></a>
				</div>										
				
				<div class="provider_settings dropdown">
					<span class="dashicons dashicons-admin-generic dropdown_menu"></span>			
				</div>
				
				<div class="search_section">
					<span class="dashicons dashicons-search search-icon"></span>
					<input class="provider_search_bar " type="text" name="search_provider" id="search_provider" placeholder="<?php _e( 'Search by provider / country', 'woo-advanced-shipment-tracking'); ?>">		
				</div>
				
				<ul class="dropdown-content">
					<li><a href="javaScript:void(0);" class="add_custom_provider" id="add-custom">Add Custom Provider</a></li>				
					<li><a href="javaScript:void(0);" class="sync_providers">Sync Provider List</a></li>				
					<li>Reset <a href="javaScript:void(0);" class="reset_active">active</a> | <a href="javaScript:void(0);" class="reset_inactive">inactive</a></li>				
				</ul>						
			</div>		
			<div class="provider_list">	
			<?php if($default_shippment_providers){ 
				echo $this->get_provider_html($default_shippment_providers,'active');
			} else{ $status = 'active'; ?>
				<h3><?php echo sprintf(__("You don't have any %s shipping providers.", 'woo-advanced-shipment-tracking'), $status); ?></h3>
			<?php } ?>					
			</div>
			<div id="" class="popupwrapper add_provider_popup" style="display:none;">
				<div class="popuprow">
					<span class="popupclose_btn dashicons dashicons-no-alt"></span>
					<h3 class="popup_title"><?php _e( 'Add Custom Shipping Provider', 'woo-advanced-shipment-tracking'); ?></h2>
					<form id="add_provider_form" method="POST" class="add_provider_form">
						<div>
							<input type="text" name="shipping_provider" class="shipping_provider" placeholder="Shipping Provider">
						</div>
						<div>
							<select class="select wcast_shipping_country shipping_country" name="shipping_country">
								<option value=""><?php _e( 'Shipping Country', 'woo-advanced-shipment-tracking' ); ?></option>
								<option value="Global"><?php _e( 'Global', 'woo-advanced-shipment-tracking' ); ?></option>
								<?php 
									foreach($countries as $key=>$val){ ?>
										<option value="<?php echo $key; ?>" ><?php _e( $val, 'woo-advanced-shipment-tracking'); ?></option>
									<?php } ?>
							</select>
						</div>
						<div>
							<input type='text' placeholder='Image' name='thumb_url' class='image_path thumb_url' value=''>
							<input type='hidden' name='thumb_id' class='image_id thumb_id' placeholder="Image" value='' style="">
							<input type="button" class="button upload_image_button" value="<?php _e( 'Upload' , 'woo-advanced-shipment-tracking'); ?>" />
						</div>
						<div>
							<input type="text" name="tracking_url" class="tracking_url" placeholder="Tracking URL">
						</div>
						<div class="custom_provider_instruction">
							<p><?php _e( 'You can use the variables %number%, %postal_code% and %country_code% in the URL, for more info, check our ', 'woo-advanced-shipment-tracking' ); ?><?php echo sprintf(__('<a href="%s" target="blank">documentation</a>', 'woo-advanced-shipment-tracking'), 'http://www.zorem.com/docs/woocommerce-advanced-shipment-tracking/setting-shipping-providers/#adding-custom-shipping-provider'); ?></p>
						</div>
						<p>		
							<input type="hidden" name="action" value="add_custom_shipment_provider">
							<input type="submit" name="Submit" value="Submit" class="button-primary btn_ast2 btn_large">        
						</p>			
					</form>
				</div>
				<div class="popupclose"></div>
			</div>
			
			<div id="" class="popupwrapper edit_provider_popup" style="display:none;">			
				<div class="popuprow">
					<span class="popupclose_btn dashicons dashicons-no-alt"></span>
					<h3 class="popup_title"><?php _e( 'Edit Shipping Provider', 'woo-advanced-shipment-tracking'); ?></h3>
					<p class="edit_provider_msg" style="display:none;"><?php _e( 'The custom name will display in the tracking info section on the customer order emails, my-account, shipment tracking page and shipment status emails.', 'woo-advanced-shipment-tracking' ); ?></p>
					<form id="edit_provider_form" method="POST" class="edit_provider_form">
						<div>
							<input type="text" name="shipping_provider" class="shipping_provider" value="" placeholder="<?php _e( 'Custom display name', 'woo-advanced-shipment-tracking' ); ?>">
						</div>
						<div>
							<select class="select wcast_shipping_country shipping_country" name="shipping_country">
								<option value=""><?php _e( 'Shipping Country', 'woo-advanced-shipment-tracking' ); ?></option>
								<option value="Global"><?php _e( 'Global', 'woo-advanced-shipment-tracking' ); ?></option>
								<?php 
									foreach($countries as $key=>$val){ ?>
										<option value="<?php echo $key; ?>" ><?php _e( $val, 'woo-advanced-shipment-tracking'); ?></option>
									<?php } ?>
							</select>
						</div>
						<div>
							<input type='text' placeholder='Image' name='thumb_url' class='image_path thumb_url' value=''>
							<input type='hidden' name='thumb_id' class='image_id thumb_id' placeholder="Image" value=''>
							<input type="button" class="button upload_image_button" value="<?php _e( 'Upload' , 'woo-advanced-shipment-tracking'); ?>" />
						</div>
						<div>
							<input type="text" name="tracking_url" class="tracking_url" placeholder="Tracking URL">
						</div>
						<div class="custom_provider_instruction">
							<p><?php _e( 'You can use the variables %number%, %postal_code% and %country_code% in the URL, for more info, check our ', 'woo-advanced-shipment-tracking' ); ?><?php echo sprintf(__('<a href="%s" target="blank">documentation</a>', 'woo-advanced-shipment-tracking'), 'http://www.zorem.com/docs/woocommerce-advanced-shipment-tracking/setting-shipping-providers/#adding-custom-shipping-provider'); ?></p>
						</div>
						<p>		
							<input type="hidden" name="action" value="update_custom_shipment_provider">
							<input type="hidden" name="provider_type" id="provider_type" value="">
							<input type="hidden" name="provider_id" id="provider_id" value="">							
							<input type="submit" name="Submit" value="<?php _e( 'Update' , 'woo-advanced-shipment-tracking'); ?>" class="button-primary btn_ast2 btn_large">							
							<a href="javascript:void(0);" class="reset_default_provider"><?php _e( 'Reset' , 'woo-advanced-shipment-tracking'); ?></a>
						</p>			
					</form>
				</div>
				<div class="popupclose"></div>
			</div>
			
			<div id="" class="popupwrapper sync_provider_popup" style="display:none;">
				<div class="popuprow">
					<span class="popupclose_btn dashicons dashicons-no-alt"></span>
					<h3 class="popup_title"><?php _e( 'Sync Shipping Providers', 'woo-advanced-shipment-tracking'); ?></h2>
					<p class="sync_message"><?php _e( 'Syncing the shipping providers list add or updates the pre-set shipping providers and will not effect custom shipping providers.', 'woo-advanced-shipment-tracking'); ?></p>
					<ul class="synch_result">
						<li class="providers_added"><?php _e( 'Providers Added', 'woo-advanced-shipment-tracking'); ?> - <span></span></li>
						<li class="providers_updated"><?php _e( 'Providers Updated', 'woo-advanced-shipment-tracking'); ?> - <span></span></li>
						<li class="providers_deleted"><?php _e( 'Providers Deleted', 'woo-advanced-shipment-tracking'); ?> - <span></span></li>
					</ul>
					<p class="reset_db_message" style="display:none;"><?php _e( 'Shipping providers database reset successfully.', 'woo-advanced-shipment-tracking'); ?></p>
					<fieldset class="reset_db_fieldset">						
						<label><input type="checkbox" id="reset_tracking_providers" name="reset_tracking_providers" value="1"><?php _e( 'Reset providers database, it will reset all your shipping provider database', 'woo-advanced-shipment-tracking'); ?></label>	
					</fieldset>
					<button class="sync_providers_btn button-primary btn_ast2 btn_large"><?php _e( 'Sync Shipping Providers', 'woo-advanced-shipment-tracking'); ?></button>
					<button class="close_synch_popup button-primary btn_ast2 btn_large"><?php _e( 'Close', 'woocommerce'); ?></button>
					<div class="spinner" style=""></div>
				</div>
				<div class="popupclose"></div>
			</div>   	
		</div>
		<?php include 'zorem_admin_sidebar.php';?>	
	</div>	
</section>