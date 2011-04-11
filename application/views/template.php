<?php
$this->load->helper('url');
$this->load->helper('html');
$this->load->library('session');
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$ptitle?></title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
<?php echo  link_tag('blueprint/screen.css'); ?>
<?php echo  link_tag('blueprint/'.$style); ?>
</head>
<body>
<div class='header'>OG Server Controller: <a href="<?= base_url() ?>index.php/trinfo">Torrent manager</a> | <a href="<?= base_url() ?>index.php/dwndr"> MU Downloader </a> | About | @<?= $this->session->userdata('username');?> <a href="<?= base_url();?>index.php/login/gtfo">GTFO!</a></div>
<div class='container'>
<?= $contents ?>
</div>
<div class='footer header'>Get yours <a href="https://github.com/psyrax/HomeMediaServerControl">Code</a> | info @ <a href="http://oglabs.info">OGLabs</a></div>
</body>
</html>