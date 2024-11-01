<?php
namespace WpAnimatorHelper;
/**
 * Description of CssFileHelper. Generate CSS file
 *
 * @author Pavlo
 */
class CssFileHelper{

    static function create_file($pathes,$name){
    try{
        $add_content="";
        if(is_writable($name)):
        $fp = fopen($name, 'w+');
        if(!$fp)die();
        foreach($pathes as $path):
            @$content = file_get_contents ($path);
            if(!$content  OR empty($content)){
                $content= " /* ATENTION!!! The file ".$path." does not exist  */";
            }else {
                $content .= " /* The file name  " . $path . " - included  */";
            }

            fwrite($fp,$content);
        endforeach;
            $add_content=apply_filters("animator_add_css_style", $add_content);
            if(!empty($add_content) OR $add_content!=""){
                fwrite($fp,$add_content);
            }
        fclose($fp);
        do_action('animator_file_updated');
       endif;
    }catch (Exception $ex){
        echo $ex->getMessage();
    }

    }
    static function update_file($post_id=NULL){
        $animator_settings= \AnimOptions::getInstance();
        $settings_data=$animator_settings->get_settings();
        $slug=$settings_data['slug'];
        $css_path=WPANIMATOR_PATH.'css/gen_css/'.$animator_settings->get_css_name(); 
        $anim_path_css=array();
        global $wpdb;
        $posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='".$slug."' ORDER BY post_date DESC   LIMIT 3  ");
        $animator_settings->set_ids_element($posts);
        $anim_path_css[]=WPANIMATOR_LINK.'css/anim-style.css';
        foreach ($posts as $post) {
            if(get_post_meta($post->ID,'anim_custom',true)){
                $anim_path_css[]=get_stylesheet_directory_uri() .'/animations/'.trim(get_post_meta($post->ID,'anim_name_custom',true)).'.css';
            }else{
                $anim_path_css[]=WPANIMATOR_LINK.'css/animations/'.trim(get_post_meta($post->ID,'anim_animation',true)).'.css';
            }
        }
         $anim_path_css = apply_filters('animator_add_path_css',$anim_path_css,$post_id);
         $anim_path_css=array_unique($anim_path_css);
         self::create_file($anim_path_css,$css_path);
        
    }
}