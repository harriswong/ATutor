<?php if (!defined('AT_INCLUDE_PATH')) { exit; } ?>

<?php if (($_SESSION['course_id'] > 0) && $this->side_menu): ?>
<div id="main-side">
	<script type="text/javascript">
	//<![CDATA[
	var state = getcookie("side-menu");
	if (state && (state == 'none')) {
		document.writeln('<a name="menu"></a><div style="display:none;" id="side-menu">');
	} else {
		document.writeln('<a name="menu"></a><div style="" id="side-menu">');
	}
	//]]>
	</script>
	<?php foreach ($this->side_menu as $dropdown_file): ?>
		<?php if (file_exists($dropdown_file)) { require($dropdown_file); } ?>
	<?php endforeach; ?>
	<script type="text/javascript">
	//<![CDATA[
		document.writeln('</div>');
	//]]>
	</script>
</div>
<?php endif; ?>

