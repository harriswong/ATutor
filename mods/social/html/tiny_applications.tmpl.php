<!--  compressed with java -jar {$path}/yuicompressor-2.3.5.jar -o {$file}-min.js {$file}.js -->
<script type="text/javascript"
	src="<?php echo AT_SHINDIG_URL; ?>/gadgets/js/rpc.js?c=1"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js"></script>
<script type="text/javascript" src="mods/social/lib/js/container.js"></script>

<?php	
	foreach ($this->list_of_my_apps as $id=>$app_obj): 
?>
<div class="gadget_wrapper">
<div class="gadget_title_bar"><?php echo $app_obj->getTitle(); ?></div>
<div class="gadget_container" style="padding:0.5em;">
<?php
	//the name and id here in the iframe is used by the container.js to identify the caller.
	//Simply, the id is used here to generate the $(this.f)
	//Originally it was using the ModID, I changed it to appId.
	//@harris
?>
	<iframe 
	scrolling="<?=$this->gadget['scrolling'] || $this->gadget['scrolling'] == 'true' ? 'yes' : 'no'?>"
	height="<?php echo $app_obj->getHeight();?>px" width="100%"
	frameborder="no" src="<?php echo $app_obj->getIframeUrl($_SESSION['member_id'], 'profile', $_GET['appParams']);?>" class="gadgets-gadget"
	name="remote_iframe_<?php echo $app_obj->getId(); ?>"
	id="remote_iframe_<?php echo $app_obj->getId(); ?>"></iframe>	
</div></div>
<?php endforeach; ?>