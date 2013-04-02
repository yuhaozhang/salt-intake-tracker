<?php

class user{
    private $id;               //User id
    private $fields;           //Other record fields
	
    public function __construct(){
        $this->id = null;
        $this->fields = array('name'=>'',
                              'real_name'=>'',
                              'school_name'=>'',
                              'password'=>'0',
							  'algorithm'=>'1',
                              'is_admin'=>'0');
    }
    
    public function __get($field){                  //get value
        if($field=='id') return $this->id;
        else return $this->fields[$field];
    }
    
    public function __set($field,$value){           //set value
        if (array_key_exists($field,$this->fields)) $this->fields[$field]=$value;
    }
    public static function namechk($name) {
        if ($name) return 1;
        else return 0;
    } 
    public static function classchk($class){
        if (strlen($class) && strlen($class)<=9) return 1;
        else return 0;
    }
    public static function mailchk($email){
            if(preg_match('^[0-9a-z][a-z0-9\\._-]{1,}@[a-z0-9-]{1,}[a-z0-9]\\.[a-z\\.]{1,}[a-z]+^',$email)==0) return 0;
            else return 1;
    }
    public static function phonechk($phone){
            if(preg_match('^1(3|5|8)\d{9}^',$phone)) return 1;
            else return 0;
    }
    public static function levelchk($level){
            if($level) return 1;
            else return 0;
    }
    public static function messagechk($message) {
        if (preg_match("/^[\x{4e00}-\x{9fa5}\w]{0,150}$/u",$message)) return 1;
        else return 0;
    }

    public static function getbyid($user_id){
        $user = new user;
        $query = sprintf('select username,real_name,school_name,password,algorithm,is_admin from user where id = %d',$user_id);
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
            $user->id = $user_id;
            $user->name = $row['username'];
            $user->real_name = $row['real_name'];
            $user->school_name = $row['school_name'];
            $user->password = $row['password'];
			$user->algorithm = $row['algorithm'];
            $user->is_admin = $row['is_admin'];
        }
        mysql_free_result($result);
        return $user;
    }
    
     public static function getbyname($username){
        $user = new user;
        $query = sprintf('select id,real_name,school_name,password,algorithm,is_admin from user where username = "%s"',mysql_real_escape_string($username,$GLOBALS['DB']));
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
            $user->id = $row['id'];
            $user->name = $username;
            $user->real_name = $row['real_name'];
            $user->school_name = $row['school_name'];
            $user->password = $row['password'];
			$user->algorithm = $row['algorithm'];
            $user->is_admin = $row['is_admin'];
        }
        mysql_free_result($result);
        return $user;
     }
     
     public function save(){
        if ($this->id){
            $query = sprintf('update user set username = "%s",real_name = "%s",school_name = "%s",password = "%s",algorithm = %d,is_admin = %d where id = %d',
            mysql_real_escape_string($this->name,$GLOBALS['DB']),
            mysql_real_escape_string($this->real_name,$GLOBALS['DB']),
            mysql_real_escape_string($this->school_name,$GLOBALS['DB']),
            mysql_real_escape_string($this->password,$GLOBALS['DB']),
            mysql_real_escape_string($this->algorithm,$GLOBALS['DB']),
			mysql_real_escape_string($this->is_admin,$GLOBALS['DB']),
            $this->id
            );
            mysql_query("set names 'gbk'");
            return mysql_query($query,$GLOBALS['DB']);
        }
        else{
            $query = sprintf('insert into user (username,real_name,school_name,password,algorithm,is_admin) values ("%s","%s","%s","%s",%d,%d)',
            mysql_real_escape_string($this->name,$GLOBALS['DB']),
            mysql_real_escape_string($this->real_name,$GLOBALS['DB']),
            mysql_real_escape_string($this->school_name,$GLOBALS['DB']),
            mysql_real_escape_string($this->password,$GLOBALS['DB']),
            mysql_real_escape_string($this->algorithm,$GLOBALS['DB']),
			mysql_real_escape_string($this->is_admin,$GLOBALS['DB'])
            );
            mysql_query("set names 'gbk'");
            if (mysql_query($query,$GLOBALS['DB'])){
                $this->id = mysql_insert_id($GLOBALS['DB']);
                return true;
            }
            else return false;
        }
     }   
}

?>