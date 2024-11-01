<?php
namespace animator;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class AnimSettingPage{
    private $anim_settings=NULL;
    private $option_key='';
    public $options=array();
    private $fields=array();
    public function __construct() {
        $this->anim_settings=\AnimOptions::getInstance();
        $this->option_key=$this->anim_settings->settings_key;
        $this->fields=array(
                'use_cache'=>array(
                        'key'=>'use_cache',
                        'type'=>'select',
                        'sanit'=>'int',
                        'default'=>1,
                        'label'=>__("Animator Cache",'animator'),
                        'options'=>array(
                            0=>__("No",'animator'),
                            1=>__("Yes",'animator'),
                        ),
                ),  
                'animator_delete_cache'=>array(
                        'key'=>'animator_delete_cache',
                        'type'=>'button',
                        'sanit'=>'int',
                        'default'=>1,
                        'label'=>__("Clear Cache",'animator'),
                ),
            );
        $this->fields=apply_filters('animator_settings_fields',$this->fields);
        $this->get_options();
       // var_dump($this->options);
    }
    
    public function init(){
         add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
         add_action('admin_init', array( $this,'animator_settings'));
    }
    
    public function add_settings_page(){
        $setting=$this->anim_settings->get_settings();
        add_submenu_page(
        'edit.php?post_type='.$setting['slug'],
        __('Animator settings', 'animator'),
        __('Settings', 'nimator'),
        'manage_options',
        'animator_settings',
        array($this, 'animator_options_display'));
    }
    public function animator_options_display(){
        $setting=$this->anim_settings->get_settings();
        printf( '<h1>%s</h1>', __('Animator settings', 'animator' ) );
        
        ?><div class="animator_settings_page">
        <form method="post" action="edit.php?page=animator_settings&post_type=<?php echo $setting['slug']?>"><?php
        //settings_fields( '' );

        do_settings_sections( 'option_group' );
        do_settings_sections( 'animator_settings_page' ); 
        //do_action('animator_settings_page');
        submit_button();

        ?></form></div><?php
          
    }
    public function  animator_settings(){
        register_setting( 'option_group', $this->option_key, array($this,'sanitize_callback') );
        add_settings_section( 'animator_section', __("General Settings", 'animator'), '', 'animator_settings_page' ); 

        foreach($this->fields as $key=>$item){       
                add_settings_field('animator_field_'.$key,$item['label'], array($this,'show_options'), 'animator_settings_page', 'animator_section',$key );
                add_settings_field('animator_field_'.$key,$item['label'], array($this,'show_options'), 'animator_settings_page', 'animator_section',$key );
        }       
    }
    public function show_options($key){
        if(isset($this->fields[$key])){
            $name=$this->option_key.'['.$key.']';
            switch($this->fields[$key]['type']){
                    case'input':
                            ?>
                            <input type="text" name="<?php echo $name ?>" value="<?php echo esc_attr( $this->options[$key] ) ?>" />
                            <?php
                    break;
                    case'textarea':
                            ?>
                            <textarea type="text" name="<?php echo $name ?>" ><?php echo esc_attr( $this->options[$key] ) ?></textarea>
                            <?php
                    break;
                    case'select':
                            ?>
                            <select name="<?php echo $name ?>">
                            <?php foreach($this->fields[$key]['options'] as $option=>$title): ?>  
                                <?php
                                $selected="";
                                if($this->options[$key]==$option){
                                  $selected='selected="selected"';  
                                }
                                ?>
                                <option value="<?php echo esc_attr( $option ) ?>" <?php echo $selected ?> ><?php echo esc_attr( $title ) ?></option>
                            <?php endforeach; ?>      
                            </select>
                            <?php
                    break;
                    case'button':
                        ?>
                            <input type="button" name="<?php echo $name ?>" id="<?php echo $key  ?>" value="<?php echo esc_attr( $this->fields[$key]['label'] ) ?>" />
                        <?php
                        break;
                    case'html':
                            echo esc_attr( $this->fields[$key]['label']);
                        break;
                    default:    
                        _e("Something wrong!", 'animator');
            }

        }

    }
    protected function get_options(){
            if(isset($_POST[$this->option_key])){
                update_option($this->option_key,$this->sanitize_callback($_POST[$this->option_key]));
            }
            $opt=get_option($this->option_key,array());
            
            foreach($this->fields as $name=>$item){
                    if(isset($opt[$name])){
                            $this->options[$name]=$opt[$name];
                    }else{
                            $this->options[$name]=(isset($item['default']))?$item['default']:'';
                    }
            }
    }
    function sanitize_callback( $options ){ 
        $new_opt=array();
        foreach( $options as $name =>  $val ){
                if(isset($this->fields[$name])){
                        if($val!==false){
                                $type=(isset($this->fields[$name]['sanit']))?$this->fields[$name]['sanit']:'text';
                                $new_opt[$name]=$this->sanitize_funct($val,$type);
                        }else{
                                $new_opt[$name]=(isset($this->fields[$name]['default']))?$this->fields[$name]['default']:'';
                        }

                }	
        }
        return $new_opt;
    }
    function sanitize_funct($val,$type='text'){
        switch($type){
                case'text':
                        $val = strip_tags( $val );
                break;
                case'int':
                        $val = intval( $val );
                break;
                default:
                        $val = strip_tags( $val );
        }
        return $val;
    }

}
