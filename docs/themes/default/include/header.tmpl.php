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

global $system_courses, $_base_path;


$_pages[0] = array('users/index.php', 'users/profile.php', 'users/preferences.php', 'users/inbox.php', 'index.php', 'tools/index.php');

$_pages['users/index.php']   = array('title' => _AT('my_courses'),      'parent' => 0, 'children' => array('users/browse.php', 'users/create_course.php'));
$_pages['users/browse.php']  = array('title' => _AT('browse_courses'),  'parent' => 'users/index.php');
$_pages['users/create_course.php']  = array('title' => _AT('create_course'),   'parent' => 'users/index.php');
$_pages['users/profile.php']     = array('title' => _AT('profile'),     'parent' => 0);
$_pages['users/preferences.php'] = array('title' => _AT('preferences'), 'parent' => 0);
$_pages['users/inbox.php']       = array('title' => _AT('inbox'),       'parent' => 0, 'children' => array('users/send_message.php'));
$_pages['users/send_message.php'] = array('title' => _AT('send_message'), 'parent' => 'users/inbox.php');

$_pages['index.php'] = array('title' => _AT('home'), 'parent' => 0);
$_pages['tools/index.php'] = array('title' => _AT('tools'), 'parent' => 0, 'children' => array('forum/list.php'));

$_pages['forum/list.php'] = array('title' => _AT('forums'), 'parent' => 'tools/index.php');

$current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));

if (in_array($current_page, $_pages[0])) {
	$_current_top_level_page = $_current_sub_level_page = $current_page;

	if (isset($_pages[$current_page]['children'])) {
		$_sub_level_pages[] = array('url' => $current_page, 'title' => $_pages[$current_page]['title']);
		foreach ($_pages[$_current_top_level_page]['children'] as $child) {
			$_sub_level_pages[] = array('url' => $child, 'title' => $_pages[$child]['title']);
		}
	}
} else {
	$parent = $_pages[$current_page]['parent'];

	if (in_array($parent, $_pages[0])) {
		$_current_top_level_page = $parent;
		$_current_sub_level_page = $current_page;

		if (isset($_pages[$parent]['children'])) {
			$_sub_level_pages[] = array('url' => $parent, 'title' => $_pages[$parent]['title']);
			foreach ($_pages[$parent]['children'] as $child) {
				$_sub_level_pages[] = array('url' => $child, 'title' => $_pages[$child]['title']);
			}
		}
	}
}

$_page_title = $_pages[$current_page]['title'];


