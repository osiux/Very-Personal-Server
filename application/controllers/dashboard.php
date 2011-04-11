<?php

class Dashboard extends CI_Controller {
      
  public function __construct()
       {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('number');
        $this->load->library('shell2');
        $logined = $this->session->userdata('logined');
        if ($logined!=TRUE):
        redirect(base_url(), 'refresh');
        endif;
       }
	
  function index() {
    //Basic page and controller info :D
    $data['style']='dashboard.css';
    $data['ptitle']='VPS Dashboard';
    $user=$this->config->item('vps_user');
    $pass=$this->config->item('vps_pass');
    $host=$this->config->item('vps_host');
    //Getting Dir Structure
    if ( $this->shell2->login($user,$pass,$host) ) :
      $this->shell2->exec_cmd("du -a -c -h --max-depth 2 ".$this->config->item('vps_MU_dir'));
      
      $data['dumu']=$this->shell2->get_output();

      $this->shell2->exec_cmd("du -a -c -h --max-depth 1 ".$this->config->item('vps_torrent_dir'));
      
      $data['dutor']=$this->shell2->get_output();
      

      else :
      $data['dumu']=$this->shell2->error;
      $data['dutor']=$this->shell2->error;
      $data['top']=$this->shell2->error;
      
      endif;

    //loading template
    $this->template->load('template', 'dashboard_view', $data);
    }
}
?>