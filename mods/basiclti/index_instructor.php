<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_BASICLTI);
require (AT_INCLUDE_PATH.'header.inc.php');
?>

Hello Instructor!! :)

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>
