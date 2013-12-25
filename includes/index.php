<?php
/*
 * Redirect to home page. Directory listing is not allowed.
 */
$prot = $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
header("Location: {$prot}://{$_SERVER['HTTP_HOST']}");
exit;
?>
