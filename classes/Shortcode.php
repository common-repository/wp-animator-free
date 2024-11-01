<?php
namespace element;

/**
 * Description of Shortcode. Functionality  of shortcode [wp_animator] attr: colection="test1,test2"  elements=1,2,3
 *
 * @author Pavlo
 */
class Shortcode
{
    protected $settings=array();
    public function __construct(){
        $this->settings = \AnimOptions::getInstance()->get_settings();
    }
    public function init(){
        if(class_exists('animator\Animator_front')) {
            add_shortcode('wp_animator', array($this, 'animator_shortcode'));
        }
    }
    public function animator_shortcode($attr){

        extract(shortcode_atts(array(
                  'colection'=>-1,
                  'elements'=>-1
            ), $attr));
             $ids="";
        if($colection!=-1){
            $ids =$this->get_all_id(explode(",",trim(strip_tags($colection))));
        }
        if($elements!=-1){
            $ids.=(!empty($ids))?",":"";
            $ids.=trim(strip_tags($elements));
        }
        if($ids!=""){
            $_REQUEST['is_shortcode_works']=true;
            $_REQUEST['animator_id_colection']=$ids;
        }else{
            return;
        }
    }
    public function get_all_id($col){
        $ids=array();
      //var_dump($col);
       $args = array(
           'numberposts'     => -1,
           'post_type' =>  $this->settings['slug'],
           $this->settings['slug_cat'] => $col
        );
        $posts= get_posts( $args );
       // var_dump($posts);
        foreach($posts as $post){
            $ids[]=$post->ID;
        }
        wp_reset_postdata();
        return implode(",",$ids);
    }
}