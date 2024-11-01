<?php if (!defined('ABSPATH')) die('No direct access allowed');?>
<div id="wpanimator_menu" display="none">
    <div class="animator_try_pult">
        <a style="display: none;" href="#" class="anim_try_selector"><img src="<?php echo WPANIMATOR_LINK ?>ext/customize_option/img/try.gif" alt="<?php _e("Try it!", 'animator') ?>"><?php _e("TRY IT!", 'animator') ?></a>
        <a style="display: none;" href="#" class="anim_try_stop_selector"><img src="<?php echo WPANIMATOR_LINK ?>ext/customize_option/img/stop.png" alt="<?php _e("Stop!", 'animator') ?>"><?php _e("Stop!", 'animator') ?></a>
    </div>
<div class="wpanimator_menu_wraper">
<div  id="wpanimator_menu_content" class=" dark">
    <div class="wpanimator_move"> <?php _e("MOVE ME",'animator')?></div>
  <ul>
    <li><a href="#fast_selector">
      <span><?php _e("Animate element",'animator')?></span></a></li>

    <li><a href="#anim_editor">
      <span><?php _e("All elements",'animator')?></span></a></li>
  </ul>

  <div class="wpanimator_menu_text">
   <div id="fast_selector">
       
            <p>
                <span><?php _e("To select the element, press Shift+left mouse button.", 'animator') ?></span>
                <?php _e("Title:",'animator') ?> <input id="anim_title_customize" type="text" placeholder="<?php _e("Title of the element",'animator') ?>" value="<?php echo sprintf(__('Selector:%s','animator'),date('d-m-Y',time())) ?>">
            </p>
            <div class="wpanimator_switch_row">
                <div class="wpanimator_switcher">
                    <input type="checkbox" id="wpanimator_switcher_shift" />
                    <label class="wpanimator_switcher_shift_label" for="wpanimator_switcher_shift"> </label>
                </div>
                <span><?php _e("OR check it and click the element",'animator')?></span>
            </div>
          
          <?php echo $selector_html ?>
          
            <div class="anim_submit_area"> 
                <input type="button" id="amimator_selector_submit" value="ADD" >
            </div>
            <input type="hidden" id="animator_security_add" value="<?php echo  wp_create_nonce('animator_add_anim') ?>">
  </div>
    
 <!-- <div id="fast_element">
    <h2></h2>
  </div>-->
    
  <div id="anim_editor">
    <h2><?php _e("All elements:",'amimator'); ?>
        <span class="anim_element_count"><?php echo count($all_elements)?></span>
    </h2>
    <ul id="anim_all_elements_list">
        <li id="anim_this_page"><h2><?php _e("On this page",'amimator'); ?></h2></li>
        <li id="anim_another_page"><h2><?php _e("Another element",'amimator'); ?></h2></li>
    <?php 
    //var_dump($all_elements);
    foreach ($all_elements as $item):?>
        <?php
        $selector="";
        $is_selector=0;
        if((isset($item['anim_selector']) AND $item['anim_selector'])){
            $selector=$item['anim_selector'];
            $is_selector=1;
        }else{
            $selector='#element_'.$item['id_pos'];
        }
        $edit_link="";
        if($costomize){
            $edit_link=admin_url().'post.php?action=edit&post='.intval($item['id_pos']);
        }else{
            $edit_link=get_edit_post_link(intval($item['id_pos']));
        }
        ?>
        <li class="anim_element_list_item" data-id_element="<?php echo intval($item['id_pos'])?>" data-is_selector="<?php echo $is_selector ?>">
            <?php //var_dump($item['anim_selector']) ?>
            <div class="anim_show_hover_element" data-selector="<?php echo $selector ?>">
                <img  src="<?php echo WPANIMATOR_LINK ?>ext/customize_option/img/show.png" alt="<?php _e("Show","animator");?>">
            </div>
            <div class="animator_title_el"><?php echo get_the_title(intval($item['id_pos'])) ?>
            </div>
            <a class="anim_edit_element <?php echo ($costomize)?"is_costomize":"" ?>" target="_blank" href="<?php echo ($costomize)?"#":$edit_link ?>" data-link="<?php echo $edit_link ?>">
                <img  src="<?php echo WPANIMATOR_LINK ?>ext/customize_option/img/edit.png" alt="<?php _e("Edit","animator");?>" >
            </a>
            <a href="#" class="anim_delete_element" data-id_element="<?php echo intval($item['id_pos'])?>">
                <img  src="<?php echo WPANIMATOR_LINK ?>ext/customize_option/img/delete.png" alt="<?php _e("Delete","animator");?>">
            </a>
        </li>
    <?php endforeach;?>
    </ul>   
       <input type="hidden" id="animator_security_edit" value="<?php echo  wp_create_nonce('animator_edit_anim') ?>">
  </div>
  </div>
</div>
  
  
</div>
    <div class="anim_preload">
        <div class="anim_preload_img">
            <img src="<?php echo WPANIMATOR_LINK ?>ext/customize_option/img/preloader.gif">
        </div>
    </div>
</div>
<div id="anim_temp_styles"></div>
<div id="wpanimator_str_btn">
    <img src="<?php echo WPANIMATOR_LINK ?>ext/customize_option/img/animator_btn.png">
</div>
