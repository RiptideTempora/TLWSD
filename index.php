<?php
if($_POST['cookieToggle']) {
  if($_COOKIE['alwaysForward']) {
    setcookie('alwaysForward', 0);
  } else {
    setcookie('alwaysForward', 1);
  }
  header("Location: index.php");
  exit;
}
/*
 * <script type="text/javascript"><!--
google_ad_client = "ca-pub-9736960778486389";
//Wide Ad
google_ad_slot = "7926110816";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

 */
include_once "includes/header.php";
?>
<form action="https://tlwsd.in/make.php" method="post">
<fieldset id="mainFieldset">
  <legend> Create a self-destroying link: </legend>
  <table>
    <tr>
      <th>
      Target Website URL:    
      </th>
      <th title="Number of times this link may be executed before it dies.">
      Uses: 
      </th>
      <th title="Leave blank if you do not wish for your link to expire after a set time.">
      Link Expiration: <em style="font-weight: normal;">(time limit)</em>
      </th>
    </tr>
    <tr>
      <td><input type="text" name="url" size="48" /></td>
      <td><input type="text" name="life" size="2" value="1" /></td>
      <td style="text-align: right;"><input type="text" name="time_scalar" size="2" value="30" /> <select name="time_unit">
              <option value="m">minutes</option>
              <option value="h">hours</option>
              <option value="d" selected="true">days</option>
              <option value="w">weeks</option>
          </select></td>
    </tr>
  </table>
  <table>
    <tr>
      <th>
      Password: <em style="font-weight: normal;">(optional)</em>
      </th>
      <th>
      Security:
      </th>
    </tr>
    <tr>
      <td><input type="password" name="password" size="23" /></td>
      <td><input type="radio" name="sec" value="1" /> Compact <input type="radio" name="sec" value="127" checked="true" /> Medium <input type="radio" name="sec" value="255" /> Secure </td>
    </tr>
  </table>
  <input type="submit" value="Create Link" style="float: right; margin-top: -2em;" />
</fieldset>
</form>
<br />
<div id="replaceMeJS">
    <noscript>
    <form action="keyring.php" method="post">
        <fieldset>
            Create a keyring with <input type="text" name="n" size="2" /> passwords: <input type="submit" value="Go" />
        </fieldset>
    </form>
    <form method="post">
        <fieldset>
            Click this button to turn URL Preview <input name="cookieToggle" type="submit" value="<?=($_COOKIE['alwaysForward'] ? 'ON' : 'OFF');?>" />
        </fieldset>
    </form>
    </noscript>
</div>
<script type="text/javascript">
    var label = '<?=($_COOKIE['alwaysForward'] ? 'ENABLE' : 'DISABLE');?>';
    var current = '<?=($_COOKIE['alwaysForward'] ? 'Disabled' : 'Enabled');?>';
    function toggleURLPreview() {
      if(label == 'DISABLE') {
        document.cookie='alwaysForward=1';
        label = 'ENABLE';
        current = 'Disabled';
      } else {
        document.cookie='alwaysForward=0';
        label = 'DISABLE';
        current = 'Enabled';
      }
      $("#replaceMeJS").html("<a href=\"keyring.php\">Click here to assign a distinct password for each use</a>.<br />"+
    "<a class=\"ajaxLink\" onClick=\"toggleURLPreview();\">Click here to "+label+" URL Preview</a>. It is currenly "+current+". Default: <strong>Enabled.</strong>");
    }
    $("#replaceMeJS").html("<a href=\"keyring.php\">Click here to assign a distinct password for each use</a>.<br />"+
    "<a class=\"ajaxLink\" onClick=\"toggleURLPreview();\">Click here to "+label+" URL Preview</a>. It is currenly "+current+". Default: <strong>Enabled.</strong>");
</script>

<?php
include_once "includes/footer.php";
?>
