<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.01.2017
 * Time: 19:00
 */

namespace AnimatorExtension;


class SelectorCSSHelper
{
    static public function get_style_selector($data)
    {
        $hover_stop = false;
        $hover_start = false;
        switch ($data['anim_hover']) {
            case 'hover_stop' :
                $hover_stop = true;
                break;
            case 'hover_start' :
                $hover_start = true;
                break;
            default:
                $hover_stop = false;
                $hover_start = false;
                break;
        }

        ob_start();
        ?>
        <?php echo $data['anim_selector'] ?>{
        <?php if ($hover_start == true): ?>
        -webkit-animation-play-state: paused !important;
        -moz-animation-play-state: paused !important;
        -o-animation-play-state: paused !important;
        animation-play-state: paused !important;
    <?php endif; ?>
        <?php if ($data['anim_animation'] != ''): ?>
        -webkit-animation: <?php echo $data['anim_animation'] ?> <?php echo $data['anim_time'] ?>s <?php echo $data['anim_timinf_func'] ?> 0s <?php echo $data['anim_iteration'] ?> <?php echo $data['anim_direction'] ?>;
        -moz-animation: <?php echo $data['anim_animation'] ?> <?php echo $data['anim_time'] ?>s <?php echo $data['anim_timinf_func'] ?> 0s <?php echo $data['anim_iteration'] ?> <?php echo $data['anim_direction'] ?>;
        animation: <?php echo $data['anim_animation'] ?> <?php echo $data['anim_time'] ?>s <?php echo $data['anim_timinf_func'] ?> 0s <?php echo $data['anim_iteration'] ?> <?php echo $data['anim_direction'] ?>;
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
    <?php endif; ?>

        <?php echo $data['anim_additional_css'] ?>
        }
        <?php if ($hover_stop == true OR $hover_start == true): ?>
        <?php echo $data['anim_selector'] ?>:hover {
        <?php
        $state = "paused";
        if ($hover_stop == true):
            $state = "paused ";
        elseif ($hover_start == true):
            $state = "running !important";
        endif; ?>
        -webkit-animation-play-state: <?php echo $state ?>;
        animation-play-state: <?php echo $state ?>;
        }
    <?php endif; ?>
        <?php
        return ob_get_clean();
    }
}