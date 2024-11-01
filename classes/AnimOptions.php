<?php
/**
 * Description of AnimOptions. Main settings
 *
 * @author Pavlo
 */
include_once WPANIMATOR_PATH . 'classes/caching.php';

class AnimOptions
{
    private $settings = array();
    private $meta_data = array();
    private $debag=true;
    private $version="1.1.0";
    private $animations_name=array();
    private $css_file="";
    private static $_instance = null;
    private $ids_elements=array();
    public $elements_data=array();
    public $cache=NULL;
    protected $animator_settings=array();
    public $settings_key='animator_settings';
    private function __construct() {
       $this->css_file="wp-animator-style.css";
       $this->animator_settings= get_option($this->settings_key,array());
        $this->settings=array(
            'slug'=>'animation',
            'slug_cat'=>'group_animation',
            'slug_rew'=>'animator'
        );
        $this->animations_name=array(
            ''=>__('Without animation','animator'),
            'austronaut'=>'Austronaut',
            'blinker'=>'Blinker',
            'bounce'=>'Bounce',
            'bounce-left'=>'Bounce left',
            'bounce-out'=>'Bounce out',
            'cow'=>'Cow',
            'label'=>'Label',
            'rotate-in-down'=>'Rotate in down',
            'rotate-opacity'=>'Rotate+opacity',
            'shake'=>'Shake',
            'shake-2'=>'Shake lite',
            'shake-3'=>'Shake slow',
            'shake-4'=>'Shake Hard',
            'shake-5'=>'Shake horizontal',
            'shake-6'=>'Shake vertical',
            'shake-7'=>'Shake rotate',
            'shake-8'=>'Shake opacity',
            'shake-9'=>'Shake crazy',
            'show-scale'=>'Show scale',
            'scale-2x'=>'Scale 2x',
            'simply-rotate'=>'Simply rotate',
            'speed-in'=>'Speed in',
            'swing'=>'Swing',
            'orbit'=>'Orbit',
            'orbit-long'=>'Orbit long',
            'rot-rocket'=>'Orbit long+',
            'orbit-long-2'=>'Orbit more long ',
            'rot'=>'Orbit more long+',
            'rot-back'=>'Orbit Spiral',
            'orbit-r'=>'Orbit rotate',
            'orbit-long-r'=>'Orbit long rotate',
            'orbit-long-2-r'=>'Orbit more long rotate',
            'nlo'=>'*NLO',
            'pulse-border'=>'Pulse border',
            'pulse-shadow-min'=>'Pulse shadow',
            'pulsate'=>'Pulsate',
            'pulsate-big'=>'Pulsate big',
            'move'=>'Move horizontally',
            'horizontall-linea'=>'Move horizontally relative',
            'horizantall-linea-full'=>'Move horizontally-2',
            'move-diagonal-l'=>'Move diagonal L',
            'move-diagonal-r'=>'Move diagonal R',
            'move-vertical'=>'Move vertical',
            'move-vertical-2'=>'Move vertical 2',
            'tada'=>'Tada',
            'tossing'=>'Tossing',
            'woble'=>'Woble',


        );
        $this->meta_data = array(

            array(
                'type'=>'upload_img',
                'label'=>__('Select image','animator'),
                'meta_id'=>'anim_elem_img',
                'value'=>'',
                'src'=>'',
                'desc'=>__('Select image for animation','animator'),
                'checkbox'=>'content',
                'default'=>''

            ),
            array(
                'type'=>'textarea',
                'label'=>__('Content','animator'),
                'meta_id'=>'anim_elem_content',
                'value'=>'',
                'desc'=>__('Put content for animation','animator'),
                'default'=>''
            ),
            array(
                'type'=>'select',
                'label'=>__('Position X','animator'),
                'meta_id'=>'anim_side_x',
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
                'meta_id'=>'anim_pos_x',
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
                'meta_id'=>'anim_type_x',
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
                'meta_id'=>'anim_side_y',
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
                'meta_id'=>'anim_pos_y',
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
                'meta_id'=>'anim_type_y',
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
                'label'=>__('Width','animator'),
                'meta_id'=>'anim_widh_img',
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
                'meta_id'=>'anim_type_width',
                'value'=>'',
                'desc'=>'',
                'options'=>array(
                    '%'=>'%',
                    'px'=>'px'
                ),
                'default'=>'px'
            ), array(
                'type'=>'text_numeric',
                'label'=>__('Z-index','animator'),
                'meta_id'=>'anim_z_index',
                'value'=>'',
                'desc'=>__('Z-Index of the element','animator'),
                'default'=>1,
                'step'=>1,
                'min'=>-99,
                'max'=>99999,
            ),
            array(
                'type'=>'checkbox',
                'label'=>"Position fixed: ",
                'meta_id'=>'anim_position',
                'value'=>'position: fixed;',
                'desc'=>'',
                'temp_value'=>'position: fixed;'

            ),
            array(
                'type'=>'select',
                'label'=>__('Animation ','animator'),
                'meta_id'=>'anim_animation',
                'value'=>'',
                'desc'=>'',
                'options'=>$this->animations_name,
                'default'=>__('Without animation','animator')
            ),
            array(
                'type'=>'text_numeric',
                'label'=>__('Time','animator'),
                'meta_id'=>'anim_time',
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
                'meta_id'=>'anim_iteration',
                'value'=>'',
                'desc'=>__('-1 is infinity','animator'),
                'default'=>-1,
                'step'=>1,
                'min'=>-1,
                'max'=>999,
            ),

            array(
                'type'=>'textarea',
                'label'=>__('Conditional Tags:','animator'),
                'meta_id'=>'anim_compabil',
                'value'=>'',
                'desc'=>__('Write pages on which the item will appear. Example: is_page(31) or !is_page(25) AND !is_home() . <a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">More about Conditional Tags </a>','animator'),
                'checkbox'=>'compability',
                'default'=>''
            ),
            array(
                'type'=>'textarea',
                'label'=>__('CSS style','animator'),
                'meta_id'=>'anim_additional_css',
                'value'=>'',
                'desc'=>__('Additional CSS style for element','animator'),
                'default'=>''
            ),
            array(
                'type'=>'select',
                'label'=>"Animation direction",
                'meta_id'=>'anim_direction',
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
                'meta_id'=>'anim_hover',
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
                'type'=>'select',
                'label'=>"Animation timing function",
                'meta_id'=>'anim_timinf_func',
                'value'=>'',
                'desc'=>'',
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
                'meta_id'=>'anim_delay',
                'value'=>'',
                'desc'=>'',
                'default'=>0,
                'step'=>0.1,
                'min'=>0,
                'max'=>999,
            ),
            array(
                'type'=>'checkbox',
                'label'=>" Use custom animation: ",
                'meta_id'=>'anim_custom',
                'value'=>'',
                'desc'=>'',
                'temp_value'=>true

            ),
            array(
                'type'=>'text',
                'label'=>"Name custom animation: ",
                'meta_id'=>'anim_name_custom',
                'value'=>'',
                'desc'=>'Enter the name of the animation, the animation name should be such as file name without ".css"',
                'default'=>''
            ),
            array(
                'type'=>'select',
                'label'=>"Fill mode",
                'meta_id'=>'anim_fill_mode',
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
        $use_cache=true;
		if(isset($this->animator_settings['use_cache'])){
			if($this->animator_settings['use_cache']==0){
			  $use_cache=false;  
			}
		}
        $this->cache= new \animator\Caching($use_cache);

    }
    protected function __clone() {
    }
    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function set_ids_element($ids){
        if(is_array($ids)) {
            $this->ids_elements = $ids;
        }
    }
    public function get_ids_element(){
		//return array_slice($this->ids_elements,0,4);
        return $this->ids_elements;
    }
    public function import() {

    }
    public function get_meta_data() {
         return $this->meta_data;
    }
    public function get_settings() {
        return $this->settings;

    }
    public function get_animator_settings($key){
        if(isset($this->animator_settings[$key])){
            return $this->animator_settings[$key];
        }
        return false;
        
    }

    public function get_debag() {
        return $this->debag;

    }
    public function get_anim_name() {
        return $this->animations_name;

    }
    public function get_version() {
        return $this->version;

    }
    public function get_css_name(){
        return $this->css_file;
    }
    public function set_elements_data($data){
        $this->elements_data= $data;
    }
    public function get_elements_data() {
        return $this->elements_data;
    }
}