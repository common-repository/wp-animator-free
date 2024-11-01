<?php

namespace AnimatorExtension;
include_once WPANIMATOR_PATH . 'ext/animator_tooltip/TooltipCSSHelper.php';
/**
 * Description of AnimatorTooltip. Extension Tooltip - add tooltip for elements
 *
 * @author Pavlo
 */
class AnimatorTooltip extends Extensions
{
    protected $tooltipe_name=array();
    protected $additional_style=array();
    public  function __construct(){
        parent::__construct();
        $this->tooltipe_name=array(
            'tooltip-classic-top'=>'Classic top',
            'tooltip-classic-bottom'=>'Classic bottom  ',
            'tooltip-classic-bottom-right'=>'Classic bottom right ',
            'tooltip-classic-bottom-left'=>'Classic bottom left',
            'tooltip-class-t'=>'Classic Top',
            'tooltip-curve-left'=>'Curved left',
            'tooltip-curve-right'=>'Curved right',
            'tooltip-anim-sharp-left'=>'Sharp Left',
            'tooltip-anim-sharp-right'=>'Sharp Right',
            'tooltip-anim-comic-angular'=>'Comic Angular',
            'tooltip-anim-comic-cloud'=>'Comic cloud',

        );
        $this->additional_style=array(
            'tooltip-curve-left'=>'tooltip-curve',
            'tooltip-curve-right'=>'tooltip-curve',
            'tooltip-anim-sharp-left'=>'tooltip-anim-sharp',
            'tooltip-anim-sharp-right'=>'tooltip-anim-sharp',
            'tooltip-anim-comic-cloud'=>'tooltip-anim-comic',
            'tooltip-anim-comic-angular'=>'tooltip-anim-comic',
            'tooltip-classic-bottom-riht'=>'tooltip-classic',
            'tooltip-classic-bottom-left'=>'tooltip-classic',
            'tooltip-classic-bottom'=>'tooltip-classic',
            'tooltip-classic-top'=>'tooltip-classic',
        );
        $this->ext_meta_data =array(
            array(
            'type'=>'checkbox',
            'label'=>__("Use tooltip: ",'animator'),
            'meta_id'=>'anim_is_tooltip',
            'value'=>'',
            'desc'=>'',
            'temp_value'=>true
        ),
            array(
                'type'=>'textarea',
                'label'=>__('Cotnent of the tooltip','animator'),
                'meta_id'=>'anim_tooltip_content',
                'value'=>'',
                'desc'=>'',
                'default'=>''
            ),
            array(
                'type'=>'select',
                'label'=>__('Tooltip style','animator'),
                'meta_id'=>'anim_tooltip_style',
                'value'=>'',
                'desc'=>'',
                'options'=>$this->tooltipe_name,
                'none'=>'None'
            )
        );

    }
    public function init(){
        parent::init();
    add_filter('animator_list_meta_filds', array($this, 'anim_add_meta_filds'),1, 2);
    add_filter('animator_add_path_css', array($this, 'animator_add_path_css'),1,2);
    add_filter('animator_get_html_element',array($this, 'animator_get_html_element'),1,2);

}

    public function animator_add_path_css($pathes,$post_id){
        global $wpdb;
        $posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='".$this->data_post['slug']."' ");
        foreach($posts as $post){
            $file_path=trim(get_post_meta($post->ID,'anim_tooltip_style',true));
            if(get_post_meta($post->ID,'anim_is_tooltip',true)==true){
                $pathes[]=WPANIMATOR_LINK.'ext/animator_tooltip/css/'.$file_path.'.css';
            }
            if(isset($this->additional_style[$file_path]) AND !empty($this->additional_style[$file_path])){
                $pathes[]=WPANIMATOR_LINK.'ext/animator_tooltip/css/'.$this->additional_style[$file_path].'.css';
            }

        }

        return $pathes;
    }
    public function anim_add_meta_filds($meta_filds,$post){
        $meta_value=array();
        $meta_value=$this->fill_data_in_form($post);
        $html_filds="";
        $html_filds = \WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,0,1,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,1,2,"col-3");
        $html_filds.=\WpAnimatorHelper\HtmlFormHelper::get_section_array($meta_value,2,3,"col-3");
        array_splice($meta_filds, 1, 0, \WpAnimatorHelper\HtmlFormHelper::get_sections_optional($html_filds,__('Tooltip','animator'),'anim_tooltip'));
        return $meta_filds;
    }

    public function animator_get_html_element($item,$id){
        if(get_post_meta($id,'anim_is_tooltip',true)==true){
            $tooltip_name=get_post_meta($id,'anim_tooltip_style',true);
            $content_tooltip=get_post_meta($id,'anim_tooltip_content',true);
            $item=TooltipCSSHelper::get_tooltip_contanier($tooltip_name,$item,$content_tooltip);
        }
      return $item;
    }
}

$anim_tooltip=new AnimatorTooltip();
$anim_tooltip->init();