<?php
if(isset($_GET['file']) && !empty($_GET['file'])) {
	$file = $_GET['file'];
	if (file_exists($file)) {
		if (ob_get_level()) {
			ob_end_clean();
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		sleep(10);
		unlink($file);
		exit;
	} else die();
}