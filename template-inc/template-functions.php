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

    $user_ip = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];

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

    // map.panBy(0, -80);  -  Line 491

    /**
     * http://ip-api.com/docs/ 
     * 
     * (function() {
     *   var container = document.getElementById( 'get-your-city' );
     *   if ( !container ) { return; };
     *   var user_ip = '<?php echo $user_ip; ?>';
     *   var api_url = 'http://ip-api.com/json/';
     *   var api = api_url + user_ip;
     *   var request = $.getJSON(api).done(function(data){
     *     if( data.status == 'success' ){
     *       $(container).val(data.city);
     *       debug['location'] = data.city;
     *     }
     *   })
     * })();
     */

?><script type='text/javascript'>
var debug = [];
!function(){var n,e,a,t,s,i;if((n=document.getElementById("site-navigation"))&&void 0!==(e=n.getElementsByTagName("button")[0]))if(void 0!==(a=n.getElementsByTagName("ul")[0])){for(a.setAttribute("aria-expanded","false"),-1===a.className.indexOf("nav-menu")&&(a.className+=" nav-menu"),e.onclick=function(){-1!==n.className.indexOf("toggled")?(n.className=n.className.replace(" toggled",""),e.setAttribute("aria-expanded","false"),a.setAttribute("aria-expanded","false")):(n.className+=" toggled",e.setAttribute("aria-expanded","true"),a.setAttribute("aria-expanded","true"))},s=0,i=(t=a.getElementsByTagName("a")).length;s<i;s++)t[s].addEventListener("focus",l,!0),t[s].addEventListener("blur",l,!0);!function(e){var a,t,s=n.querySelectorAll(".menu-item-has-children > a, .page_item_has_children > a");if("ontouchstart"in window)for(a=function(e){var a,t=this.parentNode;if(t.classList.contains("focus"))t.classList.remove("focus");else{for(e.preventDefault(),a=0;a<t.parentNode.children.length;++a)t!==t.parentNode.children[a]&&t.parentNode.children[a].classList.remove("focus");t.classList.add("focus")}},t=0;t<s.length;++t)s[t].addEventListener("touchstart",a,!1)}()}else e.style.display="none";function l(){for(var e=this;-1===e.className.indexOf("nav-menu");)"li"===e.tagName.toLowerCase()&&(-1!==e.className.indexOf("focus")?e.className=e.className.replace(" focus",""):e.className+=" focus"),e=e.parentElement}}();
var _extends=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(t[o]=n[o])}return t},_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};!function(t,e){"object"===("undefined"==typeof exports?"undefined":_typeof(exports))&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):t.LazyLoad=e()}(this,function(){"use strict";function t(t,e,n){var o=e._settings;!n&&i(t)||(C(o.callback_enter,t),R.indexOf(t.tagName)>-1&&(N(t,e),I(t,o.class_loading)),E(t,e),a(t),C(o.callback_set,t))}var e={elements_selector:"img",container:document,threshold:300,thresholds:null,data_src:"src",data_srcset:"srcset",data_sizes:"sizes",data_bg:"bg",class_loading:"loading",class_loaded:"loaded",class_error:"error",load_delay:0,callback_load:null,callback_error:null,callback_set:null,callback_enter:null,callback_finish:null,to_webp:!1},n=function(t){return _extends({},e,t)},o=function(t,e){return t.getAttribute("data-"+e)},r=function(t,e,n){var o="data-"+e;null!==n?t.setAttribute(o,n):t.removeAttribute(o)},a=function(t){return r(t,"was-processed","true")},i=function(t){return"true"===o(t,"was-processed")},s=function(t,e){return r(t,"ll-timeout",e)},c=function(t){return o(t,"ll-timeout")},l=function(t){return t.filter(function(t){return!i(t)})},u=function(t,e){return t.filter(function(t){return t!==e})},d=function(t,e){var n,o=new t(e);try{n=new CustomEvent("LazyLoad::Initialized",{detail:{instance:o}})}catch(t){(n=document.createEvent("CustomEvent")).initCustomEvent("LazyLoad::Initialized",!1,!1,{instance:o})}window.dispatchEvent(n)},f=function(t,e){return e?t.replace(/\.(jpe?g|png)/gi,".webp"):t},_="undefined"!=typeof window,v=_&&!("onscroll"in window)||/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent),g=_&&"IntersectionObserver"in window,h=_&&"classList"in document.createElement("p"),b=_&&function(){var t=document.createElement("canvas");return!(!t.getContext||!t.getContext("2d"))&&0===t.toDataURL("image/webp").indexOf("data:image/webp")}(),m=function(t,e,n,r){for(var a,i=0;a=t.children[i];i+=1)if("SOURCE"===a.tagName){var s=o(a,n);p(a,e,s,r)}},p=function(t,e,n,o){n&&t.setAttribute(e,f(n,o))},y=function(t,e){var n=b&&e.to_webp,r=o(t,e.data_src),a=o(t,e.data_bg);if(r){var i=f(r,n);t.style.backgroundImage='url("'+i+'")'}if(a){var s=f(a,n);t.style.backgroundImage=s}},w={IMG:function(t,e){var n=b&&e.to_webp,r=e.data_srcset,a=t.parentNode;a&&"PICTURE"===a.tagName&&m(a,"srcset",r,n);var i=o(t,e.data_sizes);p(t,"sizes",i);var s=o(t,r);p(t,"srcset",s,n);var c=o(t,e.data_src);p(t,"src",c,n)},IFRAME:function(t,e){var n=o(t,e.data_src);p(t,"src",n)},VIDEO:function(t,e){var n=e.data_src,r=o(t,n);m(t,"src",n),p(t,"src",r),t.load()}},E=function(t,e){var n=e._settings,o=t.tagName,r=w[o];if(r)return r(t,n),e._updateLoadingCount(1),void(e._elements=u(e._elements,t));y(t,n)},I=function(t,e){h?t.classList.add(e):t.className+=(t.className?" ":"")+e},L=function(t,e){h?t.classList.remove(e):t.className=t.className.replace(new RegExp("(^|\\s+)"+e+"(\\s+|$)")," ").replace(/^\s+/,"").replace(/\s+$/,"")},C=function(t,e){t&&t(e)},O=function(t,e,n){t.addEventListener(e,n)},k=function(t,e,n){t.removeEventListener(e,n)},x=function(t,e,n){O(t,"load",e),O(t,"loadeddata",e),O(t,"error",n)},A=function(t,e,n){k(t,"load",e),k(t,"loadeddata",e),k(t,"error",n)},z=function(t,e,n){var o=n._settings,r=e?o.class_loaded:o.class_error,a=e?o.callback_load:o.callback_error,i=t.target;L(i,o.class_loading),I(i,r),C(a,i),n._updateLoadingCount(-1)},N=function(t,e){var n=function n(r){z(r,!0,e),A(t,n,o)},o=function o(r){z(r,!1,e),A(t,n,o)};x(t,n,o)},R=["IMG","IFRAME","VIDEO"],S=function(e,n,o){t(e,o),n.unobserve(e)},M=function(t){var e=c(t);e&&(clearTimeout(e),s(t,null))},j=function(t,e,n){var o=n._settings.load_delay,r=c(t);r||(r=setTimeout(function(){S(t,e,n),M(t)},o),s(t,r))},D=function(t){return t.isIntersecting||t.intersectionRatio>0},T=function(t){return{root:t.container===document?null:t.container,rootMargin:t.thresholds||t.threshold+"px"}},U=function(t,e){this._settings=n(t),this._setObserver(),this._loadingCount=0,this.update(e)};return U.prototype={_manageIntersection:function(t){var e=this._observer,n=this._settings.load_delay,o=t.target;n?D(t)?j(o,e,this):M(o):D(t)&&S(o,e,this)},_onIntersection:function(t){t.forEach(this._manageIntersection.bind(this))},_setObserver:function(){g&&(this._observer=new IntersectionObserver(this._onIntersection.bind(this),T(this._settings)))},_updateLoadingCount:function(t){this._loadingCount+=t,0===this._elements.length&&0===this._loadingCount&&C(this._settings.callback_finish)},update:function(t){var e=this,n=this._settings,o=t||n.container.querySelectorAll(n.elements_selector);this._elements=l(Array.prototype.slice.call(o)),!v&&this._observer?this._elements.forEach(function(t){e._observer.observe(t)}):this.loadAll()},destroy:function(){var t=this;this._observer&&(this._elements.forEach(function(e){t._observer.unobserve(e)}),this._observer=null),this._elements=null,this._settings=null},load:function(e,n){t(e,this,n)},loadAll:function(){var t=this;this._elements.forEach(function(e){t.load(e)})}},_&&function(t,e){if(e)if(e.length)for(var n,o=0;n=e[o];o+=1)d(t,n);else d(t,e)}(U,window.lazyLoadOptions),U});
var eveLazyLoad = new LazyLoad({
	elements_selector: '.loadlzly', callback_set: function(el){ el.classList.add( 'active' ); debug['loadlzly'] = 'Success'; }
});

