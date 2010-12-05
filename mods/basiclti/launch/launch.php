<?php
define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
// admin_authenticate(AT_ADMIN_PRIV_BASICLTI);

$cid = intval($_GET['cid']);

$sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_content
                WHERE content_id=".$cid;
$instanceresult = mysql_query($sql, $db);
$instancerow = mysql_fetch_assoc($instanceresult);
if ( ! $instancerow ) {
    echo("Not Configured\n");
    exit;
}

$toolid = $instancerow['toolid'];
$sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_tools
                WHERE toolid='".$toolid."'";
$contentresult = mysql_query($sql, $db);
$toolrow = mysql_fetch_assoc($contentresult);
if ( ! $toolrow ) {
    echo("Tool definition missing\n");
    exit;
}
// print_r($toolrow); echo("<hr>\n");

$sql = "SELECT * FROM ".TABLE_PREFIX."content
                WHERE content_id=".$cid;
$contentresult = mysql_query($sql, $db);
$contentrow = mysql_fetch_assoc($contentresult);
if ( ! $contentrow ) {
    echo("Not Configured\n");
    exit;
}
// print_r($contentrow); echo("<hr>\n");

$sql = "SELECT * FROM ".TABLE_PREFIX."courses
                WHERE course_id='".$_SESSION['course_id']."'";
$courseresult = mysql_query($sql, $db);
$courserow = mysql_fetch_assoc($courseresult);
if ( ! $courserow ) {
    echo("Course definition missing\n");
    exit;
}
// print_r($courserow); echo("<hr>\n");

$sql = "SELECT * FROM ".TABLE_PREFIX."members
                WHERE member_id='".$_SESSION['member_id']."'";
$memberresult = mysql_query($sql, $db);
$memberrow = mysql_fetch_assoc($memberresult);
if ( ! $memberrow ) {
    echo("Course definition missing\n");
    exit;
}
// print_r($memberrow); echo("<hr>\n");

    $lmsdata = array(
      "resource_link_id" => $cid,
      "resource_link_title" => $contentrow['title'],
      "resource_link_description" => $contentrow['text'],
      "user_id" => $memberrow['member_id'],
      "roles" => "Instructor,urn:TODO",  // TODO: or Learner
      "context_id" => $courserow['course_id'],
      "context_title" => $courserow['title'],
      "context_label" => $courserow['title'],
      );

    if ( $toolrow['sendemailaddr'] == 1 ||
         ( $toolrow['sendemailaddr'] == 2 && $instancerow['sendemailaddr'] == 1 ) ) {
        $lmsdata["lis_person_contact_email_primary"] = $memberrow['email'];
    }

    if ( $toolrow['sendname'] == 1 ||
         ( $toolrow['sendname'] == 2 && $instancerow['sendname'] == 1 ) ) {
        $lmsdata["lis_person_name_family"] = $memberrow['last_name'];
        $lmsdata["lis_person_name_given"] = $memberrow['first_name'];
    }

    $placementsecret = $instancerow['placementsecret'];
    if ( isset($placementsecret) ) {
        $suffix = ':::' . $memberrow['member_id'] . ':::' . $cid;
        $plaintext = $placementsecret . $suffix;
        $hashsig = hash('sha256', $plaintext, false);
        $sourcedid = $hashsig . $suffix;
    }

    if ( isset($placementsecret) &&
         ( $toolrow['acceptgrades'] == 1 && $instancerow['gradebook_test_id'] != 0 ) ) {
        $lmsdata["lis_result_sourcedid"] = $sourcedid;
        $lmsdata["ext_ims_lis_basic_outcome_url"] = AT_BASE_HREF.'mods/basiclti/launch/service.php';
    }

    if ( isset($placementsecret) &&
         ( $toolrow['allowroster'] == 1 ||
         ( $toolrow['allowroster'] == 2 && $instancerow['allowroster'] == 1 ) ) ) {
        $lmsdata["ext_ims_lis_memberships_id"] = $sourcedid;
        $lmsdata["ext_ims_lis_memberships_url"] = AT_BASE_HREF.'mods/basiclti/launch/service.php';
    }

    if ( isset($placementsecret) &&
         ( $toolrow['allowsetting'] == 1 ||
         ( $toolrow['allowsetting'] == 2 && $instancerow['allowsetting'] == 1 ) ) ) {
        $lmsdata["ext_ims_lti_tool_setting_id"] = $sourcedid;
        $lmsdata["ext_ims_lti_tool_setting_url"] = AT_BASE_HREF.'mods/basiclti/launch/service.php';
        $setting = $instancerow['setting'];
        if ( isset($setting) ) {
             $lmsdata["ext_ims_lti_tool_setting"] = $setting;
        }
    }

// print_r($lmsdata);echo("<hr>\n");

$parms = $lmsdata;

$endpoint = $toolrow['toolurl'];
$key = $toolrow['resourcekey'];
$secret = $toolrow['password'];

require_once("ims-blti/blti_util.php");

  $parms = signParameters($parms, $endpoint, "POST", $key, $secret, "Press to Launch", $tool_consumer_instance_guid, $tool_consumer_instance_description);

  $debuglaunch = false;
  if ( ( $toolrow['debuglaunch'] == 1 ||
       ( $toolrow['debuglaunch'] == 2 && $instancerow['debuglaunch'] == 1 ) ) ) {
    $debuglaunch = true;
  }

  $content = postLaunchHTML($parms, $endpoint, $debuglaunch);

  print($content);


?>
