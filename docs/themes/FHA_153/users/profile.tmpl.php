<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
<?php global $languageManager, $_config; ?>
<div class="input-form">
	<div class="row">
		<h3><?php echo _AT('required_information'); ?></h3>
	</div>

	<div class="row">
		<label for="login"><?php echo _AT('login_name'); ?></label><br />
				<span id="login"><?php echo stripslashes(htmlspecialchars($_POST['login'])); ?></span>
				<input name="member_id" type="hidden" value="<?php echo intval($_POST['member_id']); ?>" />
				<input name="login" type="hidden" value="<?php echo stripslashes(htmlspecialchars($_POST['login'])); ?>" />
	</div>
	<div class="row">
		<label for="email"><?php echo _AT('email_address'); ?></label><br />
		<?php echo stripslashes(htmlspecialchars($_POST['email'])); ?>
		<input type="checkbox" id="priv" name="private_email" value="1" <?php if ($_POST['private_email']) { echo 'checked="checked"'; } ?> /><label for="priv"><?php echo _AT('keep_email_private');?></label>
	</div>
	<?php if (admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE)): 
			if ($_POST['status'] == AT_STATUS_INSTRUCTOR) {
				$inst = ' checked="checked"';
			} else if ($_POST['status'] == AT_STATUS_STUDENT) {
				$stud = ' checked="checked"';
			}  else if ($_POST['status'] == AT_STATUS_DISABLED) {
				$disa = ' checked="checked"';
			} else {
				$uncon = ' checked="checked"';
			}?>
			<input type="hidden" name="id" value="<?php echo $_POST['member_id']; ?>" >
			<div class="row">
				<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><?php echo _AT('account_status'); ?><br />

				<input type="radio" name="status" value="0" id="disa" <?php echo $disa; ?> /><label for="disa"><?php echo _AT('disabled'); ?></label>
				<?php if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION): ?>
					<input type="radio" name="status" value="1" id="uncon" <?php echo $uncon; ?> /><label for="uncon"><?php echo _AT('unconfirmed'); ?></label>
				<?php endif; ?>

				<input type="radio" name="status" value="2" id="stud" <?php echo $stud; ?> /><label for="stud"><?php echo _AT('student'); ?></label>

				<input type="radio" name="status" value="3" id="inst" <?php echo $inst; ?> /><label for="inst"><?php echo _AT('instructor'); ?></label>

				<input type="hidden" name="old_status" value="<?php echo $_POST['old_status']; ?>" />
			</div>
	<?php endif; ?>

	<?php if (admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE) && defined('AT_MASTER_LIST') && AT_MASTER_LIST): ?>
	<div class="row">
		<h3><?php echo _AT('personal_information').' ('._AT('optional').')'; ?></h3>
	</div>

	<div class="row">
		<label for="student_id"><?php echo _AT('student_id'); ?></label><br />
		<input type="text" name="student_id" value="<?php echo $_POST['student_id']; ?>" size="20" /><br />
	</div>
	<div class="row">
		<label for="student_pin"><?php echo _AT('student_pin'); ?></label><br />
		<input id="student_pin" name="student_pin" type="password" size="15" maxlength="15" value="<?php echo stripslashes(htmlspecialchars($_POST['student_pin'])); ?>" /><br />
	</div>
	<?php endif; ?>

	
	<div class="row buttons">
		<input type="submit" name="submit" value=" <?php echo _AT('save'); ?> " accesskey="s" />
		<input type="submit" name="cancel" value=" <?php echo _AT('cancel'); ?> " />
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>