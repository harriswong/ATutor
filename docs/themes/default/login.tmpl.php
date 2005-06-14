<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="form_login_action" value="true" />
	<input type="hidden" name="form_course_id" value="<?php echo $this->course_id; ?>" />

<table class="data static" rules="cols">
<thead>
	<tr>
		<th><h3><?php echo _AT('login') ?></h3></th>
		<th><h3>New User</h3></th>
		<th><h3>Password and Login Reminder</h3></th>
	</tr>
</thead>

<tfoot>
	<tr align="center">
		<td valign="top"><input type="submit" name="login" value="<?php echo _AT('login'); ?>" /></td>
		<td valign="top"><input type="submit" name="register" value="<?php echo _AT('register'); ?>" /> </td>
		<td valign="top"><input type="submit" name="forgot"   value="Email Reminder"/> </td>
	</tr> 
</tfoot>

<tbody>
	<tr>
		<td width="33%" valign="top">Enter the Login Name and Password you chose when you first registered with the system.<br /><br />
		
			<div align="center"
			<label for="login"><?php echo _AT('login_name'); ?></label><br /> 
			<input type="text" name="form_login" id="login" /><br />
			<label for="pass"><?php echo _AT('password'); ?></label><br />
			<input type="password" class="formfield" name="form_password" id="pass" /><br /><br />
			</div>
		</td>
		<td width="33%" valign="top">If you do not have an account on this system,  please create a new account.  		
		
		<?php if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION): ?>
					<br /><br />If you have already registered but did not confirm your account and have lost your confirmation email: <a href="confirm.php">resend confirmation email<?php //echo _AT('confirm_account'); ?></a>.<br /><br />
		<?php endif; ?></td>
		<td width="33%" valign="top">If you have forgotten your login name and/or password, use the Password and Login Reminder to have it emailed to you.</td>
	</tr>

</tbody>
</table>

</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>