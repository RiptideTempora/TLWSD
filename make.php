<?php
include_once "includes/global.php";
$life = intval($_POST['life']);
if($life < 1) {
  include "includes/header.php";
  echo "Invalid lifetime.";
  include "includes/footer.php";
  exit;
}
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
} while(@file_exists(NONCE_ROOT."{$nonce}.nonce"));

if(empty($_POST['password'])) {
  // Plaintext
  $passhash = 'nopass';
  $url = $_POST['url'];
  if(!preg_match('/^(http|ftp|https|irc):\/\//', $url)) {
    $url = "http://{$url}";
  }
} else {
  // 2012-09-07:
  // Updated the passhash algorithm. Prior to today, this was the line of code
  // that produced a hash for simple TLWSD links. Upgrade uses SHA-2 and bcrypt
  // $passhash = substr(hash('sha512', $_POST['password']), 0, 64); // Hash
  $cost = floor(10 + ((date('Ym') - 201204)/30)); // Increase by 1 every 30 months
                                           // to conform to Moore's Law
  $random = convBase(raw2hex(openssl_random_pseudo_bytes(33)), '0123456789abcdef', './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
  if($random{23}) $random = substr($random, 0, 22);
  $salt = "\$2a\${$cost}\${$random}";
  $passhash = substr(hash('sha512', $_POST['password']), 0, 64); // Step 1: Part of SHA512
  for($i = 1; $i <= 1000; $i++) { // Step 2: HMAC-SHA256 with an increasing key
    $passhash = hash_hmac('sha256', $_POST['password'].$passhash, $i);
  }
  $passhash = crypt($passhash, $salt); // Bcrypt the final result -- new feature!
  
  $key = substr(hash('sha512', $_POST['password'], 1), 32); // Encryption key
  $IV = hash('sha256', $_POST['password'], 1); // IV for 
  $url = $_POST['url'];
  if(!preg_match('/^(http|ftp|https|irc):\/\//', $url)) {
    $url = "http://{$url}";
  }
  $url = AES256_Encrypt($url, $key, $IV);
}
if($_POST['time_scalar']) {
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
  file_put_contents(NONCE_ROOT."{$nonce}.nonce", "{$life}\n{$passhash}\n{$url}\n{$t}");

$pageTitle = "Success!";
include "includes/header.php";
?>
Your Self-Destroying Link is:
<div style="margin: 0 2em;" class="mono">
  https://tlwsd.in/u<?php echo $nonce; ?>
</div>
<?php
include "includes/footer.php";
//var_dump($_POST);
?>