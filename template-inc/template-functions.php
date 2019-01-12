<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package eve
 */


 
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function eve_body_classes( $classes )
{
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}



/**
 * Registers a text field setting for Wordpress 4.7 and higher.
 */
function eve_general_section() 
{  
  $section_id = 'progresseve';
  $page_name = 'general';

  add_settings_section(  
      $section_id, 
      'Progresseve Settings', // Section Title
      'progresseve_section_options_callback', // Callback
      $page_name // What Page?  This makes the section show up on the General Settings Page
  );

  add_settings_field( // Option 1
      'eve_gtm_code', // Option ID
      'GTM Code', // Label
      'my_textbox_callback', // !important - This is where the args go!
      $page_name, // Page it will be displayed (General Settings)
      $section_id, // Name of our section
      array( // The $args
          'eve_gtm_code' // Should match Option ID
      )  
  );

  add_settings_field(
      'eve_environment_details',
      'Environment',
      'eve_env_dd_callback',
      $page_name,
      $section_id,
      array(
          'eve_environment_details'
      )  
  );

  add_settings_field(
      'eve_theme_version',
      'Version',
      'my_textbox_callback',
      $page_name,
      $section_id,
      array(
          'eve_theme_version'
      )  
  ); 

  add_settings_field(
    'eve_html_compression',
    'HTML Compression',
    'eve_htmlgen_dd_callback',
    $page_name,
    $section_id,
    array(
        'eve_html_compression'
    )  
  );

  register_setting( $page_name, 'eve_gtm_code', 'esc_attr' );
  register_setting( $page_name, 'eve_environment_details', 'esc_attr' );
  register_setting( $page_name, 'eve_theme_version', 'esc_attr' );
  register_setting( $page_name, 'eve_html_compression', 'esc_attr' );
}

function progresseve_section_options_callback()
{
  echo '<p>General theme settings</p>';  
}

function my_textbox_callback($args)
{
  $option = get_option($args[0]);
  echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
}

function eve_env_dd_callback($args)
{
	$options = get_option($args[0]);
	$items = array(
    0 => 'Development',
    1 => 'Live'
  );
	echo '<select id="'. $args[0] .'" name="'. $args[0]. '">';
	foreach($items as $k => $v) {
		$selected = ($options[0]==$k) ? ' selected="selected"' : '';
    echo '<option value="'. $k .'"'. $selected .'>'. $v .'</option>';
	}
	echo "</select>";
}

function  eve_htmlgen_dd_callback($args)
{
	$options = get_option($args[0]);
	$items = array(
    0 => 'No',
    1 => 'Yes'
  );
	echo '<select id="'. $args[0] .'" name="'. $args[0]. '">';
	foreach($items as $k => $v) {
		$selected = ($options[0]==$k) ? ' selected="selected"' : '';
    echo '<option value="'. $k .'"'. $selected .'>'. $v .'</option>';
	}
	echo "</select>";
}



/**
 * Theme verison control
 */
if ( !function_exists( 'progresseve_verison_control' ) ) :
	/**
	 * Template style loader
	 */
  function progresseve_verison_control() 
  { 
    $e = get_option('eve_environment_details');
    if( $e == false || empty($e) ){ return time(); }

    $v = get_option('eve_theme_version');
    if( $v === false || empty($v) ){ return '1.0.0'; }
    return $v;
  }
endif;



/**
 * Remove XMLrpc method
 */
function ayn_remove_xmlrpc_pingback_ping( $methods )
{
  unset( $methods['pingback.ping'] );
  return $methods;
};



if ( !function_exists( 'ayn_disable_emojis_tinymce' ) ) :
  /**
   * Filter function used to remove the tinymce emoji plugin.
   *
   * @param    array  $plugins
   * @return   array  Difference betwen the two arrays
   */
  function ayn_disable_emojis_tinymce( $plugins )
  {
    if ( is_array( $plugins ) ) {
      return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
      return array();
    }
  }
endif;



if ( !function_exists( 'eve_itsme_disable_feed' ) ) :
  /**
   * Redirect feed page
   */
  function eve_itsme_disable_feed() 
  {
    wp_die( __( 'No feed available, please visit the <a href="'. esc_url( home_url( '/' ) ) .'">homepage</a>!' ) );
  }
