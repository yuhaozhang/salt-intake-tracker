<?php

class salt{
    private $student_id;               //Student id
	private $week;
    private $data;           //Other data fields
	
    public function __construct() {
        $this->student_id = null;
		$this->week = null;
        $this->data = array('add_salt_weight'=>'',
                              'salt_container_weight'=>'',
                              'eat_salt_weight'=>'',
                              'add_soy_weight'=>'',
							  'soy_container_weight'=>'',
                              'eat_soy_weight'=>'');
    }
    
	//�������ݺ�����������6����������������������ʳ���뽴��ʳ�������ɼ���ó�
	public function setData($stu_id, $week, $a_salt_w, $salt_c_w, $a_soy_w, $soy_c_w) {
		
		$this->student_id = $stu_id;
		$this->week = $week;
        $this->add_salt_weight = $a_salt_w;
        $this->salt_container_weight = $salt_c_w;
        
        $this->add_soy_weight = $a_soy_w;
		$this->soy_container_weight = $soy_c_w;
        
		if($week == 0) {
			
			$this->eat_salt_weight = $this->add_salt_weight - $this->salt_container_weight;
			$this->eat_soy_weight = $this->add_soy_weight - $this->soy_container_weight;
			return true;
			
		} else {
			//TODO���˴�Ӧ�����Ӷ����Ƿ�����������ݵļ��ģʽ
			$query = sprintf('select salt_container_weight,soy_container_weight from data where student_id = %d and week = %d', $stu_id, $week-1);
			mysql_query("set names 'gbk'");
			$result = mysql_query($query,$GLOBALS['DB']);
			if (mysql_num_rows($result)){
				
				$row = mysql_fetch_assoc($result);
				$last_salt_container = $row['salt_container_weight'];
				$last_soy_container = $row['soy_container_weight'];
				mysql_free_result($result);
				
				//���㱾�ܵ�ʳ��ʳ����
				$this->eat_salt_weight = $last_salt_container + $a_salt_w - $salt_c_w;
				$this->eat_soy_weight = $last_soy_container + $a_soy_w - $soy_c_w;
				
				return true;  //���ز����������Ƿ����б������óɹ�
				
			} else {
				mysql_free_result($result);
				return false;
			}
		}
	}
	
	//�������������ݺ���
    public function getData($student_id, $week) {
		
		$query = sprintf('select add_salt_weight,salt_container_weight,eat_salt_weight,
						add_soy_weight,soy_container_weight,eat_soy_weight from data where student_id = %d and week = %d', $student_id, $week);
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
			$this->student_id = $student_id;
			$this->week = $week;
            $this->add_salt_weight = $row['add_salt_weight'];
            $this->salt_container_weight = $row['salt_container_weight'];
            $this->eat_salt_weight = $row['eat_salt_weight'];
            $this->add_soy_weight = $row['add_soy_weight'];
			$this->soy_container_weight = $row['soy_container_weight'];
            $this->eat_soy_weight = $row['eat_soy_weight'];
			mysql_free_result($result);
			return true;  //���ز����������ü�¼�Ƿ����
        } else {
			mysql_free_result($result);
			return false;
		}
	}
	
	//���溯��������ǰ�����ݱ��������ݿ�
	public function save() {
		
		if(!empty($this->student_id)) {
			
			$query1 = sprintf('insert into data (student_id,week,add_salt_weight,salt_container_weight,eat_salt_weight,
						add_soy_weight,soy_container_weight,eat_soy_weight) values (%d,%d,%d,%d,%d,%d,%d,%d)', 
						$this->student_id, $this->week, $this->add_salt_weight, $this->salt_container_weight, $this->eat_salt_weight,
						$this->add_soy_weight, $this->soy_container_weight, $this->eat_soy_weight);
			
			//update student field: latest_data_week
			$query2 = sprintf('update student set latest_data_week = %d where id = %d', $this->week, $this->student_id);
			mysql_query("set names 'gbk'");
			
			if (mysql_query($query1,$GLOBALS['DB']) && mysql_query($query2,$GLOBALS['DB'])){
                return true;
            }
            else return false;
			
		} else {
			return false;
		}
	}
}

?>