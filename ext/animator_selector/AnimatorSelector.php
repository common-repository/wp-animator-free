<?php
namespace AnimatorExtension;
/**
 * Description of AnimatorSelector. Extension  selector - The ability to assign an animation to any page element
 *
 * @author Pavlo
 */
include_once WPANIMATOR_PATH . 'ext/animator_selector/SelectorCSSHelper.php';
class AnimatorSelector extends Extensions
{
public function __construct()
{
    parent::__construct();
    $this->ext_meta_data =array(
        array(
            'type'=>'checkbox',
            'label'=>__("Use selector option ",'animator'),
            'meta_id'=>'anim_is_selector',
            'value'=>'',
            'desc'=>'',
            'temp_value'=>true
        ),
        array(
            'type'=>'text',
            'label'=>"Name of the selector: ",
            'meta_id'=>'anim_selector',
            'value'=>'',
            'desc'=>'Enter id or class your selector/item. <a href="https://dailypost.wordpress.com/2013/07/25/css-selectors/" target="_blank"> About selectors </a>',
            'default'=>''
        ),

    );

}

public function init()
{
    parent::init();
    add_filter('anim_add_content_to_section', array($this, 'anim_add_meta_filds_additional'),10, 3);
    add_action('admin_enqueue_scripts',array($this,'add_script_admin'));
    add_filter('animator_add_css_style', array($this, 'animator_add_css_style'));
    add_filter('animator_data_element', array($this, 'anim_corect_element_data'),13);
}
public function add_script_admin(){
    wp_enqueue_script('anim_js_selector', WPANIMATOR_LINK.'/ext/animator_selector/js/anim_selector.js', array('jquery'));
}
    public function anim_add_meta_filds_additional($content,$id,$post){
        if($id!="Id1"){
            return $content;
        }
        $meta_value=array();
        $meta_value=$this->fill_data_in_form($post);
        $html_filds="";
        $html_filds = \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,0,1,"col-2");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,1,2,"col-2");
        $content.=\WpAnimatorHelper\HtmlFormHelper::get_sections_optional($html_filds,__('Selector','animator'),'anim_selector');
        return $content;
    }
    public function animator_add_css_style($css_selector){
        $css_selector = $css_selector."/* Start selector style */";
        $this->ids_elements=$this->animator_settings->get_ids_element();
		$i=0;
        foreach($this->animator_settings->get_ids_element() as $item){
			//if($i>3){break;}
            if(get_post_meta($item->ID,'anim_is_selector',true) AND get_post_meta($item->ID,'anim_selector',true)!="" ){
                $css_selector.=SelectorCSSHelper::get_style_selector($this->get_all_data_selector($item->ID));
            }
			$i++;
        }

        return $css_selector."/* end selector style */";
    }
    public function get_all_data_selector($id)
    {
        $elemet_data=array();
        $data_name_id=array("anim_animation","anim_time","anim_iteration","anim_additional_css","anim_direction","anim_hover","anim_timinf_func","anim_delay","anim_fill_mode","anim_selector");
        foreach ($data_name_id as $data) {
            $elemet_data[$data] = get_post_meta($id, $data, true);
            $elemet_data['id_pos'] = $id;
            if (isset($data['checkbox'])):
                $elemet_data[$data['checkbox']] = get_post_meta($id, "is_check_" . $data['meta_id'], true);
            elseif ($data == 'anim_iteration' AND (int)get_post_meta($id, $data, true) < 0):
                $elemet_data[$data] = 'infinite';
            endif;
        }
        //fix for AnimatorlistenerJs
        if(get_post_meta($id, 'anim_js_start_element', true)){
            $elemet_data['anim_additional_css'].="animation-play-state:paused;";
        }
        return $elemet_data;
    }
    public function anim_corect_element_data($data){
       // var_dump($data);
        for ($i = 0; $i < count($data); $i++) {
           if (get_post_meta($data[$i]['id_pos'], 'anim_is_selector', true)) {
                $data[$i]['anim_selector']= get_post_meta($data[$i]['id_pos'], 'anim_selector', true);
                $data[$i]['not_element']=TRUE;
            }
        }
        return $data;
    }
}
$animator_selector=new AnimatorSelector();
$animator_selector->init();