<?php
namespace WpAnimatorHelper;

/**
 *  HtmlAnimHelper add styles for animation and HTML code in body
 *
 * @author Pavlo
 */
class HtmlAnimHelper {
    static public function get_style_container( $styles){
        ob_start();
        ?>
        <style type="text/css">
           <?php echo $styles;?>
        </style>
        <?php
        return ob_get_clean();

    }

    static public function get_style_header($data){
        $hover_stop=false;
        $hover_start=false;
           switch($data['anim_hover']){
               case 'hover_stop' :
                   $hover_stop=true;
                   break;
               case 'hover_start' :
                   $hover_start=true;
                   break;
               default:
                   $hover_stop=false;
                   $hover_start=false;
                   break;
           }

        ob_start();
        ?>
        .element_<?php echo $data['id_pos'] ?>{
                width: <?php echo $data['anim_widh_img'],$data['anim_type_width'] ?>;
                height:auto;
            <?php if($hover_start==true):?>
                -webkit-animation-play-state: paused !important;
                -moz-animation-play-state: paused !important;
                -o-animation-play-state: paused !important;
                animation-play-state: paused !important;
            <?php endif; ?>
            <?php echo $data['anim_side_x'],": ",  $data['anim_pos_x'],$data['anim_type_x'] ?>;
            <?php echo $data['anim_side_y'],": ",  $data['anim_pos_y'],$data['anim_type_y'] ?>;
                position: absolute;
               /* position: fixed;*/
            <?php echo $data['anim_position'] ?>
                z-index: <?php echo $data['anim_z_index'] ?>;
            <?php if($data['anim_animation']!=''):?>
                -webkit-animation: <?php echo $data['anim_animation'] ?>  <?php echo $data['anim_time'] ?>s <?php echo $data['anim_timinf_func'] ?> 0s <?php echo $data['anim_iteration'] ?>  <?php echo $data['anim_direction'] ?>;
                -moz-animation: <?php echo $data['anim_animation'] ?>  <?php echo $data['anim_time'] ?>s <?php echo $data['anim_timinf_func'] ?> 0s <?php echo $data['anim_iteration'] ?>  <?php echo $data['anim_direction'] ?>;
                animation: <?php echo $data['anim_animation'] ?>  <?php echo $data['anim_time'] ?>s <?php echo $data['anim_timinf_func'] ?> 0s <?php echo $data['anim_iteration'] ?>  <?php echo $data['anim_direction'] ?>;
                animation-delay: <?php echo $data['anim_delay'] ?>s;
            -webkit-animation-fill-mode: <?php echo $data['anim_fill_mode'] ?>;
            -moz-animation-fill-mode: <?php echo $data['anim_fill_mode'] ?>;
            animation-fill-mode: <?php echo $data['anim_fill_mode'] ?>;
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
            -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            -webkit-perspective: 1000;
            -moz-perspective: 1000;
            perspective: 1000;
            <?php endif;?>

        <?php echo $data['anim_additional_css'] ?>
            }
        <?php if($hover_stop==true OR $hover_start==true ):?>
        .element_<?php echo $data['id_pos'] ?>:hover {
            <?php
            $state="paused";
            if( $hover_stop==true):
                $state="paused ";
              elseif($hover_start==true):
                  $state="running !important";
               endif;   ?>
            -webkit-animation-play-state: <?php echo $state?>;
            animation-play-state: <?php echo $state?>;
        }
            <?php endif; ?>
        <?php
        return ob_get_clean();

    }
    static public function get_animation_container( $content,$version){
        ob_start();
        ?>
        <figure class="figuru" data-version="<?php echo $version ?>">
            <?php echo $content;?>
        </figure>
        <?php
        return ob_get_clean();

    }
    static public function get_element_image_item($data){
        $item="";
        if(!empty($data['value'])){
            $item = "<img src='".$data['value']."'>";
        }
        $item = apply_filters('animator_get_html_element',$item,$data['id_pos']);
        ob_start();
        ?>
        <div class="element_<?php echo $data['id_pos'] ?>" id="element_<?php echo $data['id_pos'] ?>"> <?php echo $item ?> </div>
        <?php
        return ob_get_clean();

    }
    static public function get_element_content_item($data){

       $item = do_shortcode($data['anim_elem_content']);
        $item = apply_filters('animator_get_html_element',$item,$data['id_pos']);
        ob_start();
        ?>
      <div class="element_<?php echo $data['id_pos'] ?>" id="element_<?php echo $data['id_pos'] ?>" ><?php echo $item ?></div>
        <?php
        return ob_get_clean();

    }
}
