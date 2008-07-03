<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>
<?php

$tabs = get_tabs();	
$num_tabs = count($tabs);
if($_POST['current_tab']){
$current_tab = addslashes($_POST['current_tab']);
}else{
	for ($i=0; $i < $num_tabs; $i++) {
		if (isset($_POST['button_'.$i]) && ($_POST['button_'.$i] != -1)) { 
			$current_tab = $i;
			$_POST['current_tab'] = $i;
			break;
		}else{
			$current_tab = 0;
		}			
	}
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="form" enctype="multipart/form-data">
<div align="center" style="width:90%; margin-left:auto; margin-right:auto;">
	<?php output_tabs($current_tab, $changes_made); ?>
</div>

</form>
<div class="input-form">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="current_tab"  value="<?php echo $current_tab; ?>" />
<?php

 include(getcwd().'/'.$tabs[$current_tab][1]);

?>
	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('apply'); ?>" accesskey="s" />
		<input type="reset" name="reset" value="<?php echo _AT('reset'); ?>" />
	</div>
</form>	
</div>
<?php
debug($_REQUEST, REQUEST);
debug($tabs[$current_tab]);
debug($prefs);

?>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
