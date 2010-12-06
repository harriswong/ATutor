/* The javascript is used in module.php @ $this->_content_tools["js"] */

/*global jQuery*/
/*global ATutor */
/*global tinyMCE */
/*global window */

ATutor = ATutor || {};
ATutor.mods = ATutor.mods || {};
ATutor.mods.basiclti = ATutor.mods.basiclti || {};

(function () {
    var basicLTIOnClick = function () {
    	alert("Clicked on Basic LTI tool icon!");
    }
    
	//set up click handlers and show/hide appropriate tools
    var initialize = function () {
        jQuery("#basiclti_tool").click(basicLTIOnClick);
    };
    
    jQuery(document).ready(initialize);
})();
