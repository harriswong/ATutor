<?php if (!defined('AT_INCLUDE_PATH')) { exit; } ?>

		<?php if ($_SESSION['course_id'] > 0): ?>
			<div style="text-align:right">		
				<span style="font-size:smaller;padding-right:3px;"><a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES); ?>#content" title="<?php echo _AT('goto_content'); ?> Alt-c" ><?php echo _AT('goto_top'); ?></a>	</span>
			</div>  
		<?php endif; ?> 
	</div>
</div>

<div id="footer">
		<?php require(AT_INCLUDE_PATH.'html/languages.inc.php'); ?>
		<?php require(AT_INCLUDE_PATH.'html/copyright.inc.php'); ?>
</div>

</body>
</html> 