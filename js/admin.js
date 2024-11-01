jQuery(document).ready(function() {

jQuery("#animator_delete_cache").click(function(){
        var values={
            action: "animator_clear_cache",
            animator_clear_cache_key:"animator_elements_data"
        };
       jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: values,
        success: function (data) {    
              jQuery("#animator_delete_cache").val("Ok!");
        },
        error: function () {
           alert("Error!(cache) Please reload the page and try again. ");
        }
    });
});

function animator_check_cache_settings(){
    var cache=jQuery("select[name='animator_settings[use_cache]']").val();
    if(cache==0){
        jQuery("#animator_delete_cache").hide();
    }
}
animator_check_cache_settings();  

});


