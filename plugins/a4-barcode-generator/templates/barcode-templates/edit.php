<div class="wrap wp-barcodes-custom-templates">

    <?php include __DIR__.'/_header.php'; ?>

    <table class="form-table" style="width: 600px;">
        <tr>
            <td style="width: 300px;">
                <p><b><?php echo __('Select template', 'wpbcu-barcode-generator'); ?></b></p>
                <p>
                    <select name="id" id="js-templates-select" style="width: 300px;">
                        <?php foreach ($templates as $template): ?>
                            <option value="<?php echo $template->id; ?>" <?php echo $template->id === $chosenTemplate->id ? 'selected' : ''; ?>><?php
                                echo $template->name; ?><?php echo $template->is_active ? ' (active)' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </td>
            <td style="text-align: right; vertical-align: bottom; padding-left: 0;">
                <a class="button button-default" href="<?php echo esc_url(admin_url('/admin.php?page=wpbcu-barcode-templates-create')); ?>"><?php
                    echo '+ '.__('Add new', 'wpbcu-barcode-generator');
                ?></a
            </td>
        </tr>
    </table>

    <h2 class="nav-tab-wrapper">
        <a href="javascript:void(0);" data-target="#general" class="nav-tab nav-tab-active js-nav-tab"><?php _e('Template', 'wpbcu-barcode-generator'); ?></a>
        <a href="javascript:void(0);" data-target="#code_setup" class="nav-tab js-nav-tab"><?php _e('[code] value', 'wpbcu-barcode-generator'); ?></a>
    </h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

        <div id="general" class="a4b-tab a4b-tab-active js-nav-tab-content">
            <table class="form-table">
                <tr>
                    <td width="25%">
                        <p><b><?php echo __('Name', 'wpbcu-barcode-generator'); ?></b></p>
                        <p><input type="text" name="name"
                                <?php echo $chosenTemplate->is_default ? 'disabled' : ''; ?>
                                  value="<?php echo esc_attr(a4bOld('name')) ?: esc_attr($chosenTemplate->name); ?>"
                                  style="width: 300px;"></p>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        <p>
                            <?php echo __('HTML Template', 'wpbcu-barcode-generator'); ?>:
                            <span style="float: right;"><?php echo __('<b>Notice</b>: Use only ', 'wpbcu-barcode-generator'); ?>"<span class="js-uol-value"><?php echo $chosenTemplate->uol; ?></span>"<?php echo __(' in HTML instead of "px", "pt" and other dimensions.', 'wpbcu-barcode-generator'); ?></span>
                        </p>
                        <textarea name="template" id="js-template-tpl" rows="20" style="width: 100%; min-width: 600px;"
                        <?php echo $chosenTemplate->is_default ? 'disabled' : ''; ?>><?php
                            echo $chosenTemplate->is_base
                                ? 'HTML code for this template is not available. Use other pre-installed templates as an example.'
                                : a4bOld('template') ?: htmlentities($chosenTemplate->template);
                            ?></textarea>
                        <p><?php
                            echo __('To specify where image and texts should be placed use next placeholders in html', 'wpbcu-barcode-generator'); ?>:
                            [barcode_img_url], [2dcode_img_url], [code], [name], [text1], [text2], [product_image_url]</p>
                        <br/>
                        <p style="margin-bottom: 10px;"><?php
                            echo __('To put attributes or custom fields use next shortcodes with their names: [attr=Color] or [cf=Size]. Use shortcode [category] for categories list. For example, you can use [field=ID] for post id, or [cf=_sale_price], [cf=_regular_price] for corresponding custom fields. After barcode import, these shortcodes will be replaced with values.', 'wpbcu-barcode-generator'); ?></p>
                        <br/>


                    </td>
                    <td style="vertical-align: top;">
                        <table class="wpbc-template-sizes-table">
                            <?php if (!$chosenTemplate->is_base): ?>
                                <tr>
                                    <td><?php echo __('Unit of length', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td>
                                        <select class="template-field-small js-template-size" name="uol" <?php echo $chosenTemplate->is_default ? 'disabled' : ''; ?>>
                                            <option <?php echo 'mm' === $chosenTemplate->uol ? 'selected' : ''; ?> value="mm">mm</option>
                                            <option <?php echo 'in' === $chosenTemplate->uol ? 'selected' : ''; ?> value="in">inch</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Base padding', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-field-small js-template-size"
                                               name="base_padding_uol"
                                               placeholder="base padding"
                                               type="number" step="0.0001" min="0"
                                               value="<?php echo floatval(a4bOld('base_padding_uol')) ?: floatval($chosenTemplate->base_padding_uol); ?>"
                                            <?php echo $chosenTemplate->is_default ? 'disabled' : ''; ?>
                                        >
                                        <input type="hidden" name="base_padding" value="<?php echo $chosenTemplate->base_padding; ?>">
                                    </td>
                                </tr>
                                <?php if ($chosenTemplate->base_padding_uol === null): ?>
                                    <tr><td colspan="2"><?php echo __('Currently base padding is set to: ', 'wpbcu-barcode-generator').$chosenTemplate->base_padding.'px.'; ?></td></tr>
                                <?php endif; ?>
                                <tr>
                                    <td><?php echo __('Label width', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-size js-template-size" name="width" placeholder="width"
                                               type="number" step="0.0001" min="0"
                                               value="<?php echo a4bOld('width') ?: $chosenTemplate->width; ?>"
                                            <?php echo $chosenTemplate->is_default ? 'disabled' : ''; ?>
                                        ></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Label height', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-size js-template-size" name="height" placeholder="height"
                                               type="number" step="0.0001" min="0"
                                               value="<?php echo a4bOld('height') ?: $chosenTemplate->height; ?>"
                                            <?php echo $chosenTemplate->is_default ? 'disabled' : ''; ?>
                                        ></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td><?php echo __('Label width', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-size" type="text" disabled="" placeholder="<?php echo __('Any', 'wpbcu-barcode-generator'); ?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Label height', 'wpbcu-barcode-generator'); ?>:</td>
                                    <td><input class="template-size" type="text" disabled="" placeholder="<?php echo __('Any', 'wpbcu-barcode-generator'); ?>"></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        <p><?php echo __('Preview', 'wpbcu-barcode-generator'); ?>:</p>
                        <?php if ($chosenTemplate->is_base): ?>
                            <!--Hard codded template markup-->
                            <div class="barcode-template-preview-box">
                                <div class="barcode-label">
                                    <?php echo $chosenTemplate->getPreview(); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!--Custom template markup in iframe-->
                            <iframe id="js-template-preview-iframe"
                                    src="" frameborder="0" width="300px" height="200px" scrolling="no"
                                    data-template="<?php echo esc_attr($chosenTemplate->getPreview()); ?>"
                                    data-template-wrapper="<?php echo esc_attr(include A4B_PLUGIN_BASE_PATH.'templates/template-preview-iframe.php'); ?>"
                            ></iframe>
                        <?php endif; ?>
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
                                                        value="1"
                                                    <?php echo (a4bOld('code_match') || $chosenTemplate->code_match) ? 'checked' : ''; ?>>
                                                <?php echo __('Match [code] with a WooCommerce field(s).', 'wpbcu-barcode-generator'); ?>
                                            </label></b>
                                        <input type="hidden" id="code_match_shadow" name="code_match" value="0" <?php echo (a4bOld('code_match') || $chosenTemplate->code_match) ? 'disabled' : ''; ?>>
                                    </p>

                                    <p><?php echo __('For single product:', 'wpbcu-barcode-generator'); ?></p>
                                    <p><textarea type="text" name="single_product_code" rows="4"
                                                 class="js-woocommerce-match"
                                <?php echo $chosenTemplate->code_match ? '' : 'disabled'; ?>
                                 style="width: 300px;"><?php echo esc_attr(a4bOld('single_product_code')) ?: esc_attr($chosenTemplate->single_product_code); ?></textarea></p>
                                    <br/>

                                    <p><?php echo __('For variation product:', 'wpbcu-barcode-generator'); ?></p>
                                    <p><textarea type="text" name="variable_product_code" rows="4"
                                                 class="js-woocommerce-match"
                                <?php echo $chosenTemplate->code_match ? '' : 'disabled'; ?>
                                 style="width: 300px;"><?php echo esc_attr(a4bOld('variable_product_code')) ?: esc_attr($chosenTemplate->variable_product_code); ?></textarea></p>
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

        <table class="form-table" style="max-width: 690px;">
            <tr>
                <td>
                    <input type="submit" class="button button-primary <?php echo $chosenTemplate->is_default && !$chosenTemplate->code_match ? 'js-woocommerce-match' : ''; ?>"
                        <?php echo $chosenTemplate->is_default && !$chosenTemplate->code_match ? 'disabled' : ''; ?>
                           value="<?php echo __('Save Changes', 'wpbcu-barcode-generator'); ?>"
                           style="float: right;">

                    <a class="button button-cancel"
                       href="#"
                       onclick="event.preventDefault(); document.getElementById('templates-choose-form').submit();"
                       style="float: right; margin-right: 8px;"><?php
                        echo __('Set active', 'wpbcu-barcode-generator');
                        ?></a>
                    <!--Editing template: action 'update', delete button, hidden input id.-->
                    <input type="hidden" name="action" value="a4barcode_template_update">
                    <input type="hidden" name="id" value="<?php echo $chosenTemplate->id; ?>">
                    <?php if (!$chosenTemplate->is_default): ?>
                        <a class="button button-cancel"
                           href="#"
                           onclick="event.preventDefault(); document.getElementById('templates-delete-form').submit();"
                        ><?php echo __('Delete', 'wpbcu-barcode-generator'); ?></a>
                    <?php endif; ?>
                </td>
                <td></td>
            </tr>
        </table>
    </form>

    <form id="templates-choose-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="id" value="<?php echo $chosenTemplate->id; ?>">
        <input type="hidden" name="action" value="a4barcode_template_setactive">
    </form>
    <?php if (!$chosenTemplate->is_default): ?>
        <form id="templates-delete-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: none;">
            <input type="hidden" name="id" value="<?php echo $chosenTemplate->id; ?>">
            <input type="hidden" name="action" value="a4barcode_template_delete">
        </form>
    <?php endif; ?>

</div>
