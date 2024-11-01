<?php


namespace WpAnimatorHelper;

/**
 * Description of HtmlFormHelper. add forms and HTML on admin side
 *
 * @author Pavlo
 */
class HtmlFormHelper {
    public static $debug=true;
    public static $count_row=0;
 public static function get_button_toggle_sections($content,$text_btn,$desc,$id="",$post_id=null){
     $id=($id=="")?uniqid(rand(), true):$id;
     $content =apply_filters('anim_add_content_to_section',$content,$id,$post_id);

     ob_start();
     ?>
     <div class="toggle_btn">
     <input class="add_btn" type="button" value="<?php echo $text_btn ?> " id="button_<?php echo $id?>">

     <div class="my_row" id="<?php echo $id?>" style="display: none">

         <?php echo $content?>
     </div>
         <p class="description">
             <?php echo $desc ?>
         </p>
         <hr>
     </div>
     <script>
         jQuery("#button_<?php echo $id?>").click(function () {
             jQuery("#<?php echo $id?>").toggle("slow");
         });

     </script>
     <?php
     return ob_get_clean();
 }
    public static function get_toggle_section($content1,$content2,$label,$checked=0,$title,$id="",$class=""){
        $id=($id=="")?uniqid(rand(), true):$id;
        ob_start();
        self::$count_row++;
        ?>
        <span class="sector_title  title_<?php echo self::$count_row ?> title_<?php echo $class ?>" ><?php echo $title ?></span>
<div class="my_row  row_<?php echo self::$count_row ?> row_<?php echo $class ?>">

    <div class="col-1">
        <div class="check_toggle" id="<?php echo $id ?>"><input value=true <?php echo ($checked==true)?'checked="checked"':''; ?> name="<?php echo $id ?>" type="checkbox" class="animator_toggle_control" ><?php echo $label  ?>
            <div class="animator_section_togle1"><?php echo $content1?></div>
            <div class="animator_section_togle2" style="display: none"><?php echo $content2?></div>

        
        </div>
        
    </div>
    <div class="clear"></div>
    <hr>
</div><script>
            jQuery(document).ready(function(){

                    if (jQuery("#<?php echo $id ?>").children(".animator_toggle_control").attr("checked") == 'checked') {
                        jQuery("#<?php echo $id ?>").children(".animator_section_togle1").hide(100);
                        jQuery("#<?php echo $id ?>").children(".animator_section_togle2").show(100);
                    } else {
                        jQuery("#<?php echo $id ?>").children(".animator_section_togle1").show(100);
                        jQuery("#<?php echo $id ?>").children(".animator_section_togle2").hide(100);
                    }

               jQuery("#<?php echo $id ?>").children(".animator_toggle_control").click(function () {
                   if (jQuery("#<?php echo $id ?>").children(".animator_toggle_control").attr("checked") == 'checked') {
                       jQuery("#<?php echo $id ?>").children(".animator_section_togle1").hide(100);
                       jQuery("#<?php echo $id ?>").children(".animator_section_togle2").show(100);
                   } else {
                       jQuery("#<?php echo $id ?>").children(".animator_section_togle1").show(100);
                       jQuery("#<?php echo $id ?>").children(".animator_section_togle2").hide(100);
                   }
               });

            });
</script>
          
        <?php
        return ob_get_clean();
        
        
        
    }
    public static function get_sections_optional($content,$title,$class=""){
        self::$count_row++;
        ob_start();
        ?>
        <span class="sector_title title_<?php echo self::$count_row ?>  title_<?php echo $class ?> "><?php echo $title ?></span>
<div class="my_row row_<?php echo self::$count_row ?>  row_<?php echo $class ?> ">

    <?php echo $content?>
    <div class="clear"></div>
    <hr>
</div>
         <?php
        return ob_get_clean();
    }
    public static function get_sections_element($content,$numer){
        ob_start();
            if(!is_array($content)):
                $temp=array($content);
                $content=$temp;
            endif;
            foreach($content as $item):
            ?>
<div class="col-sm-<?php echo $numer ?>"><?php echo $item ?></div>
            <?php
            endforeach;
        return ob_get_clean();
    }
    public  static function get_section_array($array,$start,$end,$class=""){
        if(count($array)<$end AND $end<$start ){
            return (self::$debug)?"Wrong data":"";
        }
        ob_start();
        ?>
        <div class="<?php echo $class ?>">
        <?php
        for($i=$start;$i<$end;$i++){
          echo $array[$i];
        }
        ?>
        </div>
        <?php
        return ob_get_clean();

    }
    public static function get_html_input(array $argt){

        if(!is_array($argt)){
            return (self::$debug)?"Expendet array in HTML helper  ":"";
        }
        ob_start();
       
        switch ($argt['type']){
             case 'text_numeric':
                if(!empty($argt['label'])):
                ?>
                <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label><br>
                <?php 
                endif;
                 if(empty($argt['value']))
                     $argt['value']=0;
                $step="";
                if(isset($argt['step'])):
                    $step='step="'.$argt['step'].'""';
                    endif;
                ?>
                <input name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>" type="number" min="<?php echo $argt['min']?>" max="<?php echo $argt['max']?>" <?php echo $step ?> value="<?php echo $argt['value'] ?>" >
                <?php if(!empty($argt['desc'])): ?>
                <p class="description"><?php echo $argt['desc'] ?></p>
                <?php
                endif;
                break;
            
            case 'text':
                $read=(isset($argt['parent']))?'readonly':"";
                if(!empty($argt['label'])):
                ?>
                <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label><br>
                <?php endif;?>
                <input name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>" type="text" value="<?php echo $argt['value'] ?>" <?php echo $read ?> >
                <?php if(!empty($argt['desc'])): ?>
                <p class="description"><?php echo $argt['desc'] ?></p>
                <?php
                endif;
                break;
             case 'textarea':
                 if(!empty($argt['label'])):
                ?>
                <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label><br>
                <?php 
                endif;
                ?>
                <textarea class="anim_textarea" name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>"><?php echo $argt['value'] ?></textarea>
                <?php if(!empty($argt['desc'])): ?>
                <p class="description"><?php echo $argt['desc'] ?></p>
                <?php
                endif;
                break;
            
             case 'select':
                 if(!empty($argt['label'])):
                     ?>
                     <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label><br>
                     <?php
                 endif;
                 if(!isset($argt['options'])){
                     return (self::$debug)?"Wrong data for select":"";
                 }
                 ?>
                 <select name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>" >
                   <?php foreach($argt['options'] as $key=>$value): ?>
                       <option value="<?php echo $key;?>" <?php echo ($argt['value']==$key)?"selected":""; ?> ><?php echo $value ?></option>
                     <?php endforeach; ?>
                 </select>


                 <?php if(!empty($argt['desc'])): ?>
                 <p class="description"><?php echo $argt['desc'] ?></p>
                 <?php
                        endif;
                 break;
                
                break;
             case 'datepicer':
                 $dae = new DateTime();
                 $d=$dae->getTimestamp()+$argt['time_edit'];
                 
                 $argt['value']=(empty($argt['value']))?$d:$argt['value'];    
                 $date=(!empty($argt['value']))?date('Y.m.d H:i',$argt['value']):date('Y.m.d H:i');
                 ?>
                <input hidden value="//<?php echo  $argt['value'] ?>" name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>"  type="text" >
                <label for="//<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label><br> 
                <input  id="//<?php echo $argt['meta_id']?>_visual" type="text" >
                <script type="text/javascript">jQuery("#//<?php echo $argt['meta_id']?>_visual").datetimepicker({
                         format:'Y.m.d H:i ',formatTime:'H:i'
                         ,value:"//<?php echo $date ?>"
                         ,step://<?php echo $argt['time_step']?>}).change(function() {
                           jQuery("#//<?php echo $argt['meta_id']?>").val(Date.parse(jQuery("#<?php echo $argt['meta_id']?>_visual").val())/1000+<?php echo $argt['time_edit'] ?>);
                          });
                </script>
                <p class="description">//<?php echo $argt['desc'] ?>
              
                <?php
                break;
            case 'checkbox':
                $select=($argt['temp_value']==$argt['value'])?"checked":"";
                ?>
                <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label>
                <input name="<?php echo $argt['meta_id']?>" type="checkbox" value="<?php echo $argt['temp_value'] ?>" <?php echo $select?>>
                <p class="description"><?php echo $argt['desc'] ?></p>
           
                <?php
                break;
            case 'upload_img':
               
                ?>
                <img  data-src="<?php echo WPANIMATOR_LINK ?>images/noimages.png"  src="<?php echo $argt['src'] ?>" width="120px" height="95px" />
		<div>
			<input type="hidden" name="<?php echo $argt['meta_id']?>" id="img_prod" value="<?php echo $argt['value'] ?>" />
			<button type="submit" class="upload_image_button button"><?php  echo __('Upload', 'animator')?></button>
			<button type="submit" class="remove_image_button button">&times;</button>
		</div>
           
                <?php
                break;
        }
     
        return ob_get_clean();
        
    }
    public static function get_html_input_compact(array $argt){

        if(!is_array($argt)){
            return (self::$debug)?"Expendet array in HTML helper  ":"";
        }
        ob_start();
       
        switch ($argt['type']){
             case 'text_numeric':
                if(!empty($argt['label'])):
                ?>
                <div class="anim_html_compact compact_form_<?php echo $argt['meta_id'] ?>">
                <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label>
                <?php 
                endif;
                 if(empty($argt['value']))
                     $argt['value']=0;
                $step="";
                if(isset($argt['step'])):
                    $step='step="'.$argt['step'].'""';
                    endif;
                ?>
                <input name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>" type="number" min="<?php echo $argt['min']?>" max="<?php echo $argt['max']?>" <?php echo $step ?> value="<?php echo $argt['value'] ?>" >
                <?php if(!empty($argt['desc'])): ?>
                <p class="description"><?php echo $argt['desc'] ?></p>
                <?php
                endif;
                ?></div><?php
                break;
            
            case 'text':
                $read=(isset($argt['parent']))?'readonly':"";
                if(!empty($argt['label'])):
                ?>
                <div class="anim_html_compact compact_form_<?php echo $argt['meta_id'] ?>">
                <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label>
                <?php endif;?>
                <input name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>" type="text" value="<?php echo $argt['value'] ?>" <?php echo $read ?> >
                <?php if(!empty($argt['desc'])): ?>
                <p class="description"><?php echo $argt['desc'] ?></p>
                <?php
                endif;
                ?></div><?php
                break;
             case 'textarea':
                 if(!empty($argt['label'])):
                ?>
                <div class="anim_html_compact compact_form_<?php echo $argt['meta_id'] ?>">
                <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label>
                <?php 
                endif;
                ?>
                <textarea class="anim_textarea" name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>"><?php echo $argt['value'] ?></textarea>
                <?php if(!empty($argt['desc'])): ?>
                <p class="description"><?php echo $argt['desc'] ?></p>
                <?php
                endif;
                ?></div><?php
                break;
            
             case 'select':
                 ?><div class="anim_html_compact compact_form_<?php echo $argt['meta_id'] ?>"><?php
                 if(!empty($argt['label'])):
                     ?>
                     <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label>
                     <?php
                 endif;
                 if(!isset($argt['options'])){
                     return (self::$debug)?"Wrong data for select":"";
                 }
                 ?>
                 <select name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>" >
                   <?php foreach($argt['options'] as $key=>$value): ?>
                       <option value="<?php echo $key;?>" <?php echo ($argt['value']==$key)?"selected":""; ?> ><?php echo $value ?></option>
                     <?php endforeach; ?>
                 </select>


                 <?php if(!empty($argt['desc'])): ?>
                 <p class="description"><?php echo $argt['desc'] ?></p>
                 <?php
                        endif;
                ?></div> <?php
                break;
             case 'datepicer':
                 $dae = new DateTime();
                 $d=$dae->getTimestamp()+$argt['time_edit'];
                 
                 $argt['value']=(empty($argt['value']))?$d:$argt['value'];    
                 $date=(!empty($argt['value']))?date('Y.m.d H:i',$argt['value']):date('Y.m.d H:i');
                 ?>
                <input hidden value="//<?php echo  $argt['value'] ?>" name="<?php echo $argt['meta_id']?>" id="<?php echo $argt['meta_id']?>"  type="text" >
                <label for="//<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label><br> 
                <input  id="//<?php echo $argt['meta_id']?>_visual" type="text" >
                <script type="text/javascript">jQuery("#//<?php echo $argt['meta_id']?>_visual").datetimepicker({
                         format:'Y.m.d H:i ',formatTime:'H:i'
                         ,value:"//<?php echo $date ?>"
                         ,step://<?php echo $argt['time_step']?>}).change(function() {
                           jQuery("#//<?php echo $argt['meta_id']?>").val(Date.parse(jQuery("#<?php echo $argt['meta_id']?>_visual").val())/1000+<?php echo $argt['time_edit'] ?>);
                          });
                </script>
                <p class="description">//<?php echo $argt['desc'] ?>
              
                <?php
                break;
            case 'checkbox':
                $select=($argt['temp_value']==$argt['value'])?"checked":"";
                ?>
                <div class="anim_html_compact compact_form_<?php echo $argt['meta_id'] ?>">   
                    <label for="<?php echo $argt['meta_id']?>"><?php echo $argt['label']?></label>
                    <input name="<?php echo $argt['meta_id']?>" type="checkbox" value="<?php echo $argt['temp_value'] ?>" <?php echo $select?>>
                    <p class="description"><?php echo $argt['desc'] ?></p>
                </div>
                <?php
                break;
            case 'upload_img':
               
                ?>
                <div class="anim_html_compact compact_form_<?php echo $argt['meta_id'] ?>">
                    <img  data-src="<?php echo WPANIMATOR_LINK ?>images/noimages.png"  src="<?php echo $argt['src'] ?>" width="120px" height="95px" />
                    <div>
                            <input type="hidden" name="<?php echo $argt['meta_id']?>" id="img_prod" value="<?php echo $argt['value'] ?>" />
                            <button type="submit" class="upload_image_button button"><?php  echo __('Upload', 'animator')?></button>
                            <button type="submit" class="remove_image_button button">&times;</button>
                    </div>
                </div>
                <?php
                break;
        }
     
        return ob_get_clean();
        
    }    
    public static function get_info_content($view="",$data=array()){
        ob_start();
        switch($view){
            case "info":
            ?>
            <h4>Version: <?php echo $data['version']?></h4>
            <p style="font-size: 15px;">
            <ul style="font-size: 16px">
                <li><b>You can use shortcode</b> [wp_animator] Attribute:
                    <ul style="margin-left: 30px;font-size: 12px ">
                        <li>
                            colection - slug of the colections
                        </li>
                        <li>
                            elements - IDs of the elements
                        </li>
                        <li>
                            <b> Example</b>: [wp_animator colection='test,test1' elements=30,43,56 ]
                        </li>

                    </ul>
                </li>

            </ul>
            </p>
            <?php
            break;
        case'info_free':
                ?>
                <p style="font-size: 15px;">
                    <span style="color:red">Attention!</span> In this version, you can display<b> only three elements.</b>
                    Version: <?php echo $data['version'] ?>
                    <a href="http://www.wp-animator.com/" target="_blank" >Read about this plugin</a>
                    <p><a href="http://www.pluginsmaster.com/product/wordpress-animator/" target="_blank" >You can buy the full version of the plugin</a></p>
                </p>             
                <?php
                break;
        case'info_rekl':
            ?>
            <p >
                <img src="">
            </p>
            <?php
            break;
            default:
                
    }
        return ob_get_clean();

    }
    
    
}
