<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<div class="container" style="width:58%; margin:auto;border:none;float:left;">
<table class="data" style="width:100%;">
<tr><th></th>
<th><?php echo _AT('course'); ?></th>
<th><?php echo _AT('instructor'); ?></th>
<th><?php echo _AT('status'); ?></th>
<th><?php echo _AT('shortcuts'); ?></th>
</tr>
<?php foreach ($this->courses as $row):
	static $counter;
	$counter++;
?>

    <tr class="<?php if ($counter %2) { echo 'odd'; } else { echo 'even'; } ?>">
    <td>
      <?php if ($row['icon'] == ''): ?>
			      <img src="images/clr.gif" class="icon" border="1" width="79" height="79" alt="<?php echo htmlentities($row['title'], ENT_QUOTES, 'UTF-8'); ?>" />
	      <?php else: 
			      echo $link;  

		    $sql2="SELECT icon from ".TABLE_PREFIX."courses WHERE course_id='$row[course_id]'";
				    $result2 = mysql_query($sql2, $db);
				    
				    while($row2=mysql_fetch_assoc($result2)){
					    $filename = $row2['icon'];
				    }
		    
		    $path = AT_CONTENT_DIR .$row['course_id'].'/custom_icons/'.$filename;
		    
		    if (file_exists($path)) {
			if (defined('AT_FORCE_GET_FILE')) {
			    $dir = 'get_course_icon.php?id='.$row['course_id'];
			} else {
			    $dir = 'content/' . $_SESSION['course_id'] . '/'.$row['icon'];
			}
		    } else {
			    $dir = "images/courses/".$row['icon'];
		    }
		    ?>
		    <img src="<?php echo $dir; ?>" class="icon" border="0" alt="<?php echo htmlentities($row['title'], ENT_QUOTES, 'UTF-8'); ?>" />
				    <?php echo $link2; ?>
		    <?php endif; ?>


    </td>

    <td><?php echo '<a href="'.url_rewrite('bounce.php?course=' . $row['course_id']) . '"> '.htmlentities($row['title']).'</a>' ?>
    <br /><small><?php echo _AT('category'); ?>: <?php echo get_category_name($row['cat_id']); ?></small>
    </td>
    <td><small><?php echo '<a href="'.AT_BASE_HREF.'inbox/send_message.php?id='.$row['member_id'].'">'. get_display_name($row['member_id']).'<a/>'; ?></td>
    <td><small>
    <?php	

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


    </small></td>
    <td>
    <small>
    <ul>
    <?php if ($row['member_id'] != $_SESSION['member_id']  && $_config['allow_unenroll'] == 1): ?>
	    <li><a href="users/remove_course.php?course=<?php echo $row['course_id']; ?>"><?php echo _AT('unenroll_me'); ?></a></li>
    <?php endif; ?><br>
    <?php if ($row['tests']): ?>
	    <?php foreach ($row['tests'] as $test): ?>
		    <li><a href="bounce.php?course=<?php echo $row['course_id'].SEP.'p='.urlencode('mods/_standard/tests/test_intro.php?tid='.$test['test_id']); ?>"><span title="<?php echo _AT('tests'); ?>:<?php echo $test['title']; ?>"><?php echo $test['title']; ?></span></a> </li>
	    <?php endforeach ;?>
    <?php endif; ?>
    </ul>
    <?php if ($row['last_cid']): ?>
	  <div class="shortcuts" style="float:right;">
		  <a href="bounce.php?course=<?php echo $row['course_id'].SEP.'p='.urlencode('content.php?cid='.$row['last_cid']); ?>"><img src="<?php echo $_base_href;  ?>themes/default/images/resume.png" border="" alt="<?php echo _AT('resume'); ?>" title="<?php echo _AT('resume'); ?>" /></a>
	  </div>
    <?php endif; ?>

    </small>
    </td>
    </tr>
<?php endforeach; ?>
</table>
</div>

<div class="current_box">
<div class="current_head"> <h3><?php echo _AT('things_current'); ?></h3></div><br />
<?php
	    
//display current news
if (isset($this->all_news)) {
    echo '<ul class="current_list">';
    foreach($this->all_news as $news){
        $count++;
        if($count < 100){
            echo '<li"><img src="'.$news['thumb'].'" alt="'.$news['alt'].'" title="'.$news['alt'].'"/> ' . $news['link'] .' <br /><small>(<a href="bounce.php?course='.$news['object']['course_id'].'">'.$news['course'].'</a>)| ('.AT_DATE('%F %j, %g:%i',$news['time']).')</small><hr style=""/></li>';
        }
    }
    echo '</ul>';
}
?>
</div>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>