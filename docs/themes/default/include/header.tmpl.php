<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

if (!defined('AT_INCLUDE_PATH')) { exit; }

// will have to be moved to the header.inc.php
global $system_courses, $_base_path, $_pages, $_my_uri;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?php echo $this->tmpl_lang; ?>">
<head>
	<title><?php echo SITE_NAME; ?> : <?php echo $this->_page_title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->tmpl_charset; ?>" />
	<meta name="Generator" content="ATutor - Copyright 2005 by http://atutor.ca" />
	<base href="<?php echo $this->tmpl_content_base_href; ?>" />
	<link rel="shortcut icon" href="<?php echo $this->tmpl_base_path; ?>favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path; ?>print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path.'themes/'.$this->tmpl_theme; ?>/styles.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path.'themes/'.$this->tmpl_theme; ?>/forms.css" type="text/css" />
	<?php echo $this->tmpl_rtl_css; ?>
	<style type="text/css"><?php echo $this->tmpl_banner_style; ?></style>
	<?php if ($system_courses[$_SESSION['course_id']]['rss']): ?>
	<link rel="alternate" type="application/rss+xml" title="ATutor course - RSS 2.0" href="<?php echo $this->tmpl_base_href; ?>get_rss.php?<?php echo $_SESSION['course_id']; ?>-2" />
	<link rel="alternate" type="application/rss+xml" title="ATutor course - RSS 1.0" href="<?php echo $this->tmpl_base_href; ?>get_rss.php?<?php echo $_SESSION['course_id']; ?>-1" />
	<?php endif; ?>
</head>
<body <?php echo $this->tmpl_onload; ?>><div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script language="JavaScript" src="<?php echo $this->tmpl_base_path; ?>overlib.js" type="text/javascript"><!-- overLIB (c) Erik Bosrup --></script>
<script language="JavaScript" src="<?php echo $this->tmpl_base_path; ?>jscripts/help.js" type="text/javascript"></script><div>

<!-- section title -->
	<h1 id="section-title"><?php echo $this->section_title; ?></h1>

<!-- top help/search/login links -->
<div align="right" id="top-links">
	<a href="<?php echo $this->tmpl_base_path; ?>search.php"><?php echo _AT('search'); ?></a> | <a href="<?php echo $this->tmpl_base_path; ?>help/index.php"><?php echo _AT('help'); ?></a>
<?php if ($_SESSION['valid_user'] && ($_SESSION['course_id'] >= 0)): ?>
	 | <a href="<?php echo $this->tmpl_base_path; ?>logout.php"><?php echo _AT('logout'); ?></a><br />
	<form method="post" action="<?php echo $this->tmpl_base_path; ?>bounce.php?p=<?php echo urlencode($this->tmpl_rel_url); ?>" target="_top">
		<label for="jumpmenu" accesskey="j"></label>
			<select name="course" id="jumpmenu" title="<?php echo _AT('jump'); ?>:  ALT-j">							
				<option value="0"><?php echo _AT('my_start_page'); ?></option>
				<optgroup label="<?php echo _AT('courses_below'); ?>">
					<?php foreach ($this->tmpl_nav_courses as $this_course_id => $this_course_title): ?>
						<?php if ($this_course_id == $_SESSION['course_id']): ?>
							<option value="<?php echo $this_course_id; ?>" selected="selected"><?php echo $this_course_title; ?></option>
						<?php else: ?>
							<option value="<?php echo $this_course_id; ?>"><?php echo $this_course_title; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</optgroup>
			</select> <input type="submit" name="jump" value="<?php echo _AT('jump'); ?>" id="jump-button" /><input type="hidden" name="g" value="22" /></form>
<?php elseif ($_SESSION['valid_user']): ?>
	 | <a href="<?php echo $this->tmpl_base_path; ?>logout.php"><?php echo _AT('logout'); ?></a><br />
<?php else: ?>
	 | <a href="<?php echo $this->tmpl_base_path; ?>login.php?course=<?php echo $_SESSION['course_id']; ?>"><?php echo _AT('login'); ?></a><br /><br />
<?php endif; ?>
</div>

<!-- back to the current section -->
	<?php if ($_SESSION['valid_user'] && ($_SESSION['course_id'] > 0)): ?>
		<a href="<?php echo $this->tmpl_base_path; ?>bounce.php?course=0" id="my-start-page">Back to My Start Page</a>
	<?php endif; ?>

<!-- the bread crumbs -->
	<div id="breadcrumbs">
		<?php echo $this->section_title; ?> : 
		<?php foreach ($this->path as $page): ?>
			<a href="<?php echo $page['url']; ?>"><?php echo $page['title']; ?></a> » 
		<?php endforeach; ?> <?php echo $this->page_title; ?>
	</div>

