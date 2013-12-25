<?php
session_start();
/*
 * Encryption Functions
 * AES-256-CBC, TwoFish-256-CBC
 * Initilization Vectors are stored in $_SESSION variables
 */
    function AES256_Encrypt($sValue, $sSecretKey, $IV="\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0")
    {
      return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sValue, MCRYPT_MODE_CBC, $IV)));
    }	
    function AES256_Decrypt($sValue, $sSecretKey, $IV="\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0")
    {
      return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_CBC, $IV));
    }
    function TwoFish_Encrypt($sValue, $sSecretKey, $IV) {
      return trim(base64_encode(mcrypt_encrypt(MCRYPT_TWOFISH, $sSecretKey, $sValue, MCRYPT_MODE_CBC, $IV)));
    }
    function TwoFish_Decrypt($sValue, $sSecretKey, $IV) {
      return trim(mcrypt_decrypt(MCRYPT_TWOFISH, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_CBC, $IV));
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
  $in = str_replace('&', '&amp;', $in);
  $in = str_replace('\\', '\\\\', $in);
  $in = str_replace('<', '&lt;', $in);
  $in = str_replace('"', '&quot;', $in);
  $in = str_replace(':', '&#58;', $in);
  $in = str_replace("'", "\\'", $in);
  $in = str_replace('>', '&gt;', $in);
  $in = preg_replace('/([^\x20-\x7F]+)/', '', $in); // Remove non-ASCII chars
                                                    // and whitespace!
  
  // You can replace this with a call to HTMLPurifier in your implementation;
  // I haven't had the time to research XSS filters so I'm employing a basic one
  // until my circumstances change.                 --@RiptideTempora
  
  // If anyone wants to submit some XSS proof-of-concept code, I encourage you
  // to publish it openly as soon as you find it and just tweet me in the public
  // disclosure.
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
function raw2hex($raw) {
  $m = unpack('H*', $raw);
  return $m[1];
}
function hex2raw($hex) { 
  return pack('H*', $hex);
}

include "conf.php";
?>