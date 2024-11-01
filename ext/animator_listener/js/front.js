
jQuery(window).load(function() {
    //console.log(animation_data);

var count_a;
var pfx = ["webkit", "moz", "MS", "o", ""];

function OnResizeElement(element, handler, time) {
        var id = null;
        var _constructor = function () {
            id = setInterval(function () {
                    handler(element,this);
            }, time);
        };
        var AnimationListener = function trer() {
            //console.log('STOP!!!!!!!!!');
            clearInterval(id);
        }
        var _destructor = function () {
            clearInterval(id);
        };
        this.Destroy = function () {
            _destructor();
        };
        _constructor();
    };
    var show_clip = function show(el,timer) {
        try{
            var L_C_C,T_C_C,L_E_C,T_E_C=0;
            var element =el;
            var selector="body";
            if(element.data()['anim_selector_name']!=undefined && element.data()['anim_selector_name']!=""){
                selector =element.data()['anim_selector_name'];
            }
            var container=jQuery(selector);
            if(container.length==0){
                timer.Destroy();
                return;
            }
            var L_cont=Math.round(container.offset()['left']);
            var T_cont=Math.round(container.offset()['top']);
            var L_elem=Math.round(element.offset()['left']);
            var T_elem=Math.round(element.offset()['top']);
             if(L_elem==L_E_C && T_elem==T_E_C && L_C_C==L_cont && T_cont==T_C_C){return;}
            L_E_C=L_elem;
            T_E_C=T_elem;
            L_C_C=L_cont;
            T_C_C=T_cont;
            var H_cont= container.height();
            var W_cont=container.width();
            var H_elm=element.height();
            var W_elem=element.width();
            var x1,x2,y1,y2="auto";
            if((L_cont+W_cont)<(L_elem+W_elem) ){
               x2=(L_cont+W_cont)-L_elem;
                if(x2<0){x2=0}else{x2=x2+"px"};
            }else{
                x2="auto";
            }
            if( L_cont >L_elem ){
                x1=L_cont-L_elem
                if(x1>W_elem){x1=W_elem+"px"}else{ x1=x1+"px"};
            }else{
                x1="auto";
            }
            if((H_cont+T_cont)<(H_elm+T_elem)){
                y2=(H_cont+T_cont)-T_elem;
                if(y2<0){y2=0}else{y2=y2+'px'};
            }else{
                y2="auto";
            }
            if(T_cont>T_elem){
                y1=T_cont - T_elem
                if(y1>=H_cont){y1=H_cont+"px"}else{ y1=y1+"px";  }
            }else{
                y1="auto";
            }
            if(y1=="auto" && y2=="auto" && x1=="auto" && x2=="auto"){
                count_a++;
               //console.log("No redraw");
                if(count_a>1){
                    count_a=0;
                    return;
                }
            }
            element.css(
                {'clip': 'rect('+y1+', '+x2+','+y2+','+x1+')'}
            );
        }catch(err){
            timer.Destroy();
            console.log(err);
            return;
        }

    }

 var hide_element = function hide_element(el,timer){
     try{
         var L_C_C,T_C_C,L_E_C,T_E_C=0;
         var element =el;
         var selector="body";
         if(element.data()['anim_selector_name']!=undefined && element.data()['anim_selector_name']!=""){
             selector =element.data()['anim_selector_name'];
         }
         var container=jQuery(selector);
         if(container.length==0){
             timer.Destroy();
             return;
         }
         var L_cont=Math.round(container.offset()['left']);
         var T_cont=Math.round(container.offset()['top']);
         var L_elem=Math.round(element.offset()['left']);
         var T_elem=Math.round(element.offset()['top']);
         if(L_elem==L_E_C && T_elem==T_E_C && L_C_C==L_cont && T_cont==T_C_C){
             return;
         }
         L_E_C=L_elem;
         T_E_C=T_elem;
         L_C_C=L_cont;
         T_C_C=T_cont;
         var B_cont= container.height()+T_cont;
         var R_cont=container.width()+L_cont;
         var B_elem=element.height()+T_elem;
         var R_elem=element.width()+L_elem;
         if(B_cont<T_elem || R_cont<L_elem || T_cont>B_elem || L_cont>R_elem ){
            // if(element.css('visibility')=='hidden'){return};
             //element.hide();
             element.children().css(
                 //{'display': 'none'}
                {'visibility': 'hidden'}
             );
             return;
         }
         //if(element.css('visibility')=='visible'){return};
         //element.show();
         element.children().css(
             {'visibility': 'visible'}
         );
     }catch(err){
         timer.Destroy();
         console.log(err);
         return;
     }


 }

 function add_animation() {
     var pref=["","webkit-","moz-"];
     var data=jQuery(this).data();
     if(!data['anim_is_use_animation2'])return;
     var el =jQuery(this);
     var add_anim=function(){
        if(data['anim_iteration_2']<0){
            data['anim_iteration_2']='infinite';
        }
        for (var p = 0; p < pref.length; p++) {
            el.css( pref[p]+'animation-name',data['anim_animation_2'] );
            el.css( pref[p]+'animation-duration',data['anim_time_2']+'s' );
            el.css( pref[p]+'animation-timing-function',data['anim_timinf_func_2']);
            el.css( pref[p]+'animation-iteration-count',data['anim_iteration_2']);
            el.css( pref[p]+'animation-direction',data['anim_direction_2']);
            el.css( pref[p]+'animation-delay',data['anim_delay_2']+'s');
            el.css( pref[p]+'animation-fill-mode',data['anim_fill_mode_2']);
        }
     }
     add_anim();
}

function  event_show_on_screen(element){
    var start=function startAnim(elem){
            elem.css('animation-play-state', 'running');
    }
    var el = element;
    var targetPos = el.offset().top;
    var winHeight = jQuery(window).height();
    var scrollToElem = targetPos - winHeight;
    jQuery(window).scroll(function(){
        var winScrollTop = jQuery(this).scrollTop();
        if(winScrollTop > scrollToElem){
            start(el);
        }
    });
}

    function get_anim_selector(index_el,val){
        var el = jQuery(index_el);
        if(el.length<1){
            return false;
        }
        return el.data(val);

    }

jQuery.each(animation_data, function( index, value ) {
         var el =  get_anim_selector( index, value );
        if(el) {
            if (value['anim_is_use_animation2'] == 1) {
                el.bind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', add_animation);
            }
            if (value['anim_js_hide_element'] == 1) {
                OnResizeElement(el, hide_element, 50);
            }
            if (value['anim_js_clip_element'] == 1) {
                OnResizeElement(el, show_clip, 50);
            }
            if (value['anim_js_start_element'] == 1) {
                event_show_on_screen(el);
            }
        }else{
            //console.log("Wrong selector!");
        }
});

});
