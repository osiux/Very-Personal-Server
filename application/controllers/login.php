<?php
class Login extends CI_Controller {

      
       public function __construct()
       {
            parent::__construct();
        $this->load->library('TransmissionRPC');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('encrypt');
       }
	

	function index(){
	    $logined = $this->session->userdata('logined');
        if ($logined==TRUE):
        redirect(base_url().'index.php/dashboard/', 'refresh');
        endif;
       
	$this->load->helper('form');
	$data['formop']=form_open('login/check');
	$finput=array('name'=>'user', 
				'class'=>'loginput',
				'placeholder'=>'Username');
	$pinput=array('name'=>'pass', 
				'class'=>'loginput',
				'placeholder'=>'Password');
	$fsubmit=array('name'=>'login',
				'class'=>'logo',
				'value'=>'Login');
	$data['formin']=form_input($finput);
	$data['formps']=form_password($pinput);
	$data['formsu']=form_submit($fsubmit);
	$data['ptitle']='HMSC';
	$this->load->view('login_view', $data);
		}
	
	function loginer (){
		/*
	$passdir=$this->config->item('vps_pass_dir');
	$newuser= 'newuser';
	$pass = 'newpass';
	$encrypted_string = $this->encrypt->encode($pass);
	file_put_contents($passdir.$newuser, $encrypted_string);
	$pfile = file_get_contents($passdir.$newuser);
	$backecho = $this->encrypt->decode($pfile);
	echo $backecho;
	*/
	}
	function check(){
	//echo print_r($_POST);
	$userpoint=$_POST['user'];
	$userpass=$_POST['pass'];
	$passdir=$this->config->item('vps_pass_dir');
	$fsize=@filesize($passdir.$userpoint);
	if($fsize>0):
		$passcheck=@file_get_contents($passdir.$userpoint);
		$passchecker=$this->encrypt->decode($passcheck);
		if ($passchecker==$userpass):
			$this->session->set_userdata('username', $userpoint);
			$this->session->set_userdata('logined', true);
			redirect(base_url().'index.php/dashboard', 'refresh');
			exit();
		endif;
	endif;
	echo "End of line!";
	}
	function gtfo(){
	$this->session->sess_destroy();
	redirect(base_url(), 'refresh');
	}
	}


?>