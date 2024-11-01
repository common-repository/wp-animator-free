<?php

namespace AnimatorExtension;
/**
 * Description of AnimatorMobil. Extension Mobil - changing element option on any mobile device
 *
 * @author Pavlo
 */

class AnimatorMobil extends Extensions
{

    public function __construct()
    {
        parent::__construct();
        $this->ext_meta_data =array(
            array(
                'type'=>'checkbox',
                'label'=>__("Change on mobile device: ",'animator'),
                'meta_id'=>'anim_change_mobile',
                'value'=>'',
                'desc'=>'',
                'temp_value'=>true
            ),
            array(
                'type'=>'checkbox',
                'label'=>__("Hide on mobile device: ",'animator'),
                'meta_id'=>'anim_hide_mobile',
                'value'=>'',
                'desc'=>'',
                'temp_value'=>true
            ), array(
                'type'=>'text_numeric',
                'label'=>__('Width','animator'),
                'meta_id'=>'anim_widh_img_mobile',
                'value'=>'',
                'desc'=>'',
                'default'=>100,
                'step'=>1,
                'min'=>0,
                'max'=>9999,
            ),
            array(
                'type'=>'select',
                'label'=>"",
                'meta_id'=>'anim_type_width_mobile',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    '%'=>'%',
                    'px'=>'px'
                ),
                'default'=>'px'
            ),
            array(
                'type'=>'select',
                'label'=>__('Position X','animator'),
                'meta_id'=>'anim_side_x_mobile',
                'value'=>'',
                'desc'=>"",
                'options'=>array(
                    'top'=>__('Top','animator'),
                    'bottom'=>__('Bottom','animator')
                ),
                'default'=>'top'
            ),
            array(
                'type'=>'text_numeric',
                'label'=>"",
                'meta_id'=>'anim_pos_x_mobile',
                'value'=>'',
                'desc'=>'',
                'default'=>0,
                'step'=>1,
                'min'=>-999,
                'max'=>9999,
            ),
            array(
                'type'=>'select',
                'label'=>"",
                'meta_id'=>'anim_type_x_mobile',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    '%'=>'%',
                    'px'=>'px'
                ),
                'default'=>'%'
            ),
            array(
                'type'=>'select',
                'label'=>__('Position Y','animator'),
                'meta_id'=>'anim_side_y_mobile',
                'value'=>'',
                'desc'=>"",
                'options'=>array(
                    'left'=>__('Left','animator'),
                    'right'=>__('Right','animator')
                ),
                'default'=>'left'
            ),
            array(
                'type'=>'text_numeric',
                'label'=>"",
                'meta_id'=>'anim_pos_y_mobile',
                'value'=>'',
                'desc'=>'',
                'default'=>0,
                'step'=>1,
                'min'=>-999,
                'max'=>9999,
            ),
            array(
                'type'=>'select',
                'label'=>"",
                'meta_id'=>'anim_type_y_mobile',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    '%'=>'%',
                    'px'=>'px'
                ),
                'default'=>'%'
            ),
         array(
                'type'=>'text_numeric',
                'label'=>__('Z-index','animator'),
                'meta_id'=>'anim_z_index_mobile',
                'value'=>'',
                'desc'=>__('Z-Index of the element','animator'),
                'default'=>1,
                'step'=>1,
                'min'=>-99,
                'max'=>99999,
            ),

        );

    }
    public function init()
    {
        parent::init();
        //add_filter('animator_list_meta_filds', array($this, 'anim_add_meta_filds'),1, 2);
        add_filter('anim_add_content_to_section', array($this, 'anim_add_meta_filds_additional'),1, 3);
        add_filter('animator_data_element', array($this, 'anim_corect_element_data'),1);

    }

    public function anim_add_meta_filds($meta_filds,$post){
        $meta_value=array();
        $meta_value=$this->fill_data_in_form($post);
        $html_filds="";
        $html_filds = \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,0,1,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,1,2,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,2,4,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,4,7,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,7,10,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,10,11,"col-3");
        $html_filds=\WpAnimatorHelper\HtmlFormHelper::get_sections_optional($html_filds,__('Mobil','animator'),'mobl');
        //$html[]=HtmlFormHelper::get_button_toggle_sections($html_filds,__("Mobil options",'animator'),__("Options for mobile device",'animator'),"Id2");
        array_splice($meta_filds, 8, 0, \WpAnimatorHelper\HtmlFormHelper::get_button_toggle_sections($html_filds,__("Mobil options",'animator'),__("Options for mobile device",'animator'),"Id2"));
        return $meta_filds;
    }
    public function anim_add_meta_filds_additional($content,$id,$post){
        if($id!="Id1"){
            return $content;
        }
        $meta_value=array();
        $meta_value=$this->fill_data_in_form($post);
        $html_filds="";
        $html_filds = \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,0,1,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,1,2,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,2,4,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,4,7,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,7,10,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,10,11,"col-3");
        $content.=\WpAnimatorHelper\HtmlFormHelper::get_sections_optional($html_filds,__('Mobil','animator'),"mobil");
        return $content;
    }
    public function anim_corect_element_data($data){
       //var_dump($data);
        if(!wp_is_mobile())
            return $data;
        for ($i = 0; $i < count($data); $i++) {
            if (get_post_meta($data[$i]['id_pos'], 'anim_hide_mobile', true)) {
                $data[$i]=NULL;
                continue;
            }elseif (get_post_meta($data[$i]['id_pos'], 'anim_change_mobile', true)) {

                foreach($this->ext_meta_data as $item_data){
                    $key=substr($item_data['meta_id'], 0, -7);
                    if(isset($data[$i][$key])){
                        $data[$i][$key]=get_post_meta($data[$i]['id_pos'], $item_data['meta_id'], true);
                    }
                }
            }
        }
        return $data;
    }

}

$animator_bahavior_mobil=new AnimatorMobil();
$animator_bahavior_mobil->init();