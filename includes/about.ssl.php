<?php
$pageTitle = "About SSL/TLS";
include "header.php";
?>
<h2>About SSL/TLS</h2>
<div class="aboutindent">
    If you are reading this page, odds are you tried to access a Self-Destroying Hyperlink that began with
    <span class="mono">http</span> instead of <span class="mono">https</span>.
    <?php
    if($_SESSION['keepLink']) {
      ?>
    <div class="aboutindent">
        The URL you should have accessed was: 
        <?php
        $k = preg_replace('/[^0-9a-z]/', '', substr($_SESSION['keepLink'], 1));
        
        echo "<a href=\"https://tlwsd.in/{$k}\">https://tlwsd.in/{$k}</a>";
        ?>
        <br />
    </div>
      <?php
      unset($_SESSION['keepLink']);
    }
    ?>
</div>
<h2>Why Does it Matter?</h2>
<div class="aboutindent">
    This page (courtesy of the Electronic Frontier Foundation) illustrates why using HTTPS protects your
    privacy more than unsecured HTTP: <a href="https://www.eff.org/pages/tor-and-https">Tor and HTTPS</a>.
    <br /><br />
    <em>Note:</em> Although we do not log information about our visitors, we do recommend that internet users use the latest
    version of the <a href="https://www.torproject.org/">Tor Browser Bundle</a> when communicating sensitive information.
    Short-lived hyperlinks included.
</div>
<?php
include "footer.php";
?>