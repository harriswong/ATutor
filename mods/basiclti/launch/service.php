<?php

define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'config.inc.php');
require_once(AT_INCLUDE_PATH.'lib/mysql_connect.inc.php');

    require_once("ims-blti/OAuth.php");
    require_once("TrivialStore.php");

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

    function message_response($major, $severity, $minor=false, $message=false, $xml=false) {
        $lti_message_type = $_REQUEST['lti_message_type'];
        $retval = '<?xml version="1.0" encoding="UTF-8"?>'."\n" .
        "<message_response>\n" .
        "  <lti_message_type>$lti_message_type</lti_message_type>\n" .
        "  <statusinfo>\n" .
        "     <codemajor>$major</codemajor>\n" .
        "     <severity>$severity</severity>\n";
        if ( ! $codeminor === false ) $retval = $retval .  "     <codeminor>$minor</codeminor>\n";
	$retval = $retval . 
        "     <description>$message</description>\n" .
        "  </statusinfo>\n";
        if ( ! $xml === false ) $retval = $retval . $xml;
        $retval = $retval . "</message_response>\n";
	return $retval;
    }

    function doError($message) {
        print message_response('Fail', 'Error', false, $message);
        exit();
    }

    $lti_version = $_REQUEST['lti_version'];
    if ( $lti_version != "LTI-1p0" ) doError("Improperly formed message");

    $lti_message_type = $_REQUEST['lti_message_type'];
    if ( ! isset($lti_message_type) ) doError("Improperly formed message");

    $message_type = false;
    if( $lti_message_type == "basic-lis-replaceresult" ||
        $lti_message_type == "basic-lis-createresult" ||
        $lti_message_type == "basic-lis-updateresult" ||
        $lti_message_type == "basic-lis-deleteresult" ||
        $lti_message_type == "basic-lis-readresult" ) {
          $sourcedid = $_REQUEST['sourcedid'];
          $message_type = "basicoutcome";
    } else if ( $lti_message_type == "basic-lti-loadsetting" ||
        $lti_message_type == "basic-lti-savesetting" ||
        $lti_message_type == "basic-lti-deletesetting" ) {
          $sourcedid = $_REQUEST['id'];
          $message_type = "toolsetting";
    } else if ( $lti_message_type == "basic-lis-readmembershipsforcontext") {
          $sourcedid = $_REQUEST['id'];
          $message_type = "roster";
    }

    if ( $message_type == false ) {
        doError("Illegal lti_message_type");
    }

    if ( !isset($sourcedid) ) {
        doError("sourcedid missing");
    }
    // Truncate to maximum length
    $sourcedid = substr($sourcedid, 0, 2048);

    try {
        $info = explode(':::',$sourcedid);
        if ( ! is_array($info) ) doError("Bad sourcedid");
        $signature = $info[0];
        $userid = intval($info[1]);
        $placement = $info[2];
    }
    catch(Exception $e) {
        doError("Bad sourcedid");
    }

    if ( isset($signature) && isset($userid) && isset($placement) ) {
        // OK
    } else {
        doError("Bad sourcedid");
    }

function loadError($msg) {
   doError($msg);
}

echo($placement);

$content_id = $placement;
$member_id = $userid;
require("loadrows.php");

