<?php
include_once "includes/header.php";
?>
<h1>XSS Filter testing ground!</h1>
<?
$vectors = array("https://tlwsd.in", "https://eff.org", "irc://20.111.15.65/owned", "'>://dsfjk:'\"\";",
        "http://\"><img src=\"/index.php\" />", "http://\"><script>alert('Unfiltered');</script>", $_SERVER['PHP_SELF'], 
	"https://<IMG SRC=javascript:alert(&quot;XSS&quot;)>",
	"http://';alert(String.fromCharCode(88,83,83))//\';alert(String.fromCharCode(88,83,83))//\";alert(String.fromCharCode(88,83,83))//\";alert(String.fromCharCode(88,83,83))//--></SCRIPT>\">'><SCRIPT>alert(String.fromCharCode(88,83,83))</SCRIPT>");
foreach($vectors as $v) {
  //echo "<p style=\"display: inline-block; width: 400px; margin: 0;\">{$v}</p>";
  $r = removeXSS($v);
  if(is_array($r)) {
    var_dump($r);
    echo "<br />\n";
  } elseif(!empty($r)) {
    echo $r."<br />\n";
  } else {
    echo "<em>NULL -- Didn't pass the test!</em><br />\n";
  }
}
?>

<?
include_once "includes/footer.php";
?>
