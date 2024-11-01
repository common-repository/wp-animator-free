<?php
namespace animator;
use WpAnimatorHelper\HtmlFormHelper;

if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly
}



/**
 * Description of Animator. Functionality  on admin side. Forms,options,properties
 *
 * @author Pavlo
 */
class Animator  {
    protected  $slug="animation";
    protected  $slug_cat="group_animation";
    protected  $slug_rew="animator";
    protected  $version="";
    protected  $debag=true;
    protected  $css_path;
    public static $is_init=false;
    protected $meta_data_box=array();
    protected $animation_name=array();
    protected $animaton_setting;

    public function __construct() {
        $this->animaton_setting=\AnimOptions::getInstance();
        $this->meta_data_box=$this->animaton_setting->get_meta_data();
        $this->debag=$this->animaton_setting->get_debag();
        $this->animation_name=$this->animaton_setting->get_anim_name();
        $this->version=$this->animaton_setting->get_version();
        $setting=$this->animaton_setting->get_settings();
        $this->slug_cat=$setting['slug_cat'];
        $this->slug=$setting['slug'];
        $this->slug_rew=$setting['slug_rew'];
        $this->css_path= WPANIMATOR_PATH.'css/gen_css/'.$this->animaton_setting->get_css_name();
    }
    public function init(){
       // add_action('wp_enqueue_scripts', array($this, 'set_script_style_front'));
        add_action('admin_enqueue_scripts', array($this, 'set_script_style_admin'));
        add_action( 'init', array($this, 'anim_init_custom_post'), 1);
        add_action( 'add_meta_boxes',array($this, 'anim_meta_box'), 1);
        add_action( 'save_post', array($this, 'anim_save_meta_box'), 1 );

        add_action('delete_post', array($this, 'anim_delete_post'));
    }
       public static function get_application_path()
    {
        return plugin_dir_path(__FILE__);
    }
    public static function get_application_uri()
    {
        return plugin_dir_url(__FILE__);
    }
    public function set_script_style_admin(){
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        wp_enqueue_script('jquery');
        wp_enqueue_style('anim_style',WPANIMATOR_LINK.'/css/anim-admin-style.css') ;
        wp_enqueue_script('img_uploader', WPANIMATOR_LINK.'/js/bory.uploader.js', array('jquery'));
        wp_enqueue_script('anim_admin_js', WPANIMATOR_LINK.'/js/admin.js', array('jquery'));
    }
       public function set_script_style_front(){
                //wp_enqueue_style('anim_style',plugins_url('/wordpress-animator/css/anim-style.css') );
       }
   public function anim_init_custom_post(){
         $args = array(
            'labels' => array(
                'name' => __('Elements', 'animator'),
                'singular_name' => __('Element', 'animator'),
                'add_new' => __('Add New Element', 'animator'),
                'add_new_item' => __('Add New Element', 'animator'),
                'edit_item' => __('Edit Element', 'animator'),
                'new_item' => __('New Element', 'animator'),
                'view_item' => __('View Element', 'animator'),
                'search_items' => __('Search Element', 'animator'),
                'not_found' => __('No Element found', 'animator'),
                'not_found_in_trash' => __('No Element found in Trash', 'animator'),
                'parent_item_colon' => '',
                'menu_name'=>'Animator'
                
            ),
            'public' => false,
            'archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => true,
            'menu_position' => null,
            'supports' => array('title', 'excerpt', 'tags'),
            'rewrite' => array('slug' => $this->slug_rew),
            'show_in_admin_bar' => false,
            'menu_icon'=>'dashicons-welcome-view-site"',
            'orderby' => 'date',
            'taxonomies' => array($this->slug_cat) // this is IMPORTANT
        );
        register_taxonomy($this->slug_cat, array($this->slug), array(
            "labels" => array(
                'name' => __('Collections', 'animator'),
                'singular_name' => __('Collection', 'animator'),
                'add_new' => __('Add New', 'animator'),
                'add_new_item' => __('Add New Collection', 'animator'),
                'edit_item' => __('Edit Collection Categories', 'animator'),
                'new_item' => __('New Collection', 'animator'),
                'view_item' => __('View Collection', 'animator'),
                'search_items' => __('Search Collection', 'animator'),
                'not_found' => __('No Collection found', 'animator'),
                'not_found_in_trash' => __('No Collection found in Trash', 'animator'),
                'parent_item_colon' => ''
            ),
            "singular_label" => __("Collection", 'animator'),
            'show_in_nav_menus' => false,
            'capabilities' => array('manage_terms1'),
            'show_ui' => true,
            'term_group' => true,
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $this->slug_rew),
            'orderby' => 'name'
        ));
          register_post_type($this->slug, $args);
    }
    public function anim_meta_box(){
        
         add_meta_box("anim_meta_data", __("Animator options", 'animator'), array($this, 'anim_data_meta_box'), $this->slug, "normal", "high");
    }
    public function  anim_data_meta_box($post){

        ///add filter for meta data

        $this->meta_data_box =  apply_filters('animator_meta_form_data',  $this->meta_data_box);
        $meta_value=array();
        wp_nonce_field( 'animator_inner_mbox', 'animator_inner_mbox_box_nonce' );
        foreach ($this->meta_data_box as $item):
             if($item['meta_id']=='anim_animation'){
            //add filter for animation names
             $item['options']=apply_filters('animator_animation_names', $item['options']);
            }
            $temp=$this->get_value_meta($item, $post);

            $meta_value[]=HtmlFormHelper::get_html_input($temp);
        endforeach;

        $html=array();
        $checked=get_post_meta($post->ID,"is_check_".$this->meta_data_box[0]['meta_id'],true);
       // echo $checked;
        $data_info['version'] = $this->version;
        $html[]= HtmlFormHelper::get_toggle_section($meta_value[0], $meta_value[1], __('Use content','animator'),$checked, __('Image or Content','animator'),"is_check_".$this->meta_data_box[0]['meta_id'],'img_content');
        $temp_html="";
        $temp_html=HtmlFormHelper::get_section_array($meta_value,2,5,"col-2");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,5,8,"col-2");
        $html[]=HtmlFormHelper::get_sections_optional($temp_html,__('Position of the Element','animator'),'position');
        $temp_html="";
        $temp_html=HtmlFormHelper::get_section_array($meta_value,8,10,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,10,11,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,11,12,"col-3");

        $html[]=HtmlFormHelper::get_sections_optional($temp_html,__('Element properties','animator'),'elem_prop');
        $temp_html=HtmlFormHelper::get_section_array($meta_value,12,13,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,13,14,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,14,15,"col-3");
        $html[]=HtmlFormHelper::get_sections_optional($temp_html,__('Animation options','animator'),'anim_option');
        $checked=get_post_meta($post->ID,"is_check_".$this->meta_data_box[15]['meta_id'],true);
       //echo $checked;
        $html[]= HtmlFormHelper::get_toggle_section(__("<h3>Show in all pages</h3>",'animator'), $meta_value[15], __('Show in exact pages','animator'),$checked,__('Condition display','animator'),"is_check_".$this->meta_data_box[15]['meta_id'],'anim_conditional');
        $temp_html=HtmlFormHelper::get_section_array($meta_value,16,17,"col-1");
        $html[]=HtmlFormHelper::get_sections_optional($temp_html,__('CSS','animator'),'anim_css');
        $temp_html=HtmlFormHelper::get_section_array($meta_value,17,18,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,18,19,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,19,20,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,20,21,"col-3");
        $temp_html.=HtmlFormHelper::get_section_array($meta_value,23,24,"col-3");
        $temp_html=HtmlFormHelper::get_sections_optional($temp_html,__("Additional animation properties",'animator'),'aditonal_anim_pro');
        $temp_html.=HtmlFormHelper::get_sections_optional(HtmlFormHelper::get_section_array($meta_value,21,23,"col-3"),__("Custom animation",'animator'),'custom_anim');
        $html[]=HtmlFormHelper::get_button_toggle_sections($temp_html,__("Additional options",'animator'),__("Options expand animation and behavior on the mobile device",'animator'),"Id1",$post);
        $html[]=HtmlFormHelper::get_sections_optional(HtmlFormHelper::get_info_content("info",$data_info),__('Information','animator'));
		$html=apply_filters('animator_list_meta_filds', $html,$post);
		echo HtmlFormHelper::get_sections_optional(HtmlFormHelper::get_info_content("info_free",$data_info),__('Information','animator'));
        foreach($html as $item){
            echo $item;
        }
       
    }
    public function get_value_meta($arg, $post){
        switch ($arg['type']):

            case 'upload_img':
                $arg['value']=get_post_meta($post->ID,$arg['meta_id'],true);
                if(!empty($arg['value'])):
                         $src=wp_get_attachment_image_src( $arg['value'],'full');
                         $arg['src']=$src[0];
                        else:
                         $arg['src']=WPANIMATOR_LINK."images/noimages.png";
                        endif;
                return $arg;
                break;
                default :
                    $arg['value']=get_post_meta($post->ID,$arg['meta_id'],true);
                    if((empty($arg['value'])OR $arg['value']=="")AND isset($arg['default'])):
                        $arg['value']=$arg['default'];
                        endif;
                    return $arg;
                break;
                
        endswitch;
        
    }
    private  function  create_css_file($post_id){
        $anim_path_css=array();
        global $wpdb;
        $posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='".$this->slug."' ORDER BY post_date DESC   LIMIT 3 ");
        $this->animaton_setting->set_ids_element($posts);
        $anim_path_css[]=WPANIMATOR_LINK.'css/anim-style.css';
        foreach ($posts as $post) {
            if(get_post_meta($post->ID,'anim_custom',true)){
                $anim_path_css[]=get_stylesheet_directory_uri() .'/animations/'.trim(get_post_meta($post->ID,'anim_name_custom',true)).'.css';
            }else{
                $anim_path_css[]=WPANIMATOR_LINK.'css/animations/'.trim(get_post_meta($post->ID,'anim_animation',true)).'.css';
            }
        }
         $anim_path_css = apply_filters('animator_add_path_css',$anim_path_css,$post_id);
         $anim_path_css=array_unique($anim_path_css);
          \WpAnimatorHelper\CssFileHelper::create_file($anim_path_css,$this->css_path);
    }
    public function anim_validat_sanitiz_data($item,$data){
        switch($item['type']){
            case "upload_img":
                $data= intval(sanitize_key( $data ));
                return (!$data)?-1:$data;
                break;
            case "text_numeric":
                $data= floatval(sanitize_text_field( $data ));
                return (!$data)?$item['default']:$data;
                break;
            case "textarea":
                $data=strval(wp_kses_post($data));
                return $data;
                break;
            default:
                return strval(sanitize_text_field($data));

        }

    }
    public function anim_delete_post($postid){
       $all_id=$this->animaton_setting->get_ids_element();
       if(in_array($postid,$all_id)){
           \WpAnimatorHelper\CssFileHelper::update_file(NULL);
       }
    }

    public function anim_save_meta_box($post_id){
        if ( ! isset( $_POST['animator_inner_mbox_box_nonce'] ) )
            return $post_id;
        $nonce = $_POST['animator_inner_mbox_box_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'animator_inner_mbox' ) )
            return $post_id;

        if ( defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return $post_id;

        if ( $this->slug == $_POST['post_type'] ) {

            if (!current_user_can('edit_page', $post_id))
                return $post_id;
        }else {
            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }
        $this->meta_data_box =  apply_filters('animator_meta_form_data',  $this->meta_data_box);
        foreach ($this->meta_data_box as $item){
            if(isset($_POST[$item['meta_id']] )){
                $temp= $this->anim_validat_sanitiz_data($item,$_POST[$item['meta_id']]);
                update_post_meta( $post_id, $item['meta_id'], $temp);
            }elseif($item['type']=='checkbox'){
                $temp=(isset($_POST[$item['meta_id']]))?$this->anim_validat_sanitiz_data($item,$_POST[$item['meta_id']]):"";
                update_post_meta( $post_id, $item['meta_id'], $temp);
            }

            if(isset($item['checkbox'])){
                $temp=(isset($_POST["is_check_".$item['meta_id']]))?$this->anim_validat_sanitiz_data($item,$_POST["is_check_".$item['meta_id']]):false;
                update_post_meta( $post_id, "is_check_".$item['meta_id'], $temp);
            }
        }
        $this->create_css_file($post_id);
    }
}