endif;



if ( !function_exists( 'progresseve_inline_style' ) ) :
	/**
	 * Template style loader
	 */
  function progresseve_inline_style()
  { 
    echo "<style>";
    echo file_get_contents( get_template_directory() . '/inline.css' ); 
    
    global $template;
    $local_template = basename($template);
    if( !empty($local_template) && is_string($local_template) )
    {
      $local_template_path = get_template_directory() .'/css/'. $local_template .'.css';

      if( file_exists($local_template_path) )
      {
        echo file_get_contents( $local_template_path );
      }
    }

    echo "</style>\r\n";
  }
endif;



/**
 * HTML Compression script
 */
class WP_HTML_Compression
{
  // Settings
  protected $compress_css = true;
  protected $compress_js = true;
  protected $info_comment = false;
  protected $remove_comments = true;

  // Variables
  protected $html;
  public function __construct($html)
  {
    if (!empty($html))
    {
      $this->parseHTML($html);
    }
  }
  public function __toString()
  {
    return $this->html;
  }
  protected function bottomComment($raw, $compressed)
  {
    $raw = strlen($raw);
    $compressed = strlen($compressed);
    
    $savings = ($raw-$compressed) / $raw * 100;
    
    $savings = round($savings, 2);
    
    return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
  }
  protected function minifyHTML($html)
  {
    $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
    preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
    $overriding = false;
    $raw_tag = false;
    // Variable reused for output
    $html = '';
    foreach ($matches as $token)
    {
      $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
      
      $content = $token[0];
      
      if (is_null($tag))
      {
        if ( !empty($token['script']) )
        {
          $strip = $this->compress_js;
        }
        else if ( !empty($token['style']) )
        {
          $strip = $this->compress_css;
        }
        else if ($content == '<!--wp-html-compression no compression-->')
        {
          $overriding = !$overriding;
          
          // Don't print the comment
          continue;
        }
        else if ($this->remove_comments)
        {
          if (!$overriding && $raw_tag != 'textarea')
          {
            // Remove any HTML comments, except MSIE conditional comments
            $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
          }
        }
      }
      else
      {
        if ($tag == 'pre' || $tag == 'textarea')
        {
          $raw_tag = $tag;
        }
        else if ($tag == '/pre' || $tag == '/textarea')
        {
          $raw_tag = false;
        }
        else
        {
          if ($raw_tag || $overriding)
          {
            $strip = false;
          }
          else
          {
            $strip = true;
            
            // Remove any empty attributes, except:
            // action, alt, content, src
            $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
            
            // Remove any space before the end of self-closing XHTML tags
            // JavaScript excluded
            $content = str_replace(' />', '/>', $content);
          }
        }
      }
      
      if ($strip)
      {
        $content = $this->removeWhiteSpace($content);
      }
      
      $html .= $content;
    }
    
    return $html;
  }
    
  public function parseHTML($html)
  {
    $this->html = $this->minifyHTML($html);
    
    if ($this->info_comment)
    {
      $this->html .= "\n" . $this->bottomComment($html, $this->html);
    }
  }
  
  protected function removeWhiteSpace($str)
  {
    $str = str_replace("\t", ' ', $str);
    $str = str_replace("\n",  '', $str);
    $str = str_replace("\r",  '', $str);
    
    while (stristr($str, '  '))
    {
      $str = str_replace('  ', ' ', $str);
    }
    
    return $str;
  }
}

function eve_html_compression_finish($html)
{
  return new WP_HTML_Compression($html);
}

if ( ! function_exists( 'eve_html_compression_start' ) ) :
	/**
	 * HTML Compression
	 */
  function eve_html_compression_start()
  {
    ob_start( 'eve_html_compression_finish' );
  }
endif;



