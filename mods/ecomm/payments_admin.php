<?php

define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_ECOMM);

/// Get pagination info
$sql	= "SELECT COUNT(shopid) AS cnt FROM ".TABLE_PREFIX."ec_shop WHERE amount > '0'";

if($result = mysql_query($sql, $db)){
	$row = mysql_fetch_assoc($result);
	$num_results = $row['cnt'];
}

$results_per_page = 25;
$num_pages = max(ceil($num_results / $results_per_page), 1);
$page = intval($_GET['p']);

if (!$page) {
	$page = 1;
}	

$count  = (($page-1) * $results_per_page) + 1;
$offset = ($page-1)*$results_per_page;

/// Get a list of those who have made payments
if ($_GET['reset_filter']) {
	unset($_GET);
}

$page_string = '';
$orders = array('asc' => 'desc', 'desc' => 'asc');
$cols   = array('ec_student_name' => 1, 'ec_payment_made' => 1, 'ec_enroll_approved' => 1, 'ec_course_name' => 1, 'ec_date' => 1);

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = isset($cols[$_GET['asc']]) ? $_GET['asc'] : 's.'.$_GET['asc'];
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = isset($cols[$_GET['desc']]) ? $_GET['desc'] : 's.'.$_GET['desc'];
} else {
	// no order set
	$order = 'desc';
	$col   = 's.date';
}

if($_POST['startdate'] && $_POST['enddate']){
		$sql_daterange = "AND s.date >= '$_POST[startdate]' AND s.date <='$_POST[enddate]' ";
}else if($_POST['startdate']){
		$sql_daterange = "AND s.date >= '$_POST[startdate]'";
}

	$sql = "SELECT  s.course_id,  s.date, s.shopid, s.member_id, s.approval, s.course_name, f.course_fee, f.auto_approve, m.login, m.first_name, m.last_name from ".TABLE_PREFIX."ec_shop AS s, ".TABLE_PREFIX."ec_course_fees AS f, ".TABLE_PREFIX."members AS m WHERE s.member_id = m.member_id AND  s.course_id = f.course_id $sql_daterange GROUP BY s.member_id, s.course_id  ORDER BY  $col $order LIMIT $offset, $results_per_page";

	$result = mysql_query($sql,$db);


if($_POST['export']){
	header('Location: payments_export_csv.php?thisquery='.urlencode($_POST['thisquery']));
	exit;
}

	require (AT_INCLUDE_PATH.'header.inc.php'); ?>

<script language="javascript" type="text/javascript" src="mods/ecomm/datetimepicker.js"></script>
	<div class="input-form">
		<p><?php echo _AT('ec_date_picker');  ?></p>
		<form action="<?php  echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="row">
		<label for="startdate"><?php echo _AT('ec_start_date');  ?></label>	<input type="Text" id="startdate" name="startdate" maxlength="25" size="25" value="<?php echo $_POST['startdate']; ?>"><a href="javascript:NewCal('startdate','ddmmyyyy',true,24)"><img src="mods/ecomm/images/cal.gif" width="16" height="16" border="0" alt="<?php echo _AT('ec_start_date');  ?>"></a>
		<label for="enddate"><?php echo _AT('ec_end_date');  ?></label><input type="Text" id="enddate"  name="enddate" maxlength="25" size="25"><a href="javascript:NewCal('enddate','ddmmyyyy',true,24)"><img src="mods/ecomm/images/cal.gif" width="16" height="16" border="0" alt="<?php echo _AT('ec_end_date');  ?>"></a>
		<input type="submit" value="<?php echo _AT('ec_set_date');  ?>" class="button">
		</div>

