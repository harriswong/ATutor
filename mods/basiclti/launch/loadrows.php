<?php

// Needs $content_id and $member_id for the BasicLTI placement 
$sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_content
                WHERE content_id=".$content_id;
$instanceresult = mysql_query($sql, $db);
$instancerow = mysql_fetch_assoc($instanceresult);
if ( ! $instancerow ) {
    loadError("Not Configured\n");
    exit;
}
// echo("INSTANCE<br/>\n");print_r($instancerow); echo("<hr>\n");

$toolid = $instancerow['toolid'];
$sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_tools
                WHERE toolid='".$toolid."'";
$contentresult = mysql_query($sql, $db);
$toolrow = mysql_fetch_assoc($contentresult);
if ( ! $toolrow ) {
    loadError("Tool definition missing\n");
    exit;
}
// echo("TOOL<br/>\n");print_r($toolrow); echo("<hr>\n");

$sql = "SELECT * FROM ".TABLE_PREFIX."content
                WHERE content_id=".$content_id;
$contentresult = mysql_query($sql, $db);
$contentrow = mysql_fetch_assoc($contentresult);
if ( ! $contentrow ) {
    loadError("Not Configured\n");
    exit;
}
// echo("CONTENT<br/>\n");print_r($contentrow); echo("<hr>\n");

$sql = "SELECT * FROM ".TABLE_PREFIX."courses
                WHERE course_id='".$contentrow['course_id']."'";
$courseresult = mysql_query($sql, $db);
$courserow = mysql_fetch_assoc($courseresult);
if ( ! $courserow ) {
    loadError("Course definition missing\n");
    exit;
}
// echo("COURSE<br/>\n");print_r($courserow); echo("<hr>\n");

$sql = "SELECT * FROM ".TABLE_PREFIX."course_enrollment
                WHERE member_id='".$member_id."'";
$enrollresult = mysql_query($sql, $db);
$enrollrow = mysql_fetch_assoc($enrollresult);
if ( ! $enrollrow ) {
    loadError("Course enrollment missing\n");
    exit;
}
// echo("enrollrow<br/>\n");print_r($enrollrow); echo("<hr>\n");

$sql = "SELECT * FROM ".TABLE_PREFIX."members
                WHERE member_id='".$member_id."'";
$memberresult = mysql_query($sql, $db);
$memberrow = mysql_fetch_assoc($memberresult);
if ( ! $memberrow ) {
    loadError("Course definition missing\n");
    exit;
}
// echo("MEMBER<br/>\n");print_r($memberrow); echo("<hr>\n");

?>
