<?php
/*
  Plugin Name:WordPress Animator FREE version
  Plugin URI: http://www.wp-animator.com/
  Description: Plugin for  create animation in your site
  Author: pabloborysenko
  Author URI:  http://pavloborysenko.h1n.ru/
  Version: 1.1.0
  Tags:  wordpress animator, animation, animations, funny, marketing tool, animations, tooltip, CSS animation
  Text Domain: animator
  Domain Path: /languages
 */

if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly
}

define('WPANIMATOR_PATH', plugin_dir_path(__FILE__));
define('WPANIMATOR_LINK', plugin_dir_url(__FILE__));
define('WPANIMATOR_PLUGIN_NAME', plugin_basename(__FILE__));

include WPANIMATOR_PATH . 'classes/AnimOptions.php';

//admin
include_once WPANIMATOR_PATH . 'classes/HtmlFormHelper.php';
include_once WPANIMATOR_PATH. 'classes/CssFileHelper.php';
include_once WPANIMATOR_PATH . 'classes/Animator.php';
include_once WPANIMATOR_PATH . 'classes/Element.php';
include_once WPANIMATOR_PATH . 'classes/Shortcode.php';
include_once WPANIMATOR_PATH . 'classes/Extensions.php';
include_once WPANIMATOR_PATH . 'classes/AnimSettingPage.php';
include_once WPANIMATOR_PATH . 'ext/customize_option/customize_option.php';

$animator= new animator\Animator();
$animator->init();

add_action('init',function(){
    if(is_admin()){
       $settings=new animator\AnimSettingPage();
       $settings->init();
    }
});

include_once WPANIMATOR_PATH . 'ext/animator_selector/AnimatorSelector.php';

//front
include_once WPANIMATOR_PATH . 'classes/HtmlAnimHelper.php';
include_once WPANIMATOR_PATH . 'classes/Animator_front.php';
include_once WPANIMATOR_PATH . 'ext/animator_tooltip/AnimatorTooltip.php';
include_once WPANIMATOR_PATH . 'ext/animator_behavior_mobil/AnimatorMobil.php';
include_once WPANIMATOR_PATH . 'ext/animator_listener/AnimatorListenerJS.php';
$animator_front= new animator\Animator_front();
$animator_front->init();
$shortcode=new element\Shortcode();
$shortcode->init();




