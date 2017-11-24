<?php
if(!isset($_SESSION)) session_start();
foreach(glob('../includes/*.php') as $filepath) include $filepath;
if($offline && !isAdmin()) return;
if(getPost('inventory')) {
  echo displayItems(getInventory(2),'flip');
} else {
  echo displayItems(getItems(getPost('search')));
}
