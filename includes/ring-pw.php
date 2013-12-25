<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/header.php";
?>
<form action="" method="post">
The link you are trying to access is protected by a password.
  <fieldset style="width: 480px; background: #eee; margin: auto;">
    <legend>Please enter the password below:</legend>
    <input type="password" name="password" /><input type="submit" />
  </fieldset>
</form>
<br />
<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/includes/footer.php";
?>
