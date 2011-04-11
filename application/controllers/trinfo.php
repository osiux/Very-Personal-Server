<?php

class Trinfo extends CI_Controller {
      
       public function __construct()
       {
            parent::__construct();
        $this->load->library('session');
        $this->load->library('TransmissionRPC');
        $this->transmissionrpc->return_as_array = true;
        $this->transmissionrpc->url =$this->config->item('vps_torrent_url');
        $this->load->helper('url');
        $this->load->helper('number');
        $logined = $this->session->userdata('logined');
        if ($logined!=TRUE):
        redirect(base_url(), 'refresh');
        endif;
       }
	
    function index() { 

        $listaar=$this->transmissionrpc->get();
        $statsar=$this->transmissionrpc->stats();
        $sinfo=$this->transmissionrpc->sinfo(array( 'alt-speed-down','alt-speed-up'));
        $data['style']="torrent.css";
        $data['sinfo']=$sinfo;
        $data['torrents']=$listaar['arguments']['torrents'];
		$data['stats']=$statsar['arguments'];
        $data['techo']=byte_format($data['stats']['cumulative-stats']['downloadedBytes']);
		$this->load->helper('form');
		$data['formop']=form_open('trinfo/tadd');
		$finput=array('name'=>'torrenturl', 
					'class'=>'urlplz',
						'placeholder'=>'urlplz');
		$fsubmit=array('name'=>'urladd',
						'class'=>'urladd',
						'value'=>' ');
		$data['formin']=form_input($finput);
		$data['formsu']=form_submit($fsubmit);
		$data['ptitle']='Torrent Manager';
		//$this->load->view('trinfo_view', $data);
		$this->template->load('template', 'trinfo_view', $data);
    }
    function tadd(){
    	$url=$_POST['torrenturl'];
        $result = $this->transmissionrpc->add( $url, $this->config->item('vps_torrent_dir'));
        $this->load->view('tadd_view', $data);
    }
    function tstop(){
    	$id = strrchr(uri_string(), '/');
    	$id = str_replace('/', '', $id);
    	$id = (int)$id;
    	$result = $this->transmissionrpc->stop( $id );
        $this->load->view('tadd_view', $data);
    }
    function tstart(){
    	$id = strrchr(uri_string(), '/');
    	$id = str_replace('/', '', $id);
    	$id = (int)$id;
    	$result = $this->transmissionrpc->start( $id );
        $this->load->view('tadd_view', $data);
    }
    function tremove(){
    	$id = strrchr(uri_string(), '/');
    	$id = str_replace('/', '', $id);
    	$id = (int)$id;
    	$result = $this->transmissionrpc->remove( $id, false );
        $this->load->view('tadd_view', $data);
    }
    function tdestroy(){
    	$id = strrchr(uri_string(), '/');
    	$id = str_replace('/', '', $id);
    	$id = (int)$id;
    	$result = $this->transmissionrpc->remove( $id, true );
        $this->load->view('tadd_view', $data);
    }
    function tspeed(){
    $speedchanger = strrchr(uri_string(), '/');
    $speedchanger = str_replace('/', '', $speedchanger);
    	

    $user=$this->config->item('vps_user');
    $pass=$this->config->item('vps_pass');
    $host=$this->config->item('vps_host');
    //Getting Dir Structure
    if ( $this->shell2->login($user,$pass,$host) ) :
      $this->shell2->exec_cmd($this->config->item('vps_torrent_script')." ".$speedchanger);
      
      $data['speeder']=$this->shell2->get_output();

      else :
      $data['speeder']=$this->shell2->error;
      
      endif;
       $this->load->view('tadd_view', $data);

    }
}
?>