(function() {
  var css = document.createElement('link');
  css.rel = 'stylesheet';
  css.href = '<?php echo get_template_directory_uri(); ?>/css/photoswipe.css?v=<?php echo progresseve_verison_control(); ?>';
  css.type = 'text/css';
  var godefer = document.getElementsByTagName('link')[0];
  godefer.parentNode.insertBefore(css, godefer);
})();

(function() {
  var wf = document.createElement('script');
  wf.src = '<?php echo get_template_directory_uri(); ?>/js/photoswipe.min.js?v=<?php echo progresseve_verison_control(); ?>';
  wf.type = 'text/javascript';
  document.body.appendChild(wf);
})();

function initMap(){
  var center = [], container, pin;
  container = document.getElementById( 'google-map' );
  center.lat = parseFloat(container.getAttribute( 'data-gmap-lat' ));
  center.lng = parseFloat(container.getAttribute( 'data-gmap-lng' ));
  pin = container.getAttribute( 'data-pin-src' );

	var map = new google.maps.Map(container, {
		center: center,
		zoom: 14,
		mapTypeControl: false,
    scaleControl: true,
    streetViewControl: false,
    rotateControl: true,
    fullscreenControl: false,
    navigationControl: true,
    scrollwheel: false,
    draggable: true,
		scrollwheel: false,
		styles: [{featureType:"water",elementType:"geometry",stylers:[{color:"#e9e9e9"},{lightness:17}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:20}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{color:"#ffffff"},{lightness:17}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{color:"#ffffff"},{lightness:29},{weight:.2}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:18}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:16}]},{featureType:"poi",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:21}]},{featureType:"poi.park",elementType:"geometry",stylers:[{color:"#dedede"},{lightness:21}]},{elementType:"labels.text.stroke",stylers:[{visibility:"on"},{color:"#ffffff"},{lightness:16}]},{elementType:"labels.text.fill",stylers:[{saturation:36},{color:"#333333"},{lightness:40}]},{elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"geometry",stylers:[{color:"#f2f2f2"},{lightness:19}]},{featureType:"administrative",elementType:"geometry.fill",stylers:[{color:"#fefefe"},{lightness:20}]},{featureType:"administrative",elementType:"geometry.stroke",stylers:[{color:"#fefefe"},{lightness:17},{weight:1.2}]}]
  });
  
  var df = new google.maps.Marker({
		position : new google.maps.LatLng(center),
		map : map,
		icon: pin,
		title: '<?php echo esc_html(get_bloginfo('name')); ?>'
  });
  map.panBy(0, -80);
  debug['gmap'] = 'Success';
}