if ( !function_exists( 'progresseve_scripts' ) ) :
	/**
	 * Template scripts and style loader
	 */
	function progresseve_scripts() { 

    /**
     * 
     * Load JS files
     * 
     * (function() {
     *   var wf = document.createElement('script');
     *   wf.src = '<?php echo get_template_directory_uri(); ?>/js/eve.js?v=<?php echo progresseve_verison_control(); ?>';
     *   wf.type = 'text/javascript';
     *   document.body.appendChild(wf);
     * })();
     */

    /**
     * 
     * Load CSS files
     * 
     * (function() {
     *   var css = document.createElement('link');
     *   css.rel = 'stylesheet';
     *   css.href = '<?php echo get_template_directory_uri(); ?>/css/swiper.css?v=<?php echo progresseve_verison_control(); ?>';
     *   css.type = 'text/css';
     *   var godefer = document.getElementsByTagName('link')[0];
     *   godefer.parentNode.insertBefore(css, godefer);
     * })();
     */

    /**
     * 
     * Load CSS background images (OLD METHOD, USE THE LAZY LOAD OPTION FOR THIS)
     * 
     * (function() {
     *   var container = document.getElementById( 'section__bg' );
     *   if ( !container ) { return; }
     *   var image = new Image();
     *   image.src = container.dataset.bg;
     *   image.onload = function() {
     *     container.style.backgroundImage = "url('"+ image.src +"')";
     *     container.classList.add( 'active' );
     *   }
     * })();
     */

?><script type='text/javascript'>
!function(){var n,e,a,t,s,i;if((n=document.getElementById("site-navigation"))&&void 0!==(e=n.getElementsByTagName("button")[0]))if(void 0!==(a=n.getElementsByTagName("ul")[0])){for(a.setAttribute("aria-expanded","false"),-1===a.className.indexOf("nav-menu")&&(a.className+=" nav-menu"),e.onclick=function(){-1!==n.className.indexOf("toggled")?(n.className=n.className.replace(" toggled",""),e.setAttribute("aria-expanded","false"),a.setAttribute("aria-expanded","false")):(n.className+=" toggled",e.setAttribute("aria-expanded","true"),a.setAttribute("aria-expanded","true"))},s=0,i=(t=a.getElementsByTagName("a")).length;s<i;s++)t[s].addEventListener("focus",l,!0),t[s].addEventListener("blur",l,!0);!function(e){var a,t,s=n.querySelectorAll(".menu-item-has-children > a, .page_item_has_children > a");if("ontouchstart"in window)for(a=function(e){var a,t=this.parentNode;if(t.classList.contains("focus"))t.classList.remove("focus");else{for(e.preventDefault(),a=0;a<t.parentNode.children.length;++a)t!==t.parentNode.children[a]&&t.parentNode.children[a].classList.remove("focus");t.classList.add("focus")}},t=0;t<s.length;++t)s[t].addEventListener("touchstart",a,!1)}()}else e.style.display="none";function l(){for(var e=this;-1===e.className.indexOf("nav-menu");)"li"===e.tagName.toLowerCase()&&(-1!==e.className.indexOf("focus")?e.className=e.className.replace(" focus",""):e.className+=" focus"),e=e.parentElement}}();
var _extends=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(t[o]=n[o])}return t},_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};!function(t,e){"object"===("undefined"==typeof exports?"undefined":_typeof(exports))&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):t.LazyLoad=e()}(this,function(){"use strict";function t(t,e,n){var o=e._settings;!n&&i(t)||(C(o.callback_enter,t),R.indexOf(t.tagName)>-1&&(N(t,e),I(t,o.class_loading)),E(t,e),a(t),C(o.callback_set,t))}var e={elements_selector:"img",container:document,threshold:300,thresholds:null,data_src:"src",data_srcset:"srcset",data_sizes:"sizes",data_bg:"bg",class_loading:"loading",class_loaded:"loaded",class_error:"error",load_delay:0,callback_load:null,callback_error:null,callback_set:null,callback_enter:null,callback_finish:null,to_webp:!1},n=function(t){return _extends({},e,t)},o=function(t,e){return t.getAttribute("data-"+e)},r=function(t,e,n){var o="data-"+e;null!==n?t.setAttribute(o,n):t.removeAttribute(o)},a=function(t){return r(t,"was-processed","true")},i=function(t){return"true"===o(t,"was-processed")},s=function(t,e){return r(t,"ll-timeout",e)},c=function(t){return o(t,"ll-timeout")},l=function(t){return t.filter(function(t){return!i(t)})},u=function(t,e){return t.filter(function(t){return t!==e})},d=function(t,e){var n,o=new t(e);try{n=new CustomEvent("LazyLoad::Initialized",{detail:{instance:o}})}catch(t){(n=document.createEvent("CustomEvent")).initCustomEvent("LazyLoad::Initialized",!1,!1,{instance:o})}window.dispatchEvent(n)},f=function(t,e){return e?t.replace(/\.(jpe?g|png)/gi,".webp"):t},_="undefined"!=typeof window,v=_&&!("onscroll"in window)||/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent),g=_&&"IntersectionObserver"in window,h=_&&"classList"in document.createElement("p"),b=_&&function(){var t=document.createElement("canvas");return!(!t.getContext||!t.getContext("2d"))&&0===t.toDataURL("image/webp").indexOf("data:image/webp")}(),m=function(t,e,n,r){for(var a,i=0;a=t.children[i];i+=1)if("SOURCE"===a.tagName){var s=o(a,n);p(a,e,s,r)}},p=function(t,e,n,o){n&&t.setAttribute(e,f(n,o))},y=function(t,e){var n=b&&e.to_webp,r=o(t,e.data_src),a=o(t,e.data_bg);if(r){var i=f(r,n);t.style.backgroundImage='url("'+i+'")'}if(a){var s=f(a,n);t.style.backgroundImage=s}},w={IMG:function(t,e){var n=b&&e.to_webp,r=e.data_srcset,a=t.parentNode;a&&"PICTURE"===a.tagName&&m(a,"srcset",r,n);var i=o(t,e.data_sizes);p(t,"sizes",i);var s=o(t,r);p(t,"srcset",s,n);var c=o(t,e.data_src);p(t,"src",c,n)},IFRAME:function(t,e){var n=o(t,e.data_src);p(t,"src",n)},VIDEO:function(t,e){var n=e.data_src,r=o(t,n);m(t,"src",n),p(t,"src",r),t.load()}},E=function(t,e){var n=e._settings,o=t.tagName,r=w[o];if(r)return r(t,n),e._updateLoadingCount(1),void(e._elements=u(e._elements,t));y(t,n)},I=function(t,e){h?t.classList.add(e):t.className+=(t.className?" ":"")+e},L=function(t,e){h?t.classList.remove(e):t.className=t.className.replace(new RegExp("(^|\\s+)"+e+"(\\s+|$)")," ").replace(/^\s+/,"").replace(/\s+$/,"")},C=function(t,e){t&&t(e)},O=function(t,e,n){t.addEventListener(e,n)},k=function(t,e,n){t.removeEventListener(e,n)},x=function(t,e,n){O(t,"load",e),O(t,"loadeddata",e),O(t,"error",n)},A=function(t,e,n){k(t,"load",e),k(t,"loadeddata",e),k(t,"error",n)},z=function(t,e,n){var o=n._settings,r=e?o.class_loaded:o.class_error,a=e?o.callback_load:o.callback_error,i=t.target;L(i,o.class_loading),I(i,r),C(a,i),n._updateLoadingCount(-1)},N=function(t,e){var n=function n(r){z(r,!0,e),A(t,n,o)},o=function o(r){z(r,!1,e),A(t,n,o)};x(t,n,o)},R=["IMG","IFRAME","VIDEO"],S=function(e,n,o){t(e,o),n.unobserve(e)},M=function(t){var e=c(t);e&&(clearTimeout(e),s(t,null))},j=function(t,e,n){var o=n._settings.load_delay,r=c(t);r||(r=setTimeout(function(){S(t,e,n),M(t)},o),s(t,r))},D=function(t){return t.isIntersecting||t.intersectionRatio>0},T=function(t){return{root:t.container===document?null:t.container,rootMargin:t.thresholds||t.threshold+"px"}},U=function(t,e){this._settings=n(t),this._setObserver(),this._loadingCount=0,this.update(e)};return U.prototype={_manageIntersection:function(t){var e=this._observer,n=this._settings.load_delay,o=t.target;n?D(t)?j(o,e,this):M(o):D(t)&&S(o,e,this)},_onIntersection:function(t){t.forEach(this._manageIntersection.bind(this))},_setObserver:function(){g&&(this._observer=new IntersectionObserver(this._onIntersection.bind(this),T(this._settings)))},_updateLoadingCount:function(t){this._loadingCount+=t,0===this._elements.length&&0===this._loadingCount&&C(this._settings.callback_finish)},update:function(t){var e=this,n=this._settings,o=t||n.container.querySelectorAll(n.elements_selector);this._elements=l(Array.prototype.slice.call(o)),!v&&this._observer?this._elements.forEach(function(t){e._observer.observe(t)}):this.loadAll()},destroy:function(){var t=this;this._observer&&(this._elements.forEach(function(e){t._observer.unobserve(e)}),this._observer=null),this._elements=null,this._settings=null},load:function(e,n){t(e,this,n)},loadAll:function(){var t=this;this._elements.forEach(function(e){t.load(e)})}},_&&function(t,e){if(e)if(e.length)for(var n,o=0;n=e[o];o+=1)d(t,n);else d(t,e)}(U,window.lazyLoadOptions),U});
var eveLazyLoad = new LazyLoad({
	elements_selector: '.loadlzly', callback_set: function(el){ el.classList.add( 'active' ); }
});

