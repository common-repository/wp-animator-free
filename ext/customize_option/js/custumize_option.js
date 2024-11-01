jQuery(document).ready(function() {
var anim_key_down=false
jQuery(window,document).on("keydown",function(e){
    if(e.which ==16 ){
         anim_key_down=true;
    }
});
jQuery(window,document).on("keyup",function(e){
    if(e.which ==16 ){
        anim_key_down=false;
    }

});

 jQuery(window,document).on("click1",function(e){
     anim_key_down=animator_semulate_shift(anim_key_down);
     
     if(anim_key_down && animator_exclude_element(e.target)){
        animator_add_selector(e.target);
        anim_key_down=false;
        e.preventDefault();
     }

 });
  jQuery(window,document).on("mouseup",function(e){
     anim_key_down=animator_semulate_shift(anim_key_down);
     
     if(anim_key_down && animator_exclude_element(e.target)){
        animator_add_selector(e.target);
        anim_key_down=false;
        e.preventDefault();
     }

 });

function animator_semulate_shift(anim_key_down){
    if(jQuery("#wpanimator_switcher_shift").attr("checked") == 'checked'){
        anim_key_down=true;
    }
    return anim_key_down;
}
jQuery("#wpanimator_switcher_shift").click(function(){
    if(jQuery(this).attr("checked") != 'checked'){
       anim_key_down=false; 
    }
});
function animator_exclude_element(_target){
    var excl=jQuery("#wpanimator_menu").find(_target);
    if(excl.length){
      return false;  
    }
    return true;
}
/*style*/
jQuery("#wpanimator_str_btn").on("click",function(e){
jQuery("#wpanimator_menu").toggle(200);
});

jQuery("#wpanimator_menu").draggable({
  handle: ".wpanimator_move",
  cursor: "move"
});
jQuery("#wpanimator_menu_content").tabs({ 
  show: { effect: "slide", direction: "left", duration: 200, easing: "easeOutBack" } ,
  hide: { effect: "slide", direction: "right", duration: 200, easing: "easeInQuad" } 
});

/*aja*/
/*add element*/
jQuery('#amimator_selector_submit').on('click',function(){
   var selector =  jQuery('#anim_selector_customize').val();
   var nonce= jQuery('#animator_security_add').val();
   if(!selector ){
       alert("Try another element");
       return false;
   }
   data_value=animator_get_value_selector(selector);
   data_value['action']='anim_add_element_ajax';
   data_value['security']=nonce;
   jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: data_value,
        beforeSend: function(){
            jQuery('.anim_preload').show();
        },
        success: function (data) {           
           /*console.log(data);*/
           jQuery('.anim_preload').hide();
           location.reload();
        },
        error: function () {
           alert("Error! Try another element.");
           jQuery('.anim_preload').hide();
        }
    });
   
});
/*Delete*/
jQuery('.anim_delete_element').click(function(){
    var id = jQuery(this).data("id_element");
    var nonce= jQuery('#animator_security_edit').val();
    if(!confirm("Sure? Delete element?")){
        return false;
    }
    if(id){
        var values={
            action: "animator_delete_element",
            animator_id_element:id,
            security:nonce
        };

        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: values,
            beforeSend: function(){
                jQuery('.anim_preload').show();
            },
            success: function (data) {           
               /*console.log(data);*/
               jQuery('.anim_preload').hide();
               location.reload();
            },
            error: function () {
               alert("Error!");
               jQuery('.anim_preload').hide();
            }
        });
    }
   return false; 
});
/*try element*/
jQuery('.anim_try_selector').on('click',function(){
   var selector =  jQuery('#anim_selector_customize').val();
   var nonce= jQuery('#animator_security_add').val();
   jQuery('#anim_temp_styles').empty();
   if(!selector || jQuery(selector).length<1){
       alert("Try another element");
       return false;
   }
   var data_value=animator_get_value_selector(selector);
   data_value['action']='anim_try_element_ajax';
   data_value['security']=nonce;

    jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: data_value,
        beforeSend: function(){
            jQuery('.anim_preload').show();
        },
        complete: function(data) {
            jQuery('.anim_preload').hide();
        },
        success: function (data) {
            data=jQuery.parseJSON(data);
            /*console.log(data);*/
            if (jQuery("link[href='" + data.css_href + "']").length < 1) {
                jQuery("link[animator='temp_css']").remove();
                jQuery("head").append("<link>");
                var css = jQuery("head").children(":last");
                css.attr({
                    rel: "stylesheet",
                    type: "text/css",
                    href: data.css_href, 
                    animator:"temp_css"
                });
               
            }
            var style = "<style>" + data.css_style + "</style>";
            jQuery('#anim_temp_styles').html(style);
            jQuery('.anim_preload').hide();
            animator_check_stop_fn();
        },
        error: function () {
            alert("Error! Try another element.");
        }
    });
   
    return false;
});
 
