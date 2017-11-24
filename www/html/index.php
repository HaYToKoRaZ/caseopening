<!-- Start of Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '2bea6c4d04fb259cef0b1385e656b87c4cebc441';
window.smartsupp||(function(d) {
	var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
	s=d.getElementsByTagName('script')[0];c=d.createElement('script');
	c.type='text/javascript';c.charset='utf-8';c.async=true;
	c.src='//www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>

<?php

	if($_SERVER['SERVER_NAME'] == '89.34.97.158' || $_SERVER['SERVER_NAME'] == '5.230.131.8') {
		echo 'Welcome to my website! I\'m currently working on it! Have fun :-)';
		exit();
	}

  foreach(glob('includes/*.php') as $filepath) include $filepath;

	if(isAdmin() && DEBUG) {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	include 'includes/steamauth/steamauth.php';

  function isPage($filepath = null) {
    $filepath = VIEW_PATH.$filepath.'.php';
    return file_exists($filepath) && is_file($filepath);
  }

  function getValidRoute($_route = array()) {
      if (!$_route) {
          return array('case');
      } else {
          $page = end($_route);
          if ($page && in_array($page{0}, array('@', '_', '.', '-'))) {
              array_pop($_route);
              return getValidRoute($_route);
          }
      }
      if ($_route && !isPage(implode('/', $_route))) {
          array_pop($_route);
          return getValidRoute($_route);
      }

      return $_route;
  }

  function is_current_page($page = null) {
      global $_view_path;
      $inPathStart = rtrim(substr($_view_path, 0, strlen($page) + 1), '/') == $page;
      if($inPathStart || $page == $_view_path) {
          return 'class="active"';
      }
      return false;
  }

  $_route = array_values(array_diff(explode('/', urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))), explode('/', urldecode(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH)))));
  $_route = filter_var_array(array_filter(array_map('strip_tags', $_route)), FILTER_SANITIZE_STRING);
  $_view = $_route ? getValidRoute($_route) : array(DEFAULT_HOME);
  $_view_path = $_view ? implode('/', $_view) : DEFAULT_HOME;
  $_params = array_slice($_route, count($_view));
  $_page = end($_view);
  $_page_class = str_replace('/', '-', $_view_path);
  $_title = (isset($seo[$_view_path][0]) && $seo[$_view_path][0]) ? $seo[$_view_path][0] : "Nanoflip";

  // LOAD CONTROLLER //
  include 'controllers/base.php';
	if($_view_path == 'offline') {
		include VIEW_PATH.'offline.php';
	} else {
  	include LAYOUT_PATH.'inside.php';
	}