jQuery(document).ready(function($) {
console.log('<?php echo esc_html(get_bloginfo('name')); ?>');
$("a[href*='#']:not([href='#'])").click(function(){if(location.pathname.replace(/^\//,"")==this.pathname.replace(/^\//,"")&&location.hostname==this.hostname){var t=$(this.hash);if((t=t.length?t:$("[name="+this.hash.slice(1)+"]")).length)return $("html,body").animate({scrollTop:t.offset().top},1e3),!1}});
(function(){ })();
});
</script>
<?php

  }
endif;



if ( !function_exists( 'progresseve_dynamic_vars' ) ) :
	/**
	 * Dynamic variables for template
	 */
  function progresseve_dynamic_vars()
  {
    ?><script></script>
<?php
  }
endif;



if ( !function_exists( 'eve_theme_icons' ) ) :
	/**
	 * Theme svg icons
	 */
  function eve_theme_icons()
  {
?><svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" display="none">
<symbol id="i-logo" viewBox="0 0 100 20.252"><path d="M5.241,13.15a3.837,3.837,0,0,1,2.526.774A2.669,2.669,0,0,1,8.7,16.1a3.3,3.3,0,0,1-.4,1.646,3.261,3.261,0,0,1-1,1.115,5.292,5.292,0,0,1-1.443.659,11.278,11.278,0,0,1-3.2.424,2.817,2.817,0,0,0,.7,1.816,2.529,2.529,0,0,0,1.954.691,4.533,4.533,0,0,0,2.632-.936l.807,1.719a4.965,4.965,0,0,1-1.434.784,6.193,6.193,0,0,1-2.3.4,4.685,4.685,0,0,1-3.8-1.434A5.889,5.889,0,0,1,0,19.05a6.47,6.47,0,0,1,1.378-4.2,4.708,4.708,0,0,1,3.863-1.7Zm77.5,2.314V13.745h3.586v1.378a3.765,3.765,0,0,1,3.259-1.7,2.755,2.755,0,0,1,2.793,1.7,3.732,3.732,0,0,1,3.227-1.7,3.008,3.008,0,0,1,2.355.922,4.036,4.036,0,0,1,.83,2.77v5.54l1.208.065v1.7H94.948V22.807l.7-.065a.778.778,0,0,0,.456-.18.79.79,0,0,0,.161-.5V17.672A4.193,4.193,0,0,0,96,15.943c-.175-.373-.59-.562-1.24-.562a1.891,1.891,0,0,0-1.5.595,2.335,2.335,0,0,0-.521,1.59v5.093l1.166.065v1.7H88.9V22.807l.7-.065a.823.823,0,0,0,.466-.18.717.717,0,0,0,.148-.521V17.667a4.286,4.286,0,0,0-.263-1.729c-.175-.373-.595-.562-1.254-.562a1.822,1.822,0,0,0-1.5.618,2.316,2.316,0,0,0-.512,1.53v5.135l1.148.065v1.7h-5.01V22.807l.7-.065a.823.823,0,0,0,.466-.18.717.717,0,0,0,.148-.521h.023V16.183a.789.789,0,0,0-.115-.5.6.6,0,0,0-.415-.161l-.885-.06ZM81.572,22.47v1.678H78.133V22.77a3.929,3.929,0,0,1-3.439,1.655q-3.291,0-3.291-3.674v-4.9c0-.383-.175-.581-.53-.595l-.678-.041V13.468h3.738V20.2a3.563,3.563,0,0,0,.318,1.7,1.371,1.371,0,0,0,1.318.572,2.139,2.139,0,0,0,1.6-.618,2.028,2.028,0,0,0,.6-1.484v-4.48a.784.784,0,0,0-.115-.479.6.6,0,0,0-.415-.161l-.678-.041V13.459H80.3v8.32a.49.49,0,0,0,.53.618l.738.074Zm-22.153-.429V11.707a.692.692,0,0,0-.115-.466.62.62,0,0,0-.415-.147l-.742-.041V9.288h3.78v9.191a4.088,4.088,0,0,0,2.037-.724,3.078,3.078,0,0,0,1.189-1.613,1.3,1.3,0,0,0,.065-.341c0-.24-.134-.36-.406-.36l-.807-.041V13.745H68.4v1.719l-1.019.041a4.96,4.96,0,0,1-1.867,3.416l2.079,3.738,1.3.065v1.7h-3.1L63.369,20a11.262,11.262,0,0 1-1.443.295v2.355h.023l1.208.065v1.7H58.106V22.8l.7-.065a.823.823,0,0,0,.466-.18.69.69,0,0,0,.148-.516Zm-3.628-8.3v8.914L57,22.724v1.7H51.947V22.807l.7-.065a.611.611,0,0,0,.618-.678v-5.9a.749.749,0,0,0-.115-.489.557.557,0,0,0-.415-.129l-.742-.041V13.745h3.8Zm-2.673-1.839a1.5,1.5,0,0,1-.447-1.115A1.546,1.546,0,0,1,54.284,9.2a1.6,1.6,0,0,1,1.18.456,1.539,1.539,0,0,1,.456,1.134,1.477,1.477,0,0,1-.456,1.115,1.635,1.635,0,0,1-1.18.433,1.583,1.583,0,0,1-1.166-.433Zm-6.969-.71h1.378v2.249h2.821l-.212,1.784H47.527v5.582a1.935,1.935,0,0,0,.341,1.3,1.352,1.352,0,0,0,1.051.373,2.7,2.7,0,0,0,1.434-.447l.636,1.636a4.6,4.6,0,0,1-2.7.742,4.7,4.7,0,0,1-1.613-.244,2.842,2.842,0,0,1-.977-.544,1.924,1.924,0,0,1-.489-.871,4.211,4.211,0,0 1-.189-.9c-.014-.221-.023-.539-.023-.968V15.22H43.494l.212-1.549a2.079,2.079,0,0,0,1.507-.71,6.044,6.044,0,0,0,.936-1.765Zm-14.33,4.245v-1.7H35.4v1.378a3.9,3.9,0,0,1,1.466-1.24,4.354,4.354,0,0,1,1.973-.456,3.291,3.291,0,0,1,2.494.922,3.867,3.867,0,0,1,.88,2.77v5.54l1.212.065v1.7H38.378V22.807l.7-.065a.823.823,0,0,0,.466-.18.717.717,0,0,0,.148-.521V17.667a3.265,3.265,0,0,0-.36-1.729,1.506,1.506,0,0,0-1.369-.562,2.09,2.09,0,0,0-1.6.636,2.133,2.133,0,0,0-.595,1.507v5.139l1.208.065v1.7H31.924V22.807l.7-.065a.823.823,0,0,0,.466-.18.7.7,0,0,0,.147-.521V16.165c0-.424-.175-.645-.53-.659l-.89-.065ZM27.467,13.15a3.837,3.837,0,0,1,2.526.774,2.669,2.669,0,0,1,.936,2.176,3.251,3.251,0,0,1-.406,1.646,3.309,3.309,0,0,1-1,1.115,5.292,5.292,0,0,1-1.443.659,11.278,11.278,0,0,1-3.2.424,2.817,2.817,0,0,0,.7,1.816,2.529,2.529,0,0,0,1.954.691,4.533,4.533,0,0,0,2.632-.936l.807,1.719a4.965,4.965,0,0,1-1.434.784,6.193,6.193,0,0,1-2.3.4,4.685,4.685,0,0,1-3.8-1.434,5.911,5.911,0,0,1-1.208-3.936,6.47,6.47,0,0,1,1.378-4.2,4.693,4.693,0,0,1,3.858-1.7ZM26.5,18a3.392,3.392,0,0,0,1.434-.618,1.384,1.384,0,0,0,.659-1.134,1.135,1.135,0,0,0-1.272-1.3A2.065,2.065,0,0,0,25.5,15.9a4.334,4.334,0,0,0-.7,2.259A10.639,10.639,0,0,0,26.5,18ZM4.277,18a3.392,3.392,0,0,0,1.434-.618,1.384,1.384,0,0,0,.659-1.134,1.135,1.135,0,0,0-1.272-1.3,2.065,2.065,0,0,0-1.825.945,4.334,4.334,0,0,0-.7,2.259A10.639,10.639,0,0,0,4.277,18Z" transform="translate(0 -4.959)" fill-rule="evenodd"/><path d="M20.237,10.279c0,.544,4.351,7.652,4.849,8.619.724,1.323,2.176,2.014,2.876.475C31.078,12.713,37.679,3.743,42.588,0H40.855C35.752,3.047,30,9.661,26.741,14.478A18.6,18.6,0,0,1,24.653,9.91C24.487,8.675,19.739,9.08,20.237,10.279Z" transform="translate(-10.889)" fill="#fff" fill-rule="evenodd"/><path d="M20.237,10.279c0,.544,4.351,7.652,4.849,8.619.724,1.323,2.176,2.014,2.876.475C31.078,12.713,37.679,3.743,42.588,0H40.855C35.752,3.047,30,9.661,26.741,14.478A18.6,18.6,0,0,1,24.653,9.91C24.487,8.675,19.739,9.08,20.237,10.279Z" transform="translate(-10.889)" fill="#fff" fill-rule="evenodd"/><path d="M20.237,10.279c0,.544,4.351,7.652,4.849,8.619.724,1.323,2.176,2.014,2.876.475C31.078,12.713,37.679,3.743,42.588,0H40.855C35.752,3.047,30,9.661,26.741,14.478A18.6,18.6,0,0,1,24.653,9.91C24.487,8.675,19.739,9.08,20.237,10.279Z" transform="translate(-10.889)" fill="#fff" fill-rule="evenodd"/><path d="M20.237,10.279c0,.544,4.351,7.652,4.849,8.619.724,1.323,2.176,2.014,2.876.475C31.078,12.713,37.679,3.743,42.588,0H40.855C35.752,3.047,30,9.661,26.741,14.478A18.6,18.6,0,0,1,24.653,9.91C24.487,8.675,19.739,9.08,20.237,10.279Z" transform="translate(-10.889)" fill="#95c159" fill-rule="evenodd"/><path d="M20.237,20.992c0,.544,4.351,7.652,4.849,8.619.724,1.323,2.176,2.014,2.876.475.36-.77.77-1.572,1.217-2.392h0l-.009.014c-.171.244-1.351-1.069-2.429-2.512a18.6,18.6,0,0,1-2.088-4.568C24.487,19.387,19.739,19.793,20.237,20.992Z" transform="translate(-10.889 -10.713)" fill="#fff" fill-rule="evenodd"/><path d="M20.237,20.992c0,.544,4.351,7.652,4.849,8.619.724,1.323,2.176,2.014,2.876.475.36-.77.77-1.572,1.217-2.392h0l-.009.014c-.171.244-1.351-1.069-2.429-2.512a18.6,18.6,0,0,1-2.088-4.568C24.487,19.387,19.739,19.793,20.237,20.992Z" transform="translate(-10.889 -10.713)" fill="#b4d980" fill-rule="evenodd"/>
</symbol>
<symbol id="i-foo" viewBox="0 0 10 10"></symbol>
</svg>
<?php 
  }
endif;



if ( !function_exists( 'progresseve_cf7_api_settings' ) ) :
	/**
	 * CF7 API settings
	 */
	function progresseve_cf7_api_settings() {

    $wpcf7 = array(
      'apiSettings' => array(
        'root' => esc_url_raw( rest_url( 'contact-form-7/v1' ) ),
        'namespace' => 'contact-form-7/v1',
      ),
      'recaptcha' => array(
        'messages' => array(
          'empty' =>
            __( 'Please verify that you are not a robot.', 'contact-form-7' ),
        ),
      ),
    );
    
    if ( defined( 'WP_CACHE' ) && WP_CACHE ) {
      $wpcf7['cached'] = 1;
    }

    $wpcf7 = json_encode( $wpcf7 );
    $wpcf7 = '/* <![CDATA[ */ var wpcf7 = '. $wpcf7  .'; /* ]]> */';

    echo "<script type='text/javascript'>". $wpcf7 ."</script>\n";
  }
endif;



if ( !function_exists( 'progresseve_fonts_url' ) ) :
  /**
   * Register custom fonts.
   */
  function progresseve_fonts_url() {
    $fonts_url = '';

    $query_args = array(
      'family' => 'Bree+Serif|Montserrat:400,700&amp;subset=latin-ext'
    );

    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

    return $fonts_url;
  }
endif;



if ( !function_exists( 'progresseve_resource_hints' ) ) :
  /**
   * Add preconnect for Google Fonts.
   *
   * @since eve 1.0
   *
   * @param array  $urls           URLs to print for resource hints.
   * @param string $relation_type  The relation type the URLs are printed.
   * @return array $urls           URLs to print for resource hints.
   */
  function progresseve_resource_hints( $urls, $relation_type ) {
    if ( wp_style_is( 'progresseve-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
      $urls[] = array(
        'href' => 'https://fonts.gstatic.com',
        'crossorigin',
      );
    }
    return $urls;
  }
endif;



if ( !function_exists( 'eve_ignore_sticky' ) ) :
  // the function that does the work
  function eve_ignore_sticky($query)
  {
    // sure we're were we want to be.
    if ( is_home() && $query->is_main_query() )
      $query->set('ignore_sticky_posts', true);
  }
endif;



if ( !function_exists( 'eve_custom_excerpt_length' ) ) :
  function eve_custom_excerpt_length( $length )
  {
    return 26;
  }
endif;



if ( !function_exists( 'eve_new_excerpt_more' ) ) :
  function eve_new_excerpt_more( $more )
  {
    return '...';
  }
endif;



if ( ! function_exists( 'eve_cpt_redirect_post' ) ) :
  function eve_cpt_redirect_post()
  {
    $queried_post_type = get_query_var('post_type');
    if ( is_single() && 'transformations' ==  $queried_post_type ) {
      wp_redirect( get_post_type_archive_link( 'transformations' ), 301 );
      exit;
    }
  }
endif;



if ( ! function_exists( 'eve_cpt_change_sort_order' ) ) :
  function eve_cpt_change_sort_order( $query )
  {
    if( is_archive() && is_post_type_archive( 'members' ) ):
      //If you wanted it for the archive of a custom post type use: is_post_type_archive( $post_type )
      //Set the order ASC or DESC
      $query->set( 'order', 'ASC' );
      //Set the orderby
      // $query->set( 'orderby', 'title' );
    endif;    
  };
endif;



if ( ! function_exists( 'eve_remove_jquery_migrate' ) ) :
  /**
   * Dequeue jQuery Migrate script in WordPress.
   */
  function eve_remove_jquery_migrate( &$scripts) {
    if(!is_admin()) {
      $scripts->remove( 'jquery' );
      $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }
  }

endif;



if ( ! function_exists( 'eve_admin_remove_menus' ) ) :
	/**
	 * Admin menu removal
	 */
  function eve_admin_remove_menus()
  {
    // remove_menu_page( 'index.php' );
    // remove_menu_page( 'jetpack' );  
    // remove_menu_page( 'edit.php' ); 
    // remove_menu_page( 'upload.php' );             
    // remove_menu_page( 'edit.php?post_type=page' );
    remove_menu_page( 'edit-comments.php' );      
    // remove_menu_page( 'themes.php' );             
    // remove_menu_page( 'plugins.php' );            
    // remove_menu_page( 'users.php' );
    // remove_menu_page( 'tools.php' );
    // remove_menu_page( 'options-general.php' );    
  }
endif;



if ( ! function_exists( 'eve_admin_bar_render' ) ) :
	/**
	 * Remove comments menu from admin bar
	 */
  function eve_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
  }
endif;



if ( ! function_exists( 'eve_foo' ) ) :
	/**
	 * Comment
	 */
	function eve_foo() {

	}
endif;