if (!$_SESSION['course_id']) {

	/*
	debug($_sub_level_pages, '_sub_level_pages');
	debug($_current_top_level_page, '_current_top_level_page');
	debug($_current_sub_level_page, '_current_sub_level_page');
	debug($_top_level_pages, '_top_level_pages');
	*/

	$_top_level_pages[] = array('url' => 'users/index.php',       'title' => _AT('my_courses'));
	$_top_level_pages[] = array('url' => 'users/profile.php',     'title' => _AT('profile'));
	$_top_level_pages[] = array('url' => 'users/preferences.php', 'title' => _AT('preferences'));
	$_top_level_pages[] = array('url' => 'users/inbox.php',       'title' => _AT('inbox'));


	$_section_title = 'My Start Page';

} else {
	$_top_level_pages[] = array('url' => 'index.php',                   'title' => _AT('home'));
	$_top_level_pages[] = array('url' => 'tools/index.php',             'title' => _AT('tools'));
	$_top_level_pages[] = array('url' => 'resources/links/index.php',   'title' => _AT('links'));
	$_top_level_pages[] = array('url' => 'forum/list.php',              'title' => _AT('forums'));
	$_top_level_pages[] = array('url' => 'discussions/achat/index.php', 'title' => _AT('chat'));
	$_top_level_pages[] = array('url' => 'discussions/polls.php',       'title' => _AT('polls'));
	
	$_section_title = $_SESSION['course_title'];
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?php echo $this->tmpl_lang; ?>">
<head>
	<title><?php echo $this->tmpl_title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->tmpl_charset; ?>" />
	<meta name="Generator" content="ATutor - Copyright 2005 by http://atutor.ca" />
	<base href="<?php echo $this->tmpl_content_base_href; ?>" />
	<link rel="shortcut icon" href="<?php echo $this->tmpl_base_path; ?>favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path; ?>print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path.'themes/'.$this->tmpl_theme; ?>/styles.css" type="text/css" />
	<?php echo $this->tmpl_rtl_css; ?>
	<style type="text/css"><?php echo $this->tmpl_banner_style; ?></style>
	<?php if ($system_courses[$_SESSION['course_id']]['rss']): ?>
	<link rel="alternate" type="application/rss+xml" title="ATutor course - RSS 2.0" href="<?php echo $this->tmpl_base_href; ?>get_rss.php?<?php echo $_SESSION['course_id']; ?>-2" />
	<link rel="alternate" type="application/rss+xml" title="ATutor course - RSS 1.0" href="<?php echo $this->tmpl_base_href; ?>get_rss.php?<?php echo $_SESSION['course_id']; ?>-1" />
	<?php endif; ?>
</head>
<body <?php echo $this->tmpl_onload; ?>><div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script language="JavaScript" src="<?php echo $this->tmpl_base_path; ?>overlib.js" type="text/javascript"><!-- overLIB (c) Erik Bosrup --></script>
<script>

function toggleToc() {
	//var tocmain = document.getElementById('help');
	var toc = document.getElementById('help');
	var showlink=document.getElementById('showlink');
	var hidelink=document.getElementById('hidelink');
	if(toc.style.display == 'none') {
		toc.style.display = tocWas;
		hidelink.style.display='';
		showlink.style.display='none';
		//tocmain.className = '';

		var help = document.getElementById('help-title');
		help.className = '';

	} else {
		tocWas = toc.style.display;
		toc.style.display = 'none';
		hidelink.style.display='none';
		showlink.style.display='';
		//tocmain.className = 'tochidden';

		var help = document.getElementById('help-title');
		help.className = 'line';
	}
}

</script>
<div id="search-help-links">
	<a href="">Search</a> | <a href="">Help</a><br><form><select><option>course name</option></select><input type="submit" value="jump"></form>
</div>

<?php if ($_SESSION['course_id']): ?>
	<a href="<?php echo $_base_path; ?>bounce.php?course=0" id="my-start-page">[x] Back to My Start Page</a>
<?php endif; ?>

<h1><?php echo $_section_title; ?></h1>

<div id="breadcrumbs">
	<?php echo $_section_title; ?> : <a href="tools.html">Tools</a> > Tests &amp; Surveys
</div>

<table class="tabbed-table" align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
	<th id="left-empty-tab">&nbsp;</th>
	<?php foreach ($_top_level_pages as $page): ?>
		<?php if ($page['url'] == $_current_top_level_page): ?>
			<th class="selected"><?php echo $page['title']; ?></th>
			<th class="tab-spacer">&nbsp;</th>
		<?php else: ?>
			<th class="tab"><a href="<?php echo $_base_path . $page['url']; ?>"><div><?php echo $page['title']; ?></div></a></th>
			<th class="tab-spacer">&nbsp;</th>
		<?php endif; ?>
	<?php endforeach; ?>
	<th id="right-empty-tab">&nbsp;</th>
</tr>
<tr>
	<td colspan="<?php echo count($_top_level_pages) *2 +2; ?>" class="content">
	<div id="sub-navigation">
		<?php if (isset($_sub_level_pages)): ?>
			<?php foreach ($_sub_level_pages as $page): ?>
				<?php if ($page['url'] == $_current_sub_level_page): ?>
					<strong><?php echo $page['title']; ?></strong>
				<?php else: ?>
					<a href="<?php echo $_base_path . $page['url']; ?>"><?php echo $page['title']; ?></a>
				<?php endif; ?>
				|
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<h2 id="page-title"><?php echo $_page_title; ?></h2>
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
		<p>this is a help message. what do you think?</p>
		<p>this is a really long stupid help message.</p>
	</div>
	

<?php return; ?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" id="maintable" summary="">
<tr>
	<td id="top-heading" style="background-image: url('<?php echo $this->tmpl_base_path . HEADER_IMAGE; ?>'); background-repeat: no-repeat; background-position: 0px 0px;" nowrap="nowrap" align="right" valign="top">
		<table border="0" align="right" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td align="right"><?php echo $this->tmpl_bypass_links; ?><br /><br />
					<?php if (HEADER_LOGO): ?>
						<img src="<?php echo $this->tmpl_base_path.HEADER_LOGO; ?>" border="0" alt="<?php echo SITE_NAME; ?>" />&nbsp;
					<?php endif; ?>
					<br /><h4><?php echo stripslashes(SITE_NAME); ?>&nbsp;</h4><br />
				</td>
				<td align="left" class="login-box">
					� <small><?php echo _AT('logged_in_as'); ?>: <?php echo $this->tmpl_user_name; ?>&nbsp;<br /></small>
					� <small><?php echo $this->tmpl_log_link; ?></small>
				</td>
			</tr>	
		</table>
	</td>
</tr>
<tr>
	<td class="cyan">
	<!-- page top navigation links: -->
	<table border="0" cellspacing="0" cellpadding="0" align="right" class="navmenu">
		<tr>			
			<?php foreach ($this->tmpl_user_nav as $page => $link): ?>
				<?php if ($page == 'jump_menu'): ?>
					<!-- course select drop down -->
					<td align="right" valign="middle" class="navmenu"><form method="post" action="<?php echo $this->tmpl_base_path; ?>bounce.php?p=<?php echo urlencode($this->tmpl_rel_url); ?>" target="_top"><label for="jumpmenu" accesskey="j"></label>
						<select name="course" id="jumpmenu" title="<?php echo _AT('jump'); ?>:  ALT-j">							
							<option value="0"><?php echo _AT('my_courses'); ?></option>
							<?php if ($_SESSION['valid_user']): ?>								
								<optgroup label="<?php echo _AT('courses_below'); ?>">
									<?php foreach ($this->tmpl_nav_courses as $this_course_id => $this_course_title): ?>
										<?php if ($this_course_id == $_SESSION['course_id']): ?>
											<option value="<?php echo $this_course_id; ?>" selected="selected"><?php echo $this_course_title; ?></option>
										<?php else: ?>
											<option value="<?php echo $this_course_id; ?>"><?php echo $this_course_title; ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>
						</select>&nbsp;<input type="submit" name="jump" value="<?php echo _AT('jump'); ?>" id="jump-button" /><input type="hidden" name="g" value="22" /></form></td>
					<!-- end course select drop down -->

				<?php else: ?>

					<!-- regular menu item -->			

					<?php if ($this->tmpl_page == $page): ?>
						<td valign="middle" class="navmenu selected" onclick="window.location.href='<?php echo $link['url'];?>'">
						<?php if (!$this->tmpl_main_text_only && $link['image']): ?>
							<a href="<?php echo $link['url']; ?>" <?php echo $link['attributes']; ?>><img src="<?php echo $link['image']; ?>" alt="<?php echo $link['name']; ?>" title="<?php echo $link['name']; ?>" class="menuimage17" border="0" /></a>
						<?php endif; ?>
						<?php if (!$this->tmpl_main_icons_only): ?>
							<small><a href="<?php echo $link['url'] ?>" <?php echo $link['attributes']; ?>><?php echo $link['name'] ?></a></small>
						<?php endif; ?>	
						
						</td>

					<?php else: ?>
						<td valign="middle" class="navmenu" onmouseover="this.className='navmenu selected';" onmouseout="this.className='navmenu';" onclick="window.location.href='<?php echo $link['url'];?>'">
						<?php if (!$this->tmpl_main_text_only && $link['image']): ?>
							<a href="<?php echo $link['url']; ?>" <?php echo $link['attributes']; ?>><img src="<?php echo $link['image'] ?>" alt="<?php echo $link['name']; ?>" title="<?php echo $link['name']; ?>" class="menuimage17" border="0" /></a>
						<?php endif; ?>
						<?php if (!$this->tmpl_main_icons_only): ?>
							<small><a href="<?php echo $link['url'] ?>" <?php echo $link['attributes']; ?>><?php echo $link['name'] ?></a></small>
						<?php endif; ?>	
						</td>
					<?php endif; ?>

					<!-- end regular menu item -->

				<?php endif; ?>
			<?php endforeach; ?>
		</tr>
		</table></td>
</tr>
<!-- the course banner (or section title) -->
	<tr> 
		<td id="course-banner"><?php echo $this->tmpl_section; ?></td>
	</tr>
<!-- end course banner -->

<!-- course navigation elements: ( course nav links, instructor nav links) -->
<?php if ($this->tmpl_course_nav): ?>
	<tr>
		<td id="course-nav"><a name="navigation"></a>
		<!-- course navigation links: -->
		<table border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>			
				<?php foreach ($this->tmpl_course_nav as $page => $link): ?>
					<!-- regular menu item -->					
					<td class="cat2" valign="top" nowrap="nowrap" onclick="window.location.href='<?php echo $link['url'];?>'">				
					<?php if (!$this->tmpl_course_text_only): ?>
						<a href="<?php echo $link['url']; ?>" <?php echo $link['attribs']; ?>><img src="<?php echo $link['image'] ?>" alt="<?php echo $link['title']; ?>" title="<?php echo $link['title']; ?>" class="menuimage" border="0" /></a>
					<?php endif; ?>
					<?php if (!$this->tmpl_course_icons_only): ?>
						<small><a href="<?php echo $link['url']; ?>" <?php echo $link['attribs']; ?> title="<?php echo $link['title']; ?>" ><?php echo $link['name'] ?></a></small>
					<?php endif; ?>
					</td>
					<td width="10"></td>
					<!-- end regular menu item -->
				<?php endforeach; ?>
			</tr>
		</table>
		<!-- end course navigation links -->
		</td>
	</tr>
<?php endif; ?>
<!-- end course navigation elements -->
<!-- the breadcrumb navigation -->
<?php if ($this->tmpl_breadcrumbs): ?>
	<tr>
		<td valign="middle" class="breadcrumbs">
				<?php foreach($this->tmpl_breadcrumbs as $item): ?>
					<?php if ($item['link']): ?>
						<a href="<?php echo $item['link']; ?>" class="breadcrumbs"><?php echo $item['title']; ?></a> � 
					<?php else: ?>
						<!-- the last item in the list is not a link. current location -->
						<?php echo $item['title']; ?>
					<?php endif; ?>
				<?php endforeach; ?>
		</td>
	</tr>
<?php endif; ?>
<!-- end the breadcrumb navigation -->
<tr>
	<td><a name="content"></a>