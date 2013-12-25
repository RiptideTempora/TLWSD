<?php
include_once "includes/header.php";
$n = 1;
if(!empty($_POST['n'])) $n = intval($_POST['n']);
?>
<form action="https://tlwsd.in/makering.php" method="post">
<fieldset id="mainFieldset">
  <legend> Create a self-destroying link (with keyring): </legend>
  <table style="width: 100%;">
    <tr>
      <th>
      Target Website URL:    
      </th>
      <th title="Leave blank if you do not wish for your link to expire after a set time.">
      Expiration:
      </th>
      <th title="Number of passwords to assign.">
      Uses: 
      </th>
    </tr>
    <tr>
      <td><input type="text" name="url" size="58" /></td>
      <td style="text-align: right;"><input type="text" name="time_scalar" size="2" value="30" /> <select name="time_unit">
              <option value="m">minutes</option>
              <option value="h">hours</option>
              <option value="d" selected="true">days</option>
              <option value="w">weeks</option>
          </select></td>
      <td><input type="text" id="life" name="life" size="2" value="<?=$n;?>" onChange="expandPasswords();" /></td>
    </tr>
  </table>
            <fieldset>
                <ul style="list-style: none; margin: 0; padding: 0;" id="passContainer">
            <?php
            if(!empty($_POST['n'])) {
              for($i = 1; $i <= $n; $i++) {
                echo "<li id=\"passwd_{$i}\"><p style=\"display: inline-block; margin: 0; min-width: 110px;\">Password {$i}:</p> <input type=\"password\" name=\"passwds[]\" size=\"48\" /></li>\n";
              }
            } else {
                echo "<li id=\"passwd_1\"><p style=\"display: inline-block; margin: 0; min-width: 110px;\">Password 1:</p> <input type=\"password\" name=\"passwds[]\" size=\"48\" /></li>\n";
            }
            ?></ul>
            </fieldset>
  <table>
    <tr>
    </tr>
    <tr>
      
      <th>
      Security:
      </th>
      <td><input type="radio" name="sec" value="1" /> Compact <input type="radio" name="sec" value="127" checked="true" /> Medium <input type="radio" name="sec" value="255" /> Secure </td>
    </tr>
  </table>
  <input type="submit" value="Create Link" style="float: right; margin-top: -2em;" />
</fieldset>
</form>
<script type="text/javascript"> <!--
  var passwds = <?=$n; ?>;
  function expandPasswords() {
    var n = $("#life").val();
    if(n == passwds) return true; // Nothing to do here
    if(n > passwds) {
      i = passwds; i++;
      for(; i <= n; i++) {
        $("#passContainer").append("<li id=\"passwd_"+i+"\"><p style=\"display: inline-block; margin: 0; min-width: 110px;\">Password "+i+":</p> <input type=\"password\" name=\"passwds[]\" size=\"48\" /></li>\n");
      }
      passwds = n;
      return true;
    } else if(n < passwds) {
      if(n < 1) n = 0;
      for(i = 0; i <= passwds; ++i) {
        if(i > n) {
          $("#passwd_"+i).html("");
	  $("#passwd_"+i).remove();
        }
      }
      passwds = n;
      return true;
    } // Remove all passwords with ID > n
    alert(passwds);
    return false; // If we're still here;
  }
  -->
</script>

<?php
include_once "includes/footer.php";
?>
