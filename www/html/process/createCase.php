<?php
if(!isset($_SESSION)) session_start();
foreach(glob('../includes/*.php') as $filepath) include $filepath;
if($offline && !isAdmin()) return;
echo createCase(getPost('name'), getPost('image'), $_POST['items']);
