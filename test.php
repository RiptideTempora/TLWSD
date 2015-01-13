<?php
include "includes/global.php";
header("Content-Type: text/plain");

$enc = AES256_Encrypt("This is a test!", 'YELLOW SUBMARINE');
echo $enc;

echo "\n\n";

$dec = AES256_Decrypt($enc, 'YELLOW SUBMARINE');
var_dump($dec);

echo "\n\n";

// These should be constant:

for ($i = 0; $i < 10; ++$i) {
	echo base64_encode(
			create_key('YELLOW SUBMARINE', hash_hmac('sha256', 'YELLOW SUBMARINE', $i, 1), 'sha512' )
		) . "\n";
}