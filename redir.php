<?php
include "includes/global.php";
//header("Content-Type: text/plain");
//ini_set("display_errors", "On");
//error_reporting(E_ALL & ~E_NOTICE);
// DURING TESTING, UNCOMMENT ABOVE LINESss
if($_SERVER['SERVER_PORT'] != 443) {
    $_SESSION['keepLink'] = $_GET['req'];
  header("Location: /about/ssl");
  exit; // LOL NOPE
}
$req = substr($_GET['req'], 2);
if(!preg_match('/^[0-9a-z]{2,32}$/i', $req)) {
  header("Location: /404.php", false, 404);
  exit; // LOL NOPE
} // Regex filtering
if(!file_exists(NONCE_ROOT."{$req}.nonce") || filesize(NONCE_ROOT."{$req}.nonce") < 1) {
  header("Location: /404.php", false, 404);
  exit; // LOL NOPE
}
//list($life, $passhash, $data, $time) = explode("\n", file_get_contents(NONCE_ROOT."{$req}.nonce"));
$array = explode("\n", file_get_contents(NONCE_ROOT."{$req}.nonce"));
    // Process data from file
    $life = trim($array[0]);
    $passhash = trim($array[1]);
    $data = trim($array[2]);
    if(count($array) > 2) {
      // Special condition -- Time expiration!
      $timeMax = trim($array[3]);
    } else {
      $timeMax = 2147483647; // LARGE VALUE
    }
    
if($life > 0 && $timeMax >= time()) {
  if($passhash == 'nopass') {
    if($_COOKIE['alwaysForward']) {
      $life--;
      if($life > 0) {
        file_put_contents(NONCE_ROOT."{$req}.nonce", "{$life}\n{$passhash}\n{$data}\n{$timeMax}");
      } else {
        while(!shredData(NONCE_ROOT."{$req}.nonce")) {
          // If it returns false, wait a few clock cycles
          usleep(1000);
        }
      }
      header("Location: {$data}");
    } else {
      include "includes/header.php";
      echo "The destination URL is: <a href=\"".$data."\">".$data."</a>";
      include "includes/footer.php";
    }
  } elseif(!empty($_POST['password'])) {
    if(validate_password($_POST['password'], $passhash)) {
      $key = create_key($_POST['password'],
            hash('sha256', $_POST['password'], 1),
            'sha512'
         ); // Encryption key
      $data = AES256_Decrypt($data, $key);
        $life--;
        if($life > 0) {
          file_put_contents(NONCE_ROOT."{$req}.nonce", "{$life}\n{$passhash}\n{$data}\n{$timeMax}");
        } else {
          while(!shredData(NONCE_ROOT."{$req}.nonce")) {
            // If it returns false, wait a few clock cycles
            usleep(1000);
          }
        }
      if($_COOKIE['alwaysForward']) {
        header("Location: {$data}");
      } else {
        $data = removeXSS($data); // Experimental; without warranty
        include "includes/header.php";
        echo "The destination URL is: <a href=\"".$data."\">".$data."</a>";
        include "includes/footer.php";
      }
    } else {
      // Prompt for username and password
      include "includes/header.php";
      echo "<div style=\"color: red;\">Incorrect password.</div>\n";
      include "includes/nonce-pw.php";
      include "includes/footer.php";
    }
  } else {
    // Prompt for username and password
    include "includes/header.php";
    include "includes/nonce-pw.php";
    include "includes/footer.php";
  }
} else {
  while(!shredData(NONCE_ROOT."{$req}.nonce")) {
    // If it returns false, wait a few clock cycles
    usleep(1000);
  }
  header("Location: http://tlwsd.in/404.php", false, 404);
  exit; // LOL NOPE
}
?>