<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>variety42.com</title>
	<meta name="description" content="variety42.com">

	<meta property="og:title" content="variety42.com">
	<meta property="og:type" content="website" />
	<meta property="og:description" content="variety42.com">
	<meta property="og:image" content="https://variety42.com<?php echo $config->folder; ?>/variety42_share.jpg">
	<meta property="fb:app_id" content="616440488939771"/>

	<link rel="shortcut icon" href="/favicon.png" />
	<link rel="stylesheet" href="data/css/main.css" />

	<script type="text/javascript" src="data/js/main.js"></script>
</head>

<body>
    <a href="/jokes/">glume</a>
</body>
</html>
<?php
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Location: /jokes', true, 307 );
exit;
?>
