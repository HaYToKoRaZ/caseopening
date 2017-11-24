<?php
if(!isset($_SESSION)) session_start();
foreach(glob('../includes/*.php') as $filepath) include $filepath;
if($offline && !isAdmin()) return;
echo sellItems(getPost('itemids'));
