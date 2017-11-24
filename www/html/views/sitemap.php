<h1 class="title">Sitemap</h1>
<ul>
<?php
foreach($seo as $page => $meta) {
  if(strpos($page, 'admin') !== false) {
    if(isAdmin()) {
      $title = explode('/', $page);
      $title = isset($title[1]) ? $title[1] : $title[0];
      echo '<li><a href="/'.$page.'">'.ucwords($title).'</a></li>';
    }
  } else if($page == 'case') {
  } else if($page == 'cases') {
    echo '<li><a href="/">'.ucwords($page).'</a></li>';
  } else {
    echo '<li><a href="/'.$page.'">'.ucwords($page).'</a></li>';
  }
}
?>
</ul>
