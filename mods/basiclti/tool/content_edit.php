<?php
define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_BASICLTI);

require_once('forms.php');

if ( !is_int($_SESSION['course_id']) || $_SESSION['course_id'] < 1 ) {
    $msg->addFeedback('NEED_COURSE_ID');
    exit;
}

// Add/Update The Tool
if ( isset($_POST['toolid']) && at_form_validate($blti_content_edit_form, $msg)) {
    $toolid = $_POST['toolid'];
    $sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_content
            WHERE content_id=".$_POST[cid]." AND course_id=".$_SESSION[course_id];
    $result = mysql_query($sql, $db);
    if ( $toolid == '--none--' ) {
        $sql = "DELETE FROM ". TABLE_PREFIX . "basiclti_content 
                       WHERE content_id=".$_POST[cid]." AND 
                             course_id=".$_SESSION[course_id];
            $result = mysql_query($sql, $db);
            if ($result===false) {
                $msg->addError('MYSQL_FAILED');
            } else {
                $msg->addFeedback('BASICLTI_DELETED');
            }
    } else if ( mysql_num_rows($result) == 0 ) {
            $sql = "INSERT INTO ". TABLE_PREFIX . "basiclti_content 
                       SET toolid='".$toolid."', content_id=".$_POST[cid].",
                             course_id=".$_SESSION[course_id];
            $result = mysql_query($sql, $db);
            if ($result===false) {
                $msg->addError('MYSQL_FAILED');
            } else {
                $msg->addFeedback('BASICLTI_SAVED');
            }

    } else { 
            $sql = "UPDATE ". TABLE_PREFIX . "basiclti_content 
                       SET toolid='".$toolid."' WHERE content_id=".$_POST[cid]." AND 
                           course_id=".$_SESSION[course_id];
            $result = mysql_query($sql, $db);
            if ($result===false) {
                $msg->addError('MYSQL_FAILED');
            } else {
                $msg->addFeedback('BASICLTI_SAVED');
            }
    }
}

// echo("<hr>$sql<hr>\n");

$cid = intval($_REQUEST['cid']);

global $framed, $popup;

if ((isset($_REQUEST['popup']) && $_REQUEST['popup']) &&
    (!isset($_REQUEST['framed']) || !$_REQUEST['framed'])) {
    $popup = TRUE;
    $framed = FALSE;
} elseif (isset($_REQUEST['framed']) && $_REQUEST['framed'] && isset($_REQUEST['popup']) && $_REQUEST['popup']) {
    $popup = TRUE;
    $framed = TRUE;
    $tool_flag = TRUE;
} else {
    $popup = FALSE;
    $framed = FALSE;
}

require(AT_INCLUDE_PATH.'header.inc.php');

/* get a list of all the tools, we have */
$sql    = "SELECT * FROM ".TABLE_PREFIX."basiclti_tools WHERE course_id = 0".
          " OR course_id=".$_SESSION[course_id]." ORDER BY course_id,title";

$toolresult = mysql_query($sql, $db);
$num_tools = mysql_num_rows($toolresult);

//If there are no Tools, don't display anything except a message
if ($num_tools == 0){
        $msg->addInfo('NO_PROXY_TOOLS');
        $msg->printInfos();
        return;
}

?>
<div class="input-form">

<form name="datagrid" action="" method="POST">

<fieldset class="group_form">
   <legend class="group_form"><?php echo _AT('bl_content_title'); ?></legend>
<br/>
<?php echo _AT('basiclti_comment');?>
<br/>
<?php echo $msg->printFeedbacks();

// Get the current content item
$sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_content 
                WHERE content_id=$cid";
$contentresult = mysql_query($sql, $db);
$row = mysql_fetch_assoc($contentresult);
// if ( $row ) echo("FOUND"); else echo("NOT");
?>

<div class="row">
   <?php echo _AT('bl_choose_tool'); ?><br/>
   <select id="toolid" name="toolid" onchange="datagrid.submit();"> 
      <option value="--none--">&nbsp;</option><?php
      $thetoolrow = false;
      $found = false;  // Only the first one
      while ( $tool = mysql_fetch_assoc($toolresult) ) {
         $selected = "";
         if ( ! $found && $tool['toolid'] == $row['toolid'] ) {
           $selected = ' selected="yes"';
           $thetoolrow = $tool;
           $found = true;
         }
         echo '<option value="'.$tool['toolid'].'"'.$selected.'>'.$tool['title']."</option>\n";
      } ?>
   </select>
<?php
if ( $thetoolrow !== false ) {
    $blti_content_edit_form = filterForm($thetoolrow, $blti_content_edit_form);
}
?>
<?php at_form_generate($toolrow, $blti_content_edit_form); ?>
   <input type="hidden" name="cid" value="<?php echo($cid);?>" />
   <input type="submit" name="save" value="Save" class="button" />
</div>
</legend>
</form>
</div>
<?php echo("<hr><pre>\n");print_r($thetoolrow); echo("\n</pre>\n"); ?>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
