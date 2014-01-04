<?php
$pageTitle = "About Us";
include "header.php";
?>
<h2>Developer's Notes (To Do List)</h2>
<div class="aboutindent">
    <strong>Privacy</strong>
    <div class="aboutindent">
        <div class="devNote"><a href="https://ssl.alpha7f.com/txt/?ph=tlwsdGUI">Special Project</a></div>
        &#x2717; Switch password-hashing & encryption to client-side?<br />
    </div>
    <strong>Testing</strong>
    <div class="aboutindent">
        <div class="devNote">Waiting for community response</div>
        _ Test and improve the "Never Forward" XSS Filter<br />
    </div>
</div>
<h2>Development History</h2>
<div class="aboutindent">
    <div class="devNote">0.07-release</div>
    <strong>3 January 2014 - Security Enhancements</strong>
    <div class="aboutindent">
        &bull; Use AES-256, not Rijndael-256.<br />
        &bull; Use CTR mode, not CBC mode.<br />
        &bull; Randomly generate the IV, don't derive it from the passphrase.<br />
        &bull; Password hashing upgraded to PBKDF2.<br />
    </div>
    <div class="devNote">0.06-release</div>
    <strong>7 September 2012 - Security Enhancements</strong>
    <div class="aboutindent">
        &bull; Default behavior is now to preview the URL. This should help prevent the possibility of TLWSD being <br />
        &nbsp;&nbsp; used to spread malware. <br />
        &bull; Password hashing upgraded to add 1000 rounds of HMAC-SHA-256 with an increasing key, and then<br />
        &nbsp;&nbsp; bcrypt the result.<br />
    </div>
    <div class="devNote">0.05-release</div>
    <strong>8 August 2012 - <q>Do Not Forward</q></strong>
    <div class="aboutindent">
        <div class="devNote">Original Design Idea</div>
        &#x2713; "Do Not Forward" cookie support.<br />
    </div>
    <div class="devNote">0.04-release</div>
    <strong>30 June 2012 - Bug Fixes</strong>
    <div class="aboutindent">
        &bull; Fixed Javascript for keyring creation. Previously, it wasn't correctly erasing password fields when you<br />
        &nbsp;&nbsp; changed the number of passwords to assign.<br />
        &bull; Fixed bug where expiration time wasn't being recorded after the first use of a standard SDH. This led to<br />
        &nbsp;&nbsp; a 404 error and the valid record being securely wiped.
    </div>
    <div class="devNote">0.03-release</div>
    <strong>20 June 2012 - New Feature</strong>
    <div class="aboutindent">
        <div class="devNote">Suggester: Natanael</div>
        &#x2713; Optional mode: Ring of one-time passwords for each link.<br />
    </div>
    <div class="devNote">0.02-release</div>
    <strong>5 May 2012 - Features and Bigfixes</strong>
    <div class="aboutindent">
        <div class="devNote">Suggester: <a href="http://www.ernest.net/">Nicholas Bohm</a></div>
	&#x2713; Clarified the purpose and application of this service.<br />
        <div class="devNote">Suggester: H. Rid</div>
        &#x2713; Generated SHD's now have an expiration time.<br />
	&#x2713; Hardened against Denial of Service attacks for short URLs.<br />
	&#x2713; Added support for irc:// destination URLs.<br />
    </div>
    <div class="devNote">0.01-release</div>
    <strong>2 May 2012 - Public Release</strong>
    <div class="aboutindent">
        &#x2713; Released publicly-deployable source code.<br />
        <div class="devNote">Suggester: <a href="https://twitter.com/#!/kaepora">@kaepora</a></div>
        &#x2713; Made it obvious that privacy is not guaranteed.<br />
    </div>
    <div class="devNote">0.0-release</div>
    <strong>23 March 2012 - First Launch</strong>
    <div class="aboutindent">
        <em>This Link Will Self-Destruct</em> is first launched.
    </div>
</div>
<?php
include "footer.php";
?>
