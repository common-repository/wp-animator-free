<?php
namespace element;
use WpAnimatorHelper\HtmlAnimHelper;

/**
 * Description of Element.Element object
 *
 * @author Pavlo
 */
abstract  class Element {
    protected $data=array();


    
   abstract  function __construct(array $arg);
   abstract  function get_anim_html();

    public  function get_anim_style_head(){
      return HtmlAnimHelper::get_style_header($this->data);
    }

    public function get_exact_page(){
        if(isset($this->data['compability']) AND $this->data['compability']==true){
          //  wp_reset_query();
            if (empty($this->data['anim_compabil']))  return false;
            $is_ex_page=stripslashes(trim($this->data['anim_compabil']));
            if (empty($is_ex_page)) return false;

            $is_ex_page=apply_filters( "anim_eval_override", $is_ex_page );

            if ($is_ex_page===false):
                return false;
            endif;

            if ($is_ex_page===true) return true;

            if (stristr($is_ex_page,"return")===false)
                $is_ex_page="return (" . $is_ex_page . ");";

            if( eval($is_ex_page)):
                return true;
            else:
                return false;
                endif;

        }else{
            return true;
        }
    }
    public function get_anim_name(){
        if($this->data['anim_animation']!=''){

            return $this->data['anim_animation'];
        }
       return false;
    }
    public function get_custom_anim(){
        if($this->data['anim_custom']==true AND !empty($this->data['anim_name_custom'])){
            return trim($this->data['anim_name_custom']);
        }
        return false;
    }
    public  function add_html_filter($html,$id){
        return apply_filters('animator_get_html_element',$html,$id);
    }
}

class Image extends element{
    
    public function __construct(array $arg) {
        $this->data=$arg;
        $img=wp_get_attachment_image_src( $arg['anim_elem_img'],'full');
        $this->data['value']=$img[0];
        if($this->get_custom_anim()):
            $this->data['anim_animation']=$this->get_custom_anim();
        endif;
    }
    public function get_anim_html() {

        $html_element=HtmlAnimHelper::get_element_image_item($this->data);
        return $html_element;
    }
    public function get_anim_style_head(){
        return parent::get_anim_style_head();
    }
    public function set_anim_css(){
        return parent::get_anim_style();
    }
    public function get_exact_page(){
        return parent::get_exact_page();
    }
    public function get_anim_name(){
        return parent::get_anim_name();
    }
    public function get_custom_anim(){
       return parent::get_custom_anim();
    }
}
class Content extends element{
    public function __construct(array $arg) {
        $this->data=$arg;
        if($this->get_custom_anim()):
            $this->data['anim_animation']=$this->get_custom_anim();
        endif;
    }
    public function get_anim_html() {

        $html_element=HtmlAnimHelper::get_element_content_item($this->data);

        return $html_element ;
    }
    public function get_anim_style_head(){
        return parent::get_anim_style_head();
    }
    public function set_anim_css(){
       return parent::get_anim_style();
    }
    public function get_exact_page(){
        return parent::get_exact_page();
    }
    public function get_anim_name(){
        return parent::get_anim_name();
    }
    public function get_custom_anim(){
        return parent::get_custom_anim();
    }
}