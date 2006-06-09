<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<?php if (!$this->courses): ?>
		<p><?php echo _AT('none_found'); ?> <a href="users/browse.php"><?php echo _AT('browse_courses'); ?></a>.</p>
<?php endif; ?>

<?php foreach ($this->courses as $row):?>	
	<div class="course">
		<div style="font-size:smaller;" align="right"><?php
			$link  = '<a href="bounce.php?course=' . $row['course_id'] . '">';
			$link2 = '</a>';

			if ($_SESSION['member_id'] == $row['member_id']) {
				//if instructor
				echo _AT('instructor');
			} else if ($row['approved'] == 'a') {
				//if alumni
				echo _AT('alumni');
			} else if ($row['approved'] == 'n') {
				//if notenrolled
				echo _AT('pending_approval');
				$link  = $link2 = "";
			} else {
				//if no role and enrolled
				echo _AT('student1');
			} ?>
		</div>
			<div class="body">
				<?php echo $link; ?>
					<?php if ($row['icon'] == ''): ?>
						<img src="images/clr.gif" class="icon" border="0" width="79" height="79" alt="" />
					<?php else: ?>
						<img src="images/courses/<?php echo $row['icon']; ?>" class="icon" border="0" alt="" />
					<?php endif; ?>
				<?php echo $link2; ?>

				<strong><?php echo $link; ?><?php echo $row['title']; ?><?php echo $link2; ?></strong>

				<?php if ($row['member_id'] != $_SESSION['member_id']): ?>
					- <a href="users/remove_course.php?course=<?php echo $row['course_id']; ?>"><?php echo _AT('unenroll'); ?></a>
				<?php endif; ?>

				<br />
 
				<p>
					<small><?php echo _AT('instructor');?>: <?php echo get_login($row['member_id']); ?>
					<?php echo ' - <a href="'. $_base_href.'inbox/send_message.php?id='.$row['member_id'].'">'._AT('send_message').'</a>'; ?>
					<br />
					<?php echo _AT('category'); ?>: <?php echo get_category_name($row['cat_id']); ?><br />
					
					
					<?php if ($row['tests']): ?>
						Challenge Test: 
						<?php foreach ($row['tests'] as $test): ?>
							<a href="bounce.php?course=<?php echo $row['course_id'].SEP.'p='.urlencode('tools/take_test.php?tid='.$test['test_id']); ?>"><?php echo $test['title']; ?></a> 
						<?php endforeach ;?>
					<?php endif; ?>
				</small>
				</p>

				<?php if ($row['last_cid']): ?>
					<div class="shortcuts">
						<small><a href="bounce.php?course=<?php echo $row['course_id'].SEP.'p='.urlencode('content.php?cid='.$row['last_cid']); ?>"><?php echo _AT('resume'); ?></a></small>
					</div>
				<?php endif; ?>
			</div>
	</div>
<?php endforeach; ?>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>