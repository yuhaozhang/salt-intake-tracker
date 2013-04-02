<?php 
//Target�����ڼ�������ĳһ��ѧ���������о������е�Ŀ��ʳ����

class target {
	private $student_id;
	private $base_salt;
	private $base_soy;
	private $algorithm;
	private $week_number; //��������week_number��Ϊ���о����ڷ����仯����Ե��������Խ׶�Ϊ8
	private $total_stated_percent; //ͨ��ʵ��ȫ�̵��ܽ��Ͱٷֱȣ����Խ׶�Ϊ50%��
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
		//����������ڸ���������target����stu_id�Լ������㷨�����ࣻ�ɹ�����true��
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
		//����������ڸ�������stu_id���target��ȡbaseֵ����0��ʳ��������Ȼ�����baseֵ����ÿ�ܵ�Ԥ��Ŀ�ꡣ
		//���baseֵ�����ò��ɹ������򷵻�true�����򷵻�false��
		
		if($this->student_id == null) return false; //�����û������stu_id������false��
		
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
		//���ڼ���ÿ�ܵ�Ԥ��Ŀ�꣬��ͨ��updateTarget()��������
		if($this->algorithm == 1) { //�㷨1�������𽥼��ٲ���
			$week_stated_percent = 1 - pow($this->total_stated_percent, 1 / $this->week_number);
			$week_expected_percent = 1 - pow($this->total_expected_percent, 1 / $this->week_number);
			
			for($i=1; $i<=$this->week_number; $i++) {
				$this->stated_target_salt[$i] = round($this->base_salt * pow(( 1 - $week_stated_percent), $i)); //100*(1-x)^n
				$this->expected_target_salt[$i] = round($this->base_salt * pow(( 1 - $week_expected_percent), $i));
				$this->stated_target_soy[$i] = round($this->base_soy * pow(( 1 - $week_stated_percent), $i));
				$this->expected_target_soy[$i] = round($this->base_soy * pow(( 1 - $week_expected_percent), $i));
			}
		
		} else { //�㷨2�������������ٲ���
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