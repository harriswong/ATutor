<?php
//Profile template for social
?>

<div>
	<div><h3><?php echo printSocialName($this->profile['member_id'], false); ?></h3></div>
	<div style="float:left; width:40%;">		
		<div class="headingbox" style="margin-right:1em;">
			<h4><?php echo _AT('profile'); ?></h4>
		</div>
		<div class="contentbox" style="margin-right:1em;">
		<?php if ($this->scope=='owner'): ?>
		<div style="float:right; border:thin #cccccc solid;">
			<a href="<?php echo url_rewrite(AT_SOCIAL_BASENAME."edit_profile.php");?>"><img src="<?php echo $_base_href.AT_SOCIAL_BASENAME;?>images/edit_profile.gif" alt="<?php echo _AT('edit_profile'); ?>" title="<?php echo _AT('edit_profile'); ?>" border="0"/></a>
		</div>		
		<?php endif; ?>
		<?php 
		//TODO: include this in the printSocialProfileImg function itself
		if (profile_image_exists($this->profile['member_id'])): ?>
			<a href="get_profile_img.php?id=<?php echo $this->profile['member_id'].SEP ;?>size=o"><?php echo printSocialProfileImg($this->profile['member_id'], 2, false); ?></a>
		<?php else: ?>
			<?php echo printSocialProfileImg($this->profile['member_id'], 2, false); ?>
		<?php endif; ?>
		<p><a href="inbox/send_message.php?id=<?php echo $this->profile['member_id']; ?>"><?php echo _AT('send_message'); ?></a></p>
		<dl class="public-profile">
			<?php if($this->profile['occupation']){ ?>
			<dt><?php echo _AT('occupation'); ?></dt>
			<dd><?php echo $this->profile['occupation']; ?></dd>
			<?php }?>
			<?php if($this->profile['expertise']){ ?>
			<dt><?php echo _AT('expertise'); ?></dt>
			<dd><?php echo $this->profile['expertise']; ?></dd>
			<?php }?>
			<?php if ($this->relationship==AT_SOCIAL_FRIENDS_VISIBILITY || $this->relationship==AT_SOCIAL_OWNER_VISIBILITY): ?>
			<?php if($this->profile['email']): ?>
			<dt><?php echo _AT('email'); ?></dt>
			<dd><?php echo $this->profile['email']; ?></dd>
			<?php endif; ?>
			<?php endif; ?>
			<?php if($this->profile['gender']!='n'){ ?>
			<dt><?php echo _AT('gender'); ?></dt>
			<dd><?php echo $this->profile['gender']; ?></dd>
			<?php }?>
			<?php if($this->profile['dob']!='0000-00-00'){ ?>
			<dt><?php echo _AT('dob'); ?></dt>
			<dd><?php echo $this->profile['dob']; ?></dd>
			<?php }?>
			<?php if($this->profile['phone']){ ?>
			<dt><?php echo _AT('phone'); ?></dt>
			<dd><?php echo $this->profile['phone']; ?></dd>
			<?php }?>
			<?php if($this->profile['country']){ ?>
			<dt><?php echo _AT('country'); ?></dt>
			<dd><?php echo $this->profile['country']; ?></dd>
			<?php }?>
			<?php if($this->profile['postal']){ ?>
			<dt><?php echo _AT('street_address'); ?></dt>
			<dd><?php echo $this->profile['postal']; ?></dd>
			<?php }?>
			<?php if($this->profile['interests']){ ?>
			<dt><?php echo _AT('interests'); ?></dt>
			<dd><?php echo htmlentities_utf8($this->profile['interests']); ?></dd>
			<?php }?>
			<?php if($this->profile['associations']){ ?>e
			<dt><?php echo _AT('associations'); ?></dt>
			<dd><?php echo htmlentities_utf8($this->profile['associations']); ?></dd>
			<?php }?>
			<?php if($this->profile['awards']){ ?>
			<dt><?php echo _AT('awards'); ?></dt>
			<dd><?php echo htmlentities_utf8($this->profile['awards']); ?></dd>
			<?php }?>
			<?php if($this->profile['others']){ ?>
			<dt><?php echo _AT('others'); ?></dt>
			<dd><?php echo $this->profile['others']; ?></dd>
			<?php }?>
			</dl>
		</div>
	</div>

	<div style="float:left; width:59%;">	
		<?php if (PrivacyController::validatePrivacy(AT_SOCIAL_PROFILE_EDUCATION, $this->relationship, $this->prefs)): ?>
			<?php if (!empty($this->education)){ ?>
			<div>
				<div class="headingbox"><h5><?php echo _AT('training_and_education'); ?></h5></div>
				<div class="contentbox">
				<table class="data static">	
					<thead><tr>
						<th> <?php echo _AT('institution'); ?></th>
						<th> <?php echo _AT('degrees'); ?></th>
						<th> <?php echo _AT('year'); ?></th>
					</tr></thead>
					<tbody>
					<?php
						foreach($this->education as $edu){
							echo '<tr><td>'.htmlentities_utf8($edu['university']).'</td>';
							echo '<td>'.htmlentities_utf8($edu['degree'].'/'.$edu['field']).'</td>';
							echo '<td>'.htmlentities_utf8($edu['from'].'-'.$edu['to']).'</td></tr>';
						}							
					?>
					</tbody>
				</table>
				</div>
			</div><br/>
			<?php } ?>
		<?php endif; ?>

		<?php if (PrivacyController::validatePrivacy(AT_SOCIAL_PROFILE_POSITION, $this->relationship, $this->prefs)): ?>
		
			<?php if (!empty($this->position)){ ?>
			<div>
				<div class="headingbox"><h5><?php echo _AT('credits_and_work_experience'); ?></h5></div>
				<div class="contentbox">
				<table class="data static">	
					<thead><tr>
						<th><?php echo _AT('company'); ?></th>
						<th><?php echo _AT('position'); ?></th>
						<th><?php echo _AT('year'); ?></th>
					</tr></thead>
					<tbody>
					<?php
						foreach($this->position as $pos){
							echo '<tr><td>'.htmlentities_utf8($pos['company']).'</td>';
							echo '<td>'.htmlentities_utf8($pos['title']).'</td>';
							echo '<td>'.htmlentities_utf8($pos['from'].'-'.$pos['to']).'</td></tr>';
						}							
					?>
					</tbody>
				</table></div>
			</div><br/>
			<?php } ?>		
		<?php endif; ?>

		<?php if (PrivacyController::validatePrivacy(AT_SOCIAL_PROFILE_MEDIA, $this->relationship, $this->prefs)): ?>
		<div>
			<?php if (!empty($this->websites)): ?>
			<div class="headingbox"><h5><?php echo _AT('websites'); ?></h5></div>
			<div class="contentbox">
			<table class="data static">	
				<thead><tr>
					<th><?php echo _AT('site_name'); ?></th>
					<th><?php echo _AT('url'); ?></th>
				</tr></thead>
				<tbody>
				<?php
					foreach($this->websites as $sites){
						$is_http = preg_match("/^http/", $sites['url']);
						if ($is_http==0){
							$sites['url'] = 'http://' . $sites['url'];
						}
						echo '<tr><td>'.htmlentities_utf8($sites['site_name']).'</td>';
						echo '<td><a href="'.$sites['url'].'" target="user_profile_site">'.$sites['url'].'</a></td></tr>';
					}							
				?>
				</tbody>
			</table>
			</div><br/>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if (PrivacyController::validatePrivacy(AT_SOCIAL_PROFILE_STATUS_UPDATE, $this->relationship, $this->prefs)): ?>
		<div class="headingbox">
			<h5><?php echo _AT('activities'); ?></h5></div>
		<div class="contentbox" id="activity">
			<?php if(!empty($this->activities)): ?>
			<ul>
				<?php
					foreach($this->activities as $id=>$activity):
						/*
						 * harris @may 26, 2009
						 * Would be nice to use this, but we will have to change "has" to "have" for _AT('you')
						 *
						if ($_SESSION['member_id']== $this->profile['member_id']){
							echo '<li>'._AT('you');
							echo ' '.$activity.' ';
							echo '<a href="'.url_rewrite(AT_SOCIAL_BASENAME.'sprofile.php?delete='.$id).'"><img src="'.$_base_href.AT_SOCIAL_BASENAME.'images/b_drop.png" alt="'._AT('remove').'" title="'._AT('remove').'" border="0" /></a></li>';
						} else {
							echo '<li>'.printSocialName($this->profile['member_id']).' '.$activity.'</li>';
						}
						*/
				 ?>
				 <li><?php echo $activity['created_date']. ' - '. printSocialName($activity['member_id']).' '. $activity['title']; ?></li>
				<?php endforeach; ?>
			</ul>
			<?php else: ?>
			<?php echo _AT('no_activities'); ?>
			<?php endif; ?>
		</div><br />
		<?php endif; ?>

		<?php if (PrivacyController::validatePrivacy(AT_SOCIAL_PROFILE_CONNECTION, $this->relationship, $this->prefs)): ?>
		<div class="headingbox">
			<h5><?php echo _AT('connections'); ?></h5>
		</div>
		<div class="contentbox">
			<?php if (sizeof($this->friends)>0):
					foreach($this->friends as $friend_id): ?>													
				<div style="float:left; margin-left:1em;">
				<?php echo printSocialProfileImg($friend_id); ?><br/>
				<?php echo printSocialName($friend_id); ?>
				</div>
			<?php 	endforeach;
				else: 
					echo _AT('no_friends');
				endif; ?>
		</div><br/>

		<?php if (isset($this->mutual_friends)): ?>
		<div class="headingbox">
			<h5><?php echo _AT('mutual_connections'); ?></h5>
		</div>
		<div class="contentbox">
			<?php foreach($this->mutual_friends as $friend_id): ?>
				<div style="float:left; margin-left:1em;">
				<?php echo printSocialProfileImg($friend_id); ?><br/>
				<?php echo printSocialName($friend_id); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; //this->mutual_friends != empty ?>
		<?php endif; ?>
	</div>
</div>

