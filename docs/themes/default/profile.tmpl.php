<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id: profile.tmpl.php 3928 2005-03-16 20:21:45Z shozubq $

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="input-form">

	<h3><?php echo AT_print($this->row['login'], 'members.login'); ?></h3>

	<div class="row">
		<a href="inbox/send_message.php?id=<?php echo $this->row['member_id']; ?>"><?php echo _AT('send_message'); ?></a>
	</div>

	<div class="row">
		<?php echo _AT('name'); ?><br />
		<?php if($this->row['first_name'] || $this->row['last_name']) {
			echo AT_print($this->row['first_name'],'members.first_name').' '. AT_print($this->row['last_name'],'members.last_name');
		} else {
			echo "--";
		}
		?>
	</div>

	<div class="row">
		<?php echo _AT('web_site'); ?><br />
		<?php if ($this->row['website']) { 
			echo '<a href="'.$this->row['website'].'">'.AT_print($this->row['website'], 'members.website').'</a>'; 
		} else {
			echo "--";
		}
		?>
	</div>

	<div class="row">
		<?php echo _AT('status'); ?><br />
		<?php echo $this->status; ?>
		<?php 
		if ($this->privs) { 
			$priv_string = "(";
			foreach ($this->privs as $priv) {
				$priv_string .= _AT($priv).', ';
			}				
			$priv_string = substr($priv_string, 0, -2);
			echo $priv_string .')';
		}
		?>
	</div>
</div>
</form>