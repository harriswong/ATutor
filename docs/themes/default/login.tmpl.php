<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="form_login_action" value="true" />
	<input type="hidden" name="form_course_id" value="<?php echo $this->course_id; ?>" />

<table rules="cols" style="	border: 1px solid #e0e0e0; width: 90%; border-spacing: 0px;	border-collapse: collapse; margin-left: auto; margin-right: auto;
">
<thead>
	<tr style="background-color: #fafafa; border-bottom: 1px solid #e0e0e0;	padding: 2px; white-space: nowrap;">
		<th><h3><?php echo _AT('login') ?></h3></th>
		<th><h3>New User</h3></th>
		<th><h3>Password and Login Reminder</h3></th>
	</tr>
</thead>

<tfoot style="background-color: #fafafa; border-top: 1px solid #e0e0e0;">
	<tr align="center">
		<td valign="top"><input type="submit" name="login" value="<?php echo _AT('login'); ?>" class="button" /></td>
		<td valign="top"><input type="submit" name="register" value="<?php echo _AT('register'); ?>" class="button" /> </td>
		<td valign="top"><input type="submit" name="forgot"   value="Email Reminder" class="button" /> </td>
	</tr> 
</tfoot>

<tbody>
	<tr>
		<td width="33%" valign="top">Enter the Login Name and Password you chose when you first registered with the system.<br /><br />
		
			<div align="center" class="input-form" style="border:0px;">
			<label for="login"><?php echo _AT('login_name'); ?></label><br /> 
			<input type="text" name="form_login" id="login" /><br />
			<label for="pass"><?php echo _AT('password'); ?></label><br />
			<input type="password" class="formfield" name="form_password" id="pass" /><br />
			</div>
		</td>
		<td width="33%" valign="top">If you do not have an account on this system,  please create a new account.  		
		
		<?php if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION): ?>
					<br /><br />If you have already registered but did not confirm your account and have lost your confirmation email: <a href="confirm.php">resend confirmation email<?php //echo _AT('confirm_account'); ?></a>.<br />
		<?php endif; ?></td>
		<td width="33%" valign="top">If you have forgotten your login name and/or password, use the Password and Login Reminder to have it emailed to you.</td>
	</tr>

</tbody>
</table>

</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>