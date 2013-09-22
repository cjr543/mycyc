<?php 

class Pages extends CI_Controller{
	
	public function view($page = 'home'){
		
		if( ! file_exists('application/views/pages/'.$page.'.php') ){
			show_404();//页面不存在
		}
		
		$data['title'] = ucfirst($page); //将title中的第一个字符大写
		$data['pageName'] = $page;
		
		$this->load->view('templates/header', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer', $data);
		
	}
	
}

?>