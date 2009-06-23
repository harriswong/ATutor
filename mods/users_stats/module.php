<?php
if (!defined('AT_INCLUDE_PATH')) { exit; }
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

// if this module is to be made available to students on the Home or Main Navigation
$_student_tool = 'mods/users_stats/index.php';

$this->_pages['mods/users_stats/index.php']['title_var'] = 'users_statistics';
$this->_pages['mods/users_stats/index.php']['parent']    = 'tools/index.php';
$this->_pages['mods/users_stats/index.php']['img']       = 'mods/users_stats/images/users.jpg';


$_pages['mods/users_stats/users_stats1.php']['parent'] = 'mods/users_stats/index.php';

$_pages['mods/users_stats/tests.php']['parent'] = 'mods/users_stats/user_stats2.php';
?>