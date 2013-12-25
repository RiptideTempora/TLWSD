<?php
include "includes/global.php";
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE);
// DURING TESTING, UNCOMMENT ABOVE LINES
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
if(!file_exists(NONCE_ROOT."{$req}.ring") || filesize(NONCE_ROOT."{$req}.ring") < 1) {
  header("Location: /404.php", false, 404);
  exit; // LOL NOPE
}
if(!empty($_POST['password'])) {
  $DB = new PDO("sqlite:".NONCE_ROOT."{$req}.ring"); // sqlite 3
  $data = $DB->query("SELECT * FROM metadata")->fetchAll();
  $expiration = $data[0][0];
  $saltKey = base64_decode($data[0][1]); // My jokes are terrible
  $numValid = $DB->query("SELECT count(id) FROM rings WHERE validFlag = '1'")->fetchColumn(0);
  if(time() > $expiration || $numValid == 0) {
    while(!shredData(NONCE_ROOT."{$req}.ring")) {
      // If it returns false, wait a few clock cycles
      usleep(1000);
    }
    header("Location: http://tlwsd.in/404.php", false, 404);
    exit; // LOL NOPE
  }
  $found = false;
  //ob_start();
  //echo "{$saltKey}\n<pre>";
  foreach($DB->query("SELECT * FROM rings") as $row) {
    $i = intval($row['id']);
    $salt = hash_hmac('sha256', $saltKey, $i, true);
    if(validate_password($_POST['password'], $row['hash'])) {
      // CORRECT PASSWORD:
      $found = true;
      $key = create_key($_POST['password'], hash('sha256', $_POST['password'], 1), 'sha512' ); // Encryption key
        // 32 bytes = 256 bits, encryption key
      $newHash = hash('sha256', openssl_random_pseudo_bytes(64));
      $url = AES256_Decrypt($row['ciphertext'], $key);
    
      $newCipher = base64_encode(openssl_random_pseudo_bytes(strlen(base64_decode($row['ciphertext'])))); // For replacing
      $DB->exec("UPDATE rings SET validFlag = '0', ciphertext = '{$newCipher}', hash = '{$newHash}' WHERE id = '{$i}'");
      $numValid--;
      if($numValid < 1) {
        while(!shredData(NONCE_ROOT."{$req}.ring")) {
          // If it returns false, wait a few clock cycles
          usleep(1000);
        }
      }
      // Overwrite
      //ob_end_clean();
      if(!$_COOKIE['neverForward']) {
        header("Location: {$data}");
        die($url);
      } else {
        $data = removeXSS($data); // Experimental; without warranty
        include "includes/header.php";
        echo "The destination URL is: <a href=\"".$data."\">".$data."</a>";
        include "includes/footer.php";
      }
      exit;
    }
    //echo substr( hash_hmac('sha512', $_POST['password'], $salt, false), 0, 64)." != ".$row['hash']."\n"; // DEBUG
  }
  //echo "</pre>";
  //$data = ob_get_clean();
  if(!$found) {
      include "includes/header.php";
      echo "<div style=\"color: red;\">Incorrect password, or it has already been used.</div>\n";
      //echo $data;
      include "includes/nonce-pw.php";
      include "includes/footer.php";
  }
} else {
    // Prompt for username and password
    include "includes/header.php";
    include "includes/ring-pw.php";
    include "includes/footer.php";
}
?>