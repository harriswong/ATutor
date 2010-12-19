<?php
define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');


function loadError($message) {
    print $message;
    exit();
}

$cid = intval($_GET['cid']);

$content_id = $cid;
$member_id = $_SESSION['member_id'];
require("loadrows.php");
$course_id = $contentrow['course_id'];
// echo("instancerow<br/>\n");print_r($instancerow); echo("<hr>\n");
// echo("toolrow<br/>\n");print_r($toolrow); echo("<hr>\n");
// echo("contentrow<br/>\n");print_r($contentrow); echo("<hr>\n");
// echo("courserow<br/>\n");print_r($courserow); echo("<hr>\n");
// echo("memberrow<br/>\n");print_r($memberrow); echo("<hr>\n");
// echo("enrollrow<br/>\n");print_r($enrollrow); echo("<hr>\n");

    $lmsdata = array(
      "resource_link_id" => $cid,
      "resource_link_title" => $contentrow['title'],
      "resource_link_description" => $contentrow['text'],
      "user_id" => $memberrow['member_id'],
      "roles" => "Learner",
      "context_id" => $courserow['course_id'],
      "context_title" => $courserow['title'],
      "context_label" => $courserow['title'],
      );

    if ( $enrollrow['role'] == 'Instructor' ) {
        $lmsdata["roles"] = 'Instructor';
    }

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
