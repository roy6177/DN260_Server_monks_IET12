<?php

class Class_Pi_Dcw_AddToCart{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'add-to-cart';

    private $tab_name = "Add To Cart";

    private $setting_key = 'dcw_add_to_cart_settting';

    private $pages =array();

   
    
    private $pro_version = false;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $this->settings = array(
            array('field'=>'sunday', 'class'=> 'bg-secondary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Add to Cart button','pi-dcw'), 'type'=>'setting_category'),
            array('field'=>'pi_dcw_change_add_to_cart','desc'=>__('Do you want to change the button text of add to cart button','pi-dcw'), 'label'=>__('Change Add to cart button text','pi-dcw'),'type'=>'switch', 'default'=>0),
            array('field'=>'pi_dcw_add_to_cart_text','desc'=>__('This text will be shown inside add to cart button','pi-dcw'), 'label'=>__('Add to cart button text','pi-dcw'),'type'=>'text', 'default'=>__('Add to cart', 'woocommerce')),
            
           
        );
        $this->register_settings();
        
        /**
         * change add to cart text in single product page
         */
        add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'add_product_text'), 10, 2);
        /**
         * change add to cart text in product loop
         */
        add_filter('woocommerce_product_add_to_cart_text', array($this, 'add_product_text'), 10, 2);
    }

   
    

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name, 'http2-push-content' ); ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_dcw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }

    function add_product_text($text, $product) {
        $change = get_option( 'pi_dcw_change_add_to_cart', 0);
        if (1 == get_option( 'pi_dcw_change_add_to_cart', 0)) {
          $saved = esc_html(get_option( 'pi_dcw_add_to_cart_text', __('Add to cart','woocommerce')));
          if($saved != ""){
              $text = $saved;
          }
        }
  
        return $text;
      }
    
}

new Class_Pi_Dcw_AddToCart($this->plugin_name);