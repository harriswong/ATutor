<?php
/*******
 * doesn't allow this file to be loaded with a browser.
 */
if (!defined('AT_INCLUDE_PATH')) { exit; }

/******
 * this file must only be included within a Module obj
 */
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

/*******
 * assign the instructor and admin privileges to the constants.
 */
define('AT_PRIV_ECOMM',       $this->getPrivilege());
define('AT_ADMIN_PRIV_ECOMM', $this->getAdminPrivilege());


/*******
 * add the admin pages when needed.
 */
if (admin_authenticate(AT_ADMIN_PRIV_ECOMM, TRUE) || admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
	$this->_pages[AT_NAV_ADMIN] = array('mods/ecomm/index_admin.php');
	$this->_pages['mods/ecomm/index_admin.php']['title_var'] = 'ec_payments';
	$this->_pages['mods/ecomm/payments_admin.php']['title_var'] = 'ec_payments_received';
	$this->_pages['mods/ecomm/payments_admin.php']['parent']   = 'mods/ecomm/index_admin.php';
	$this->_pages['mods/ecomm/payments_export_csv.php']['title_var'] = 'ec_payments';
	$this->_pages['admin/enrollment/index.php']['children'] =array('mods/ecomm/payments_admin.php');
	$this->_pages['mods/ecomm/index_admin.php']['children'] = array('mods/ecomm/payments_admin.php','admin/enrollment/index.php');
	$this->_pages['mods/ecomm/index_admin.php']['parent']    = AT_NAV_ADMIN;
}

/*******
 * instructor Manage section:
 */

$this->_pages['mods/ecomm/index_instructor.php']['title_var'] = 'ec_payments';
$this->_pages['mods/ecomm/index_instructor.php']['parent']   = 'tools/index.php';
$this->_pages['mods/ecomm/index_instructor.php']['children'] = array('tools/enrollment/index.php');
$this->_pages['tools/enrollment/index.php']['children'] = array('mods/ecomm/index_instructor.php');


/*******
 * student page.
 */
$this->_pages['mods/ecomm/index.php']['title_var'] = 'ec_payments';
$this->_pages['mods/ecomm/index.php']['img']       = 'mods/ecomm/ecomm.jpg';
$this->_pages['mods/ecomm/payment.php']['title_var'] = 'ec_payments';
$this->_pages['mods/ecomm/failure.php']['title_var'] = 'ec_payments';
$this->_pages['mods/ecomm/invoice.php']['title_var'] = 'ec_payments';



/* my start page pages */
$this->_pages[AT_NAV_START]  = array('mods/ecomm/index_mystart.php');
$this->_pages['mods/ecomm/index_mystart.php']['title_var'] = 'ec_payments';
$this->_pages['mods/ecomm/index_mystart.php']['parent'] = AT_NAV_START;

?>