<?php

	if(@mysql_num_rows($result) >=1){ ?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="thisquery" value="<?php echo $sql; ?>" />
		<input type="submit" class="button" name="export" value="<?php echo _AT('ec_export_data'); ?>" /></form>
</form>
<br />
<?php  }  ?>
</div>
<?php
	if(@mysql_num_rows($result) >=1){ ?>
	
<div class="paging">
	<ul>
	<?php for ($i=1; $i<=$num_pages; $i++): ?>
		<li>
			<?php if ($i == $page) : ?>
				<a class="current" href="<?php echo $_SERVER['PHP_SELF']; ?>?p=<?php echo $i.$page_string;?>"><em><?php echo $i; ?></em></a>
			<?php else: ?>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?p=<?php echo $i.$page_string; ?>"><?php echo $i; ?></a>
			<?php endif; ?>
		</li>
	<?php endfor; ?>
	</ul>
</div>
	<table class="data static" summary="">
	<colgroup>
		<?php if ($col == 'lastname'): ?>
			<col />
			<col class="sort" />
			<col span="<?php echo 4 + $col_counts; ?>" />
		<?php elseif($col == 'amount'): ?>
			<col span="<?php echo 4 + $col_counts; ?>" />
			<col class="sort" />
			<col span="4" />
		<?php elseif($col == 'approval'): ?>
			<col span="<?php echo 3 + $col_counts; ?>" />
			<col class="sort" />
			<col span="3" />
		<?php elseif($col == 'course_name'): ?>
			<col span="<?php echo 2 + $col_counts; ?>" />
			<col class="sort" />
			<col span="2" />
		<?php elseif($col == 'ec_date'): ?>
			<col span="<?php echo 1 + $col_counts; ?>" />
			<col class="sort" />
		<?php endif; ?>
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $orders[$order]; ?>=lastname<?php echo $page_string; ?>"><?php echo _AT('ec_student_name'); ?></a></th>
		<th scope="col"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $orders[$order]; ?>=amount<?php echo $page_string; ?>"><?php echo _AT('ec_payment_made'); ?></a></th>
		<th scope="col"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $orders[$order]; ?>=approval<?php echo $page_string; ?>"><?php echo _AT('ec_enroll_approved'); ?></a></th>
		<th scope="col"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $orders[$order]; ?>=course_name<?php echo $page_string; ?>"><?php echo _AT('ec_course_name'); ?></a></th>
		<th scope="col"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $orders[$order]; ?>=date<?php echo $page_string; ?>"><?php echo _AT('ec_date'); ?></a></th>
	</tr>
	</thead>
	<?php
		/// Loop through each payment
		while($row = mysql_fetch_assoc($result)){
			
			echo "\n".'<tr><td><a href="admin/edit_user.php?id='.$row['member_id'].'">'.$row['last_name'].', '.$row['first_name'].'  ('.$row['login'].')</a></td>';
		
			$sql3 = "SELECT amount from ".TABLE_PREFIX."ec_shop WHERE course_id = '$row[course_id]' AND member_id = '$row[member_id]'";
			$result3 = mysql_query($sql3,$db);
	
			$amount_paid = '';
			while($row3 = mysql_fetch_assoc($result3)){
				$amount_paid = $amount_paid+$row3['amount'];
				//$amount_paid = $row3['amount'];
			}
			if($amount_paid != 0){
			
				echo '<td>'.$_config['ec_currency_symbol'].$amount_paid.'</td>';
			}else{
				echo '<td>'.$_config['ec_currency_symbol'].'0</td>';
			}	
			
			$sql6 = "SELECT * from ".TABLE_PREFIX."course_enrollment WHERE course_id = '$row[course_id]' AND member_id = '$row[member_id]'";
			$result6 = mysql_query($sql6, $db);
			if(mysql_num_rows($result6) >= 1){
				while($row6 = mysql_fetch_assoc($result6)){
					if($row6['approved'] == 'y'){
						echo '<td>'._AT('yes').'<small>(<a href="admin/enrollment/enroll_edit.php?id0='.$row['member_id'].SEP.'func=unenroll'.SEP.'tab=0'.SEP.'course_id='.$row['course_id'].'">'._AT('unenroll').'</a>)</small> </td>';
					}else{
						echo '<td>'._AT('no').' <small>(<a href="admin/enrollment/enroll_edit.php?id0='.$row['member_id'].SEP.'func=enroll'.SEP.'tab=0'.SEP.'course_id='.$row['course_id'].'">'._AT('enroll').'</a>)</small></td>';
					}
				}
			}else{
					echo '<td>'._AT('no').' <small>(<a href="admin/enrollment/enroll_edit.php?id0='.$row['member_id'].SEP.'func=enroll'.SEP.'tab=0'.SEP.'course_id='.$row['course_id'].'">'._AT('enroll').'</a>)</small></td>';
			}

		echo '<td><a href="admin/enrollment/index.php?tab=0'.SEP.'course_id='.$row['course_id'].'">'.$row['course_name'].'</a></td>';
	
		/// Get the payment date
			if($row['date']){
 				echo '<td>'.$row['date'].'</td></tr>';
 			}else{
				echo '<td>'._AT('na').'</td></tr>';
 			}
	}
		echo '</table>'."\n";

}else{
	$msg->printInfos('EC_NO_STUDENTS_ENROLLED');
}

require (AT_INCLUDE_PATH.'footer.inc.php');

?>