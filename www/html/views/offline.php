<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <title>Nanoflip | Countdown</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="CSGODisease.com is a CSGO case opening website. Our cases give great odds on the best items from the sought after Dragon Lore, Medusa and much, much more.">
    <link rel="icon" href="/img/favicon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,700">
    <link rel="stylesheet" href="css/offline.css?v=1.0">
  </head>
  <body class="main">
    <div class="container">
      <img src="/img/csgodisease.png" class="img-responsive"></img>
      <h1>We're offline for routine maintenance. We'll be back soon!</h1>
      <div id="countdown" class="countdown">
        <div class="row">
          <div class="countdown-item col-sm-3 col-xs-6">
            <div id="countdown-days" class="countdown-number">&nbsp;</div>
            <div class="countdown-label">days</div>
          </div>
          <div class="countdown-item col-sm-3 col-xs-6">
            <div id="countdown-hours" class="countdown-number">&nbsp;</div>
            <div class="countdown-label">hours</div>
          </div>
          <div class="countdown-item col-sm-3 col-xs-6">
            <div id="countdown-minutes" class="countdown-number">&nbsp;</div>
            <div class="countdown-label">minutes</div>
          </div>
          <div class="countdown-item col-sm-3 col-xs-6">
            <div id="countdown-seconds" class="countdown-number">&nbsp;</div>
            <div class="countdown-label">seconds</div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer">
      <div class="container">
        <div class="row">
          <p class="social"><a href="https://twitter.com/CSGODisease" title="Twitter" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a><a href="https://steamcommunity.com/groups/csgodisease" title="Steam" class="steam" target="_blank"><i class="fa fa-steam"></i></a></p>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.rawgit.com/carhartl/jquery-cookie/master/src/jquery.cookie.js"></script>
    <script src="https://cdn.rawgit.com/hilios/jQuery.countdown/master/src/countdown.js"></script>
    <script>function demo(){$.cookie("theme_csspath")&&$("link#theme-stylesheet").attr("href",$.cookie("theme_csspath")),$("#colour").change(function(){if(""!==$(this).val()){var a="css/style."+$(this).val()+".css";$("link#theme-stylesheet").attr("href",a),$.cookie("theme_csspath",a,{expires:365,path:"/"})}return!1})}function countdown(){var a=new Date(config.countdown.year,config.countdown.month-1,config.countdown.day,config.countdown.hour,config.countdown.minute,config.countdown.second),b={days:$("#countdown-days"),hours:$("#countdown-hours"),minutes:$("#countdown-minutes"),seconds:$("#countdown-seconds")};$("#countdown").countdown(a).on("update.countdown",function(a){b.days.text(a.offset.totalDays),b.hours.text(("0"+a.offset.hours).slice(-2)),b.minutes.text(("0"+a.offset.minutes).slice(-2)),b.seconds.text(("0"+a.offset.seconds).slice(-2))})}function utils(){function a(a){var b=a.split("#"),c=b[1],d=$("#"+c).offset(),e=d.top-100;e<0&&(e=0),$("html, body").animate({scrollTop:e},1e3)}$('[data-toggle="tooltip"]').tooltip(),$("#checkout").on("click",".box.shipping-method, .box.payment-method",function(a){$(this).find(":radio").prop("checked",!0)}),$(".box.clickable").on("click",function(a){window.location=$(this).find("a").attr("href")}),$(".external").on("click",function(a){a.preventDefault(),window.open($(this).attr("href"))}),$(".scroll-to, .scroll-to-top").click(function(b){var c=this.href;c.split("#").length>1&&(a(c),b.preventDefault())})}!function a(b,c,d){function e(g,h){if(!c[g]){if(!b[g]){var i="function"==typeof require&&require;if(!h&&i)return i(g,!0);if(f)return f(g,!0);throw new Error("Cannot find module '"+g+"'")}var j=c[g]={exports:{}};b[g][0].call(j.exports,function(a){var c=b[g][1][a];return e(c||a)},j,j.exports,a,b,c,d)}return c[g].exports}for(var f="function"==typeof require&&require,g=0;g<d.length;g++)e(d[g]);return e}({1:[function(a,b,c){},{}]},{},[1]),$.cookie("theme_csspath")&&$("link#theme-stylesheet").attr("href",$.cookie("theme_csspath")),$(function(){countdown(),utils(),demo()}),config={countdown:{year:2017,month:4,day:10,hour:12,minute:00,second:0}};</script>
  </body>
</html>
