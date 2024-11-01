<?php

namespace animator;


use element\Content;
use element\Image;
use WpAnimatorHelper\HtmlAnimHelper;
/**
 * Description of Animator_front. Functionality  on front side. Formation of elements and displays them on the screen
 *
 * @author Pavlo
 */
class Animator_front
{
    protected $elemet_data;
    public $elements=array();
    protected $anim_sett=array();
    protected $version="";
    protected $is_use_additional_shortcode=false;
    protected $css_name="";
    protected $setting;
    protected $cache=NULL;
    private $cache_key="animator_elements_data";

    public function __construct(){

        $this->is_use_additional_shortcode=false;
        $this->setting=\AnimOptions::getInstance();
        $this->css_name=$this->setting->get_css_name();
        $this->anim_sett=$this->setting->get_settings();
        $this->version=$this->setting->get_version();
        $cache_data=$this->setting->cache->check_transient($this->cache_key);
        //$cache_data=false;
        if($cache_data==false){
            $this->elemet_data =$this->preparation_data_element($this->setting,$this->get_all_id_posts());
            $this->setting->cache->set_data($this->cache_key,$this->elemet_data);
        }else{
            $this->elemet_data=$cache_data;
        }
        $this->setting->elements_data=$this->elemet_data;
        $this->elements=$this->fabric_elememt($this->elemet_data);
    }
    public function init(){
        add_action('wp_enqueue_scripts', array($this, 'add_styles_script'));
        add_shortcode('wp_animator_container', array($this, 'add_alternative_animator_contenier'));
        add_action( 'wp_footer',array($this, 'add_animator_contenier') );

    }

    protected function fabric_elememt($element_data){
        if(!is_array($element_data)){
           $element_data=array();
        }
        $elements=array();
        foreach($element_data as $data){
            if($data==NULL OR $data['id_pos']==NULL OR ( isset($data['not_element']) AND $data['not_element'] ))continue;
            if(isset($data['content']) AND $data['content']==true ):
                $elements[]=new \element\Content($data);
            else:
                $elements[]=new \element\Image($data);
            endif;

        }
        return $elements;
    }
    public function add_styles_script(){
        echo'<meta http-equiv="X-UA-Compatible" content="IE=9;IE=10;IE=Edge,chrome=1"/>'; // IE compatibility
        $add_style=true;
        // For exclude style on exact page
        $add_style=apply_filters('animator_enqueue_style',  $add_style);
        if($add_style) {
            wp_enqueue_style('anim_style_css', WPANIMATOR_LINK.'/css/gen_css/' . $this->css_name);
            wp_add_inline_style('anim_style_css', $this->add_stylesheet_to_head($this->elements));
        }
    }
    private function get_all_id_posts(){
        global $wpdb;
        $posts_id=array();
        $posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='".$this->anim_sett['slug']."' ORDER BY post_date DESC   LIMIT 3 ");
        foreach ($posts as $post) {
            $posts_id[]=$post->ID;
        }
        return $posts_id;
    }
    public  function  add_stylesheet_to_head($elem=array() ){
        $temp_style="";
        foreach($elem as $item):
            if($item->get_exact_page() ):
                   $temp_style.=$item->get_anim_style_head($item);
                endif;
        endforeach;
        return $temp_style;
    }
    public  function  add_stylesheet_shortcode($elem=array() ){
        $temp_style="";
        foreach($elem as $item):
                $temp_style.=$item->get_anim_style_head($item);
        endforeach;
        return HtmlAnimHelper::get_style_container( $temp_style);
    }
    public function add_animator_contenier() {
        // add filter if additional shortcode paste after footer
        $this->is_use_additional_shortcode=apply_filters('is_use_additional_shortcode',  $this->is_use_additional_shortcode);
        if($this->is_use_additional_shortcode)return;
        $html_items="";
        $html_items=$this->get_html_contteier();
        if(isset($_REQUEST['is_shortcode_works']) AND $_REQUEST['is_shortcode_works']==true ){
            $html_items.=$this->animation_get_data_shortcode();
        }
        echo HtmlAnimHelper::get_animation_container($html_items,$this->version);
    }
    public function add_alternative_animator_contenier( $atts){
        $this->is_use_additional_shortcode=true;
        $html_items="";
        $html_items=$this->get_html_contteier();
        if(isset($_REQUEST['is_shortcode_works']) AND $_REQUEST['is_shortcode_works']==true ){
            $html_items.=$this->animation_get_data_shortcode();
        }
        echo HtmlAnimHelper::get_animation_container($html_items,$this->version." - use help_shortcode");
    }
    public function animation_get_data_shortcode(){
      if(!isset($_REQUEST['animator_id_colection']) AND empty($_REQUEST['animator_id_colection']))return;
        $ids=array();
        $ids=explode(",",$_REQUEST['animator_id_colection']);
        $elem=array();
        $elem=$this->fabric_elememt($this->preparation_data_element($this->setting,$ids));
        $html_elem="";
        foreach($elem as $item):
                $html_elem.=$item->get_anim_html();
        endforeach;
        echo $this->add_stylesheet_shortcode($elem);
        return $html_elem;
    }
    protected function get_html_contteier(){
        $html_elem="";
        foreach($this->elements as $item):
            if($item->get_exact_page()):
                $html_elem.=$item->get_anim_html();
            endif;
        endforeach;
        return $html_elem;
    }
    protected  function preparation_data_element($setting, $posts_id=array()){
        $elemet_data=array();
        $i=0;
        foreach($posts_id as $id):
            if($i>3)break;
            foreach($setting->get_meta_data() as $data){
                $elemet_data[$i][$data['meta_id']]=get_post_meta($id,$data['meta_id'],true);
                $elemet_data[$i]['id_pos']=$id;
                if(isset($data['checkbox'])):
                    $elemet_data[$i][$data['checkbox']]=get_post_meta($id,"is_check_".$data['meta_id'],true);
                elseif($data['meta_id']=='anim_iteration' AND (int)get_post_meta($id,$data['meta_id'],true)<0):
                    $elemet_data[$i][$data['meta_id']]='infinite';
                endif;
            }
            $i++;
        endforeach;
        //add filter for element data
        $elemet_data=  apply_filters('animator_data_element',  $elemet_data);
        return $elemet_data;
    }

}