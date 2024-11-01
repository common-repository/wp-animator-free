/**
 * Created by User on 28.01.2017.
 */
jQuery(document).ready(function(){
    if (jQuery("input[name='anim_is_selector']").attr("checked") == 'checked') {
        jQuery(".title_img_content,.row_img_content").hide();
        jQuery(".row_anim_tooltip,.title_anim_tooltip").hide();
        jQuery(".row_position,.title_position ").hide();
        jQuery(".row_elem_prop,.title_elem_prop ").hide();
        jQuery(".row_anim_conditional,.title_anim_conditional ").hide();
        jQuery(".row_mobil,.title_mobil ").hide();
    }
    jQuery("input[name='anim_is_selector']").click(function () {
        if (jQuery("input[name='anim_is_selector']").attr("checked") == 'checked') {
            jQuery(".title_img_content,.row_img_content").hide(200);
            jQuery(".row_anim_tooltip,.title_anim_tooltip").hide(200);
            jQuery(".row_position,.title_position ").hide(200);
            jQuery(".row_elem_prop,.title_elem_prop ").hide(200);
            jQuery(".row_anim_conditional,.title_anim_conditional ").hide(200);
            jQuery(".row_mobil,.title_mobil ").hide(200);
        } else {
            jQuery(".title_img_content,.row_img_content").show(200);
            jQuery(".row_anim_tooltip,.title_anim_tooltip").show(200);
            jQuery(".row_position,.title_position ").show(200);
            jQuery(".row_elem_prop,.title_elem_prop ").show(200);
            jQuery(".row_anim_conditional,.title_anim_conditional ").show(200);
            jQuery(".row_mobil,.title_mobil ").show(200);
        }
    });

});