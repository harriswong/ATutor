<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<?php 
	
if (!$_POST['email']) {
	$_POST['email'] = 'firstname.lastname@fraserhealth.ca';
} else if ($_POST['email']) {
	$_POST['email2'] = $_POST['email'];
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
<?php global $languageManager; ?>
<div class="input-form">

	<?php if (!$_POST['member_id'] && defined('AT_MASTER_LIST') && AT_MASTER_LIST && !admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE)): ?>
		<div class="row">
			<h3>Account Authorization</h3>
		</div>

		<div class="row">
			<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="student_id">Employee Number</label><br />
			<input id="student_id" name="student_id" type="text" size="15" maxlength="15" value="<?php echo stripslashes(htmlspecialchars($_POST['student_id'])); ?>" /><br />
		</div>

		<div class="row">
			<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="dob">Date of Birth</label><br />
			<select name="year" id="dob">
				<option value="0">Year</option>
				<?php for ($i=1920; $i<2000; $i++): ?>
					<option value="<?php echo $i;?>" <?php if ($i == $_POST['year']) { echo 'selected="selected"';} ?>><?php echo $i;?></option>
				<?php endfor; ?>
			</select>
			
			<select name="month">
				<option value="0">Month</option>
				<option value="1"  <?php if ($_POST['month'] == 1)  { echo 'selected="selected"';} ?>>January</option>
				<option value="2"  <?php if ($_POST['month'] == 2)  { echo 'selected="selected"';} ?>>February</option>
				<option value="3"  <?php if ($_POST['month'] == 3)  { echo 'selected="selected"';} ?>>March</option>
				<option value="4"  <?php if ($_POST['month'] == 4)  { echo 'selected="selected"';} ?>>April</option>
				<option value="5"  <?php if ($_POST['month'] == 5)  { echo 'selected="selected"';} ?>>May</option>
				<option value="6"  <?php if ($_POST['month'] == 6)  { echo 'selected="selected"';} ?>>June</option>
				<option value="7"  <?php if ($_POST['month'] == 7)  { echo 'selected="selected"';} ?>>July</option>
				<option value="8"  <?php if ($_POST['month'] == 8)  { echo 'selected="selected"';} ?>>August</option>
				<option value="9"  <?php if ($_POST['month'] == 9)  { echo 'selected="selected"';} ?>>September</option>
				<option value="10" <?php if ($_POST['month'] == 10) { echo 'selected="selected"';} ?>>October</option>
				<option value="11" <?php if ($_POST['month'] == 11) { echo 'selected="selected"';} ?>>November</option>
				<option value="12" <?php if ($_POST['month'] == 12) { echo 'selected="selected"';} ?>>December</option>
			</select>

			<select name="day">
				<option value="0">Day</option>
				<?php for ($i=1; $i<32; $i++): ?>
					<option value="<?php echo $i;?>" <?php if ($i == $_POST['day']) { echo 'selected="selected"';} ?>><?php echo $i;?></option>
				<?php endfor; ?>
			</select>
		</div>

		<div class="row">
			<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="secret">Image Validation</label><br />
			<p>In this image (<img src="secret.php" alt="" />) there is a number displayed. <br />Please type this number into the following field.<br />
			<input id="secret" name="secret" type="text" size="6" maxlength="6" value="" />
			<br />
			<small>&middot; This helps ensure a live person is registering on this system.<br /></small>
		</div>
	<?php endif; ?>

	<div class="row">
		<h3><?php echo _AT('required_information'); ?></h3>
	</div>

	<?php if ($_POST['member_id'] && defined('AT_MASTER_LIST') && AT_MASTER_LIST && !admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE)): ?>
		<div class="row">
			Employee Number<br />
			<?php if($_POST['student_id']) { 
				echo $_POST['student_id']; 
			} else {
				echo '--';
			}?>
		</div>
	<?php endif; ?>

	<div class="row">
		<?php if ($_POST['member_id']): ?>
			Login Name<br />
			<span id="login"><?php echo stripslashes(htmlspecialchars($_POST['login'])); ?></span>
			<input name="member_id" type="hidden" value="<?php echo intval($_POST['member_id']); ?>" />
			<input name="login" type="hidden" value="<?php echo stripslashes(htmlspecialchars($_POST['login'])); ?>" />
		<?php else: ?>		
			<div class="required" title="<?php echo _AT('required_field'); ?>">*</div>
			<label for="login">Choose a <?php echo _AT('login_name'); ?></label><br />
			<input id="login" name="login" type="text" maxlength="20" size="15" value="<?php echo stripslashes(htmlspecialchars($_POST['login'])); ?>" /><br />
			<small>&middot; <?php echo _AT('contain_only'); ?><br />
				   &middot; <?php echo _AT('20_max_chars'); ?></small>			
		<?php endif; ?>
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="password">Choose a <?php echo _AT('password'); ?></label><br />
		<input id="password" name="password" type="password" size="15" maxlength="15" value="<?php echo stripslashes(htmlspecialchars($_POST['password'])); ?>" /><br />
		<small>&middot; <?php echo _AT('combination'); ?><br />
		       &middot; <?php echo _AT('15_max_chars'); ?></small>
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="password2">Type the <?php echo _AT('password_again'); ?></label><br />
		<input id="password2" name="password2" type="password" size="15" maxlength="15" value="<?php echo stripslashes(htmlspecialchars($_POST['password2'])); ?>" />
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="email"><?php echo _AT('email_address'); ?></label><br />
		<input id="email" name="email" type="text" size="50" maxlength="60" value="<?php echo stripslashes(htmlspecialchars($_POST['email'])); ?>" /> (e.g. firstname.lastname@fraserhealth.ca)
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="email2">Email Address Again</label><br />
		<input id="email2" name="email2" type="text" size="50" maxlength="60" value="<?php echo stripslashes(htmlspecialchars($_POST['email2'])); ?>" />
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="langs"><?php echo _AT('language'); ?></label><br />
		<?php $languageManager->printDropdown($_SESSION['lang'], 'lang', 'langs'); ?>
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

	<div class="row">
		<h3><?php echo _AT('personal_information').' ('._AT('optional').')'; ?></h3>
	</div>

	<?php if (admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE) && defined('AT_MASTER_LIST') && AT_MASTER_LIST): ?>
		<div class="row">
			<label for="student_id">Employee Number</label><br />
				<input type="text" name="student_id" value="<?php echo $_POST['student_id']; ?>" size="20" /><br />
		</div>
	<?php endif; ?>

	<div class="row">
		<label for="first_name"><?php echo _AT('first_name'); ?></label><br />
		<input id="first_name" name="first_name" type="text" value="<?php echo stripslashes(htmlspecialchars($_POST['first_name'])); ?>" />
	</div>

	<div class="row">
		<label for="last_name"><?php echo _AT('last_name'); ?></label><br />
		<input id="last_name" name="last_name" type="text" value="<?php echo stripslashes(htmlspecialchars($_POST['last_name'])); ?>" />
	</div>
	
	<div class="row">
		<label for="email3">Alternate Email Address</label><br />
		<input id="email3" name="email3" type="text" size="50" maxlength="60" value="<?php echo stripslashes(htmlspecialchars($_POST['email3'])); ?>" />
	</div>

	<div class="row">
		<label for="email4">Alternate Email Address Again</label><br />
		<input id="email4" name="email4" type="text" size="50" maxlength="60" value="<?php echo stripslashes(htmlspecialchars($_POST['email4'])); ?>" />
	</div>

	<div class="row buttons">
		<input type="submit" name="submit" value=" <?php echo _AT('submit'); ?> " accesskey="s" />
		<input type="submit" name="cancel" value=" <?php echo _AT('cancel'); ?> " />
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>