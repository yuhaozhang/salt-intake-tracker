<?php

//From 2013/4/1 version, the system has support different schools with different initial date.

class date{
	public $year;
	public $month;
	public $day;
	
	public function __construct(){
		
        $this->year = '2013';
		$this->month = '1';
		$this->day = '1';
    }
	
	public static function getCurrentDate() {
		$date = new date;
		$date->year = date('Y');
		$date->month = date('n');
		$date->day = date('j');
		
		return $date;
	}
	
	public static function getInitialDate($school_name) {
		$date = new date;
		$query = sprintf('select initial_date from school where school_name = %s', $school_name);
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
            $date->year = date('Y',$row['initial_date']);
            $date->month = date('n',$row['initial_date']);
            $date->day = date('j',$row['initial_date']);
        }
        mysql_free_result($result);
        
		return $date;
	}
	
	/*
	public static function getCurrentWeek() {
	
		$c_date = new date;
		$c_date = date::getCurrentDate();
		
        $query = 'select initial_date from system';
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
        }
		
		$i_time = $row['initial_date'];
		$c_time = mktime(0,0,0,$c_date->month,$c_date->day,$c_date->year); //First convert to timestamp
		$time = $c_time - $i_time;
		$days = floor($time / (60*60*24));  //Then compute days number.
		$week = floor($days / 7);
		
        return $week; 
	}
	*/
	
	public static function getCurrentWeek($school_name) {
	
		$c_date = new date;
		$c_date = date::getCurrentDate();
		
        $query = sprintf('select initial_date from school where school_name = %s', $school_name);
        mysql_query("set names 'gbk'");
        $result = mysql_query($query,$GLOBALS['DB']);
        if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
        }
		
		$i_time = $row['initial_date'];
		$c_time = mktime(0,0,0,$c_date->month,$c_date->day,$c_date->year); //First convert to timestamp
		$time = $c_time - $i_time;
		$days = floor($time / (60*60*24));  //Then compute days number.
		$week = floor($days / 7);
		
        return $week; 
	}
	
	public static function saveInitialDate($year, $month, $day, $school) {
		$date = mktime(0,0,0,$month, $day, $year);
		$query = sprintf('update school set initial_date = %d where school_name = %s', $date, (string)$school);
		mysql_query("set names 'gbk'");
		return mysql_query($query,$GLOBALS['DB']);
	}
	
}



?>