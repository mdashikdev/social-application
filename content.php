<?php 
if (isset($_REQUEST["page"])) {
	$page=trim($_REQUEST["page"]);
	if (file_exists($page)) {
		include($page);
	}
}
?>