/*sort element*/
function anim_sort_element(){
    var elements=jQuery('#anim_all_elements_list li');
    jQuery(elements).each(function(index,element){
        var selector_el=jQuery(element).find(".anim_show_hover_element");
        if(selector_el && selector_el.data("selector") ){
            var selector=  selector_el.data("selector");
            if(jQuery(selector).length){
                var tmp_element=jQuery(element).detach();
                jQuery('#anim_this_page').after(jQuery(tmp_element));
            }
        }
    });       
}
/*show element*/
jQuery('.anim_show_hover_element').click(function(){
    var selector = jQuery(this).data("selector");
    /*console.log(selector);*/
    if(selector && jQuery(selector).length){
        if(jQuery(jQuery(selector)).hasClass("anim_element_show_class")){
            jQuery(jQuery(selector)).removeClass("anim_element_show_class");
            jQuery(this).removeClass("anim_checked");
        }else{
            jQuery(jQuery(selector)).addClass("anim_element_show_class");
            jQuery(this).addClass("anim_checked");
        }
    }
    
});

jQuery(".anim_edit_element").click(function(){
    if(jQuery(this).hasClass("is_costomize")){
        window.open(jQuery(this).data("link"), '_blank');
        return false;
    }
});

anim_sort_element();

});

/*form*/
function animator_add_selector(selector){
    var selector_address=animator_getSelector(jQuery(selector));
    jQuery('#anim_selector_customize').val(selector_address);
    jQuery('#anim_selector_customize').trigger('keydown');
}
jQuery('#anim_selector_customize').on('keydown',function(){
    var sel=jQuery(this).val();
    if(sel && sel.length>1){
        try{
            jQuery('.wpanimator_current_element').removeClass('wpanimator_current_element');
            jQuery(sel).addClass('wpanimator_current_element');
            if(jQuery(sel).length>0){
                animator_check_try_fn();
            }else{
                animator_check_try_fn();
            }
        }catch(e){
            
        }
    }
});

jQuery('.anim_try_stop_selector').on('click',function(){
    jQuery('#anim_temp_styles').empty();
    animator_check_stop_fn();
});
function animator_check_stop_fn(){
    jQuery('#anim_temp_styles').html();
    if(jQuery('#anim_temp_styles').html()){
        jQuery('.anim_try_stop_selector').show();
    }else{
        jQuery('.anim_try_stop_selector').hide();
    }
}
jQuery('#anim_animation_customize').on('change',function(){
    animator_check_try_fn();
});
function animator_check_try_fn(){
    var anim=jQuery('#anim_animation_customize').val();
    if(jQuery('.wpanimator_current_element').length>0 && anim){
        jQuery('.anim_try_selector').show(200);
    }else{
        jQuery('.anim_try_selector').hide(200);
    }
    

}
function animator_get_value_selector(selector){
   var title=jQuery('#anim_title_customize').val();
   var animation=jQuery('#anim_animation_customize').val();
   var time=jQuery('#anim_time_customize').val();
   var iteration=jQuery('#anim_iteration_customize').val();
   var direction=jQuery('#anim_direction_customize').val();
   var hover=jQuery('#anim_hover_customize').val();
   var delay=jQuery('#anim_delay_customize').val();
   var fill_mode=jQuery('#anim_fill_mode_customize').val();
   var data_value={
       anim_title:title,
       anim_type:'selector',
       anim_selector:selector,
       anim_animation:animation,
       anim_time:time,
       anim_iteration:iteration,
       anim_direction:direction,
       anim_hover:hover,
       anim_delay:delay,
       anim_is_selector:true,
       anim_fill_mode:fill_mode,
       /*action:'anim_add_element_ajax'*/
   };   
   return data_value;
}
function animator_getSelector(el){
    var $el = jQuery(el);
    var id = $el.attr("id");
    if (id) { /*"should" only be one of these if theres an ID*/
        return "#"+ id;
    }
    var selector = $el.parents()
                .map(function() {
                    return this.tagName;;        
                })
                .get().reverse().join(" ");
   $parent=jQuery($el.parent());
   if($parent){
     var id_p = $parent.attr("id");
     if (id_p) { /*"should" only be one of these if theres an ID*/
       selector+="#"+ id_p;
     }else{
        var classNames_p = $parent.attr("class");
        if (classNames_p) {
            selector += "." + jQuery.trim(classNames_p).replace(/\s/gi, ".");
        } 
     }      
   }
    if (selector) {
        selector += " "+ $el[0].nodeName;
    }

    var classNames = $el.attr("class");
    if (classNames) {
        selector += "." + jQuery.trim(classNames).replace(/\s/gi, ".");
    }
    var name = $el.attr('name');
    if (name) {
        selector += "[name='" + name + "']";
    }

    if (!name){
        var index = $el.index();
        if (index) {
            index = index + 1;
            selector += ":nth-child(" + index + ")";
        }
    }
    return selector;
}

