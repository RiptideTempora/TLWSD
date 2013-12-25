<?php
$pageTitle = "Technical Specifications";
include "header.php";
?>
<noscript><div class="aboutindent">
    If you're looking for the details of keyrings, <a href="/about/rings">go here</a>.
</div>
</noscript>
<h2>Technical Specifications (Standard)</h2>
<div class="aboutindent">
  This page was written for the technically-minded internet user who is curious about the
  reliability of this service.
</div>
<h2>Overview</h2>
<div class="aboutindent">
  At a glance, TLWSD's service consists of the following:
  <ul>
    <li>Pseudo-randomly generated self-destroying hyperlinks</li>
    <li>AES encryption for password-protected destination URLs</li>
  </ul>
</div>
<h2>SDH Generation</h2>
<div class="aboutindent">
  When a user decides to generate a self-destroying hyperlink, the following operations take place
  depending on the level of security desired:
  <ul>
      <li>Compact &mdash; 16 pseudorandom bytes (128 bits) are fed into an SHA1 hash, which is truncated.</li>
      <li>Medium &mdash; 20 pseudorandom bytes (160 bits) are fed into an SHA1 hash, which is truncated.</li>
      <li>Secure &mdash; 20 pseudorandom bytes (160 bits) plus another 20 random bytes (160 bits) are fed into an HMAC-SHA1 hash, which is <strong>NOT</strong> truncated.</li>
  </ul>
  The end result is a base-36 number preceded by the letter "u". For example, if you generate <span class="mono"><b>1w8a2</b></span>,
  then the URL you are provided is <span class="mono">https://tlwsd.in/u<b>1w8a2</b></span>.
</div>
<h2>URL Encryption</h2>
<div class="aboutindent">
  If a user supplies a password when creating their self-destroying hyperlink, the following operations take place:
  <ol>
    <li>The SHA-512 hash is taken of the password.</li>
    <li>The hash generated in step 1 is split in half. The first half is stored for hash-comparison. The second half is
    used as the encryption key.</li>
    <li>The SHA-256 hash is taken of the password.</li>
    <li>The password is encrypted with AES-256-CBC (IV = SHA256(password))</li>
  </ol>
  My reasoning for this is as follows: Since the SHA-512 hash is split in half, you end up with two 256-bit values that
  resulted from the same input but do not reveal the other's value. Combine this with a third 256-bit value (to use for an
  Initialization Vector) from the same input, and it becomes to possible to store a hash of the password while still having
  two more-or-less independent values for the key and IV for AES-256-CBC encryption.
  <br /><br />
  Even if I were a malicious user, having SHA-512-Left does not give me SHA-512-Right, nor SHA-256 and especially not AES-256-CBC.
</div>
<h2>Data Storage</h2>
<div class="aboutindent">
  The information about each link is stored in a file on the server that looks like this:
<div class="code"><ol><li>2</li>
        <li>ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db2</li>
        <Li>Zu/VdsTX3LBom8wWYDF8fh4czfdrVoDoiSKD9x3xQQc=</li>
        <li>1338829976</li></ol></div>
  Line 1 is the number of times this link can be used again.<br />
  Line 2 is the stored hash of the user-supplied password.<br />
  Line 3 is the AES-encrypted URL.<br />
  Line 4 is the <a href="https://en.wikipedia.org/wiki/Unix_time">expiration time</a>. (Default: 30 days after creation.)
</div>
<h2>Final Destination</h2>
<div class="aboutindent">
  If the user has not sent the "neverForward" cookie with their request, the URL
  is then served to the end-user via HTTP Location header. If the user does have
  the "neverForward" setting enabled, they are instead served the (currently
  experimentally) XSS-sanitized URL.
</div>
<h2>Data Retention</h2>
<div class="aboutindent">
  When a self-destroying link's lifetime reaches 0, it is immediately securely deleted from the server. Secure deletion,
  meaning a seven-pass overwrite (<strong>0xF6</strong>, <strong>0x00</strong>, <strong>0xFF</strong>, random bytes,
  <strong>0x00</strong>, <strong>0xFF</strong>, and more random bytes) before being deleted to ensure the information
  is completely scrambled and unrecoverable.
</div>
<?php
include "footer.php";
?>