jQuery(document).ready(function($) {
  (function(){ debug['init'] = '<?php echo esc_html(get_bloginfo('name')); ?>'; })();

  $("a[href*='#']:not([href='#'])").click(function(){if(location.pathname.replace(/^\//,"")==this.pathname.replace(/^\//,"")&&location.hostname==this.hostname){var t=$(this.hash);if((t=t.length?t:$("[name="+this.hash.slice(1)+"]")).length)return $("html,body").animate({scrollTop:t.offset().top},1e3),!1}});

  var initPhotoSwipe = function( gallerySelector ) {
    container = document.getElementById( gallerySelector );
    if( !container ) { return; };
    
    var items = [];
    $('.gallery__fig').each(function(index, value){
      var size = $(this).data('imgsize').split('x');
      var item = {
        src: $(this).data('imgsrc'),
        w: parseInt(size[0], 10),
        h: parseInt(size[1], 10),
        index: $(this).data('imgindex')
      };
      items.push(item); });

    function openPhotoSwipe(index){
      var pswp = document.querySelectorAll('.pswp')[0];
      var options = { index: index };

      if (typeof PhotoSwipe === 'function'){
        gallery = new PhotoSwipe( pswp, PhotoSwipeUI_Default, items, options );
        gallery.init();
      };
    };

    $('.gallery__fig').each(function(index, value){
      $(this).on( 'click', function(e){
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        openPhotoSwipe( index ); });
    });
  };
  initPhotoSwipe('evenp-gallery');

  (function() {
		var container = document.getElementById( 'google-map' );
		if ( !container ) { return; };

		var wf = document.createElement('script');
		wf.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDFi3XIrYAofAdNL80nf8Q1d8fehewzhqQ&callback=initMap';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
  })();
  
  (function() {
    $('#evenp__content-action').click(function() {
      $($(this).data('fade')).hide();
      $($(this).data('ctn')).css('height', 'auto');
	  });
  })();

  $(function () {
    $('#suggested-notification-trigger input[type=checkbox]').change(function () {                
      $('#suggested-notification').toggle(this.checked);
    }).change();
  });
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
<symbol id="i-send" viewBox="0 0 33.656 32.001"><path d="M2.482.134A2.282,2.282,0,0,0,0,.659L5.06,14.551l25.779,1.432L5.06,17.416,0,31.308a2.282,2.282,0,0,0,2.482.525c8.5-3.628,31.173-13.51,31.173-15.849S11.028,3.762,2.482.134Z" transform="translate(0 0.017)"/></symbol><symbol id="i-phone" viewBox="0 0 30 30"><path d="M32.009,25.267a1.129,1.129,0,0,0-.628-1c0-.008,0-.015,0-.023l-7.364-3.753a0,0,0,0,0,0,0,1.1,1.1,0,0,0-.591-.19,1.121,1.121,0,0,0-.864.421.061.061,0,0,0-.014-.009l-3.178,2.895a1.088,1.088,0,0,1-1.338.164,23.207,23.207,0,0,1-7.663-7.66,1.1,1.1,0,0,1,.163-1.341l2.888-3.179-.006-.01a1.121,1.121,0,0,0,.421-.863,1.1,1.1,0,0,0-.19-.59h0L9.864,2.719l-.022.005a1.12,1.12,0,0,0-.977-.591,1.057,1.057,0,0,0-.261.053,0,0,0,0,0,0,0A7.224,7.224,0,0,0,4.057,4.156C-.12,8.335,2.3,17.522,9.459,24.683s16.351,9.58,20.53,5.4A7.282,7.282,0,0,0,32,25.433l-.018-.012A1.3,1.3,0,0,0,32.009,25.267Z" transform="translate(-2.009 -2.133)"/></symbol><symbol id="i-messenger" viewBox="0 0 24 24"><path d="M13.193,14.963,10.137,11.7,4.174,14.963,10.733,8l3.131,3.259L19.752,8ZM12,0C5.373,0,0,4.975,0,11.111a10.8,10.8,0,0,0,4.472,8.652V24l4.086-2.243A12.9,12.9,0,0,0,12,22.222c6.627,0,12-4.975,12-11.111S18.627,0,12,0Z"/></symbol><symbol id="i-facebook" viewBox="0 0 32 32.001"><g><path d="M-3951-73h-15a2,2,0,0,1-2-2v-28a2,2,0,0,1,2-2h28a2,2,0,0,1,2,2v28a2,2,0,0,1-2,2h-8V-85h4l1-5h-5v-2a2.659,2.659,0,0,1,3-3h2v-5h-4a5.533,5.533,0,0,0-4.379,1.955A7.7,7.7,0,0,0-3951-93v3h-4v5h4v12Z" transform="translate(3968 105)"/></g></symbol><symbol id="i-instagram" viewBox="0 0 32.823 32.823"><g transform="translate(-3.4 -4.8)"><path d="M23.87,16.7a8.47,8.47,0,1,0,8.47,8.47A8.487,8.487,0,0,0,23.87,16.7Zm0,13.9A5.426,5.426,0,1,1,29.3,25.17,5.451,5.451,0,0,1,23.87,30.6Z" transform="translate(-4.059 -4.025)"/><circle cx="1.919" cy="1.919" r="1.919" transform="translate(26.694 10.557)"/><path d="M33.576,7.513A9.425,9.425,0,0,0,26.628,4.8H13c-5.757,0-9.6,3.838-9.6,9.6V27.961a9.522,9.522,0,0,0,2.779,7.081,9.657,9.657,0,0,0,6.882,2.581h13.5a9.771,9.771,0,0,0,6.948-2.581,9.474,9.474,0,0,0,2.713-7.015V14.4A9.486,9.486,0,0,0,33.576,7.513Zm-.265,20.514a6.466,6.466,0,0,1-1.919,4.831,6.84,6.84,0,0,1-4.831,1.721h-13.5a6.84,6.84,0,0,1-4.831-1.721,6.652,6.652,0,0,1-1.787-4.9V14.4A6.6,6.6,0,0,1,8.231,9.565a6.729,6.729,0,0,1,4.831-1.721H26.694a6.6,6.6,0,0,1,4.831,1.787A6.829,6.829,0,0,1,33.311,14.4V28.028Z"/></g></symbol>
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



if ( ! function_exists( 'eve_acf_google_map_api' ) ) :
	/**
	 * Set Google map API key for ACF
	 */
  function eve_acf_google_map_api( $api )
  {
    // Email: eventikum.web@gmail.com
    $api['key'] = 'AIzaSyDFi3XIrYAofAdNL80nf8Q1d8fehewzhqQ';
    return $api;
  }
endif;



if ( ! function_exists( 'eve_foo' ) ) :
	/**
	 * Comment
	 */
	function eve_foo() {

	}
endif;