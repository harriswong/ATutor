<?php 

require(AT_INCLUDE_PATH.'header.inc.php'); 

global $msg;
?>

<?php $msg->printAll();?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="form_login_action" value="true" />
	<input type="hidden" name="form_course_id" value="<?php echo $this->tmpl_course_id; ?>" />

<h4><?php echo _AT('login'); ?> <?php echo $this->tmpl_title; ?></h4>

<div class="input-form">
	<div class="row">
		<label for="login"><?php echo _AT('login_name'); ?></label><br />
		<input type="text" name="form_login" id="login" />
	</div>

	<div class="row">
		<label for="pass"><?php echo _AT('password'); ?></label><br />
		<input type="password" class="formfield" name="form_password" id="pass" />
	</div>

	<div class="row">
		<input type="checkbox" name="auto" value="1" id="auto" /><label for="auto"><?php echo _AT('auto_login2'); ?></label>
	</div>

	<div class="row buttons"><input type="submit" name="submit" value="<?php echo _AT('login'); ?>" /> 
						<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
		
	<div class="row">&middot; <a href="password_reminder.php"><?php echo _AT('forgot'); ?></a><br />
		&middot; <?php echo _AT('no_account'); ?> <a href="registration.php"><?php echo _AT('free_account'); ?></a></div>

</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>