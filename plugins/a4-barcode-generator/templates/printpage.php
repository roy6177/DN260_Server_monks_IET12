<!DOCTYPE html>
<html lang="en">
<head>
    <title>Print page</title>
    <link rel='stylesheet' type='text/css' href='<?php echo A4B_PLUGIN_BASE_URL."public/dist/css/app_demo_2.14.4.css"; ?>'>
<link rel='stylesheet' type='text/css' href='<?php echo A4B_PLUGIN_BASE_URL."public/dist/css/chunk-vendors_demo_2.14.4.css"; ?>'>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,Oxygen-Sans,Ubuntu,Cantarell,\"Helvetica Neue\",sans-serif;
            font-size: 13px;
            line-height: 1.4em;
        }
        #wpwrap {
            height: auto;
            min-height: 100%;
            width: 100%;
            position: relative;
            -webkit-font-smoothing: subpixel-antialiased;
        }
        #wpcontent {
            height: 100%;
            padding-left: 20px;
        }
    </style>
</head>
<body class='body-defender'>
<div id='wpwrap'>
    <div id='wpcontent'>
        <div id='wpbody'>
            <div id='wpbody-content'></div>
        </div>
    </div>
</div>
<script>
    window.outline = <?php echo (string) (isset($_GET['grid']) && 'true' == $_GET['grid']) ? 'true' : 'false'; ?>;
    window.barcodes = {nativePage:true};
    window.ajaxurl = '<?php echo A4B_SITE_BASE_URL.'/wp-admin/admin-ajax.php'; ?>';
    window.a4bjs = {};
    window.a4bjs.active_template = {};
    window.a4bjs.active_template_uol = '<?php echo $chosenTemplateRow->uol ? $chosenTemplateRow->uol : 'mm'; ?>';
    window.a4bjs.active_template_type = '<?php echo 1 == $chosenTemplateRow->is_base ? 'default' : 'custom'; ?>';
    window.a4bjs.active_template_base_padding = '<?php echo isset($chosenTemplateRow->base_padding) ? $chosenTemplateRow->base_padding : '8'; ?>';
    window.a4bjs.active_template_base_padding_uol = '<?php echo isset($chosenTemplateRow->base_padding_uol) ? $chosenTemplateRow->base_padding_uol : null; ?>';
    window.a4bjs.activeTemplateData = <?php echo json_encode($chosenTemplateRow); ?>
    // json_encode for adding quotes around string and escaping quotes inside.
    window.a4bjs.active_template = <?php echo json_encode(preg_replace("/\s\s?+/", ' ', trim($chosenTemplateRow->template))); ?>;
</script>
<script src='<?php echo A4B_SITE_BASE_URL.'/wp-includes/js/jquery/jquery.js'; ?>'></script>
<script src='<?php echo A4B_PLUGIN_BASE_URL."public/dist/js/app_demo_2.14.4.js"; ?>'></script>
<script src='<?php  echo A4B_PLUGIN_BASE_URL."public/dist/js/chunk-vendors_demo_2.14.4.js"; ?>'></script>

</body>
</html>
