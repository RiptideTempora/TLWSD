<?php
$pageTitle = "About Us";
include "header.php";
?>
<h2>About This Website</h2>
<div class="aboutindent">
  <em>This Link Will Self Destruct</em> (TLWSD) is a free public temporary URL service. Our mission is to provide the internet
  with a fast, friendly, private, and secure means to exchange hyperlinks without leaving a digital paper trail.
  <br /><br />
  This service is designed for exchanging links over an untrusted medium (such as Facebook, internet forums, or IRC)
  without passive attackers (people with access to your conversation logs at a later date) being able to know what link you
  sent.
  <br /><br />
  In the event of a successful exchange, this service does not obscure the destination URL from the intended recipient.
</div>
<h2>How To Use This Website</h2>
<div class="aboutindent">
  First, you need to have a URL you want to distribute to a limited number of end users. (For example, a 
  <a href="https://crypto.cat">Cryptocat</a> chatroom.) Then head over to the <a href="https://tlwsd.in/">TLWDS</a>
  homepage and provide your hyperlink. Fill in the number of times you wish this link to be usable before it expires (a link's "life").
  You may also select an expiration time (default: 30 days) so your information doesn't sit on the server forever.
  Select the level of security you want, then press the "Create Link" and you will receive an SDH &mdash; a <b>S</b>elf-<b>D</b>estroying <b>H</b>yperlink.
  <br /><br /><b>Security Levels</b>:
  <ul>
      <li>Compact: Short URL. Ideal for sending over SMS and Twitter. Password recommended.
          <ul>Equivalent URL security: <acronym title="At 1000 guesses per second: 4 hours to break">24 bits</acronym><br />
              (If multiple hash collisions are detected, security is upgraded to <acronym title="At 1000 guesses per second: 49 days to break">32</acronym> and then <acronym title="At 1000 guesses per second: 34 years to break">40</acronym> bits)</ul></li>
      <li>Medium: Standard level of brute-force resistance to your Self-Destroying Hyperlinks.
          <ul>Equivalent URL security: <acronym title="At 1000 guesses per second: 8,926 years to break">48 bits</acronym></ul></li>
      <li>Secure: Long URL. Nearly impervious to brute-force attacks.
          <ul>Equivalent URL security: <acronym title="At 1000 guesses per second: 4 x 10^37 years to break">160 bits</acronym></ul></li>
  </ul>
  <br />
  When someone accesses your SDH, the server will take 1 point away from the life of the hyperlink. When its life reaches 0, the
  file containing the hyperlink information will be shredded (<a href="https://ssd.eff.org/tech/deletion">learn more</a>) from
  our server.
  <br /> <br />
  <em>(Optional)</em>: You may also supply a password to your SDH. When
  users load the SDH in their web browser, they will be asked for the same password before they can continue to the destination
  URL. Supplying a password encrypts your destination URL.
  <br /> <br />
  If a brute force succeeds and an attacker arrives at your SDH, they will either arrive at your destination URL or be prompted for a password.
  That is why we strongly recommend passwords for compact URLs. If an attacker can find the door, they will be slowed down if it
  turns out to be locked.
</div>
<a name="privacy"></a>
<h2>Privacy Considerations</h2>
<div class="aboutindent">
  The use of this service does not <em>guarantee</em> privacy. It is always best to assume that any information sent can
  and will be intercepted. Any data sent to the server can be seen by the server, even if it isn't stored by design. This
  includes information such as your IP address, so it is highly recommended that you access our service through
  <a href="https://www.torproject.org/">Tor</a>.
</div>
<?php
include "footer.php";
?>