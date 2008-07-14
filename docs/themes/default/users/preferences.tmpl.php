<?php 

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

if ($current_tab == 1)
{
	global $_custom_head, $onload;
	
	$_custom_head = "<script language=\"JavaScript\" src=\"jscripts/TILE.js\" type=\"text/javascript\"></script>";
	$onload = "setPreviewFace(); setPreviewSize(); setPreviewColours();";
}

require(AT_INCLUDE_PATH.'header.inc.php'); 
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="form" enctype="multipart/form-data">

	<div align="center" style="width:90%; margin-left:auto; margin-right:auto;">
		<?php output_tabs($current_tab, $changes_made); ?>
	</div>

	<div class="input-form">
<?php

	if ($current_tab != 0) 
	{
		// save selected options on tab 0 (ATutor settings)
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

	if ($current_tab != 1) 
	{
//		phpinfo();
		// save selected options on tab 1 (display settings)
		if (isset($_POST['fontface']))
			echo '	<input type="hidden" name="fontface" value="'.$_POST['fontface'].'" />'."\n\r";

		if (isset($_POST['fontsize']))
			echo '	<input type="hidden" name="fontsize" value="'.$_POST['fontsize'].'" />'."\n\r";

		if (isset($_POST['fg']))
			echo '	<input type="hidden" name="fg" value="'.$_POST['fg'].'" />'."\n\r";

		if (isset($_POST['bg']))
			echo '	<input type="hidden" name="bg" value="'.$_POST['bg'].'" />'."\n\r";

		if (isset($_POST['hl']))
			echo '	<input type="hidden" name="hl" value="'.$_POST['hl'].'" />'."\n\r";

		if (isset($_POST['invert_colour_selection']))
			echo '	<input type="hidden" name="invert_colour_selection" value="'.$_POST['invert_colour_selection'].'" />'."\n\r";

		if (isset($_POST['avoid_red']))
			echo '	<input type="hidden" name="avoid_red" value="'.$_POST['avoid_red'].'" />'."\n\r";

		if (isset($_POST['avoid_red_green']))
			echo '	<input type="hidden" name="avoid_red_green" value="'.$_POST['avoid_red_green'].'" />'."\n\r";

		if (isset($_POST['avoid_blue_yellow']))
			echo '	<input type="hidden" name="avoid_blue_yellow" value="'.$_POST['avoid_blue_yellow'].'" />'."\n\r";

		if (isset($_POST['avoid_green_yellow']))
			echo '	<input type="hidden" name="avoid_green_yellow" value="'.$_POST['avoid_green_yellow'].'" />'."\n\r";

		if (isset($_POST['use_max_contrast']))
			echo '	<input type="hidden" name="use_max_contrast" value="'.$_POST['use_max_contrast'].'" />'."\n\r";
	}
		
	if ($current_tab != 2) 
	{
		// save selected options on tab 2 (content settings)
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

	if ($current_tab != 3) 
	{
		// save selected options on tab 3 (tool settings)
		if (isset($_POST['dictionary_val']))
			echo '	<input type="hidden" name="dictionary_val" value="'.$_POST['dictionary_val'].'" />'."\n\r";

		if (isset($_POST['thesaurus_val']))
			echo '	<input type="hidden" name="thesaurus_val" value="'.$_POST['thesaurus_val'].'" />'."\n\r";

		if (isset($_POST['note_taking_val']))
			echo '	<input type="hidden" name="note_taking_val" value="'.$_POST['note_taking_val'].'" />'."\n\r";

		if (isset($_POST['calculator_val']))
			echo '	<input type="hidden" name="calculator_val" value="'.$_POST['calculator_val'].'" />'."\n\r";

		if (isset($_POST['peer_interaction_val']))
			echo '	<input type="hidden" name="peer_interaction_val" value="'.$_POST['peer_interaction_val'].'" />'."\n\r";

		if (isset($_POST['abacus_val']))
			echo '	<input type="hidden" name="abacus_val" value="'.$_POST['abacus_val'].'" />'."\n\r";
	}
	
	if ($current_tab != 4) 
	{
		// save selected options on tab 4 (control settings)
		if (isset($_POST['show_contents']))
			echo '	<input type="hidden" name="show_contents" value="'.$_POST['show_contents'].'" />'."\n\r";

		if (isset($_POST['next_previous_buttons']))
			echo '	<input type="hidden" name="next_previous_buttons" value="'.$_POST['next_previous_buttons'].'" />'."\n\r";

		if (isset($_POST['show_notes']))
			echo '	<input type="hidden" name="show_notes" value="'.$_POST['show_notes'].'" />'."\n\r";

		if (isset($_POST['level_of_detail']))
			echo '	<input type="hidden" name="level_of_detail" value="'.$_POST['level_of_detail'].'" />'."\n\r";

		if (isset($_POST['content_views']))
			echo '	<input type="hidden" name="content_views" value="'.$_POST['content_views'].'" />'."\n\r";

		if (isset($_POST['show_separate_links']))
			echo '	<input type="hidden" name="show_separate_links" value="'.$_POST['show_separate_links'].'" />'."\n\r";

		if (isset($_POST['show_transcript']))
			echo '	<input type="hidden" name="show_transcript" value="'.$_POST['show_transcript'].'" />'."\n\r";

		if (isset($_POST['window_layout']))
			echo '	<input type="hidden" name="window_layout" value="'.$_POST['window_layout'].'" />'."\n\r";
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
