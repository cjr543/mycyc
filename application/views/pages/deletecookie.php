<?php
	$this->load->helper('cookie');
	delete_cookie("mycyc_xiaoqu");
	header('Location: /mycyc');
?>