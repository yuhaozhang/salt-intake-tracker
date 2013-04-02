<?php 
//Target类用于计算和输出某一个学生在整个研究周期中的目标食盐量

class target {
	private $student_id;
	private $base_salt;
	private $base_soy;
	private $algorithm;
	private $week_number; //这里设置week_number是为了研究周期发生变化后可以调整，测试阶段为8
	private $total_stated_percent; //通过实验全程的总降低百分比，测试阶段为50%。
	private $total_expected_percent; //25%
	private $stated_target_salt;
	private $expected_target_salt;
	private $stated_target_soy;
	private $expected_target_soy;
	
	public function __construct() {
		$this->student_id = null;
		$this->base_salt = 0;
		$this->base_soy = 0;
		$this->algorithm = null;
		$this->week_number = 8;
		$this->total_stated_percent = 0.5;
		$this->total_expected_percent = 0.25;
		$this->stated_target_salt = array();
		$this->expected_target_salt = array();
		$this->stated_target_soy = array();
		$this->expected_target_salt = array();
		for($i=1; $i<=$this->week_number; $i++) {
			$this->stated_target_salt[$i] = 0;
			$this->expected_target_salt[$i] = 0;
			$this->stated_target_soy[$i] = 0;
			$this->expected_target_soy[$i] = 0;
		}
	}
	
	public function setTarget($stu_id) {
		//这个函数用于给构造后的新target设置stu_id以及采用算法的种类；成功返回true。
		$this->student_id = $stu_id;
		
        $query = sprintf('select teacher_id from student where id = %d',$stu_id);
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
		if (mysql_num_rows($result)){    
			$row = mysql_fetch_assoc($result);
			$user = new user;
			$user = user::getbyid($row['teacher_id']);
			$this->algorithm = $user->algorithm;
			return true;
		} else {
			return false;
		}
	}
	
	public function updateTarget() {
		//这个函数用于给设置了stu_id后的target读取base值（第0周食盐量），然后根据base值计算每周的预计目标。
		//如果base值已设置并成功读出则返回true，否则返回false。
		
		if($this->student_id == null) return false; //如果还没有设置stu_id，返回false。
		
		$salt = new salt;
		if($salt->getData($this->student_id, 0)) {
			$this->base_salt = $salt->eat_salt_weight;
			$this->base_soy = $salt->eat_soy_weight;
			
			$this->computeTarget();
			
			return true;
			
		} else {
			return false;
		}
	}
	
	private function computeTarget() {
		//用于计算每周的预计目标，仅通过updateTarget()函数调用
		if($this->algorithm == 1) { //算法1，采用逐渐减少策略
			$week_stated_percent = 1 - pow($this->total_stated_percent, 1 / $this->week_number);
			$week_expected_percent = 1 - pow($this->total_expected_percent, 1 / $this->week_number);
			
			for($i=1; $i<=$this->week_number; $i++) {
				$this->stated_target_salt[$i] = round($this->base_salt * pow(( 1 - $week_stated_percent), $i)); //100*(1-x)^n
				$this->expected_target_salt[$i] = round($this->base_salt * pow(( 1 - $week_expected_percent), $i));
				$this->stated_target_soy[$i] = round($this->base_soy * pow(( 1 - $week_stated_percent), $i));
				$this->expected_target_soy[$i] = round($this->base_soy * pow(( 1 - $week_expected_percent), $i));
			}
		
		} else { //算法2，采用立即减少策略
			$this->stated_target_salt[1] = round($this->base_salt * (1 - $this->total_stated_percent)); //100*(1-50%)
			$this->expected_target_salt[1] = round($this->base_salt * (1 - $this->total_expected_percent));
			$this->stated_target_soy[1] = round($this->base_soy * (1 - $this->total_stated_percent));
			$this->expected_target_soy[1] = round($this->base_soy * (1 - $this->total_expected_percent));
			for($i=2; $i<=$this->week_number; $i++) {
				$this->stated_target_salt[$i] = $this->stated_target_salt[1];
				$this->expected_target_salt[$i] = $this->expected_target_salt[1];
				$this->stated_target_soy[$i] = $this->stated_target_soy[1];
				$this->expected_target_soy[$i] = $this->expected_target_soy[1];
			}
		}
	}
	
	public function getTarget() {
		$data = array();
		
		for($i=1; $i<=$this->week_number; $i++) {
			$data['stated_target_salt'][$i] = $this->stated_target_salt[$i];
			$data['expected_target_salt'][$i] = $this->expected_target_salt[$i];
			$data['stated_target_soy'][$i] = $this->stated_target_soy[$i];
			$data['expected_target_soy'][$i] = $this->expected_target_soy[$i];
		}
		
		return $data;
		
	}
	
}



?>