<?php
$this->load->helper('url');
$this->load->helper('html');
$this->load->library('session');
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$ptitle?></title>
<link href='http://fonts.googleapis.com/css?family=Covered+By+Your+Grace' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<?php echo  link_tag('blueprint/screen.css'); ?>
<?php echo  link_tag('blueprint/login.css'); ?>
</head>
<body>
<div class='dline'>
Home Media Server Control <br />
<div class='lform'>
<?=$formop.$formin.$formps.$formsu; ?></form>
</div>
</div>
</body>
</html>