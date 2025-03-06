<?php
/*
  Plugin Name: Disable Right Click For WP
  Description: Disable Right Click For WP
  Author: Ashok Kumawat
  Version: 1.0.0
  Author URI: https://wordpress.com
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AK_DRCFW_VERSION', '1.0');
define('AK_DRCFW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AK_DRCFW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AK_DRCFW_NAME', 'Disable Right Click For WP');
define('AK_DRCFW_SLUG', 'disable_right_click_for_wp');
define('AK_DRCFW_DESC', "This plugin is used to disable right click on website to prevent cut, copy, paste, save image, view source, inspect element etc.");

include 'functions.php';

add_action('wp_head', 'ak_drcfw_style_n_script');
add_action('admin_menu', 'ak_drcfw_admin_dashboard_menu');

function ak_drcfw_admin_dashboard_menu() {
    add_options_page(AK_DRCFW_NAME, AK_DRCFW_NAME, 'manage_options', AK_DRCFW_SLUG . '_dashboard', AK_DRCFW_SLUG . '_settings');
}

function ak_drcfw_style_n_script() {
	if( current_user_can('editor') || current_user_can('administrator') ) {
		//Silence Is Golden
	}else{
		$show_msg_on_off = esc_html(get_option('ak_drcfw_show_msg'));
		?>
		<script type="text/javascript">
			//<![CDATA[
			var show_msg = '<?php echo $show_msg_on_off ?>';
			if (show_msg !== '0') {
				var options = {view_src: "View Source is disabled!", inspect_elem: "Inspect Element is disabled!", right_click: "Right click is disabled!", copy_cut_paste_content: "Cut/Copy/Paste is disabled!", image_drop: "Image Drag-n-Drop is disabled!" }
			} else {
				var options = '';
			}

         	function nocontextmenu(e) { return false; }
         	document.oncontextmenu = nocontextmenu;
         	document.ondragstart = function() { return false;}

			document.onmousedown = function (event) {
				event = (event || window.event);
				if (event.keyCode === 123) {
					if (show_msg !== '0') {show_toast('inspect_elem');}
					return false;
				}
			}
			document.onkeydown = function (event) {
				event = (event || window.event);
				//alert(event.keyCode);   return false;
				if (event.keyCode === 123 ||
						event.ctrlKey && event.shiftKey && event.keyCode === 73 ||
						event.ctrlKey && event.shiftKey && event.keyCode === 75) {
					if (show_msg !== '0') {show_toast('inspect_elem');}
					return false;
				}
				if (event.ctrlKey && event.keyCode === 85) {
					if (show_msg !== '0') {show_toast('view_src');}
					return false;
				}
			}
			function addMultiEventListener(element, eventNames, listener) {
				var events = eventNames.split(' ');
				for (var i = 0, iLen = events.length; i < iLen; i++) {
					element.addEventListener(events[i], function (e) {
						e.preventDefault();
						if (show_msg !== '0') {
							show_toast(listener);
						}
					});
				}
			}
			addMultiEventListener(document, 'contextmenu', 'right_click');
			addMultiEventListener(document, 'cut copy paste print', 'copy_cut_paste_content');
			addMultiEventListener(document, 'drag drop', 'image_drop');
			function show_toast(text) {
				var x = document.getElementById("ak_drcfw_toast_msg");
				x.innerHTML = eval('options.' + text);
				x.className = "show";
				setTimeout(function () {
					x.className = x.className.replace("show", "")
				}, 3000);
			}
		//]]>
		</script>
		<style type="text/css">body * :not(input):not(textarea){user-select:none !important; -webkit-touch-callout: none !important;  -webkit-user-select: none !important; -moz-user-select:none !important; -khtml-user-select:none !important; -ms-user-select: none !important;}#ak_drcfw_toast_msg{visibility:hidden;min-width:250px;margin-left:-125px;background-color:#333;color:#fff;text-align:center;border-radius:2px;padding:16px;position:fixed;z-index:999;left:50%;bottom:30px;font-size:17px}#ak_drcfw_toast_msg.show{visibility:visible;-webkit-animation:fadein .5s,fadeout .5s 2.5s;animation:fadein .5s,fadeout .5s 2.5s}@-webkit-keyframes fadein{from{bottom:0;opacity:0}to{bottom:30px;opacity:1}}@keyframes fadein{from{bottom:0;opacity:0}to{bottom:30px;opacity:1}}@-webkit-keyframes fadeout{from{bottom:30px;opacity:1}to{bottom:0;opacity:0}}@keyframes fadeout{from{bottom:30px;opacity:1}to{bottom:0;opacity:0}}</style>
		<?php
	}
}

add_action('wp_footer', 'ak_drcfw_add_div');

function ak_drcfw_add_div() {
    ?>
    <div id="ak_drcfw_toast_msg"></div>
<?php } ?>