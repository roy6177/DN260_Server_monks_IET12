<div class="wrap wp-barcodes-custom-templates">

    <?php include __DIR__.'/_header.php'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="javascript:void(0);" data-target="#general" class="nav-tab nav-tab-active js-nav-tab"><?php _e('Template', 'wpbcu-barcode-generator'); ?></a>
        <a href="javascript:void(0);" data-target="#code_setup" class="nav-tab js-nav-tab"><?php _e('[code] value', 'wpbcu-barcode-generator'); ?></a>
    </h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <div>
            <div id="general" class="a4b-tab a4b-tab-active js-nav-tab-content">
                <table class="form-table">
                    <tr>
                        <td width="25%">
                            <p><b><?php echo __('Name', 'wpbcu-barcode-generator'); ?></b></p>
                            <p>
                                <input type="text" name="name"
                                       value="<?php echo esc_attr(a4bOld('name')) ?: ''; ?>"
                                       style="width: 300px;">
                            </p>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                            <p>
                                <?php echo __('HTML Template', 'wpbcu-barcode-generator'); ?>:
                                <span style="float: right;"><?php echo __('<b>Notice</b>: Use only ', 'wpbcu-barcode-generator'); ?>"<span class="js-uol-value">mm</span>"<?php echo __(' in HTML instead of "px", "pt" and other dimensions.', 'wpbcu-barcode-generator'); ?></span>
                            </p>
                            <textarea name="template" id="js-template-tpl" rows="20" style="width: 100%; min-width: 600px;"><?php
                                echo a4bOld('template') ?: '';
                                ?></textarea>
                            <p><?php
                                echo __('To specify where image and texts should be placed use next placeholders in html', 'wpbcu-barcode-generator'); ?>:
                                [barcode_img_url], [2dcode_img_url], [code], [name], [text1], [text2], [product_image_url]</p>
                            <br/>
                            <p style="margin-bottom: 10px;"><?php
                                echo __('To put attributes or custom fields use next shortcodes with their names: [attr=Color] or [cf=Size]. For example, you can use [field=ID] for post id, or [cf=_sale_price], [cf=_regular_price] for corresponding custom fields. Use shortcode [category] for categories list. After barcode import, these shortcodes will be replaced with values.', 'wpbcu-barcode-generator'); ?></p>
                        </td>
                        <td style="vertical-align: top;">
                            <table class="wpbc-template-sizes-table">
                                <tr>
                                    <td><?php echo __('Unit of length', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td>
                                        <select class="template-field-small js-template-size" name="uol">
                                            <option value="mm">mm</option>
                                            <option value="in">inch</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Base padding', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-field-small js-template-size"
                                               name="base_padding_uol"
                                               placeholder="base padding"
                                               type="number" step="0.0001" min="0"
                                               value="<?php echo floatval(a4bOld('base_padding_uol')) ?: '2' ?>"
                                        >
                                        <input type="hidden" name="base_padding" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Label width', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-size js-template-size" name="width" placeholder="width"
                                               type="number" step="0.0001" min="0"
                                               value="<?php echo a4bOld('width') ?: '70'; ?>"
                                        ></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Label height', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-size js-template-size" name="height" placeholder="height"
                                               type="number" step="0.0001" min="0"
                                               value="<?php echo a4bOld('height') ?: '37'; ?>"
                                        ></td>
                                </tr>
                            </table>
                            <p><?php echo __('Preview', 'wpbcu-barcode-generator'); ?>:</p>
                            <!--Custom template markup in iframe-->
                            <iframe id="js-template-preview-iframe"
                                    src="" frameborder="0" width="300px" height="200px" scrolling="no"
                                    data-template=""
                                    data-template-wrapper="<?php echo esc_attr(include A4B_PLUGIN_BASE_PATH.'templates/template-preview-iframe.php'); ?>"
                            ></iframe>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="code_setup" class="a4b-tab js-nav-tab-content">
                <table class="form-table">
                    <tr>
                        <td colspan="2" style="padding-top: 0 !important; padding-left: 0 !important;">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">
                                        <p><b><label><input class="template-field-small"
                                                            name="code_match"
                                                            type="checkbox"
                                                            <?php echo a4bOld('code_match') ? 'checked' : ''; ?>
                                                            value="1">
                                                    <?php echo __('Match [code] with a WooCommerce field(s).', 'wpbcu-barcode-generator'); ?>
                                                </label></b>
                                            <input type="hidden" id="code_match_shadow" name="code_match" value="0" <?php echo a4bOld('code_match') ? 'disabled' : ''; ?>>
                                        </p>

                                        <p><?php echo __('For single product:', 'wpbcu-barcode-generator'); ?></p>
                                        <p><textarea type="text" name="single_product_code" rows="4" disabled class="js-woocommerce-match"
                                                     style="width: 300px;"><?php echo esc_attr(a4bOld('single_product_code')) ?: ''; ?></textarea></p>
                                        <br/>

                                        <p><?php echo __('For variation product:', 'wpbcu-barcode-generator'); ?></p>
                                        <p><textarea type="text" name="variable_product_code" rows="4" disabled class="js-woocommerce-match"
                                                     style="width: 300px;"><?php echo esc_attr(a4bOld('variable_product_code')) ?: ''; ?></textarea></p>
                                    </td>
                                    <td style="vertical-align: top;"><p>Allows to match [code] with any custom field of WooCommerce.<br/>
                                            For example use _sku, ID or custom_field as a values.<br/><br/></p>

                                        <p style="margin-top: -16px;">You can combine a few fields with separator, e.g:<br/>
                                            ID<br/>
                                            "|"<br/>
                                            custom_field<br/><br/><br/></p>

                                        <p style="margin-top: 12px;">For "Variation" you can access parent values, e.g:<br/>
                                            parent._sku<br/>
                                            "|"<br/>
                                            _sku</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <table class="form-table" style="max-width: 690px;">
            <tr>
                <td>
                    <input type="submit" class="button button-primary"
                           value="<?php echo __('Save Changes', 'wpbcu-barcode-generator'); ?>"
                           style="float: right;">
                    <!--Creating new: action 'store', button 'cancel'.-->
                    <input type="hidden" name="action" value="a4barcode_template_store">
                    <a class="button button-cancel" href="<?php echo esc_url(admin_url('/admin.php?page=wpbcu-barcode-templates-edit')); ?>"><?php
                        echo __('Cancel', 'wpbcu-barcode-generator');
                        ?></a>
                </td>
                <td>
                </td>
            </tr>
        </table>
    </form>

</div>
