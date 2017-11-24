<?php

function generateToken($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function htmlEscape($string = null) {
  return is_array($string) ? array_map('html_escape', $string) : str_replace('&amp;', '&', htmlspecialchars($string, ENT_COMPAT, 'UTF-8'));
}

function noHTML($input) {
  return htmlentities($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function getPost($index) {
  if(is_string($index) && isset($_POST[$index])) {
    return htmlEscape(trim($_POST[$index]));
  }
  return null;
}

function getGet($index) {
  if(is_string($index) && isset($_GET[$index])) {
    return htmlEscape(trim($_GET[$index]));
  }
  return null;
}

function getIP() {
  if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
    return $_SERVER['HTTP_CLIENT_IP'];
  } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    return $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    return $_SERVER['REMOTE_ADDR'];
  }
}

function prettyBalance($number, $comma=true) {
  if(strpos($number, '.') !== false) {
    $number = substr($number, 0, strpos($number, '.') + 3);
  }
  $number = number_format($number, 2);
  return $comma ? $number : str_replace(',','',$number);
}

function getLoader() {
  return '<div class="blob blob-0"></div><div class="blob blob-1"></div><div class="blob blob-2"></div><div class="blob blob-3"></div><div class="blob blob-4"></div><div class="blob blob-5"></div>';
}

function getHazard() {
  return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
    <g transform="scale(0.1)">
      <path d="m 335.40299,412.269 0,0 63.276,32.886 c 14.986,13.044 11.24,21.924 21.647,29.973 10.268,9.436 16.79,10.962 22.895,2.082 4.857,-10.13 3.886,-20.26 5.413,-31.222 7.076,-6.799 12.905,-10.268 18.733,-17.067 6.383,-7.077 6.104,-14.154 -3.748,-19.149 -8.187,-3.33 -15.957,-4.996 -25.809,-2.082 -6.245,1.943 -12.905,3.053 -21.23,-0.416 l -68.272,-31.638 -194.088,-67.283 c -26.1,-12.756 -47.784,-22.274 -73.59,-36.501 -14.031,-11.186 -9.517,-23.844 -26.4917,-34.146 -7.4571,-3.728 -12.8536,-5.396 -18.8389,4.71 -3.8267,9.028 -3.5323,18.643 -4.121,27.965 -6.6721,7.162 -14.2273,11.382 -19.722,20.311 -7.9476997,12.756 4.1209,16.975 10.5968,18.545 18.4463,4.611 25.1183,-3.729 42.0928,-0.295 33.067,15.405 66.427,31.398 98.316,47.097 z" style="fill:#FFF;stroke:#2ecc71;stroke-width:12.5;stroke-linejoin:round" class="GZgJbFGL_0"/>
      <path d="m 349.55699,354.821 0,0 94.497,-44.542 c 17.9,-7.077 20.398,2.914 36.217,0 12.072,-0.139 22.063,-4.856 21.231,-16.234 -6.106,-9.021 -11.796,-14.71 -22.48,-24.562 0.555,-10.546 -1.388,-20.676 -5.412,-30.805 -4.163,-5.967 -11.656,-8.604 -22.895,2.081 -11.379,8.603 -7.772,20.121 -22.064,32.054 -23.312,11.517 -44.542,21.786 -70.352,34.552 l -196.487,67.855 c -22.757,12.072 -43.432,23.311 -69.519,33.302 -21.231,5.273 -20.398,-7.77 -46.2079,0.833 -7.3544,5.828 -7.2156,14.153 -1.2488,20.397 6.9382,6.8 11.795,11.102 17.9007,17.069 1.943,8.603 2.22,15.957 3.33,26.225 4.163,12.628 13.321,11.934 21.23,5.829 3.469,-4.302 7.771,-8.187 10.407,-12.905 3.608,-6.244 7.632,-13.321 12.489,-19.149 l 62.859,-33.303 z" style="fill:#FFF;stroke:#2ecc71;stroke-width:12.5;stroke-linejoin:round" class="GZgJbFGL_1"/>
      <path d="m 128.49799,221.561 c -19.136,-18.603 -32.51,-50.888 -34.366,-85.752 -2.015,-38.42 18.218,-76.570099 48.537,-98.319199 33.969,-23.9094 90.766,-30.522 126.082,-28.7375 23.921,0.0166 77.206,12.4202 96.015,26.4277 32.994,23.9901 50.688,58.04 51.914,96.849999 1.813,38.983 -14.606,68.675 -34.711,87.206" style="fill:#FFF;stroke:#2ecc71;stroke-width:12.5" class="GZgJbFGL_2"/>
      <path d="m 130.4,215 c 0,0 -23.13,70.03 21.44,81.97 l 16.25,93.66 c 17.15,73.72 154.4,73.77 174.6,0 l 16.25,-93.66 c 45.7,-12.2 22.5,-82 22.5,-82 z" style="fill:#FFF" class="GZgJbFGL_3"/>
      <path d="m 129.66199,112.267 c -7.752,6.782 -6.774,16.977 -5.378,32.192 1.102,11.56 8.478,22.939 8.866,33.5 2.712,22.285 -3.876,44.57 -6.977,62.786 -2.132,20.928 3.877,38.95 11.628,45.926 7.751,6.88 17.756,9.729 28.268,12.936 9.551,2.498 14.504,6.842 21.728,12.228 8.606,9.137 10.659,16.694 10.465,25.996" style="fill:none;stroke:#2ecc71;stroke-width:12.5" class="GZgJbFGL_4"/>
      <path d="m 382.06079,111.68561 c 7.752,6.782 6.774,16.977 5.378,32.192 -1.102,11.56 -8.478,22.939 -8.866,33.5 -2.712,22.285 3.876,44.57 6.977,62.786 2.132,20.928 -3.877,38.95 -11.628,45.926 -7.751,6.88 -21.937,10.575 -28.268,12.936 -7.377,2.581 -14.92,7.258 -21.728,12.644 -8.19,7.888 -10.659,16.278 -10.465,25.58" style="fill:none;stroke:#2ecc71;stroke-width:12.5" class="GZgJbFGL_5"/>
      <path d="m 151.82099,296.959 c 6.938,16.79 14.292,33.164 14.986,50.37 -3.191,13.876 -2.22,30.25 1.249,43.294 4.441,17.9 21.786,39.13 39.132,46.207 17.761,6.452 28.862,8.118 48.704,8.118" style="fill:none;stroke:#2ecc71;stroke-width:12.5" class="GZgJbFGL_6"/>
      <path d="m 358.92279,296.95897 c -6.938,16.79 -14.292,33.164 -14.986,50.37 3.191,13.876 2.22,30.25 -1.249,43.294 -4.441,17.9 -21.994,38.506 -39.34,45.583 -17.345,7.077 -28.445,8.742 -48.288,8.742" style="fill:none;stroke:#2ecc71;stroke-width:12.5" class="GZgJbFGL_7"/>
      <path d="m 251.89499,245.711 c -9.696,7.373 -11.853,9.849 -17.211,18.008 -1.939,3.149 -5.56,10.508 -6.55,15.445 -0.989,4.936 -1.73,12.098 -1.727,15.891 0.012,12.853 4.103,20.916 8.571,20.635 3.992,-0.458 9.072,-4.621 16.77,-14.076" style="stroke:#2ecc71;stroke-width:1pt" class="GZgJbFGL_8"/>
      <path d="m 258.87699,245.9191 c 9.696,7.373 11.853,9.849 17.211,18.008 1.939,3.149 5.56,10.508 6.55,15.445 0.989,4.936 1.73,12.098 1.727,15.891 -0.012,12.853 -4.103,20.916 -8.571,20.635 -3.992,-0.458 -9.072,-4.621 -16.77,-14.076" style="stroke:#2ecc71;stroke-width:1pt" class="GZgJbFGL_9"/>
      <path d="m 169.47671,189.56097 c 13.46,-2.359 26.503,-3.885 39.963,-5.411 17.623,-4.441 26.504,15.68 21.647,30.805 -6.105,13.46 -14.708,25.255 -24.56,36.633 -7.632,8.603 -16.724,9.995 -25.394,7.076 -6.998,-1.94 -13.461,-6.243 -20.399,-16.234 -3.052,-8.604 -5.688,-13.045 -9.574,-23.729 -3.052,-9.852 1.695,-24.6 18.317,-29.14 z" style="stroke:#2ecc71;stroke-width:1pt" class="GZgJbFGL_10"/>
      <path d="m 341.55879,189.56097 c -13.46,-2.359 -26.503,-3.885 -39.963,-5.411 -17.623,-4.441 -26.504,15.68 -21.647,30.805 6.105,13.46 14.708,25.255 24.56,36.633 7.632,8.603 16.724,9.995 25.394,7.076 6.998,-1.94 13.461,-6.243 20.399,-16.234 3.052,-8.604 5.688,-13.045 9.574,-23.729 3.052,-9.852 -1.695,-24.6 -18.317,-29.14 z" style="stroke:#2ecc71;stroke-width:1pt" class="GZgJbFGL_11"/>
      <path d="m 169.23199,304.53 c 5.69,13.344 7.45,18.415 11.553,31.718 3.496,12.289 4.781,19.794 6.941,37.258" style="fill:none;stroke:#2ecc71;stroke-width:6.25" class="GZgJbFGL_12"/>
      <path d="m 341.08629,303.79411 c -5.69,13.344 -7.45,18.415 -11.553,31.718 -3.496,12.289 -4.781,19.794 -6.941,37.258" style="fill:none;stroke:#2ecc71;stroke-width:6.25" class="GZgJbFGL_13"/>
      <path d="m 193.51599,335.732 c 14.718,9.027 40.161,10.419 62.956,10.009 25.394,-0.129 47.133,-1.963 61.409,-10.597" style="fill:none;stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_14"/>
      <path d="m 198.66699,359.282 c 16.238,10.204 35.334,13.708 57.326,12.878 22.842,-0.556 40.351,-2.478 56.001,-13.172" style="fill:none;stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_15"/>
      <path d="m 186.89299,371.644 c 17.367,20.948 41.608,24.585 69.173,25.315 29.519,1.077 59.166,-10.645 66.819,-25.314" style="fill:none;stroke:#2ecc71;stroke-width:6.25" class="GZgJbFGL_16"/>
      <path d="m 198.44399,340.256 -0.416,41.212" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_17"/>
      <path d="m 211.97299,344.627 -0.208,44.126" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_18"/>
      <path d="m 226.95899,346.5 -0.416,46.208" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_19"/>
      <path d="m 240.48899,347.541 -0.209,46.832" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_20"/>
      <path d="m 255.68299,347.957 0,46.832" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_21"/>
      <path d="m 270.25299,347.749 0.208,46.832" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_22"/>
      <path d="m 283.36573,346.49997 0.416,46" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_23"/>
      <path d="m 298.55999,344.003 0.208,44.126" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_24"/>
      <path d="m 312.29699,338.591 0.417,41.628" style="stroke:#2ecc71;stroke-width:5" class="GZgJbFGL_25"/>
    </g>
  </svg>';
}

function getCure() {
  return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128">
   <g fill-rule="evenodd">
    <g stroke-linecap="square">
     <g stroke="#2ecc71">
      <path d="m44.688 6.75c-7.3086 0-13.25 5.9414-13.25 13.25 0 5.4815 3.3422 10.208 8.0938 12.219-0.49491 2.0052-0.75 4.0927-0.75 6.25 0 8.1164 3.7389 15.344 9.5625 20.125-2.6159 1.3561-5.0147 3.0128-7.125 4.9375-2.3517-2.1376-5.4491-3.4375-8.875-3.4375-7.3086 0-13.25 5.9101-13.25 13.219 0 7.0492 5.5194 12.818 12.469 13.219 0.13876 2.294 0.57719 4.4949 1.25 6.625-0.13437 0.10667-0.27642 0.19821-0.40625 0.3125-5.421 4.7722-4.7446 14.406 1.5 21.5 6.2446 7.0937 15.704 8.9909 21.125 4.2188 1.4533-1.2793 2.4746-2.9149 3.0625-4.75 2.1771 0.40632 4.441 0.625 6.75 0.625 1.8241 0 3.599-0.14997 5.3438-0.40625 0.58328 1.872 1.5854 3.5435 3.0625 4.8438 5.421 4.7722 14.912 2.875 21.156-4.2188 6.018-6.8363 6.8302-16.008 2.0312-20.938 1.0207-2.7874 1.6117-5.732 1.6875-8.8125 0.38603 0.033897 0.79271 0.09375 1.1875 0.09375 7.3086 0 13.219-5.9414 13.219-13.25s-5.9101-13.25-13.219-13.25c-4.1663 0-7.8848 1.9262-10.312 4.9375-2.2361-2.1388-4.8174-3.963-7.6562-5.4375 5.8324-4.7811 9.5625-12.033 9.5625-20.156 0-1.8775-0.21593-3.7035-0.59375-5.4688 5.1933-1.7902 8.9375-6.7324 8.9375-12.531 0-7.3086-5.9414-13.219-13.25-13.219-5.0498 0-9.4548 2.8077-11.688 6.9688-2.9422-1.1525-6.1197-1.8125-9.4688-1.8125-2.9635 0-5.8137 0.5249-8.4688 1.4375-2.2182-4.2078-6.6032-7.0938-11.688-7.0938z" fill="#d45500" stroke-width="6"/>
      <g fill="#fff">
       <g stroke-width="2.4">
        <path d="m98.142 84.841c0 16.678-14.923 30.214-33.311 30.214s-33.311-13.536-33.311-30.214 14.923-30.214 33.311-30.214 33.311 13.536 33.311 30.214z"/>
        <path d="m44.688 6.75c-7.3086 0-13.25 5.9414-13.25 13.25 0 5.4815 3.3422 10.208 8.0938 12.219-0.49491 2.0052-0.75 4.0927-0.75 6.25 0 14.385 11.677 26.062 26.062 26.062s26.062-11.677 26.062-26.062c0-1.8775-0.21593-3.7035-0.59375-5.4688 5.1933-1.7902 8.9375-6.7324 8.9375-12.531 0-7.3086-5.9414-13.219-13.25-13.219-5.0498 0-9.4548 2.8077-11.688 6.9688-2.9422-1.1525-6.1197-1.8125-9.4688-1.8125-2.9635 0-5.8137 0.5249-8.4688 1.4375-2.2182-4.2078-6.6032-7.0938-11.688-7.0938z"/>
        <path d="m45.57 73.321c0 7.3086-5.9316 13.24-13.24 13.24-7.3086 0-13.24-5.9316-13.24-13.24 0-7.3086 5.9316-13.24 13.24-13.24 7.3086 0 13.24 5.9316 13.24 13.24z"/>
        <path d="m112.54 72.371c0 7.3086-5.9316 13.24-13.24 13.24-7.3086 0-13.24-5.9316-13.24-13.24 0-7.3086 5.9316-13.24 13.24-13.24 7.3086 0 13.24 5.9316 13.24 13.24z"/>
       </g>
       <g stroke-width="2.713">
        <path d="m53.55 97.685c6.2446 7.0937 6.9131 16.724 1.4921 21.496-5.421 4.7722-14.889 2.888-21.133-4.2057-6.2446-7.0937-6.9131-16.724-1.4921-21.496 5.421-4.7722 14.889-2.888 21.133 4.2057z"/>
        <path d="m74.754 98c-6.2446 7.0937-6.9131 16.724-1.4921 21.496 5.421 4.7722 14.889 2.888 21.133-4.2057 6.2446-7.0937 6.9131-16.724 1.4921-21.496-5.421-4.7722-14.889-2.888-21.133 4.2057z"/>
       </g>
       <path d="m77.947 46.847c0 7.3086-5.9316 13.24-13.24 13.24-7.3086 0-13.24-5.9316-13.24-13.24 0-7.3086 5.9316-13.24 13.24-13.24 7.3086 0 13.24 5.9316 13.24 13.24z" stroke-width="2.4"/>
      </g>
     </g>
     <g stroke-width=".753">
      <path d="m58.284 29.215c0 2.2942-1.862 4.1562-4.1562 4.1562s-4.1562-1.862-4.1562-4.1562 1.862-4.1562 4.1562-4.1562 4.1562 1.862 4.1562 4.1562z"/>
      <path d="m80.45 29.551c0 2.2942-1.862 4.1562-4.1562 4.1562s-4.1562-1.862-4.1562-4.1562 1.862-4.1562 4.1562-4.1562 4.1562 1.862 4.1562 4.1562z"/>
      <path d="m69.031 40.97c0 2.2942-1.862 4.1562-4.1562 4.1562s-4.1562-1.862-4.1562-4.1562 1.862-4.1562 4.1562-4.1562 4.1562 1.862 4.1562 4.1562z"/>
     </g>
    </g>
    <path d="m57 49.803c2 3.403 6.6325 3.3164 7.8312-1.131 2.6755 5.8564 6.9182 3.1474 8.1688 1.4063" fill="none" stroke="#2ecc71" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
   </g>
  </svg>';
}

function createModal($id, $body, $footer=null, $size='md', $header=null, $prefoot=null) {
  return '<div class="modal fade" id="'.$id.'">
    <div class="modal-dialog modal-'.$size.'" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p>'.$header.'</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          '.$body.'
        </div>
        <div class="modal-footer">
          '.$prefoot.'
          <button class="btn btn-primary red" data-dismiss="modal">Close</button>
          '.$footer.'
        </div>
      </div>
    </div>
  </div>';
}

function menuItem($url, $icon, $name) {
  return '<li '.is_current_page($url).'><a href="'.$url.'"><i class="fa fa-'.$icon.'" aria-hidden="true"></i> '.$name.'</a></li>';
}
