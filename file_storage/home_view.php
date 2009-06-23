<?php
/* Il file viene richiamato per la gestione rapida dei file direttamente da home-page.
* Nel caso in cui il corso preveda la possibilità di offrire il download o la visualizzaione di file, quest'ultimi potranno direttamente essere "manipolati" dalla home-page.
* Naturalmente la sezione della home page consentirà il download o la visualizzazione solo dei singoli file, non di intere cartelle. Per questa ultima operazione sarà necessario entrare nella sezione specifica.
*/
define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'lib/file_storage.inc.php');

//viene passato il solo id del file che si vuole visualizzare o scaricare
if(isset($_GET['fid'])){
	$file_id = ($_GET['fid']);
	$sql = "SELECT file_name, file_size FROM ".TABLE_PREFIX."files WHERE file_id=$file_id";
	$result = mysql_query($sql, $db);
	if ($row = mysql_fetch_assoc($result)) {
		$ext = fs_get_file_extension($row['file_name']);

		if (isset($mime[$ext]) && $mime[$ext][0]) {
			$file_mime = $mime[$ext][0];
		} else {
			$file_mime = 'application/octet-stream';
		}
		$file_path = fs_get_file_path($file_id) . $file_id;

		ob_end_clean();
		header("Content-Encoding: none");
		header('Content-Type: ' . $file_mime);
		header('Content-transfer-encoding: binary'); 
		header('Content-Disposition: attachment; filename="'.htmlspecialchars($row['file_name']).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.$row['file_size']);

		// see the note in get.php about the use of x-Sendfile
		header('x-Sendfile: '.$file_path);
		header('x-Sendfile: ', TRUE); // if we get here then it didn't work

		@readfile($file_path);
		exit;
	}
}
?>
