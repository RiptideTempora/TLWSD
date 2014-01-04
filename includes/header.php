<?php
header('Content-type: text/html; charset=utf-8');
require_once "global.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php if(!empty($pageTitle)) { echo $pageTitle." - "; } ?>This Link Will Self Destruct</title>
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
                    <li><a href="mailto:BM-2DB5j88y6YZRMvesXSxsXm8GGn2jVwD24V@bitmessage.ch?subject=TLWSD">Contact</a></li>
                    <li><a href="http://tlwsd.info/source">Source Code</a></li>
                    <li><a href="http://tlwsd.info/links.php">Links</a></li>
                </ul>
              </div>
            </div>
            <div id="content">
            <?php
            if($_SERVER['SERVER_PORT'] != 443) {
              echo "<div class=\"error\">You are not using HTTPS. Are you fucking stupid?</div>\n";
            }
?>