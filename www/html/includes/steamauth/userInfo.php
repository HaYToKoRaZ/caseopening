<?php

if(empty($_SESSION['steam_uptodate']) || $_SESSION['steam_uptodate'] == false || empty($_SESSION['steam_personaname'])) {
	$url = @file_get_contents("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=".$steamauth['apikey']."&steamid=".getUser('steamid'));
	$ownCSGO = json_decode($url, true);
	$url = @file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".getuser('steamid'));
	$content = json_decode($url, true);
	$_SESSION['steam_personaname'] = $content['response']['players'][0]['personaname'];
	$_SESSION['steam_avatarfull'] = $content['response']['players'][0]['avatarfull'];
	$_SESSION['steam_uptodate'] = time();
	$user = array(
		'name' => $_SESSION['steam_personaname'],
		'avatar' => $_SESSION['steam_avatarfull'],
		'ownCSGO' => isset($ownCSGO['playerstats']),
		'shash' => $_SESSION['shash'],
		'ip' => getIP(),
	);
	setcookie('SESSID', $_SESSION['shash'], time()+31536000);
	setcookie('STEAMID', $_SESSION['steamid'], time()+31536000);
	//session_id(sha1(uniqid(microtime())));
	Update('users', array('steamid' => $_SESSION['steamid']), $user);
}

$results = Query('SELECT * FROM `users` LEFT JOIN `users_messages` ON `users_messages`.`steamid` = `users`.`steamid`', array('users`.`steamid' => $_SESSION['steamid'], 'shash' => $_SESSION['shash']));
if($results && isset($_SESSION['steamid'])) {
	if(isset($results[0]['message'])) {
		$aff = 0;
		$tra = 0;
    foreach($results as $result) {
			if($result['message'] == 'affiliate') {
				$aff++;
			} else if($result['message'] == 'trade') {
				$tra++;
			} else {
      	setMessage($result['type'], $result['message']);
			}
  	}
		if($aff > 0) {
			setMessage($result['type'], 'Affiliate code used '.$aff.' time(s) and $'.prettyBalance($aff*0.02).' has been added to your balance');
		}
		if($tra > 0) {
			setMessage($result['type'], 'Trade offer for withdraw has been sent with '.$tra.' item(s).<br /><a href=\"https://steamcommunity.com/my/tradeoffers\" target=\"_blank\">View Trade Offers</a>');
		}
    Delete('users_messages', array('steamid' => $_SESSION['steamid']));
  }
	$_SESSION['name'] = $results[0]['name'];
	$_SESSION['tlink'] = isset($results[0]['tlink']) ? $results[0]['tlink'] : null;
	$_SESSION['btc_add'] = isset($results[0]['btc_add']) ? $results[0]['btc_add'] : null;
	$_SESSION['btc_id'] = isset($results[0]['btc_id']) ? $results[0]['btc_id'] : null;
	$_SESSION['role'] = $results[0]['role'];
	$_SESSION['deposit'] = $results[0]['deposit'];
	$_SESSION['withdraw'] = $results[0]['withdraw'];
	$_SESSION['ban'] = $results[0]['ban'];
	$_SESSION['avatar'] = $results[0]['avatar'];
	$_SESSION['balance'] = $results[0]['balance'];
	$_SESSION['ownCSGO'] = $results[0]['ownCSGO'];
} else {
	session_unset();
	session_destroy();
	setcookie('SESSID', '');
	setcookie('STEAMID', '');
	header('Location: /');
	exit;
}
