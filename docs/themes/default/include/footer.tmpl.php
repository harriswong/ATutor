<?php if (!defined('AT_INCLUDE_PATH')) { exit; } ?>

	<?php if ($_SESSION['course_id'] > 0): ?>
		<br /><div align="right" style="vertical-align:bottom;padding-right:3px;font-size:smaller;"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>#content" style="border: 0px;" title="<?php echo _AT('goto_content'); ?> Alt-c" ><?php echo _AT('goto_top'); ?></a></div>  
	<?php endif; ?>
	<br />
	<div align="right">
		<?php if ($this->sequence_links['resume']): ?>
				<a style="color:white;" href="<?php echo $this->sequence_links['resume']['url']; ?>" accesskey="."><img src="<?php echo $this->base_path; ?>images/resume.gif" border="0" title="<?php echo _AT('resume').': '.$this->sequence_links['resume']['title']; ?>" alt="<?php echo $this->sequence_links['resume']['title']; ?>"></a>

		<?php else: ?>
			<a href="<?php echo $this->sequence_links['previous']['url']; ?>" title="<?php echo _AT('previous_topic').': '. $this->sequence_links['previous']['title']; ?>" accesskey=","><img src="<?php echo $this->base_path; ?>images/previous.gif" border="0"></a>

			<a href="<?php echo $this->sequence_links['next']['url']; ?>" title="<?php echo _AT('next_topic').': '.$this->sequence_links['next']['title']; ?>" accesskey="."><img src="<?php echo $this->base_path; ?>images/next.gif" border="0"></a>
		<?php endif; ?>
	</div>
	</td>
	<?php if (($_SESSION['course_id'] > 0) && $this->side_menu): ?>
		<td valign="top">
		<script type="text/javascript">
		//<![CDATA[
		var state = getcookie("side-menu");
		if (state && (state == 'none')) {
			document.writeln('<div style="display:none;" id="side-menu">');
		} else {
			document.writeln('<div style="" id="side-menu">');
		}
		//]]>
		</script>
			<?php foreach ($this->side_menu as $dropdown_file): ?>
				<?php require(AT_INCLUDE_PATH . 'html/dropdowns/' . $dropdown_file . '.inc.php'); ?>
			<?php endforeach; ?>
		<script type="text/javascript">
		//<![CDATA[
			document.writeln('</div>');
		//]]>
		</script>
		</td>
	<?php endif; ?>
</tr>
<tr>
	<td colspan="2">
		<br /><br />
		<?php require(AT_INCLUDE_PATH.'html/languages.inc.php'); ?>
		<?php require(AT_INCLUDE_PATH.'html/copyright.inc.php'); ?>
		<br />
	</td>
</tr>
</table>
</body>
</html>