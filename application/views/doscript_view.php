<?php
$this->load->helper('form');

?>
<div class='scripts' >
	<span class='namedwnld'>Written Output</span><hr />
	<span class='namedwnld'>Title: <?= $downdir ?>
	</span><hr />
	<span class='namedwnld'>Script: <?= $writepath.$runfile ?></span><hr />
	<pre>
<?= $runscript ?>
	</pre>
	<span class='namedwnld'>List: <?= $writepath.$namefile ?></span><hr />
	<pre>
<?= $links ?>
	</pre>
	<div class='linkdown'>
		<?php 
		echo form_open('dwnldr/do_stuff');
		echo form_hidden('runfile', $runfile);
		echo form_hidden('writepath', $writepath);
		echo form_hidden('linkfile', $namefile);
		echo form_hidden('downdir', $downdir);
		echo form_submit('dostuff', 'Do Stuff');
		echo form_close();
		?>
	</div>
</div>