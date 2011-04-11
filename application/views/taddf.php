<?php

$this->load->helper('form');
echo form_open('tadd');
echo form_input('torrenturl', 'urlplz');
echo form_submit('tadddo', 'Download!');
?>