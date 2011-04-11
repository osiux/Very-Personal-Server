<?php
$this->load->helper('url');
$this->load->helper('number');
/** Torrent Information
*
*/
function s2dhs($s){
		$d = intval($s/86400);
		$s -= $d*86400;

		$h = intval($s/3600);
		$s -= $h*3600;

		$m = intval($s/60);
		$s -= $m*60;

		if ($d) $str = $d . 'd ';
		if ($h) $str .= $h . 'h ';
		if ($m) $str .= $m . 'm ';
		if ($s) $str .= $s . 's';
		return $str;
	}
$rtime=s2dhs($stats['cumulative-stats']['secondsActive']);

$colorvar=0;
arsort($torrents);
?>
<br />
<img src="<?= base_url()?>blueprint/logo.png" />
<br />
<div class='tlist'>
<?php 
foreach ($torrents as $tinfo):
		$ttotal=$tinfo['sizeWhenDone']/1073741824;
		$tgot=$tinfo['haveValid']/1073741824;
		$adate=$tinfo['addedDate'];
		$ddate='';
		$ptitime=$tinfo['name'];
		//if (strlen($ptitime)>45) $ptitime=substr($tinfo['name'],0,45)." [...]";
		//if ($tinfo['desiredAvailable']>0)
		//$ddate=strftime("%w %B %G", $tinfo['desiredAvailable']);
		$tinfodown=$tinfo['rateDownload']/1024;
		$tinfoup=$tinfo['rateUpload']/1024;
		$tleft=$tinfo['leftUntilDone']/1073741824;
		$scurl=FALSE;
		switch($tinfo['status']):
		case 1:
		$status='Checking';
		break;	
		case 2:
		$status='Await Check';
		break;
		case 4:
		$status='Downloading';
		$scurl=site_url('trinfo/tstop').'/'.$tinfo['id'];
		$scname='<img src=\''.base_url().'blueprint/stop.png\' border=\'0\'>';
		break;
		case 8:
		$status='Seeding';
		$scurl=site_url('trinfo/tstop').'/'.$tinfo['id'];
		$scname='<img src=\''.base_url().'blueprint/stop.png\' border=\'0\'>';
		break;
		case 16:
		$status='Stopped';
		$scurl=site_url('trinfo/tstart').'/'.$tinfo['id'];
		$scname='<img src=\''.base_url().'blueprint/play.png\' border=\'0\'>';
		break;
		default:
		$status='It\'s a mistery';
		endswitch;
		$tremove=site_url('trinfo/tremove').'/'.$tinfo['id'];
		$tdestroy=site_url('trinfo/tdestroy').'/'.$tinfo['id'];
		if ($colorvar==0)$colorvar=1;
		switch ($colorvar):
		case 1:
		$scotch=base_url().'blueprint/gback.png';
		break;
		case 2:
		$scotch=base_url().'blueprint/oback.png';
		break;
		case 3:
		$scotch=base_url().'blueprint/pback.png';
		break;
		case 4:
		$scotch=base_url().'blueprint/bback.png';
		break;
		endswitch;
?>


<div class='tcontainer'>
<img src="<?= $scotch ?>" class='fx' />
<div class='titem var<?= $colorvar?>'>
	<div class="titemt"><?= $ptitime ?></div>
	
	Status: <span class="tval"><?= $status ?></span><br />
	<span class="tval"><?= $tinfo['percentDone']*100?>%&nbsp;(<?= number_format($tgot,2,'.','')?>GB/<?= number_format($ttotal,2,'.','')?>GB) </span><br />
	Left: <span class="tval"><?=  number_format($tleft,2,'.','')?> </span> GB<br />
	D: <span class="tval"><?= number_format($tinfodown,2,'.','')?>KB</span>
	U: <span class="tval"><?= number_format($tinfoup,2,'.','')?>KB</span>
	<br />
	Added: <span class="tval"><?= strftime("%w %B %G", $adate)?> <?= $ddate ?></span>
</div>
	<div class='tplay'>
	<?php if ($scurl!=FALSE):?>
	<a href='<?= $scurl ?>'><?= $scname ?></a>
	<?php endif ?>
	<a href='<?= $tremove ?>'><img src="<?= base_url()?>blueprint/remove.png" border="0"></a>&nbsp;<a href='<?= $tdestroy ?>'><img src="<?= base_url()?>blueprint/delete.png" border="0"></a>
	</div>
</div>
<?php 
$colorvar++;
if ($colorvar>4)$colorvar=0;
endforeach;
	$tdown=byte_format($stats['cumulative-stats']['downloadedBytes']);
	$tup=$stats['cumulative-stats']['uploadedBytes']/1073741824;
	$cdown=$stats['downloadSpeed']/1024;
	$cup=$stats['uploadSpeed']/1024;
	?>
</div>
<div class='tcontrol'>
 <div class ='tinfog'>INFO</div>
	<div class='tinfoc'>
	Down: <span class='tcspan'><?=  number_format($cdown,2,'.','') ?>KB/s </span> | 
	Up: <span class='tcspan'><?=  number_format($cup,2,'.','') ?>KB/s<br /></span>
	Total Download:  <span class='tcspan'><?= $techo ?> <br /></span>
	Total Upload: <span class='tcspan'><?= number_format($tup,2,'.','')?> GB<br /></span>
	Up Time: <span class='tcspan'><?= $rtime ;?><br /></span>
	<div class="tmotion">
	<a href='<?= site_url('trinfo/tspeed/slow')?>'><img src="<?= base_url()?>blueprint/slow.png" border="0" /></a> <a href='<?= site_url('trinfo/tspeed/speed')?>'><img src="<?= base_url()?>blueprint/speed.png" border="0" /></a>
	</div>
	</div>
<div class='tinfod'>
	ADD
	</div>
	<div class='tinfoc'>
	GET SOME MORE TORRENTS
	<div class='addform'>
	<?php
	/** Torrent Add
	*
	*/
	echo $formop.$formin.$formsu;
	?>
	</form>
	</div>
	</div>
	<div class="tinfof">
	Usage: <hr />
	<img src="<?= base_url()?>blueprint/stop.png" /> Stop Downloading Torrent<br />
	<img src="<?= base_url()?>blueprint/play.png" /> Resume Torrent Downloading<br />
	<img src="<?= base_url()?>blueprint/remove.png" /> Delete Torrent<br />
	<img src="<?= base_url()?>blueprint/delete.png" /> Delete Torrent and Files <br />
	<img src="<?= base_url()?>blueprint/slow.png" /> Enable Speed Limit<br />
	<img src="<?= base_url()?>blueprint/speed.png" /> Disable Speed Limit<br />
	<br />
	Credits:<hr />
	Drop Box Icon by <a class='tinfofl' target='_blank' href='http://2Shi.deviantart.com/'>*2Shi</a> @deviantart<br />
	Icons Supratim Nayak / <a class='tinfofl' target='_blank' href='http://iconebula.com'>iconebula.com</a><br />
	<a href="http://ipapun.deviantart.com/art/Devine-Icons-137555756?q=boost%3Apopular%20in%3Acustomization%2Ficons%2Fdock&qo=44">Devine Icons</a><br />
	Interface by <a class='tinfofl' target='_blank' href='http://funkymonstruoslab.net'>funkymonstruoslab.net</a>
	</div>
</div>
