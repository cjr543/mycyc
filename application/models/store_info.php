<?php
	class Store_info extends CI_Model {
		public function __construct() {
			$this->load->database();
			$this->load->library('get_db_info');
			$this->load->library('state_name');
		}
		private function query_store_info($univ_id, &$row){
				$store_id = $row['storeId'];
				$query_2 = $this->db->query("SELECT storeTypeName
								  FROM storeType 
								  JOIN eachStoreType
								  ON storeType.storeTypeId=eachStoreType.storeTypeId
								  WHERE eachStoreType.storeId=$store_id");
				$count = 0;
				$each_store_type = "";

				foreach ($query_2->result() as $row_2) {
					if ($count++)
						$each_store_type .= ',' . $row_2->storeTypeName;
					else
						$each_store_type = $row_2->storeTypeName;
				}
				$row['each_store_type'] = $each_store_type;
				$row['state_choise'] = 'closed';

				if ($this->state_name->is_work_state($row['state']))
					$row['state_choise'] = 'open';
				$row['state'] = $this->state_name->get_state_name($row['state']);

				$q_3 = $this->db->select('delivery_cost')
								->where('storeId', $store_id)
								->where('belongTo', $univ_id)
								->get('storeLoc');
				$row['delivery_cost'] = $q_3->row_array()['delivery_cost'];
		}

		function get_store_top_info($university_id, $store_id) {
			$this->update_shanghu_state_one_univ($university_id);
			$q = $this->db->select('gonggao, location, briefIntroduction, 
									yinyeshijian, qisongjia, imgLoc')
						  ->from('storeIntro')
						  ->join('storeLoc', 'storeLoc.storeId=storeIntro.storeId')
						  ->where('storeIntro.storeId', $store_id)
						  ->where('storeLoc.belongTo', $university_id)
						  // 状态5表示退出ycyc
						  ->where('storeIntro.state !=', '5')
						  ->get();
			if ($q->num_rows() > 0)
				return $q->row_array();
		}
		function get_store_info($university_id) {
			// 先更新商户状态
			$this->update_shanghu_state_one_univ($university_id);

			$sql = "SELECT storeIntro.storeId, storeName, state, 
					total_buyer_month, 
					ROUND(total_score_month / total_score_num_month, 1) AS avg_score_month,
					imgLoc 
					FROM storeIntro
					JOIN storeLoc
					ON storeIntro.storeId=storeLoc.storeId
					WHERE storeLoc.belongTo=$university_id
					AND storeIntro.state!='5'
					AND storeIntro.delivery_order='0'
					ORDER BY total_buyer_month desc";

			$store_info = array();
			$store_info['0'] = array();
			$store_info['1'] = array();
			$query = $this->db->query($sql);
			foreach ($query->result_array() as $row) {
				$this->query_store_info($university_id, $row);
				$store_info['0'][] = $row;
			}
			// 一模一样的来一下
			// delivery_order改为1
			$sql = "SELECT storeIntro.storeId, storeName, state, 
					total_buyer_month, 
					ROUND(total_score_month / total_score_num_month, 1) AS avg_score_month,
					imgLoc 
					FROM storeIntro
					JOIN storeLoc 
					ON storeIntro.storeId=storeLoc.storeId
					WHERE storeLoc.belongTo=$university_id
					AND storeIntro.state!='5'
					AND storeIntro.delivery_order='1'
					ORDER BY total_buyer_month desc";

			$query = $this->db->query($sql);
			foreach ($query->result_array() as $row) {
				$this->query_store_info($university_id, $row);
				$store_info['1'][] = $row;
			}
			// var_dump($store_info);
			return $store_info;
		}
		function get_store_info_limit($university_id, $opening, $taste, $limit, $start){
			//$this->db->limit($limit, $start);
			$query=$this->db->select("storeintro.storeId, storeName, state, total_buyer_month")
							->from("storeintro")
							->join("storeloc", "storeintro.storeID = storeloc.storeId")
							->where("storeloc.belongto", $university_id)
							->where("storeintro.state !=", '5')
							->where("storeintro.delivery_order", '0');
			if($opening)
				$query->where("storeintro.state", "0");
			if($taste!=0){
				$query->join('eachStoreType', 'eachStoreType.storeId=storeIntro.storeId');
				$query->where("storeTypeId", $taste);
			}	
			$query=$query->order_by("total_buyer_month", "desc")
						 ->limit($limit, $start)
						 ->get();
			foreach ($query->result_array() as $row) {
				$this->query_store_info($university_id, $row);
				$store_info[] = $row;
			}
			return $store_info;
		}
		function count_store_num($university_id, $opening, $taste){
			$query=$this->db->from("storeintro")
						->join("storeloc", "storeintro.storeID = storeloc.storeId")
						->where("storeloc.belongto", $university_id)
						->where("storeintro.state !=", '5')
						->where("storeintro.delivery_order", '0');
			if($opening)
				$query->where("storeintro.state", "0");
			if($taste!=0){
				$query->join('eachStoreType', 'eachStoreType.storeId=storeIntro.storeId');
				$query->where("storeTypeId", $taste);
			}
			$query=$query->get();
			return $result=$query->num_rows();
		}
		function get_store_type() {
			$this->db->select('storeTypeName');
			$query = $this->db->get('storeType');
			foreach ($query->result_array() as $row)
				$store_type[] = $row;
			return $store_type;
		}

		////////////////////////////////////////////
		// 商户的状态
		function get_shanghu_state($store_id) {

			$now_state = $this->get_db_info->get_shanghu_now_state_with_id($store_id);
			if ($now_state == '4' || $now_state == '5') {// 休假状态
				return $now_state;
			}

			$q = $this->db->select('storeId')
						  ->where('unix_timestamp(user_in_charge_time) 
						  		 < unix_timestamp(curdate())')
						  ->where('storeId', $store_id)
						  ->get('storeIntro');
			// 有结果说明之前修改状态					  
			if ($q->num_rows() == 0) {
				// 系统会修改用户状态，太忙啊
				return $this->get_db_info->get_shanghu_now_state_with_id($store_id);
			}
			// 使用默认的时间
			else {
				$q = $this->db->select('storeId')
							  ->where('curtime() BETWEEN openTime_1 AND closeTime_1')
							  ->or_where('curtime() BETWEEN openTime_2 AND closeTime_2')
							  ->get('storeIntro');
				if ($q->num_rows() > 0) {
					$this->set_state($store_id, '0');
					return '0'; // 返回工作中状态
				}
				// 最初的默认设置为已打烊
				else {
					$this->set_state($store_id, '2');
					return '2'; // 已打烊
				}
			}
		}

		function update_shanghu_state_one_univ($univ_id) {
			$q = $this->db->select('storeId')
						  ->where('belongTo', $univ_id)
						  ->get('storeLoc');
			foreach($q->result_array() as $row) {
				$this->get_shanghu_state($row['storeId']);
			}
			
		}

		function set_state($store_id, $state) {
			$this->db->where('storeId', $store_id);
			$this->db->update('storeIntro', array('state'=>$state));
		}
		///////////////////////////////////////////////////////

		function add_column($column, &$q) {
			if ($column)
				$q->where($column[0], $column[1]);
		}
		function ajax_get_store_info($university_id, $open, $taste) {
			$store_info = array();

			$this->update_shanghu_state_one_univ($university_id);
			
			$state = false;
			$store_type = false;
			if ($open == '1') {
				$state = array();
				$state[] = "state = ";
				$state[] = '0';
			}
		
			if ($taste > 0) {
				$store_type = array();
				$store_type[] = "storeTypeId =";
				$store_type[] = $taste;
			}
			
			$q = $this->db->select('storeIntro.storeId AS storeId, 
								  storeName, state, location, 
								  briefIntroduction, qisongjia, yinyeshijian, 
								  gonggao,
								  total_buyer_month, total_score_month / total_score_num_month AS avg_score_month')
						  ->from('storeIntro')
						  ->where('delivery_order', '0')
						  ->where('state !=', '5')
						  ->join('storeLoc', 'storeLoc.storeId=storeIntro.storeId');
			if ($taste)
				$q->join('eachStoreType', 'eachStoreType.storeId=storeIntro.storeId');

			$q->where('storeLoc.belongTo', $university_id);

			$this->add_column($state, $q);
			$this->add_column($store_type, $q);
			$q = $q->order_by('total_buyer_month', 'desc')->get();
			// var_dump($q->result_array());
			
			$store_info = array();
			foreach ($q->result_array() as $row) {
				$this->query_store_info($university_id, $row);
				$store_info[] = $row;
			}
			return $store_info;
		}
		function get_delivery_cost($university_id, $store_id)  {
			$q = $this->db->select('delivery_cost')
						  ->where('belongTo', $university_id)
						  ->where('storeId', $store_id)
						  ->get('storeLoc');
			if ($q->num_rows() > 0)
				return $q->row_array()['delivery_cost'];
		}
	}
