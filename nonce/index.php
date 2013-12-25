<?php
/*
 * Redirect to home page. Directory listing is not allowed.
 * 
 * Ideally, this should not be in your public html folder. But just in case you
 * don't have a choice, this should stop people from indexing your nonces. ;)
 */
$prot = $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
header("Location: {$prot}://{$_SERVER['HTTP_HOST']}");
exit;
?>