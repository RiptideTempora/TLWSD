<?php
header('Content-type: text/html; charset=utf-8');
require_once "global.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php if(!empty($pageTitle)) echo $pageTitle." - "; ?>This Link Will Self Destruct</title>
        <link rel="stylesheet" href="/style.css" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript"> <!--
           // jQuery menu for animated dropdowns
         $(document).ready( function() {
           $('.hover1').hover(function() {
              $(".menu1sub").fadeIn(200);
           }, function() {
              $(".menu1sub").fadeOut(200);
           });
              $(".menu1sub").fadeOut(0);
         });
        --></script>
    </head>
    <body>
        <div id="siteWrapper">
            <div id="header">
              <div id="headInner">
                <h1>This Link Will Self Destruct</h1>
                <ul id="navmenu">
                    <li><a href="https://tlwsd.in">Home</a></li>
                    <li><a href="http://tlwsd.info/about">About</a></li>
                    <li class="hover1"><a href="http://tlwsd.info/about/tech">Tech Spec</a><ul style="margin: 0; padding: 0;" class="menu1sub">
                            <li style="margin: 0; padding: 0;"><a href="http://tlwsd.info/about/tech">Standard</a></li>
                            <li style="margin: 0; padding: 0;"><a href="http://tlwsd.info/about/rings">Keyrings</a></li>
                        </ul></li>
                    <li><a href="http://tlwsd.info/about/dev">Development</a></li>
                    <li><a href="mailto:riptide.tempora@opinehub.com?subject=TLWSD">Contact</a></li>
                    <li><a href="http://tlwsd.info/source">Source Code</a></li>
                    <li><a href="http://tlwsd.info/links.php">Links</a></li>
                </ul>
              </div>
            </div>
            <div id="content">
            <?php
            if($_SERVER['SERVER_PORT'] != 443) {
                /*
                 *  Never show this on HTTPS. This is my adsense code. I don't
                 *  anticipate making any real money off the http://tlwsd.info
                 *  domain, but stranger things have happened.
                 */
              ?><div style="width: 728px; margin: auto;"><script type="text/javascript"><!--
google_ad_client = "ca-pub-9736960778486389";
//Wide Ad
google_ad_slot = "7926110816";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div><?php
            }
            /* THIS IS THE DEBUG COMMENT THAT I USED!
                <div style="color: red; text-align: center;">
                    The site is currently being worked on. If something breaks, please be patient. <a href="https://ssl.alpha7f.com/txt/?ph=tlwsd003">What's being added.</a>
                </div>
             */
            ?>