die('HOHOSHS');

    // Retrieve the Basic LTI placement
    if (! $basiclti = get_record("basiclti", "id", $placement)) doError("Bad sourcedid 5");

    if ( $message_type == "basicoutcome" ) {
        if ( $basiclti->acceptgrades == 1 ||
             ( $basiclti->acceptgrades == 2 && $basiclti->instructorchoiceacceptgrades == 1 ) ) {
            // The placement is configured to accept grades
        } else { 
            doError("Not permitted");
        }
    } else if ( $message_type == "toolsetting" ) {
        if ( $basiclti->allowsetting == 1 ||
             ( $basiclti->allowsetting == 2 && $basiclti->instructorchoiceallowsetting == 1 ) ) {
            // OK
        } else { 
            doError("Not permitted");
        }
    } else if ( $message_type == "roster" ) {
        if ( $basiclti->allowroster == 1 ||
             ( $basiclti->allowroster == 2 && $basiclti->instructorchoiceallowroster == 1 ) ) {
            // OK
        } else { 
            doError("Not permitted");
        }
    }

    // Retrieve the secret we use to sign lis_result_sourcedid
    $placementsecret = $basiclti->placementsecret;
    $oldplacementsecret = $basiclti->oldplacementsecret;
    if ( ! isset($placementsecret) ) doError("Not permitted");

    $suffix = ':::' . $userid . ':::' . $placement;
    $plaintext = $placementsecret . $suffix;
    $hashsig = hash('sha256', $plaintext, false);
    if ( $hashsig != $signature && isset($oldplacementsecret) && strlen($oldplacementsecret) > 1 ) {
        $plaintext = $oldplacementsecret . $suffix;
        $hashsig = hash('sha256', $plaintext, false);
    }
        
    if ( $hashsig != $signature ) {
        doError("Invalid sourcedid");
    }

    // Check the OAuth Signature 
    $oauth_secret = $basiclti->password;
    $oauth_consumer_key = $basiclti->resourcekey;
    if ( ! isset($oauth_secret) ) doError("Not permitted");
    if ( ! isset($oauth_consumer_key) ) doError("Not permitted");

    // Verify the message signature
    $store = new TrivialOAuthDataStore();
    $store->add_consumer($oauth_consumer_key, $oauth_secret);

    $server = new OAuthServer($store);

    $method = new OAuthSignatureMethod_HMAC_SHA1();
    $server->add_signature_method($method);
    $request = OAuthRequest::from_request();

    $basestring = $request->get_signature_base_string();

    try {
        $server->verify_request($request);
    } catch (Exception $e) {
        doError($e->getMessage());
    }

    if (! $course = get_record("course", "id", $basiclti->course)) doError("Could not retrieve course");

    // TODO: Check that user is in course

    if (! $cm = get_coursemodule_from_instance("basiclti", $basiclti->id, $course->id)) {
        doError("Course Module ID was incorrect");
    }

    // Lets store the grade
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }

    // Beginning of actual grade processing
    if ( $message_type == "basicoutcome" ) {
        $source = 'mod/basiclti';
        $courseid = $course->id;
        $itemtype = 'mod';
        $itemmodule = 'basiclti';
        $iteminstance =  $basiclti->id;

        if ( $lti_message_type == "basic-lis-readresult" ) {
            unset($grade);
            $thegrade = grade_get_grades($courseid, $itemtype, $itemmodule, $iteminstance, $userid);
            // print_r($thegrade->items[0]->grades);
            if ( isset($thegrade) && is_array($thegrade->items[0]->grades) ) {
                foreach($thegrade->items[0]->grades as $agrade) {
                    $grade = $agrade->grade;
                    break;
                }
            }
            if ( ! isset($grade) ) {
                doError("Unable to read grade");
            }
               
            $result = "  <result>\n" .
                "     <resultscore>\n" .
                "        <textstring>" .
                htmlspecialchars($grade/100.0) .
                "</textstring>\n" .
                "     </resultscore>\n" .
                "  </result>\n";
            print message_response('Success', 'Status', false, "Grade read", $result);
            exit();
       }
    
        if ( $lti_message_type == "basic-lis-deleteresult" ) {
            $params = array();
            $params['itemname'] = $basiclti->name;
    
            $grade = new object();
            $grade->userid   = $userid; 
    
            grade_update($source, $courseid, $itemtype, $itemmodule, $iteminstance, 0, $grade, array('deleted'=>1));
        } else {
            if ( isset($_REQUEST['result_resultscore_textstring']) ) {
               $gradeval = floatval($_REQUEST['result_resultscore_textstring']);
               if ( $gradeval <= 1.0 && $gradeval >= 0.0 ) $gradeval = $gradeval * 100.0;
            } else {
                doError('Missing Grade');
            }
            $params = array();
            $params['itemname'] = $basiclti->name;
    
            $grade = new object();
            $grade->userid   = $userid; 
            $grade->rawgrade = $gradeval;
    
            grade_update($source, $courseid, $itemtype, $itemmodule, $iteminstance, 0, $grade, $params);
        }
    
        print message_response('Success', 'Status', 'fullsuccess', 'Grade updated');

    } else if ( $lti_message_type == "basic-lti-loadsetting" ) {
        $xml = "  <setting>\n" .
               "     <value>".htmlspecialchars($basiclti->setting)."</value>\n" .
               "  </setting>\n";
        print message_response('Success', 'Status', 'fullsuccess', 'Setting retrieved', $xml);
    } else if ( $lti_message_type == "basic-lti-savesetting" ) {
        $setting = $_REQUEST['setting'];
        if ( ! isset($setting) ) doError('Missing setting value');
        $sql = "UPDATE {$CFG->prefix}basiclti SET setting='".
            mysql_escape_string($setting) . "' WHERE id=" . $basiclti->id;
        $success = execute_sql($sql,false);
        if ( $success ) {
            print message_response('Success', 'Status', 'fullsuccess', 'Setting updated');
        } else {
            doError("Error updating error");
        }
    } else if ( $lti_message_type == "basic-lti-deletesetting" ) {
        $sql = "UPDATE {$CFG->prefix}basiclti SET setting='' WHERE id=" . $basiclti->id;
        $success = execute_sql($sql,false);
        if ( $success ) {
            print message_response('Success', 'Status', 'fullsuccess', 'Setting deleted');
        } else {
            doError("Error updating error");
        }
    } else if ( $message_type == "roster" ) {
        if ( ! $course = get_record("course", "id", $basiclti->course)) doError("Could not retrieve course");
        if ( ! $context = get_context_instance(CONTEXT_COURSE, $course->id) ) doError("Could not retrieve context");
        $sql = 'SELECT u.id, u.username, u.firstname, u.lastname, u.email, ro.shortname
            FROM  '.$CFG->prefix.'role_assignments ra
            JOIN  '.$CFG->prefix.'user AS u ON ra.userid = u.id
            JOIN  '.$CFG->prefix.'role ro ON ra.roleid = ro.id
            WHERE ra.contextid = '.$context->id;
        $userlist = get_recordset_sql($sql);
        $xml = "  <memberships>\n";
        while ($user = rs_fetch_next_record($userlist)) {
            $role = "Learner";
            if ( $user->shortname == 'editingteacher' || $user->shortname == 'admin' ) $role = 'Instructor';
            $userxml = "    <member>\n".
                       "      <user_id>".htmlspecialchars($user->id)."</user_id>\n".
                       "      <roles>$role</roles>\n";
             if ( $basiclti->sendname == 1 ||
                 ( $basiclti->sendname == 2 && $basiclti->instructorchoicesendname == 1 ) ) {
                if ( isset($user->firstname) ) $userxml .=  "      <person_name_given>".htmlspecialchars($user->firstname)."</person_name_given>\n";
                if ( isset($user->lastname) ) $userxml .=  "      <person_name_family>".htmlspecialchars($user->lastname)."</person_name_family>\n";
            }
             if ( $basiclti->sendemail == 1 ||
                 ( $basiclti->sendemail == 2 && $basiclti->instructorchoicesendname == 1 ) ) {
                if ( isset($user->email) ) $userxml .=  "      <person_contact_email_primary>".htmlspecialchars($user->lastname)."</person_contact_email_primary>\n";
            }
            $placementsecret = $basiclti->placementsecret;
            if ( isset($placementsecret) ) {
                $suffix = ':::' . $user->id . ':::' . $basiclti->id;
                $plaintext = $placementsecret . $suffix;
                $hashsig = hash('sha256', $plaintext, false);
                $sourcedid = $hashsig . $suffix;
            }
            if ( $basiclti->acceptgrades == 1 ||
                 ( $basiclti->acceptgrades == 2 && $basiclti->instructorchoiceacceptgrades == 1 ) ) {
                if ( isset($sourcedid) ) $userxml .=  "      <lis_result_sourcedid>".htmlspecialchars($sourcedid)."</lis_result_sourcedid>\n";
            }
            $userxml .= "    </member>\n";
            $xml .= $userxml;
        }
        $xml .= "  </memberships>\n";
        print message_response('Success', 'Status', 'fullsuccess', 'Roster retreived', $xml);

    }
    
?>
