<?php
namespace AnimatorExtension;
/**
 * Description of AnimatorListenerJS. Extension  Listener - Use JS scripts to control animation
 *
 * @author Pavlo
 */

class AnimatorListenerJS extends Extensions
{   public $is_js_init=0;
    public $data_script=array();
    private $anim_css_stop="";
    private $anim_css_hide="";
    public function __construct()
    {

        parent::__construct();
        $this->anim_css_stop="-webkit-animation-play-state: paused;
                                -moz-animation-play-state: paused;
                                -o-animation-play-state: paused;
                               animation-play-state: paused;";
        $this->anim_css_hide="visibility:hidden;";
        $this->ext_meta_data =array( array(
            'type'=>'checkbox',
            'label'=>__("Clip/cut the element outside of the container",'animator'),
            'meta_id'=>'anim_js_clip_element',
            'value'=>'',
            'desc'=>'',
            'temp_value'=>true
        ),
            array(
                'type'=>'checkbox',
                'label'=>__("Hide the element outside of the container ",'animator'),
                'meta_id'=>'anim_js_hide_element',
                'value'=>'',
                'desc'=>'',
                'temp_value'=>true
            ),
            array(
                'type'=>'checkbox',
                'label'=>__("Start animation on screen ",'animator'),
                'meta_id'=>'anim_js_start_element',
                'value'=>'',
                'desc'=>'',
                'temp_value'=>true
            ),
            array(
                'type'=>'text',
                'label'=>__("Selector of the container : ",'animator'),
                'meta_id'=>'anim_selector_name',
                'value'=>'',
                'desc'=>'Enter a name of the container.  Example: #top-bar  or .container ',
                'default'=>'body'
            ),
            array(
                'type'=>'checkbox',
                'label'=>__("Use the second animation ",'animator'),
                'meta_id'=>'anim_is_use_animation2',
                'value'=>'',
                'desc'=>'After finished the first animation turn on the second animation ',
                'temp_value'=>true
            ),
            array(
                'type'=>'select',
                'label'=>__('Animation ','animator'),
                'meta_id'=>'anim_animation_2',
                'value'=>'',
                'desc'=>'',
                'options'=>$this->animator_settings->get_anim_name(),
                'default'=>__('Without animation','animator')
            ),
            array(
                'type'=>'text_numeric',
                'label'=>__('Time','animator'),
                'meta_id'=>'anim_time_2',
                'value'=>'',
                'desc'=>__('The duration of the animation','animator'),
                'default'=>10,
                'step'=>0.1,
                'min'=>0,
                'max'=>999,
            ),

            array(
                'type'=>'text_numeric',
                'label'=>__('Iteration','animator'),
                'meta_id'=>'anim_iteration_2',
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
                'meta_id'=>'anim_direction_2',
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
                'label'=>"Animation timing function",
                'meta_id'=>'anim_timinf_func_2',
                'value'=>'',
                'desc'=>'<br>',
                'options'=>array(
                    'ease-in-out'=>'ease-in-out',
                    'ease-out'=>'ease-out',
                    'ease-in'=>'ease-in',
                    'ease'=>'ease',
                    'linear'=>'linear',
                ),
                'default'=>'linear'
            ),array(
                'type'=>'text_numeric',
                'label'=>"Animation delay",
                'meta_id'=>'anim_delay_2',
                'value'=>'',
                'desc'=>'In sec.',
                'default'=>0,
                'step'=>0.1,
                'min'=>0,
                'max'=>999,
            ),
            array(
                'type'=>'select',
                'label'=>"Fill mode",
                'meta_id'=>'anim_fill_mode_2',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    'none'=>'None',
                    'forwards'=>'Forwards',
                    'backwards'=>'Backwards',
                    'both'=>'Both',
                ),
                'none'=>'None'
            ),
        );
    }

    public function init()
    {
        parent::init();
        add_filter('animator_list_meta_filds', array($this, 'anim_add_meta_filds'),1, 2);
        add_filter('animator_data_element', array($this, 'anim_check_element_data'),30);
        add_filter('animator_add_path_css', array($this, 'animator_add_path_css'),1,2);
      //  add_action('wp_footer',array($this,'add_script_front'));
    }
    public  function add_script_front(){
        wp_enqueue_script('spec_listener', WPANIMATOR_LINK.'/ext/animator_listener/js/front.js', array('jquery'),999);
        if(count($this->data_script)>0){
            wp_localize_script( 'spec_listener', 'animation_data', $this->data_script);
        }
    }
    public  function anim_check_element_data($elements){

        //if($this->is_js_init)return $elements; //single init
        $this->is_js_init=1;
        foreach($elements as $element){
            if($element==NULL OR $element['id_pos']==Null)continue;

            $key_element="";
            if(isset($element['not_element']) AND $element['not_element'] AND isset($element['anim_selector']) AND !empty($element['anim_selector'])){
                $key_element=$element['anim_selector'];
            }else{
                $key_element="#element_".$element['id_pos'];
            }
            if(empty($key_element))continue;
            if(!get_post_meta($element['id_pos'],'anim_is_use_animation2',true) AND !get_post_meta($element['id_pos'],'anim_js_clip_element',true) AND !get_post_meta($element['id_pos'],'anim_js_hide_element',true) AND !get_post_meta($element['id_pos'],'anim_js_start_element',true) )continue;

            foreach($this->ext_meta_data as $data){
                $this->data_script[$key_element]['id_element']=$element['id_pos'];
                $this->data_script[$key_element][$data['meta_id']]=get_post_meta($element['id_pos'],$data['meta_id'],true);

            }
            $js_start_el=get_post_meta($element['id_pos'],'anim_js_start_element',true);
            $hide_el_cont=get_post_meta($element['id_pos'],'anim_js_hide_element',true);
            if($js_start_el==1 OR $hide_el_cont ==1 ){
                for($i=0;$i<count($elements);$i++){
                    if($elements[$i]==NULL)continue;
                    if($js_start_el==1) {
                        $elements[$i]['anim_additional_css'] .= ($elements[$i]['id_pos'] == $element['id_pos']) ? $this->anim_css_stop : "";
                    }elseif($hide_el_cont ==1){
                        $elements[$i]['anim_additional_css'] .= ($elements[$i]['id_pos'] == $element['id_pos']) ? $this->anim_css_hide : "";
                    }
                }
            }

        }
        if(count($this->data_script)>0){
            add_action('wp_footer',array($this,'add_script_front'));
        }
        return $elements;
    }

    public function anim_add_meta_filds($meta_filds,$post){
        for($i=0;$i<count($this->ext_meta_data);$i++){
            if($this->ext_meta_data[$i]['meta_id']=='anim_animation_2'){
                $this->ext_meta_data[$i]['options']=apply_filters('animator_animation_names', $this->ext_meta_data[$i]['options']);
            }
        }

        $meta_value=array();
        $meta_value=$this->fill_data_in_form($post);
        $html_filds="";
        $html_temp="";
        $html_temp = \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,0,3,"col-2");
        $html_temp.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,3,4,"col-2");
        $html_filds=\WpAnimatorHelper\HtmlFormHelper::get_sections_optional($html_temp,__('Hide or clip','animator'),'hide_clip');

        $html_temp=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,4,6,"col-3");
        $html_temp.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,6,9,"col-3");
        $html_temp.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,9,12,"col-3");

        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_sections_optional($html_temp,__('Second animatiom','animator'),'sec_anim');
        //$html[]=HtmlFormHelper::get_button_toggle_sections($html_filds,__("Mobil options",'animator'),__("Options for mobile device",'animator'),"Id2");
        array_splice($meta_filds, 8, 0, \WpAnimatorHelper\HtmlFormHelper::get_button_toggle_sections($html_filds,__("JS controler",'animator'),__("Advanced animations with JS",'animator'),"id_js","class_js"));
        return $meta_filds;
    }
    public function animator_add_path_css($paths,$id_post){
        $elements_ids= $this->animator_settings->get_ids_element();
        foreach($elements_ids as $id){
            if(get_post_meta($id->ID,'anim_is_use_animation2',true)){
                $anim_name= get_post_meta($id->ID,'anim_animation_2',true);
                if(!empty($anim_name)){
                    $paths[]=WPANIMATOR_LINK.'css/animations/'.trim($anim_name).'.css';
                }
            }
        }
        return $paths;
    }
}
$animator_listen=new AnimatorListenerJS();
$animator_listen->init();