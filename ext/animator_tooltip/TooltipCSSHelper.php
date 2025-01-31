<?php

namespace AnimatorExtension;

/**
 * Description of TooltipCSSHelper.It Chose and add HTML for tooltip
 *
 * @author Pavlo
 */
class TooltipCSSHelper
{
    public static function get_tooltip_contanier($tooltip_name,$item,$content ){
       $prev_html="";
       $medio_html="";
       $after_html="";
        switch($tooltip_name){
            case 'tooltip-classic-bottom-right':
                $prev_html='<span class="tooltip-animator-classic tooltip-effect-5"><span class=" tooltip-item-animator-classic tooltipright ">';
                $medio_html='</span><span class="tooltip-content-classic-b-right clearfix">';
                $after_html='</span></span>';
                break;
            case 'tooltip-classic-bottom-left':
                $prev_html='<div class="tooltip-animator-classic tooltip-effect-5"><span class="tooltip-item-animator-classic tooltipleft ">';
                $medio_html='</span><span class="tooltip-content-classic-b-l clearfix">';
                $after_html='</span></div>';
                break;
            case 'tooltip-classic-bottom':
                $prev_html='<div class="tooltip-animator-classic tooltip-effect-2"><span class="tooltip-item-animator-classic tooltipbottom">';
                $medio_html='</span><span class="tooltip-content-classic-bottom clearfix">';
                $after_html='</span></div>';
            break;
            case 'tooltip-classic-top':
                $prev_html='<span class="tooltip-animator-classic tooltip-effect-2"><span class="tooltip-item-animator-classic tooltiptop">';
                $medio_html='</span><span class="tooltip-content-animator-classic-top clearfix">';
                $after_html='</span></span>';
                break;
            case 'tooltip-classic':
                $prev_html='<span class="tooltip-animator-classic tooltip-effect-3"><span class="tooltip-item-animator-classic">';
                $medio_html='</span><span class="tooltip-content1 clearfix"><span class="tooltip-text">';
                $after_html='</span></span></span>';
                break;
            case 'tooltip-class-t':
                $prev_html='<span class="tooltip tooltip-effect-class-t-1"><span class="tooltip-item-class-t">';
                $medio_html='</span><span class="tooltip-content-class-t clearfix"><span class="tooltip-text-class-t">';
                $after_html='</span></span></span>';
                break;
            case 'tooltip-curve-right':
                $prev_html=' <div class="tooltip-curve tooltip-west"><span class="tooltip-item-curve">';
                $medio_html='</span><span class="tooltip-content-curve">';
                $after_html='</span></div>';
                break;
            case 'tooltip-curve-left':
                $prev_html=' <div class="tooltip-curve tooltip-east"><span class="tooltip-item-curve">';
                $medio_html='</span><span class="tooltip-content-curve">';
                $after_html='</span></div>';
                break;
            case 'tooltip-anim-sharp-left':
                $prev_html='<span class="tooltip-animator-sharp tooltip-animator-sharp-turnleft"><span class="tooltip-item-animator-sharp">';
                $medio_html='</span><span class="tooltip-content-animator-sharp">';
                $after_html='</span></span> ';
                break;
            case 'tooltip-anim-sharp-right':
                $prev_html='<span class="tooltip-animator-sharp tooltip-animator-sharp-turnright"><span class="tooltip-item-animator-sharp">';
                $medio_html='</span><span class="tooltip-content-animator-sharp">';
                $after_html='</span></span> ';
            break;
            case 'tooltip-anim-comic-cloud':
                $prev_html='<div class="tooltip-animator-comic tooltip-animator-comic-effect-1">';
                $medio_html='<span class="tooltip-content-animator-comic">';
                $after_html='</span><div class="tooltip-shape-animator-comic"><svg viewBox="0 0 200 150" preserveAspectRatio="none">
								<path id="path1" d="M184.112,144.325c0.704,2.461,3.412,4.016,5.905,3.611c2.526-0.318,4.746-2.509,4.841-5.093
								c0.153-2.315-1.483-4.54-3.703-5.155c-2.474-0.781-5.405,0.37-6.612,2.681c-0.657,1.181-0.845,2.619-0.442,3.917"/>
								<path id="path2" d="M159.599,137.909c0.975,3.397,4.717,5.548,8.161,4.988c3.489-0.443,6.558-3.466,6.685-7.043
								c0.217-3.19-1.805-6.34-5.113-7.118c-3.417-1.079-7.469,0.508-9.138,3.701c-0.91,1.636-1.166,3.624-0.612,5.414"/>
								<path id="path3" d="M130.646,125.253c1.368,4.656,6.393,7.288,10.806,6.718c4.763-0.451,9.26-4.276,9.71-9.394
								c0.369-3.779-1.902-7.583-5.244-9.144c-5.404-2.732-12.557-0.222-14.908,5.448c-0.841,1.945-1.018,4.214-0.388,6.294"/>
								<path id="path4" d="M49.933,13.549c10.577-20.192,35.342-7.693,37.057,1.708c3.187-5.687,8.381-10.144,14.943-12.148
								c10.427-3.185,21.37,0.699,28.159,8.982c15.606-3.76,31.369,4.398,35.804,18.915c3.269,10.699-0.488,21.956-8.71,29.388
								c0.395,0.934,0.762,1.882,1.064,2.873c4.73,15.485-3.992,31.889-19.473,36.617c-5.073,1.551-10.251,1.625-15.076,0.518
								c-3.58,10.605-12.407,19.55-24.386,23.211c-15.015,4.586-30.547-0.521-39.226-11.624c-2.861,1.991-6.077,3.564-9.583,4.636
								c-18.43,5.631-32.291,2.419-38.074-19.661c-2.645-10.096,3.606-18.51,3.606-18.51C2.336,71.24,1.132,49.635,16.519,42.394
								C-1.269,28.452,18.559,0.948,37.433,6.818C42.141,8.282,49.933,13.549,49.933,13.549z"/>
							</svg></div></div>';
            break;
            case 'tooltip-anim-comic-angular':
                $prev_html='<div class="tooltip-animator-comic tooltip-animator-comic-effect-2">';
                $medio_html='<span class="tooltip-content-animator-comic">';
                $after_html='</span>
						<div class="tooltip-shape-animator-comic"><svg viewBox="0 0 200 150" preserveAspectRatio="none">
								<polygon points="29.857,3.324 171.111,3.324 196.75,37.671 184.334,107.653 104.355,136.679 100,146.676 96.292,136.355 16.312,107.653 3.25,37.671"/>
							</svg></div></div>';
                break;


        }

       return $prev_html.$item.$medio_html.do_shortcode($content).$after_html;
    }
}