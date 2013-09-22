<?php

	class Store extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->model('food_info');
			$this->load->model("store_info");
			$this->load->library("get_db_info");
			$this->load->library("state_name");
		}
		
		public function index($university_id, $store_id){
			$data = $this->store_info->get_store_top_info($university_id, $store_id);
			$data['food_info'] = $this->food_info->get_food_info($store_id);
			$data['food_type'] = $this->food_info->re;
			$data['store_id'] = $store_id;
			$data['store_name'] = $this->get_db_info->get_store_name($store_id);
			$now_state = $this->store_info->get_shanghu_state($store_id);

			$data['state'] = $this->state_name->is_work_state($now_state);
			$data['delivery_cost'] = $this->store_info
										  ->get_delivery_cost($university_id, $store_id);
			$data['university_id'] = $university_id;
			
			$this->load->view('templates/header');
			$this->load->view('store/food_list', $data);
			$this->load->view('templates/footer');
		}
	
		public function info($university_id, $store_id){
			$data = $this->store_info->get_store_top_info($university_id, $store_id);
			$data['food_info'] = $this->food_info->get_food_info($store_id);
			$data['food_type'] = $this->food_info->re;
			$data['store_id'] = $store_id;
			$data['store_name'] = $this->get_db_info->get_store_name($store_id);
			$now_state = $this->store_info->get_shanghu_state($store_id);

			$data['state'] = $this->state_name->is_work_state($now_state);
			$data['delivery_cost'] = $this->store_info
										  ->get_delivery_cost($university_id, $store_id);
			$data['university_id'] = $university_id;
			
			$this->load->view('templates/header');
			$this->load->view('store/store_info', $data);
			$this->load->view('templates/footer');
		}
	}
?>