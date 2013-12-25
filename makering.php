<?php
include_once "includes/global.php";
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE);
// DURING TESTING, UNCOMMENT ABOVE LINES
      
$n = 0;
do {
  switch($_POST['sec']) {
    /*
     * Security is a continuum, not an absolution. The system by default has
     * range defined from 1 (low) to 255 (high) with the hopes that custom
     * a smoother continuum can be established in the future.
     */
    case 1:
      $nonce = substr(hash('sha1', openssl_random_pseudo_bytes(16)), 0, 6);
      // Creates a short nonce for embedding in Twitter, etc.
      if($n > 18) { // Too many collisions - No DoS attacks allowed
        $n = 0;
        $_POST['sec']++;
      }
    break;
    case 2:
      $nonce = substr(hash('sha1', openssl_random_pseudo_bytes(18)), 0, 8);
      // Creates a short nonce for embedding in Twitter, etc.
      if($n > 36) { // Too many collisions - No DoS attacks allowed
        $n = 0;
        $_POST['sec']++;
      }
    break;
    case 3:
      $nonce = substr(hash('sha1', openssl_random_pseudo_bytes(20)), 0, 10);
      // Creates a short nonce for embedding in Twitter, etc.
      if($n > 36) { // Too many collisions - No DoS attacks allowed
        $n = 0;
        $_POST['sec']++;
      }
    break;
    case 255:
      $nonce = hash_hmac('sha1', openssl_random_pseudo_bytes(20), openssl_random_pseudo_bytes(20));
        // High security? Unique passwords must be enforced!
        $Passwords = array();
        foreach($_POST['passwds'] as $p) {
          $Passwords[hash('sha256', $p, 1)]++;
        }
        foreach($Passwords as $i => $v) {
          if($b == 1) continue;
          die("Password collision detected.");
        }
      // Long nonce, very collision-resistant
    break;
    default:
      $nonce = substr(hash('sha1', openssl_random_pseudo_bytes(20)), 0, 12);
      if($n > 216) { // Too many collisions - No DoS attacks allowed
        $n = 0;
        $_POST['sec'] = 255;
      }
      // Medium nonce. Most common case
    break;
  }
  $nonce = base_convert($nonce, 16, 36);
  $n++;
} while(@file_exists(NONCE_ROOT."{$nonce}.ring"));

if($_POST['time_scalar'] > 0) {
  switch($_POST['time_unit']) {
    case 'm':
        $tf = 60;
        break;
    case 'h':
        $tf = 60 * 60;
        break;
    case 'd':
        $tf = 60 * 60 * 24;
        break;
    case 'w':
        $tf = 60 * 60 * 24 * 7;
        break;
  }
  $t = time() + (intval($_POST['time_scalar']) * $tf);
} else {
  $t = time() + (60 * 60 * 24 * 30); // Default: 30 days
}

$saltKey = openssl_random_pseudo_bytes(64); // 256 bit key for HMAC-SHA2

$DB = new PDO("sqlite:".NONCE_ROOT."{$nonce}.ring"); // sqlite 3
  $DB->exec( "CREATE TABLE metadata (validUntil INTEGER, saltShaker TEXT);");
    $DB->exec( "INSERT INTO metadata (validUntil, saltShaker) VALUES ('{$t}', '".
            base64_encode($saltKey)."');");
  $DB->exec( "CREATE TABLE rings (id INTEGER PRIMARY KEY ASC, hash TEXT, ciphertext TEXT, validFlag INTEGER);");
  
$url = $_POST['url'];
if(!preg_match('/^(http|ftp|https|irc):\/\//', $url)) {
  $url = "http://{$url}";
}
$i = 1;
foreach($_POST['passwds'] as $p) {
  $salt = hash_hmac('sha256', $saltKey, $i, true);
  $iKey = substr( hash_hmac('sha512', $p, $salt, true), 32);
    // 32 bytes = 256 bits, encryption key
  $iHash = substr( hash_hmac('sha512', $p, $salt, false), 0, 64);
    // 64 hex digits = 256 bits, comparison hash
  $iIV = hash_hmac('sha256', $p, $salt, true);
    // 32 bytes = 256 bits, IV
  $storeURL = AES256_Encrypt($url, $iKey, $iIV);
  //echo("INSERT INTO rings (id, hash, ciphertext, validFlag) VALUES ('{$i}', '{$iHash}', '{$storeURL}', '1');\n");
  $DB->exec("INSERT INTO rings (id, hash, ciphertext, validFlag) VALUES ('{$i}', '{$iHash}', '{$storeURL}', '1');");
  $i++;
}
$pageTitle = "Success!";
include "includes/header.php";
?>
Your Multi-Password Self-Destroying Link is:
<div style="margin: 0 2em;" class="mono">
  https://tlwsd.in/v<?php echo $nonce; ?>
</div>
<?php
include "includes/footer.php";
?>