<?php 

require(AT_INCLUDE_PATH.'header.inc.php'); 

$tabs = get_tabs();	
$num_tabs = count($tabs);
if($_POST['current_tab'])
{
	$current_tab = addslashes($_POST['current_tab']);
}
else
{
	for ($i=0; $i < $num_tabs; $i++) 
	{
		if (isset($_POST['button_'.$i]) && ($_POST['button_'.$i] != -1)) 
		{ 
			$current_tab = $i;
			break;
		}
		else
		{
			$current_tab = 0;
		}			
	}
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="form" enctype="multipart/form-data">

	<div align="center" style="width:90%; margin-left:auto; margin-right:auto;">
		<?php output_tabs($current_tab, $changes_made); ?>
	</div>

	<div class="input-form">
<?php

	if ($current_tab != 0) 
	{
		// save selected options on tab 0
		if (isset($_POST['theme']))
			echo '	<input type="hidden" name="theme" value="'.$_POST['theme'].'" />'."\n\r";
		
		if (isset($_POST['mnot']))
			echo '	<input type="hidden" name="mnot" value="'.$_POST['mnot'].'" />'."\n\r";
		
		if (isset($_POST['numbering']))
			echo '	<input type="hidden" name="numbering" value="'.$_POST['numbering'].'" />'."\n\r";
		
		if (isset($_POST['use_jump_redirect']))
			echo '	<input type="hidden" name="use_jump_redirect" value="'.$_POST['use_jump_redirect'].'" />'."\n\r";
		
		if (isset($_POST['auto']))
			echo '	<input type="hidden" name="auto" value="'.$_POST['auto'].'" />'."\n\r";
		
		if (isset($_POST['form_focus']))
			echo '	<input type="hidden" name="form_focus" value="'.$_POST['form_focus'].'" />'."\n\r";
		
		if (isset($_POST['content_editor']))
			echo '	<input type="hidden" name="content_editor" value="'.$_POST['content_editor'].'" />'."\n\r";
	}

	if ($current_tab != 2) 
	{
		// save selected options on tab 0
		if (isset($_POST['use_alternate_text']))
			echo '	<input type="hidden" name="use_alternate_text" value="'.$_POST['use_alternate_text'].'" />'."\n\r";
		
		if (isset($_POST['alt_text_lang']))
			echo '	<input type="hidden" name="alt_text_lang" value="'.$_POST['alt_text_lang'].'" />'."\n\r";
		
		if (isset($_POST['long_desc_lang']))
		echo '	<input type="hidden" name="long_desc_lang" value="'.$_POST['long_desc_lang'].'" />'."\n\r";
		
		if (isset($_POST['use_graphic_alternative']))
		echo '	<input type="hidden" name="use_graphic_alternative" value="'.$_POST['use_graphic_alternative'].'" />'."\n\r";
		
		if (isset($_POST['use_sign_lang']))
		echo '	<input type="hidden" name="use_sign_lang" value="'.$_POST['use_sign_lang'].'" />'."\n\r";
		
		if (isset($_POST['sign_lang']))
		echo '	<input type="hidden" name="sign_lang" value="'.$_POST['sign_lang'].'" />'."\n\r";
		
		if (isset($_POST['use_video']))
		echo '	<input type="hidden" name="use_video" value="'.$_POST['use_video'].'" />'."\n\r";
		
		if (isset($_POST['prefer_lang']))
		echo '	<input type="hidden" name="prefer_lang" value="'.$_POST['prefer_lang'].'" />'."\n\r";
		
		if (isset($_POST['description_type']))
		echo '	<input type="hidden" name="description_type" value="'.$_POST['description_type'].'" />'."\n\r";
		
		if (isset($_POST['enable_captions']))
		echo '	<input type="hidden" name="enable_captions" value="'.$_POST['enable_captions'].'" />'."\n\r";
		
		if (isset($_POST['caption_type']))
		echo '	<input type="hidden" name="caption_type" value="'.$_POST['caption_type'].'" />'."\n\r";
		
		if (isset($_POST['caption_lang']))
		echo '	<input type="hidden" name="caption_lang" value="'.$_POST['caption_lang'].'" />'."\n\r";
		
		if (isset($_POST['enhanced_captions']))
		echo '	<input type="hidden" name="enhanced_captions" value="'.$_POST['enhanced_captions'].'" />'."\n\r";
		
		if (isset($_POST['request_caption_rate']))
		echo '	<input type="hidden" name="request_caption_rate" value="'.$_POST['request_caption_rate'].'" />'."\n\r";
		
		if (isset($_POST['caption_rate']))
		echo '	<input type="hidden" name="caption_rate" value="'.$_POST['caption_rate'].'" />'."\n\r";
	}

 include(getcwd().'/'.$tabs[$current_tab][1]);

?>
	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('apply'); ?>" accesskey="s" />
		<input type="reset" name="reset" value="<?php echo _AT('reset'); ?>" />
	</div>
</div>

</form>	

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
