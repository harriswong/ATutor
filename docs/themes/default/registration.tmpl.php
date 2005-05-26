<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<?php if (!$_POST['email']) {
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
			<div class="required" title="<?php echo _AT('required_field'); ?>">*</div>Date of Birth<br />
			<select name="year">
				<option value="0">Year</option>
				<?php for ($i=1920; $i<2000; $i++): ?>
					<option value="<?php echo $i;?>"><?php echo $i;?></option>
				<?php endfor; ?>
			</select>
			
			<select name="month">
				<option value="0">Month</option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>

			<select name="day">
				<option value="0">Day</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
		</div>
	<?php endif; ?>

	<div class="row">
		<h3><?php echo _AT('required_information'); ?></h3>
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="login"><?php echo _AT('login_name'); ?></label><br />
		<?php if ($_POST['member_id']) : ?>
				<span id="login"><?php echo stripslashes(htmlspecialchars($_POST['login'])); ?></span>
				<input name="member_id" type="hidden" value="<?php echo intval($_POST['member_id']); ?>" />
				<input name="login" type="hidden" value="<?php echo stripslashes(htmlspecialchars($_POST['login'])); ?>" />
		<?php else: ?>
			<input id="login" name="login" type="text" maxlength="20" size="15" value="<?php echo stripslashes(htmlspecialchars($_POST['login'])); ?>" /><br />
			<small>&middot; <?php echo _AT('contain_only'); ?><br />
				   &middot; <?php echo _AT('20_max_chars'); ?></small>
		<?php endif; ?>
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="password"><?php echo _AT('password'); ?></label><br />
		<input id="password" name="password" type="password" size="15" maxlength="15" value="<?php echo stripslashes(htmlspecialchars($_POST['password'])); ?>" /><br />
		<small>&middot; <?php echo _AT('combination'); ?><br />
		       &middot; <?php echo _AT('15_max_chars'); ?></small>
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="password2"><?php echo _AT('password_again'); ?></label><br />
		<input id="password2" name="password2" type="password" size="15" maxlength="15" value="<?php echo stripslashes(htmlspecialchars($_POST['password2'])); ?>" />
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="email"><?php echo _AT('email_address'); ?></label><br />
		<input id="email" name="email" type="text" size="50" maxlength="60" value="<?php echo stripslashes(htmlspecialchars($_POST['email'])); ?>" />
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="email2">Email Address Again</label><br />
		<input id="email" name="email2" type="text" size="50" maxlength="60" value="<?php echo stripslashes(htmlspecialchars($_POST['email2'])); ?>" />
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

	<?php if (!$_POST['member_id'] && admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE) && defined('AT_MASTER_LIST') && AT_MASTER_LIST): ?>
		<div class="row">
			<label for="student_id">Employee Number</label><br />
				<?php
					global $db;
					$sql    = "SELECT public_field FROM ".TABLE_PREFIX."master_list WHERE member_id=0 ORDER BY public_field";
					$result = mysql_query($sql, $db);
					if ($row = mysql_fetch_assoc($result)) {
						echo '<select name="student_id" id="student_id">';
						echo '<option value=""></option>';
						do {
							echo '<option value="'.$row['public_field'].'">'.$row['public_field'].'</option>';
						} while ($row = mysql_fetch_assoc($result));
						echo '</select>';
					}
				?><br />
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
		<label for="email2">Alternate Email Address</label><br />
		<input id="email" name="email3" type="text" size="50" maxlength="60" value="<?php echo stripslashes(htmlspecialchars($_POST['email3'])); ?>" />
	</div>

	<div class="row buttons">
		<input type="submit" name="submit" value=" <?php echo _AT('save'); ?> " accesskey="s" />
		<input type="submit" name="cancel" value=" <?php echo _AT('cancel'); ?> " />
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>