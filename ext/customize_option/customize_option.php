<?php
namespace AnimatorExtension;
if (!defined('ABSPATH')) die('No direct access allowed');

/**
 * Description of customize_option. Extension  - You can add animation on customize of the theme
 *
 * @author Pavlo
 */

class CostumizeOption extends Extensions
{   
    public $meta_data_selector=array();
    private $css_path="";
    public $is_costomize=false;
    private $setting_fields=array();
    public function __construct() {
        parent::__construct();
        $this->meta_data_selector = array(
            array(
                'type'=>'text',
                'label'=>"Name of the selector: ",
                'meta_id'=>'anim_selector_customize',
                'value'=>'',
                'desc'=>'',
                'default'=>''
            ),
            array(
                'type'=>'select',
                'label'=>__('Animation ','animator'),
                'meta_id'=>'anim_animation_customize',
                'value'=>'',
                'desc'=>'',
                'options'=>$this->animator_settings->get_anim_name(),
                'default'=>__('Without animation','animator')
            ),
            array(
                'type'=>'text_numeric',
                'label'=>__('Time','animator'),
                'meta_id'=>'anim_time_customize',
                'value'=>'',
                'desc'=>'',
                'default'=>5,
                'step'=>0.1,
                'min'=>0,
                'max'=>999,
            ),

            array(
                'type'=>'text_numeric',
                'label'=>__('Iteration','animator'),
                'meta_id'=>'anim_iteration_customize',
                'value'=>'',
                'desc'=>__('-1 is infinity','animator'),
                'default'=>-1,
                'step'=>1,
                'min'=>-1,
                'max'=>999,
            ),
            array(
                'type'=>'select',
                'label'=>"Animation direction",
                'meta_id'=>'anim_direction_customize',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    'normal'=>'Normal',
                    'reverse'=>'Reverse',
                    'alternate'=>'Alternate',
                    'alternate-reverse'=>'Alternate-reverse',

                ),
                'default'=>'alternate'
            ),
            array(
                'type'=>'select',
                'label'=>"Animation hover",
                'meta_id'=>'anim_hover_customize',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    'hover_off'=>'Without hover',
                    'hover_stop'=>'Stop hover',
                    'hover_start'=>'Start hover',
                ),
                'default'=>'hover_off'
            ),
            array(
                'type'=>'text_numeric',
                'label'=>"Animation delay",
                'meta_id'=>'anim_delay_customize',
                'value'=>'',
                'desc'=>'',
                'default'=>0,
                'step'=>0.1,
                'min'=>0,
                'max'=>999,
            ),
            array(
                'type'=>'select',
                'label'=>"Fill mode",
                'meta_id'=>'anim_fill_mode_customize',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    'none'=>'None',
                    'forwards'=>'Forwards',
                    'backwards'=>'Backwards',
                    'both'=>'Both',
                ),
                'default'=>'none'
            ),

        );
        
        $this->setting_fields=array(
            'show_opt_button'=>array(
                    'key'=>'show_opt_button',
                    'type'=>'select',
                    'sanit'=>'int',
                    'default'=>0,
                    'label'=>__("Show button to quickly add elements",'animator'),
                    'options'=>array(
                        0=>__("No",'animator'),
                        1=>__("Yes, on customize page ",'animator'),
                        2=>__("Yes, on front-end",'animator'),
                        3=>__("Yes, on customize page and front-end",'animator'),
                    ),
            ),
        );
        
       $this->css_path= WPANIMATOR_PATH.'css/gen_css/'.$this->animator_settings->get_css_name(); 
     //AJAX
	
     add_action('wp_ajax_anim_add_element_ajax', array($this,'anim_add_element_ajax'));
	// add_action('wp_ajax_nopriv_anim_add_element_ajax', array($this,'anim_add_element_ajax'));
     add_action('wp_ajax_anim_try_element_ajax', array($this,'anim_try_element_ajax'));
	// add_action('wp_ajax_nopriv_element_ajax', array($this,'anim_try_element_ajax'));
     //add settings
     add_filter('animator_settings_fields',array($this,'add_setting_fields'));
     
    }

    protected function get_ext_path(){
        return plugin_dir_path(__FILE__);
    }
    public function get_ext_link() {
	return plugin_dir_url(__FILE__);
    }

    public function init(){
        $show_btn=$this->animator_settings->get_animator_settings('show_opt_button');
        if($show_btn===false OR $show_btn===0){
            return;
        }
        if(is_customize_preview()){
            $this->is_costomize=true;
        }
        $user_role=false;
        if(is_user_logged_in() AND current_user_can('manage_options')){
           $user_role=true; 
        }
        $show=false;
        if($show_btn==1 AND $this->is_costomize){
            $show=true;
        }elseif($show_btn==2 AND ($user_role AND !$this->is_costomize)){
            $show=true;
        }elseif($show_btn==3 AND ($user_role OR $this->is_costomize )){
            $show=true;
        }
        if($show){   
            add_action('wp_ajax_animator_delete_element', array($this,'animator_delete_element'));
            add_action('wp_footer',array($this, "draw_menu")); 
        }

    }
    public function draw_menu(){
       wp_enqueue_script("jquery");
       wp_enqueue_script( 'jquery-ui-tabs' );
       wp_enqueue_script("jquery-ui-draggable");
       wp_enqueue_style('anim_customize',$this->get_ext_link().'css/customize_option.css') ;
       wp_enqueue_style('anim_opt_tada',WPANIMATOR_LINK.'css/animations/tada.css') ;
       wp_enqueue_script('anim_customize_js',$this->get_ext_link().'js/custumize_option.js', array('jquery')); 
      // var_dump($this->animator_settings->get_elements_data(););
       $data=array(); 
       //$data['meta_fields_selector']=$this->meta_data_selector;
       $data['costomize']=$this->is_costomize;
       $data['selector_html']=$this->create_selector_html();
       $data['all_elements']=$this->animator_settings->get_elements_data();
       echo $this->render_html($this->get_ext_path()."views/menu.php",$data);
    }
    public function create_selector_html(){
        $html="";
        $html_tmp="";
        $meta_value=$this->fill_data_in_form(null,$this->meta_data_selector);
        $html_tmp = \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,0,1,"col-1");
        $html_tmp.= \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,1,2,"col-2");
        $html_tmp.= \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,2,3,"col-2");
        $html_tmp.= \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,3,4,"col-2");
        $html=\WpAnimatorHelper\HtmlFormHelper::get_sections_optional($html_tmp,__('','animator'),'anim_costom_select');
        $html_tmp=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,4,5,"col-2");
        $html_tmp.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,5,6,"col-2");
        $html_tmp.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,6,7,"col-2");
        $html_tmp.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,7,8,"col-2");
        $html.=\WpAnimatorHelper\HtmlFormHelper::get_button_toggle_sections($html_tmp,__("Additional",'animator'),"","Id_2_2_2");
        return $html;
    }
    public function fill_data_in_form($post,$fields=array()){
        foreach ($fields as $item):
            $temp=$this->get_form_item($item, $post);
            $meta_value[]=\WpAnimatorHelper\HtmlFormHelper::get_html_input($temp);
        endforeach;
        return $meta_value;
    }
    public  function get_form_item($arg, $post=null){
        $arg['value']="";
        if($post){
           $arg['value']=get_post_meta($post->ID,$arg['meta_id'],true);            
        }
        if((empty($arg['value'])OR $arg['value']=="")AND isset($arg['default'])):
            $arg['value']=$arg['default'];
        endif;
        return $arg;
    }
    //ajax
    public function anim_add_element_ajax(){
        $type = (isset($_POST['anim_type']) && $_POST['anim_type'])?$_POST['anim_type']:'selector';
        check_ajax_referer( 'animator_add_anim', 'security' );
        $title="";
        if(isset($_POST['anim_title'])){
           $title=sanitize_text_field($_POST['anim_title']); 
        }
        $post_id = wp_insert_post( array(
            'post_status'   => 'publish',
            'post_type'     => $this->data_post['slug'],
            'post_author'   => get_current_user_id() ,
            'post_name'     => $title,
            'post_title'    => $title,
            'meta_input'     => $this->prepare_metadate_ajax($_POST,$type),
        ) );
        $this->create_css_file($post_id);
        exit("Done!!!");
    }
    public function anim_try_element_ajax(){
        $data=array();
        $meta_input  = $this->prepare_metadate_ajax($_POST,'selector');
        check_ajax_referer( 'animator_add_anim', 'security' );
        $data['css_style']=SelectorCSSHelper::get_style_selector($meta_input);
        $data['css_href']=WPANIMATOR_LINK.'css/animations/'.trim($meta_input['anim_animation']).'.css';
        exit(json_encode($data));
    }
    public function animator_delete_element(){
        check_ajax_referer( 'animator_edit_anim', 'security' );
        if(isset($_POST['animator_id_element'])){
            $id=intval(sanitize_text_field($_POST['animator_id_element']));
            wp_delete_post( $id, true);
            $this->create_css_file($id);
            die("Deleted");
        }
    }   
    
    public function add_setting_fields($fields){
        return array_merge($fields,$this->setting_fields);
    }

    protected function prepare_metadate_ajax($post_data,$type){
        $meta_data=array();
        $array_data=array();
        switch($type){
            case 'selector':
                $array_data=$this->meta_data_selector;
                $meta_data['anim_is_selector']=true;
                break;
            default:
                break;
        }
        foreach($array_data as $val){
            $curr_key=str_replace("_customize", "", $val['meta_id']);
            if(!isset($post_data[$curr_key])  ){
                $meta_data[$curr_key]=(isset($val['default']))?$val['default']:'';
            }else{
                //$meta_data[$curr_key]=sanitize_text_field($post_data[$curr_key]);
				
				$meta_data[$curr_key]=sanitize_text_field($post_data[$curr_key]);
            }
            
        }
        if($meta_data['anim_iteration']<1){
            $meta_data['anim_iteration']='infinite';
        }
        $meta_data=$this->check_all_meta($meta_data);
        return $meta_data;
    }
    protected function check_all_meta($meta_data){
        $all_metas=$this->animator_settings->get_meta_data();
        foreach($all_metas as $meta){
            if(!isset($meta_data[$meta['meta_id']]) AND isset($meta['default'])){
                $meta_data[$meta['meta_id']]=$meta['default'];
            }
        }
        return $meta_data;
    }
    private  function  create_css_file($post_id){
          \WpAnimatorHelper\CssFileHelper::update_file($post_id);
//        $anim_path_css=array();
//        global $wpdb;
//        $posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='".$this->data_post['slug']."' ");
//        $this->animator_settings->set_ids_element($posts);
//        $anim_path_css[]=WPANIMATOR_LINK.'css/anim-style.css';
//        foreach ($posts as $post) {
//            if(get_post_meta($post->ID,'anim_custom',true)){
//                $anim_path_css[]=get_stylesheet_directory_uri() .'/animations/'.trim(get_post_meta($post->ID,'anim_name_custom',true)).'.css';
//            }else{
//                $anim_path_css[]=WPANIMATOR_LINK.'css/animations/'.trim(get_post_meta($post->ID,'anim_animation',true)).'.css';
//            }
//        }
//         $anim_path_css = apply_filters('animator_add_path_css',$anim_path_css,$post_id);
//         $anim_path_css=array_unique($anim_path_css);
//          \WpAnimatorHelper\CssFileHelper::create_file($anim_path_css,$this->css_path);
    }   

}
add_action('init',function(){
    $customize_option=new CostumizeOption();
    $customize_option->init(); 
    
});