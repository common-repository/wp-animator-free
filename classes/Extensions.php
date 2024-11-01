<?php

namespace AnimatorExtension;


class Extensions
{
    protected $data_post=array();
    protected $ext_meta_data=array();
    protected $ids_elements=array();
    protected $animator_settings;
    public  function __construct()
    {
        $this->animator_settings=\AnimOptions::getInstance();
        $this->data_post =$this->animator_settings->get_settings();
    }
    public function init(){
        add_filter('animator_meta_form_data', array($this, 'anim_add_meta_options'), 1);
          //'animator_data_element','animator_add_path_css','animator_list_meta_filds',
          //"anim_eval_override",'animator_get_html_element',"animator_enqueue_style",
          //'animator_meta_form_data',"is_use_additional_shortcode","anim_add_content_to_section"
        //"animator_add_css_style"
    }
    public function anim_add_meta_options($meta_data){
        return  array_merge($meta_data,$this->ext_meta_data);
    }

    public function fill_data_in_form($post){
        foreach ($this->ext_meta_data as $item):
            $temp=$this->get_form_item($item, $post);
            $meta_value[]=\WpAnimatorHelper\HtmlFormHelper::get_html_input($temp);
        endforeach;
        return $meta_value;
    }
    public  function get_form_item($arg, $post){
        $arg['value']=get_post_meta($post->ID,$arg['meta_id'],true);
        if((empty($arg['value'])OR $arg['value']=="")AND isset($arg['default'])):
            $arg['value']=$arg['default'];
        endif;
        return $arg;
    }
    public function render_html($pagepath, $data = array())
    {
	@extract($data);
	ob_start();
	include($pagepath);
	return ob_get_clean();
    }


}