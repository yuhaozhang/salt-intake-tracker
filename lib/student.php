<?php

class student{
    private $id;               //Student id
    private $fields;           //Other record fields
	
    public function __construct(){
        $this->id = null;
        $this->fields = array('name'=>'',
                              'school_id'=>'',
                              'family_id'=>'',
                              'relation_id'=>'1',
							  'teacher_id'=>'',
                              'gender'=>'0',
							  'birthday'=>'',
							  'latest_data_week'=>'-1');
    }
    
    public function __get($field){                  //get value
        if($field=='id') return $this->id;
        else return $this->fields[$field];
    }
    
    public function __set($field,$value){           //set value
        if (array_key_exists($field,$this->fields)) $this->fields[$field]=$value;
    }

    public static function getbyid($student_id){
        $student = new student;
        $query = sprintf('select name,school_id,family_id,relation_id,teacher_id,gender,birthday,latest_data_week from student where id = %d',$student_id);
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
            $student->id = $student_id;
            $student->name = $row['name'];
            $student->school_id = $row['school_id'];
            $student->family_id = $row['family_id'];
            $student->relation_id = $row['relation_id'];
			$student->teacher_id = $row['teacher_id'];
            $student->gender = $row['gender'];
			$student->birthday = $row['birthday'];
			$student->latest_data_week = $row['latest_data_week'];
        } else {
			mysql_free_result($result);
			return false;
		}
        mysql_free_result($result);
        return $student;
    }
    
     public static function getbyname($name){
        $student = new student;
        $query = sprintf('select id,school_id,family_id,relation_id,teacher_id,gender,birthday,latest_data_week from student where name = "%s"',mysql_real_escape_string($name,$GLOBALS['DB']));
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
            $student->id = $row['id'];
            $student->name = $name;
            $student->school_id = $row['school_id'];
            $student->family_id = $row['family_id'];
            $student->relation_id = $row['relation_id'];
			$student->teacher_id = $row['teacher_id'];
            $student->gender = $row['gender'];
			$student->birthday = $row['birthday'];
			$student->latest_data_week = $row['latest_data_week'];
        }
        mysql_free_result($result);
        return $student;
     }
	 
	 //得到完整学生ID信息
	 public function getWholeID(){
        $id = $this->school_id . $this->family_id . $this->relation_id;
        return $id;
     }
	 
     
     public function save(){ //This function is used to save the values into database.
        if ($this->id){
            $query = sprintf('update student set name = "%s",school_id = "%s",family_id = "%s",relation_id = "%s",teacher_id = %d,gender = %d,birthday = "%s", latest_data_week = %d where id = %d',
            mysql_real_escape_string($this->name,$GLOBALS['DB']),
            mysql_real_escape_string($this->school_id,$GLOBALS['DB']),
            mysql_real_escape_string($this->family_id,$GLOBALS['DB']),
            mysql_real_escape_string($this->relation_id,$GLOBALS['DB']),
            mysql_real_escape_string($this->teacher_id,$GLOBALS['DB']),
			mysql_real_escape_string($this->gender,$GLOBALS['DB']),
			mysql_real_escape_string($this->birthday,$GLOBALS['DB']),
			mysql_real_escape_string($this->latest_data_week,$GLOBALS['DB']),
            $this->id
            );
            mysql_query("set names 'gbk'");
            return mysql_query($query,$GLOBALS['DB']);
        }
        else{
            $query = sprintf('insert into student (name,school_id,family_id,relation_id,teacher_id,gender,birthday,latest_data_week) values ("%s","%s","%s","%s",%d,%d,"%s",%d)',
            mysql_real_escape_string($this->name,$GLOBALS['DB']),
            mysql_real_escape_string($this->school_id,$GLOBALS['DB']),
            mysql_real_escape_string($this->family_id,$GLOBALS['DB']),
            mysql_real_escape_string($this->relation_id,$GLOBALS['DB']),
            mysql_real_escape_string($this->teacher_id,$GLOBALS['DB']),
			mysql_real_escape_string($this->gender,$GLOBALS['DB']),
			mysql_real_escape_string($this->birthday,$GLOBALS['DB']),
			mysql_real_escape_string($this->latest_data_week,$GLOBALS['DB'])
            );
            mysql_query("set names 'gbk'");
            if (mysql_query($query,$GLOBALS['DB'])){
                $this->id = mysql_insert_id($GLOBALS['DB']);
                return true;
            }
            else return false;
        }
     }   
	 
	 public function delete($id){ //This is used to delete item from database.
		$query1 = sprintf('delete from student where id = %d', $id);
		$query2 = sprintf('delete from data where student_id = %d', $id);
        mysql_query("set names 'gbk'");
        $result1 = mysql_query($query1,$GLOBALS['DB']);
		$result2 = mysql_query($query2,$GLOBALS['DB']);
		
		$result = $result1&&$result2;
		
		return $result;
	 }
}

?>