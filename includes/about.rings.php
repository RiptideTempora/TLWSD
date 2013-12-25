<?php
$pageTitle = "Technical Specifications";
include "header.php";
?>
<h2>Technical Specifications (Keyrings)</h2>
<div class="aboutindent">
  This page was written for the technically-minded internet user who is curious
  about the reliability of this service.
</div>
<h2>What is a keyring?</h2>
<div class="aboutindent">
  A keyring is a list of one-use passwords associated with a special mode of
  self-destroying hyperlink. It's only a slight departure from the
  <a href="http://tlwsd.info/about/tech">standard</a> TLWSD operation.
</div>
<h2>What's different between a keyring and a standard self-destroying hyperlink?</h2>
<ol style="margin-top: 0.5em;">
    <li>Passwords are not optional.<ul>
            <li>This should be a no-brainer.</li>
        </ul></li>
    <li>You may specify any number of passwords, which may be used once.</li>
    <li>The generated URL is of the form <strong>https://tlwsd.in/v{token}</strong>
    instead of <strong>https://tlwsd.in/u{token}</strong>.</li>
    <li>Instead of textfiles, the information is stored in a sqlite database file.<ul>
            <li>Different file for each token. They are shredded the same way that
            standard self-destroying hyperlinks are. Each row is overwritten with
            noise after use and flagged as non-usable.</li>
        </ul></li>
</ol>
<h2>How a keyring is created (with Javascript)</h2>
<div class="aboutindent">
  Users may access <a href="https://tlwsd.in/keyring.php">the keyring form</a>
  directly. When they specify the number of uses, a javascript event is triggered
  that fills in the box with the number of passwords.
  <br /><br />
  A user must then provide the following:
  <ul>
      <li>The destination URL (http, https, ftp, irc only)</li>
      <li>The <a href="http://tlwsd.info/about/tech">security level</a></li>
      <li>The maximum amount of time that the self-destroying hyperlink can live</li>
  </ul>
  <br />
  When the user submits the form, the server generates a token (using a process
  identical to the standard SDH's) and a "salt key". The salt key (stored in
  Base-64 form) is 256 pseudorandom bits.
  <br /><br />
  An sqlite database (<strong>{token}</strong>.ring) is then created with the following table schema:
  <div class="aboutindent">
      CREATE TABLE metadata (validUntil INTEGER, saltShaker TEXT);<br />
      CREATE TABLE rings (id INTEGER PRIMARY KEY ASC, hash TEXT, ciphertext TEXT, validFlag INTEGER);
  </div>
  The UNIX timestamp that the SDH will expire is stored in `validUntil`, and the
  Base-64 representation of the salt key is stored in `saltShaker`.
  <br /><br />
  Then, for each password, the following code runs:
  <ol style="font-family: Courier New; font-size: 10pt;">
  <li>foreach($Passwords as <span style="color: #660;">$p</span>) {</li>
  <li>&nbsp; <span style="color: #90c;">$salt</span> = hash_hmac('sha256', $saltKey, $i, true);</li>
  <li>&nbsp; <span style="color: #c00;">$Key</span> = substr( hash_hmac('sha512', <span style="color: #660;">$p</span>, <span style="color: #90c;">$salt</span>, true), 32); // encryption key</li>
  <li>&nbsp; <span style="color: #0c0;">$Hash</span> = substr( hash_hmac('sha512', <span style="color: #660;">$p</span>, <span style="color: #90c;">$salt</span>, false), 0, 64); // comparison hash</li>
  <li>&nbsp; <span style="color: #00c;">$IV</span> = hash_hmac('sha256', <span style="color: #660;">$p</span>, <span style="color: #90c;">$salt</span>, true); // initialization vector</li>
  <li>&nbsp; <span style="color: #999;">$storeURL</span> = AES256_Encrypt($url, <span style="color: #c00;">$Key</span>, <span style="color: #00c;">$IV</span>);</li>
  <li>&nbsp; $DB->exec("INSERT INTO rings (id, hash, ciphertext, validFlag) VALUES ('{$i}', '<span style="color: #0c0;">{$Hash}</span>', '<span style="color: #999;">{$storeURL}</span>', '1');");</li>
  <li>&nbsp; $i++;</li>
  <li>}</li>
  </ol>
  In English, each row's salt is the result of HMAC-SHA-256 of the salt key,
  signed with an increasing counter (1 for the first password, etc). Then, the
  same process (with HMAC instead of hash) is used for deriving the encryption
  key, IV, and comparison hash. The counter variable, comparison hash, encrypted
  URL, and a 1 (a flag to indicate this URL has not yet been used) are stored in
  a new sqlite entry (`rings` table).
  <br /><br />
  Once the database is complete, the self-destroying URL is provided to the
  user to disseminate as they see fit.
</div>
<h2>How a keyring is created (without Javascript)</h2>
<div class="aboutindent">
  Users are prompted for how many passwords they're going to use in their keyring
  on the front page. Then they are taken to a static version of the form in the
  above section. The only difference from here on is that changing the number of
  uses doesn't do anything, and you cannot dynamically change the number of
  password slots without Javascript.
</div>
<h2>What happens when a keyring is used</h2>
<div class="aboutindent">
  Just as with password-protected standard SDHs, the user is prompted to enter
  a password. Once the user supplies a value, the following takes place:
  <br /><br />
  First, the server script checks to see that the SDH exists, hasn't expired,
  and has at least one valid password left in the associated sqlite database
  file. If it exists but fails the other checks, the .ring file is shredded
  from the filesystem and the user gets a 404. If it doesn't exist, the user
  gets a 404.
  <br /><br />
  Next, each valid row is examined. The user-supplied password is HMAC'd in the
  same fashion as the correct password during creation, and the comparison hash
  is used to see if decryption should be attempted. If there are no matches, the
  user is notified that their password was incorrect and given the opportunity
  to try again.
  <br /><br />
  Upon entering a correct password, the URL is decrypted, the sqlite entry is
  overwritten with random garbage, and flagged as invalid. If this was the last
  valid entry, the file is immediately shredded.
  <br /><br />
  If the user has not sent the "neverForward" cookie with their request, the URL
  is then served to the end-user via HTTP Location header. If the user does have
  the "neverForward" setting enabled, they are instead served the (currently
  experimentally) XSS-sanitized URL.
</div>
<?php
include "footer.php";
?>