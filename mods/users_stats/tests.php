<?php
/************************************************************************/
/* ATutor								*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto		*/
/* http://atutor.ca							*/
/*									*/
/* This program is free software. You can redistribute it and/or	*/
/* modify it under the terms of the GNU General Public License		*/
/* as published by the Free Software Foundation.			*/
/************************************************************************/
// $Id: tests.php 7208 2008-01-09 16:07:24Z greg $

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_CONTENT,AT_PRIV_RETURN);

// Getting member id and test_id from page that reffered 
$member_id = intval($_GET['id']);
$test_id  = intval($_GET['t_id']);

// seleziona e visualizza il nome dello studente nel titolo della pagina
$sql = "SELECT CONCAT(first_name, ' ', second_name, ' ', last_name) AS Name
        FROM ".TABLE_PREFIX."members
        WHERE member_id = $member_id";
$title = mysql_query($sql, $db);
$title1 = mysql_fetch_assoc($title);

$_pages['mods/users_stats/tests.php']['title'] = $title1['Name'];

require(AT_INCLUDE_PATH.'header.inc.php');


?>

<table style="width:450px"align="left"class="data static" rules="cols" summary="">
<thead>
<tr>
	<th width="60%"scope="col"><?php echo _AT('date_taken'); ?></th>
	<th width="20%"scope="col"><?php echo _AT('time_spent'); ?></th>
	<th width="10%"scope="col"><?php echo _AT('mark'); ?></th>
	<th width="10%"scope="col"><?php echo _AT('issue'); ?></th>	
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT TR.date_taken , TR.final_score , (UNIX_TIMESTAMP(TR.end_time) - UNIX_TIMESTAMP(TR.date_taken)) AS time_spent , T.out_of , T.random ,TR.result_id ,T.passscore ,T.passpercent
        FROM ".TABLE_PREFIX."tests_results TR , ".TABLE_PREFIX."tests T
        WHERE TR.test_id = T.test_id
        AND TR.member_id = $member_id
        AND TR.test_id = $test_id
        AND TR.status = 1";
	
$result = mysql_query($sql, $db);
	
if ($row = mysql_fetch_assoc($result)): 
  do 
	 { ?>
	 <tr>
	    <td align="center"> <?php $startend_date_format=_AT('startend_date_format'); echo AT_date( $startend_date_format, $row['date_taken'], AT_DATE_MYSQL_DATETIME); ?></td>
		<td align="center"> <?php echo get_human_time($row['time_spent']);       ?></td>
		<td align="center">
		<?php if ($row['out_of']) 
		        {
					if ($row['random']) 
					{
						$row['out_of'] = get_random_outof($test_id, $row['result_id']);
					}

					if ($row['final_score'] != '') 
					{ 
						echo $row['final_score'].'/'.$row['out_of'];
					} 
					else 
					{
						echo _AT('unmarked');
					}
				} 
			  else 
				{
				  echo _AT('na');
				}
				?>
			</td>
		<td align="center"> 
		<?php
		if($row['passscore'] != 0)
		{
			if ($row['final_score'] >= $row['passscore'])
			{
			 ?><img src="mods/users_stats/images/checkmark.gif"> <?php
			}
			else 
			{
			 ?><img src="mods/users_stats/images/x.gif">         <?php
			}
		}
		elseif($row['passpercent'] != 0)
		{
			$final_score = ($row['final_score']*100)/$row['out_of'];
		    if ($final_score >= $row['passpercent'])
			{
			 ?><img src="mods/users_stats/images/checkmark.gif"> <?php
			}
			else 
			{
			 ?><img src="mods/users_stats/images/x.gif">         <?php
			}	
		}
		else 
		{	
		$rate=($row['final_score']/$row['out_of'])*100;
		    if($rate >= 60)
		    { 
		     ?><img src="mods/users_stats/images/checkmark.gif"> <?php
	        } 
	        else 
	        { 
	         ?><img src="mods/users_stats/images/x.gif">         <?php  
            }
        }   ?>
			
	 </tr>
	 <?php } while ($row = mysql_fetch_assoc($result)); ?> 


<?php else: ?> <!--dati non presenti nel db-->
	<tr>
		<td colspan="6"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php endif; ?>
	
</tbody>
</table>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>