<!-- the main navigation. in our case, tabs -->
<table class="tabbed-table" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<th id="left-empty-tab">&nbsp;</th>
	<?php foreach ($this->top_level_pages as $page): ?>
		<?php if ($page['url'] == $this->current_top_level_page): ?>
			<th class="selected"><a href="<?php echo $page['url']; ?>"><div><?php echo $page['title']; ?></div></a></th>
			<th class="tab-spacer">&nbsp;</th>
		<?php else: ?>
			<th class="tab"><a href="<?php echo $page['url']; ?>"><div><?php echo $page['title']; ?></div></a></th>
			<th class="tab-spacer">&nbsp;</th>
		<?php endif; ?>
	<?php endforeach; ?>
	<th id="right-empty-tab">
		<?php if (FALSE && ($_SESSION['course_id'] > 0) && show_pen() && (!$_SESSION['prefs']['PREF_EDIT'])): ?>
			<a href="<?php echo $_my_uri; ?>enable=PREF_EDIT" id="editor-link" class="off"><?php echo _AT('enable_editor'); ?></a>
		<?php elseif (FALSE && ($_SESSION['course_id'] > 0) && show_pen() && ($_SESSION['prefs']['PREF_EDIT'])): ?>
			<a href="<?php echo $_my_uri; ?>disable=PREF_EDIT" id="editor-link" class="on"><?php echo _AT('disable_editor'); ?></a>
		<?php else: ?>
			<small><?php echo $this->tmpl_current_date; ?>&nbsp;</small>
		<?php endif; ?>
	</th>
	</tr>
	</table>
</div>
<!-- the sub navigation -->

<?php if ($this->sub_level_pages): ?>
	<div id="sub-navigation">
		<?php if (($_SESSION['course_id'] > 0) && show_pen()): ?>
			<!--div style="float: right; color: black;">
				Instructor tools: <a href="">Add Content</a> | <a href="">Add Test</a> | <a href="">File Manager</a> | <a href="">Properties</a>
			</div-->
		<?php endif; ?>

		<?php if (isset($this->back_to_page)): ?>
			<a href="<?php echo $this->back_to_page['url']; ?>" id="back-to">Back to <?php echo $this->back_to_page['title']; ?></a> | 
		<?php endif; ?>

		<?php $num_pages = count($this->sub_level_pages); ?>
		<?php for($i=0; $i<$num_pages; $i++): ?>
			<?php if ($this->sub_level_pages[$i]['url'] == $this->current_sub_level_page): ?>
				<strong><?php echo $this->sub_level_pages[$i]['title']; ?></strong>
			<?php else: ?>
				<a href="<?php echo $this->sub_level_pages[$i]['url']; ?>"><?php echo $this->sub_level_pages[$i]['title']; ?></a>
			<?php endif; ?>
			<?php if ($i < $num_pages-1): ?>
				|
			<?php endif; ?>
		<?php endfor; ?>
	</div>
<?php else: ?>
	<div id="sub-navigation">
		<?php if (($_SESSION['course_id'] > 0) && show_pen()): ?>
			<!--div style="float: right; color: black;">
				Instructor tools: <a href="">Add Content</a> | <a href="">Add Test</a> | <a href="">File Manager</a> | <a href="">Properties</a>
			</div-->
		<?php endif; ?>
		&nbsp;
	</div>
<?php endif; ?>

<!-- the page title -->
	<h2 id="page-title"><?php echo $this->page_title; ?></h2>
	<!-- div style="float: right">
	<a href="/svn/atutor/redesign/docs/?cid=123;g=7" accesskey="8" title="Previous: 5.7 Accessibility Features Alt-8"><img src="/svn/atutor/redesign/docs/images/previous.gif" class="menuimage" alt="Previous: 5.7 Accessibility Features" border="0" height="25" width="28"></a>  <a href="/svn/atutor/redesign/docs/?cid=117;g=7" accesskey="9" title="Next: 5.1 Register Alt-9"><img src="/svn/atutor/redesign/docs/images/next.gif" class="menuimage" alt="Next: 5.1 Register" border="0" height="25" width="28"></a>&nbsp;&nbsp;
	</div-->
	<!--
	<script type="text/javascript">
	if (document.getElementById) {
		document.writeln('<div id=\'toctoggle\'>[<a href="javascript:toggleToc()" class="internal">' +
		'<span id="showlink" style="display:none;">show</span>' +
		'<span id="hidelink">hide</span>'
		+ '</a>]</div>');
	}
	</script></div>

	<h3 id="help-title">Help</h3>
	<div id="help">
		<p>this is a help message.</p>
		<p>More help goes here..</p>
		<p>And here</p>
	</div>
	-->
<a name="content"></a>
<?php global $msg; $msg->printAll(); ?>