<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="form_login_action" value="true" />
	<input type="hidden" name="form_course_id" value="<?php echo $this->course_id; ?>" />
<table class="data">

<thead>
<tr>
<th><h3><?php echo _AT('login') ?></h3></th>
<th><h3><?php echo _AT('register') ?></h3></th>
<th><h3><?php echo _AT('forgot') ?></h3></th>
</tr>
</thead>

<tfoot>
<td valign="top"><input type="submit" name="login" value="<?php echo _AT('login'); ?>" /> <br />
<?php if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION): ?>
			&middot; <a href="confirm.php"><?php echo _AT('confirm_account'); ?></a><br />
<?php endif; ?></td>
<td valign="top"><input type="submit" name="register" value="<?php echo _AT('register'); ?>" /> </td>
<td valign="top"><input type="submit" name="forgot"   value="<?php echo _AT('forgot'); ?>"/> </td>
</tr> 
</tfoot>

<tbody>
<tr>
<td width="33%" valign="top">Enter the Login Name and Password you chose when you first registered in the system.</td>
<td width="33%" valign="top">If you do not have an account on this system,  please create a new account.</td>
<td width="33%" valign="top">If you have forgotten your login name and/or password, please use <a href="password_reminder.php">this link</a> or the button below.</td>
</tr>

<tr>
<td><label for="login"><?php echo _AT('login_name'); ?></label><br /> 
	<input type="text" name="form_login" id="login" /><br />
	<label for="pass"><?php echo _AT('password'); ?></label><br />
	<input type="password" class="formfield" name="form_password" id="pass" /><br />
</td>
</tr>
</tbody>
</table>


<div class="input-form" style="max-width: 400px">
	<div class="row">
		<p>Enter the Login Name and Password you chose when you first registered in the system. If you can't remember either, please use the I forgot my Password or Login Name link.</p>
	</div>
	<?php if ($_GET['course']): ?>
		<div class="row">
			<h3><?php echo _AT('login'). ' ' . $this->title; ?></h3>
		</div>
	<?php endif;?>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="login"><?php echo _AT('login_name'); ?></label><br />
		<input type="text" name="form_login" id="login" />
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="pass"><?php echo _AT('password'); ?></label><br />
		<input type="password" class="formfield" name="form_password" id="pass" />
	</div>

	<!--div class="row">
		<input type="checkbox" name="auto" value="1" id="auto" /><label for="auto"><?php echo _AT('auto_login2'); ?></label>
	</div-->

	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('login'); ?>" /> 
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
		
	<div class="row footer">&middot; <a href="password_reminder.php"><?php echo _AT('forgot'); ?></a><br />
		&middot; <?php echo _AT('no_account'); ?> <a href="registration.php"><?php echo _AT('free_account'); ?></a><br />
		<?php if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION): ?>
			&middot; <a href="confirm.php"><?php echo _AT('confirm_account'); ?></a><br />
		<?php endif; ?>
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>