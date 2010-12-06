<?php
$form_create_blti = array(
	'title:text:required=true:size=25',
	'toolid:text:required=true:size=16',
	'description:textarea:required=true:rows=2:cols=25',
	'toolurl:text:label=bl_toolurl:required=true:size=80',
	'resourcekey:text:label=bl_resourcekey:required=true:size=80',
	'password:text:required=true:label=bl_password:size=80',
	'preferheight:integer:size=80',
	'launchinpopup:radio:label=bl_launchinpopup:choices=off,on,instructor',
	'debuglaunch:radio:label=bl_debuglaunch:choices=off,on,instructor',
	'sendname:radio:label=bl_sendname:choices=off,on,instructor',
	'sendemailaddr:radio:label=bl_sendemailaddr:choices=off,on,instructor',
	'acceptgrades:radio:label=bl_acceptgrades:choices=off,on',
	'allowroster:radio:label=bl_allowroster:choices=off,on,instructor',
	'allowsetting:radio:label=bl_allowsetting:choices=off,on,instructor',
	'instructorcustom:radio:label=bl_instructorcustom:choices=off,on',
	'customparameters:textarea:label=bl_customparameters:rows=5:cols=25',
	'organizationid:text:label=bl_organizationid:size=80',
	'organizationurl:text:label=bl_organizationurl:size=80',
	'organizationdescr:text:label=bl_organizationdescr:size=80',
        );
?>
