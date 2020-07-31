<?php
$staticImageUrl = "/wp-content/plugins/a4-barcode-generator/assets/img/amazon-200x113.jpg";

return array(
    // 1-st template
    array(
        'name' => 'Adaptive template',
        'slug' => 'default-1',
        'template' => '<div
    class="barcode-print barcode-label-text text-1"
    style="max-height: 17.6px; overflow: hidden; font-size: 16px;">
    [name]
</div>
<div
    class="barcode-print barcode-label-text no-wrap"
    style="font-size: 16px; max-height: 17.6px;">
    [text1]
</div>
<div
    class="barcode-print barcode-label-image-wrapper">
    <img style="width: auto; height: 100%" src="[barcode_img_url]"/>
</div>
<div
    class="barcode-print barcode-label-text no-wrap"
    style="font-size: 16px; max-height: 17.6px;">
    [code]
</div>
<div
    class="barcode-print barcode-label-text no-wrap"
    style="font-size: 16px; max-height: 17.6px;">
    [text2]
</div>',
        'is_active' => 1,
        'is_default' => 1,
        'is_base' => 1,
        'height' => null,
        'width' => null,
        'uol' => 'mm',
    ),

    // 2-nd template
    array(
        'name' => 'Example 1 - Barcode + Logo',
        'slug' => 'default-2',
        'template' => '<table  style="width:100%; height:100%" cellspacing="0" cellpadding="0">
    <tr height="65%">
      <td width="60%" rowspan="2" style="border-right: 1px solid #e2e2e2; font-size: 16px;
                                         text-align:center; vertical-align: middle;">
        <div>[name]</div>
        <div style="padding-top: 1.5mm">[code]</div>
      </td>
      <td width="40%" style="height:100%; vertical-align: bottom;" align="center">
          <img style="width:80%" src="'.$staticImageUrl.'"/>
      </td>
    </tr>
    <tr>
      <td style="font-size:12px; height:6mm; vertical-align:top" align="center">Amazon Inc.</td>
    </tr>
    <tr height="35%">
      <td colspan="2" style="vertical-align: top;">
          <div style="height:11mm; overflow:hidden;">
                  <img style="width: 100%; object-fit: cover;" src="[barcode_img_url]"/>
        </div>
      </td>
    </tr>
  </table>',
        'is_active' => 0,
        'is_default' => 1,
        'is_base' => 0,
        'height' => 37,
        'width' => 70,
        'uol' => 'mm',
        'base_padding_uol' => 2.5
    ),

    // 3-d template
    array(
        'name' => 'Example 2 - QRCode',
        'slug' => 'default-3',
        'template' => '<table style="width:100%; height:100%;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="width: 50%; vertical-align:top; height: 100%" align="left">
            <img style="width: 95%; object-fit: cover;" src="[2dcode_img_url]"/>
        </td>
        <td style="vertical-align:middle; text-align:center">
            <div style="font-size: 16px;">
                <div style="margin-bottom:4mm"><b>[name]</b></div>
              	<div style="margin-bottom:2mm">[code]</div>
                <div>[text1]</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <div style="font-size: 16px;">[text2]</div>
        </td>
    </tr>
</table>',
        'is_active' => 0,
        'is_default' => 1,
        'is_base' => 0,
        'height' => 44,
        'width' => 70,
        'uol' => 'mm',
        'base_padding_uol' => 2.5
    ),

    // 4-d template
    array(
        'name' => 'Example 3 - QRCode + Product Image',
        'slug' => 'default-4',
        'template' => '<table  style="width:100%; height:100%; font-size:16px;" cellspacing="0" cellpadding="0">
  <tr height="100%">
  	<td align="center" height="100%">
    	<img src="[product_image_url]" style="max-width:20mm;
                                              max-height:20mm; object-fit: cover;"/>
  	</td>
    <td rowspan="2" align="center">
      <div style="padding-top:1mm">[name]</div>
      <div style="padding-top:1mm">[code]</div>
      <div style="padding-top:1mm; font-size:12px;">[text1]</div>
      <div style="padding-top:1mm; font-size:12px;">[text2]</div>
    </td>
    <td  align="right" height="100%">
      <img style="width: 24mm;" src="[2dcode_img_url]"/>
    </td>
  </tr>
</table>',
        'is_active' => 0,
        'is_default' => 1,
        'is_base' => 0,
        'height' => 30,
        'width' => 100,
        'uol' => 'mm',
        'base_padding_uol' => 2
    ),

    // 5-d template
    array(
        'name' => 'Example 4 - Vertical Barcode',
        'slug' => 'default-5',
        'template' => '<table  style="width:100%; height:100%; " cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div  style="font-size:16px; width:35mm; overflow:hidden;
                     line-height:18px; padding-right:3mm">
        [name]
        </div>
      </td>
      <td style="height:100%; vertical-align:bottom; text-align:left;" rowspan="2">

        <div style="transform: rotate(270deg); overflow:hidden; transform-origin: 0% 0%;
                    text-align:center; position:relative; left:0mm; top:11mm; width:25.4mm">
          <img src="[barcode_img_url]" style="width:21mm; height:6mm; object-fit: cover;"/>
          <div style="font-size:10px; margin-top:0.5mm">[code]</div>
        </div>

      </td>
    </tr>
    <tr>
      <td  style="text-align:left; font-size:12px;">[text1]<br/>[text2]</td>
    </tr>
</table>',
        'is_active' => 0,
        'is_default' => 1,
        'is_base' => 0,
        'height' => 25.4,
        'width' => 50,
        'uol' => 'mm',
        'base_padding_uol' => 1.5
    ),

    // Vertical barcode
    array(
        'name' => 'Example 5 - Custom Fields & Attributes',
        'slug' => 'default-6',
        'template' => '<table  style="width:100%; height:100%; font-size:14px; line-height:20px;" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div style="line-height:18px;">
          	Woo Id: [field=ID]<br/>
        	SKU: [cf=_sku]<br/>
        	Name: [name]<br/>
        	Regular price: <strike>[cf=_regular_price]</strike><br/>
        	Sale price: [cf=_sale_price]<br/>
        	Actual price: [cf=_price]<br/>
        	Size: [attr=Size]<br/>
        	Color: [attr=Color]<br/>
          	Category: [category]
        </div>

        <div style="text-align:center">
          <img src="[barcode_img_url]" style="width:100%; height:10mm; object-fit: cover; margin-top:2mm"/>
        </div>

        <div style="font-size:14px; text-align:center; margin-top:1mm">[code]</div>
      </td>
    </tr>
</table>',
        'is_active' => 0,
        'is_default' => 1,
        'is_base' => 0,
        'height' => 65,
        'width' => 60,
        'uol' => 'mm',
        'base_padding_uol' => 2.5
    ),
);
