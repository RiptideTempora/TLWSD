<?php
switch($page) {
  case "ssl":
    include "about.ssl.php";
  break;
  case "dev":
    include "about.dev.php";
  break;
  case "tech":
    include "about.tech.php";
  break;
  case "rings":
    include "about.rings.php";
  break;
  case "faq":
    include "about.faq.php";
  break;
  default:
    include "about.def.php";
  break;
}
?>
