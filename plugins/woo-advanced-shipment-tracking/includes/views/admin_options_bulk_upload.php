<?php
/**
 * html code for bulk upload tab
 */
?>
<section id="content4" class="tab_section">
	<div class="tab_inner_container">				
		<!-- progressbar -->						
		<section class="woocommerce-progress-form-wrapper tpage_section" style="display:block;">
				<ol class="wc-progress-steps">
					<li class="progress_step1 active"><?php _e('Upload CSV file', 'woocommerce'); ?></li>					
					<li class="progress_step2"><?php _e('Import', 'woocommerce'); ?></li>
					<li class="progress_step3"><?php _e('Done!', 'woocommerce'); ?></li>
				</ol>	
				<form method="post" id="wc_ast_upload_csv_form" action="" enctype="multipart/form-data" style="text-align:left;">
				<div class="upload_csv_div">
					<div class="outer_form_table">							
						<table class="form-table upload_csv_table">
							<tbody>
								<tr valign="top" class="">
									<td scope="row" class="input_file_cl" colspan="2">
										<h2 style="margin-top: 0;"><?php _e('Import tracking information from a CSV file', 'woo-advanced-shipment-tracking'); ?></h2>
										<p><?php _e('Use our CSV Import Tool  to bulk Import tracking info to orders from a CSV file', 'woo-advanced-shipment-tracking'); ?></p>
									</td>
								</tr> 
								<tr valign="top" class="border-bottom-0">
									<th scope="row" class="th_80">
										<label for=""><?php _e('Upload a CSV file from your computer:', 'woo-advanced-shipment-tracking'); ?></label>
									</th>
									<td scope="row" class="input_file_cl">
										<input type="file" name="trcking_csv_file" id="trcking_csv_file">
									</td>
								</tr> 
								<tr valign="top" class="border-bottom-0">
									<th scope="row" class="th_80">
										<label for=""><?php _e('Choose the Shipped Date format', 'woo-advanced-shipment-tracking'); ?></label>
									</th>
									<td scope="row" class="th_20">
										<?php $date_format = get_option('date_format_for_csv_import','d-m-Y'); ?>
										<label class="ast_radio_label" for="date_format_ddmmyy">
											<input type="radio" <?php if($date_format == 'd-m-Y'){ echo 'checked'; }?> id="date_format_ddmmyy" name="date_format_for_csv_import" class="" value="d-m-Y"/> dd/mm/YYYY
										</label>
										<label class="ast_radio_label" for="date_format_mmddyy">
											<input type="radio" <?php if($date_format == 'm-d-Y'){ echo 'checked'; }?> id="date_format_mmddyy" name="date_format_for_csv_import" class="" value="m-d-Y"/> mm/dd/YYYY
										</label>
									</td>
								</tr>
								<tr valign="top" class="">
									<th scope="row" class="th_80">
										<label for=""><?php _e('Replace tracking information?', 'woo-advanced-shipment-tracking'); ?><span class="woocommerce-help-tip tipTip" title="<?php _e('Keep unchecked for the tracking info to be added to any existing tracking info added to the orders.', 'woo-advanced-shipment-tracking'); ?>"></span></label>													
									</th>
									<td scope="row" class="th_20">
										<input type="checkbox" id="replace_tracking_info" name="replace_tracking_info" class="" value="1"/>
									</td>
								</tr>
								<tr valign="top" class="">
									<td scope="row" class="button-column" colspan="2">
										<div class="submit">
											<button name="save" class="button-primary btn_ast2 btn_large" type="submit" value="Save"><?php _e('Continue', 'woo-advanced-shipment-tracking'); ?></button>
											<div class="spinner" style="float:none"></div>
											<div class="success_msg" style="display:none;"><?php _e('Settings Saved.', 'woo-advanced-shipment-tracking'); ?></div>
											<div class="error_msg" style="display:none;"></div>									
											<input type="hidden" name="action" value="wc_ast_upload_csv_form_update">
										</div>	
									</td>									
								</tr>								
							</tbody>				
						</table>
					</div>										
				</div>				
				<div class="bulk_upload_status_div" style="display:none;">
					<div class="outer_form_table">							
						<div class="completed_icon"></div>
						<table class="form-table upload_csv_table">
							<tbody>								
								<tr valign="top" class="bulk_upload_status_heading_tr">
									<td scope="row" class="input_file_cl bulk_upload_status_td" colspan="2">
										<h2 style="margin-top: 0;"><?php _e('Importing', 'woocommerce'); ?><span class="spinner is-active"></span></h2>	
										<p><?php _e('Your tracking information are now being imported...', 'woo-advanced-shipment-tracking'); ?></p>												
									</td>
								</tr> 
								<tr class="bulk_upload_status_tr">
									<td scope="row" colspan="2">
										<div id="p1" class="mdl-progress mdl-js-progress" style=""></div>
										<h3 class="progress_title" style="display:none;"><?php _e('Upload Progress - ', 'woo-advanced-shipment-tracking'); ?>
											<span class="progress_number"></span>
										</h3>
										<ol class="csv_upload_status">								
										</ol>
									</td>
								</tr>
								<tr valign="top" class="bulk_upload_status_action" style="display:none;">
									<td>
										<a href="javascript:void(0)" class="csv_upload_again button-primary btn_ast2 btn_large"><?php _e('Upload again', 'woo-advanced-shipment-tracking'); ?></a>
										<a class="button-primary btn_ast2 btn_large" href="<?php echo admin_url( 'edit.php?post_type=shop_order' ); ?>"><?php _e('View Orders', 'woo-advanced-shipment-tracking'); ?></a>
									</td>	
								</tr>
							</tbody>				
						</table>
					</div>					
				</div>				
				<ul class="bulk_upload_documentation_ul">
					<li>
						<a class="" href="<?php echo wc_advanced_shipment_tracking()->plugin_dir_url()?>/assets/tracking.csv"><?php _e('Download sample csv', 'woo-advanced-shipment-tracking'); ?></a>
					</li>
					<li>
						<a target="blank" href="https://www.zorem.com/docs/woocommerce-advanced-shipment-tracking/bulk-import-shipment-tracking/"><?php _e('Documentation', 'woo-advanced-shipment-tracking'); ?></a>
					</li>	
				</ul>
			</form>	
		</section>			
	</div>	
<?php include 'zorem_admin_sidebar.php';?>	
</section>