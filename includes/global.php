<?php
if(!defined('GLOBAL_LOADED')) {
  if(!session_id()) { 
    session_start();
  }
/*
 * Encryption Functions
 * AES-CTR, TwoFish-CTR
 * Initilization Vectors are stored in $_SESSION variables
 */
    function AES256_Encrypt($sValue, $sSecretKey, $IV=null)
    {
      if(empty($IV)) {
        $IV = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
      }
      return implode('$', 
               array(
                 base64_encode($IV), 
                 trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,
                         $sSecretKey, $sValue, 'ctr', $IV)))
               )
               // TODO: HMAC
             );
    }
    function AES256_Decrypt($sValue, $sSecretKey, $IV=null)
    {
      if(empty($IV)) {
        // Strip it out
        list($IV, $sValue) = explode('$', $sValue);
      }
      return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sSecretKey, base64_decode($sValue), 'ctr', base64_decode($IV) ));
    }
    function TwoFish_Encrypt($sValue, $sSecretKey, $IV=null)
    {
      if(empty($IV)) {
        $IV = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
      }
      return implode('$', 
               array(
                 base64_decode($IV), 
                 trim(base64_encode(mcrypt_encrypt(MCRYPT_TWOFISH,
                         $sSecretKey, $sValue, 'ctr', $IV)))
               )
               // TODO: HMAC
             );
    }
    function TwoFish_Decrypt($sValue, $sSecretKey, $IV=null)
    {
      if(empty($IV)) {
        // Strip it out
        list($IV, $sValue) = explode('$', $sValue);
      }
      return trim(mcrypt_decrypt(MCRYPT_TWOFISH, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_CTR, base64_decode($IV)) );
    }
function shredData($file) {
  if(!file_exists($file)) return false;
  if(!is_writeable($file)) return false;
  $l = filesize($file);
  if(!file_put_contents($file, str_repeat(chr(246), $l))) return false;
  if(!file_put_contents($file, str_repeat(chr(0), $l))) return false;
  if(!file_put_contents($file, str_repeat(chr(255), $l))) return false;
  if(!file_put_contents($file, openssl_random_pseudo_bytes($l))) return false;
  if(!file_put_contents($file, str_repeat(chr(0), $l))) return false;
  if(!file_put_contents($file, str_repeat(chr(255), $l))) return false;
  if(!file_put_contents($file, openssl_random_pseudo_bytes($l))) return false;
  if(!unlink($file)) return false;
  return true;
}
function removeXSS($in) {
  // Experimental feature. Add/remove XSS filters if new attack vectors are discovered.
  
  // First, grab the protocol and store it.
  preg_match('/^(https|http|ftp|irc):\/\/(.*)/', $in, $matches);
  // We do not support: file, data, or other stream wrappers. Sorry :(
  if(empty($matches)) return null; // Not matched to a URL
  $protocol = $matches[1];
  
  // We'll keep the rest of the string here:
  $in = $matches[2];
  
  // Replace what we don't want to have in a URL
  $in = htmlspecialchars($in, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  
  return $protocol.'://'.$in;
  // End result should be a URL
}

// Taken from http://www.php.net/manual/es/function.base-convert.php#106546
// Written by PHPCoder@niconet2k.com
function convBase($numberInput, $fromBaseInput, $toBaseInput)
{
    if ($fromBaseInput==$toBaseInput) return $numberInput;
    $fromBase = str_split($fromBaseInput,1);
    $toBase = str_split($toBaseInput,1);
    $number = str_split($numberInput,1);
    $fromLen=strlen($fromBaseInput);
    $toLen=strlen($toBaseInput);
    $numberLen=strlen($numberInput);
    $retval='';
    if ($toBaseInput == '0123456789')
    {
        $retval=0;
        for ($i = 1;$i <= $numberLen; $i++)
            $retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase),bcpow($fromLen,$numberLen-$i)));
        return $retval;
    }
    if ($fromBaseInput != '0123456789')
        $base10=convBase($numberInput, $fromBaseInput, '0123456789');
    else
        $base10 = $numberInput;
    if ($base10<strlen($toBaseInput))
        return $toBase[$base10];
    while($base10 != '0')
    {
        $retval = $toBase[bcmod($base10,$toLen)].$retval;
        $base10 = bcdiv($base10,$toLen,0);
    }
    return $retval;
}

include __DIR__."/conf.php";
include __DIR__."/pbkdf2.php";
  define('GLOBAL_LOADED', true);
}
?>