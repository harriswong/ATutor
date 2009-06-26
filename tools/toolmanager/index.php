<?php

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'lib/mime.inc.php');
require(AT_INCLUDE_PATH.'vitals.inc.php');

	
global $db;


if ((isset($_REQUEST['popup']) && $_REQUEST['popup']) && 
	(!isset($_REQUEST['framed']) || !$_REQUEST['framed'])) {
	$popup = TRUE;
	$framed = FALSE;
} else if (isset($_REQUEST['framed']) && $_REQUEST['framed'] && isset($_REQUEST['popup']) && $_REQUEST['popup']) {
	$popup = TRUE; 
	$framed = TRUE;
	$tool_flag = TRUE;
} else {
	$popup = FALSE;
	$framed = FALSE;
}
$_REQUEST['cid'] = intval($_REQUEST['cid']);	//uses request 'cause after 'saved', the cid will become $_GET.	

require(AT_INCLUDE_PATH.'header.inc.php');

$tool_file= $_REQUEST['tool_file'];				// viene prelevato il path del file necessario per prelevare le informazioni relative ai sottocontenuti ?> 

<br><br><br><br>
<?php echo _AT('ToolManComment');?>
<br><br><br><br> <?php
	
$content_list = require($tool_file); 

if(isset($content_list[0])){?>

	<table class="data" summary="" style="width: 60%" rules="cols">
		<thead>
			<tr>
				<th scope="col" style="width:5%">&nbsp;</th>
				<th scope="col"><?php echo _AT('Title');  ?></th>
			</tr>
		</thead>
		<tbody> <?php
		
		$i=0;
		foreach($content_list as $content){?>
			<tr>
				<td valign="top"><?php $files = '<input class="button" type="button" name="insert" value="' ._AT('insert') . '" onclick="javascript:insertFile(\'' .$content['title']. '\', \'' .AT_BASE_HREF. '\',\'' .$content['path']. '\',\'' . $tab . '\',\'' .$content['image']. '\');" />&nbsp;'; echo $files; ?></td>
				<td valign="top"><?php echo $content['title']; ?></td>
				
			</tr> <?php 
			$i++;
		
		}?>
		</tbody>
	</table><br><br><br> <?php
} ?>
		

<script type="text/javascript">
//<!--
function insertFile(desc, pathTo, ext, tab, image) { 

	// pathTo + fileName should be relative to current path (specified by the Content Package Path)

		var html = '<img src="'+ image +'" border="0">&nbsp;<a href="'+ pathTo + ext +'">' + desc + '</a>';
		
		insertLink(html, tab);
	
}
function insertWikiPage(filename, file_path, tab) { 

	// pathTo + fileName should be relative to current path (specified by the Content Package Path)

		var html = '<a href="' + file_path + '">' + filename + '</a>';
		
		insertLink(html, tab);
	
}
function insertLink(html, tab)
{
	if (!window.opener || window.opener.document.form.setvisual.value == 1) {
		if (!window.opener && window.parent.tinyMCE)
			window.parent.tinyMCE.execCommand('mceInsertContent', false, html);
		else
			if (window.opener && window.opener.tinyMCE)
				window.opener.tinyMCE.execCommand('mceInsertContent', false, html);
	} else {
		if (tab==5)
			insertAtCursor(window.opener.document.form.body_text_alt, html);
		else
			insertAtCursor(window.opener.document.form.body_text, html);
	}
}

function insertAtCursor(myField, myValue) {
	//IE support
	if (window.opener.document.selection) {
		myField.focus();
		sel = window.opener.document.selection.createRange();
		sel.text = myValue;
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)
		+ myValue
		+ myField.value.substring(endPos, myField.value.length);
		myField.focus();
	} else {
		myField.value += myValue;
		myField.focus();
	}
}	
//-->
</script>


<?php require(AT_INCLUDE_PATH.'footer.inc.php');?>
