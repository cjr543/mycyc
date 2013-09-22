<?php
	class State_name {
		var $state = array('工作中','休息中','已打烊',
						   '太忙了暂不接受新订单','休假','退出一餐易餐');
		public function get_state_name($state_id) {
			return $this->state[$state_id];
		}
		public function get_all_state_array() {
			return $this->state;
		}
		public function is_work_state($now_state) {
			switch ($now_state) {
				case '0':
					return 1;
					break;
				default:
					return 0;
			}
		}
	}