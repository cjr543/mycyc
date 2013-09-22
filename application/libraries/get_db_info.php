<?php
	class Get_db_info {
		function __construct() {
			$this->CI = & get_instance();
			$this->CI->load->database();
		}
		function db_one( $select_column, 
							  $where_column,
							  $where_value,
							  $table_name) {
			$q = $this->CI->db->select($select_column)
						  ->where($where_column, $where_value)
						  ->get($table_name);
			if ($q->num_rows() > 0)
				return $q->row_array()[$select_column];
			return false;
		}
		function get_univeristy_full_with_short_name($short_name) {
			return $this->db_one('schoolFullName', 'schoolShortName', $short_name, 
								 'schoolInfo');
		}
		function get_university_short_with_id($university_id) {
			return $this->db_one('schoolShortName', 'schoolId', $university_id, 
								 'schoolInfo');	
		}
		function get_university_full_with_id($university_id) {
			return $this->db_one('schoolFullName', 'schoolId', $university_id,
								 'schoolInfo');
		}

		function get_university_id_with_short_name($short_name) {
			return $this->db_one('schoolId', 'schoolShortName', $short_name,
								 'schoolInfo');
		}
		function get_store_img_loc($store_id) {
			return $this->db_one('imgLoc', 'storeId', $store_id,
								 'storeIntro');
		}
		function get_store_name($store_id) {
			return $this->db_one('storeName', 'storeId', $store_id,
								 'storeIntro');
		}
		// 商家直接获得当前的状态
		function get_shanghu_now_state_with_id($store_id) {
			return $this->db_one('state', 'storeId', $store_id,
				         		 'storeIntro');
		}
		function get_store_login_id($store_id) {
			return $this->db_one('loginId', 'storeId', $store_id,
								 'storeIntro');
		}
		function get_store_delivery_type($store_id) {
			return $this->db_one('delivery_order', 'storeId', $store_id,
								 'storeIntro');
		}
	}
