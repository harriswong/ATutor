<?php 
if (!defined('AT_INCLUDE_PATH')) { exit; } 
global $_base_path;

$compact_title = str_replace(' ', '', $this->title);
?>

<br />
<script language="javascript" type="text/javascript">
if (getcookie("m_<?php echo $this->title; ?>") == "0")
{
	image = "<?php echo $_base_path?>images/mswitch_plus.gif";
	alt_text = "<?php echo _AT('show'). ' '. $this->title; ?>";
}
else
{
	image = "<?php echo $_base_path?>images/mswitch_minus.gif";
	alt_text = "<?php echo _AT('hide'). ' '. $this->title; ?>";
}

document.writeln('<h4 class="box">'+
'	<input src="'+image+'"' + 
'	       onclick="elementToggle(this, \'<?php echo $this->title; ?>\'); return false;"' +
'	       alt="'+ alt_text + '" ' +
'	       title="'+ alt_text + '"' +
'	       style="float:right" type="image">'+
'	<?php echo $this->title; ?>'+
'</h4>');
</script>
<div class="box" id="menu_<?php echo $compact_title ?>">
	<?php echo $this->dropdown_contents; ?>
</div>

<script language="javascript" type="text/javascript">
if (getcookie("m_<?php echo $this->title; ?>") == "0")
{
	jQuery("#menu_<?php echo $compact_title; ?>").hide();
}
else
{
	jQuery("#menu_<?php echo $compact_title; ?>").show();
}
</script>