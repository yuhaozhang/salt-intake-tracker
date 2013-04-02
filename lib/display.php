<?php

//************************************************************************************************
//Display in admin.php
//************************************************************************************************

function printInitialDateTable() {
//用于在admin.php中输出所有学校的初始日期设定
	$query = 'select school_name,initial_date from school';
	mysql_query("set names 'gbk'");
	$result = mysql_query($query,$GLOBALS['DB']);
	$count = mysql_num_rows($result);
	$output = "";
	if ($count > 0){
		for($i=1; $i<=$count; $i++) {
			$row = mysql_fetch_assoc($result);
			$td1 = $i;
			$td2 = $row['school_name'];
			$td3 = date('Y',$row['initial_date'])."年".date('n',$row['initial_date'])."月".date('j',$row['initial_date'])."日";
			$td4 = date::getCurrentWeek($row['school_name']);
			$output .= "
				<tr>
					<td>$td1</td>
					<td>$td2</td>
					<td>$td3</td>
					<td>$td4</td>
				</tr>
				";
		}
	}
	mysql_free_result($result);
	
	return $output;
}

//************************************************************************************************
//Display in main.php
//************************************************************************************************
function printStuTable($teacher_id, $c_week) { 
//用于在main.php中产生特定老师的所有学生的数据表

	$query = sprintf('select id,name,school_id,family_id,relation_id,gender,birthday,latest_data_week from student where teacher_id = %d',$teacher_id);
	mysql_query("set names 'gbk'");
	$result = mysql_query($query,$GLOBALS['DB']);
	$count = mysql_num_rows($result);
	$output = "";
	if ($count > 0){
		for($i=1; $i<=$count; $i++) {
			$row = mysql_fetch_assoc($result);
			$id = $row['id'];
			$data_link_head = "<a href='"."data.php?stu_id=$id"."'>";
			$td1 = $i;
			$td2 = $data_link_head.$row['school_id'].$row['family_id'].$row['relation_id']."</a>";
			$td3 = $data_link_head.$row['name']."</a>";
			$td4 = printGender($row['gender']);
			$td5 = $row['birthday'];
			$td6 = printUpdateIcon($row['latest_data_week'], $c_week);
			$td7 = "<a href='#DeleteModal' onclick='SaveDeleteNum($id)' data-toggle='modal'><i class='icon-remove'></i>删除</a>";
			
			//用表格的不同颜色代表不同的状态，蓝色info为本周已输入，黄色warning为本周未输入。
			if($row['latest_data_week'] == $c_week) {
				$output .= "
				<tr class='info'>
					<td>$td1</td>
					<td>$td2</td>
					<td>$td3</td>
					<td>$td4</td>
					<td>$td5</td>
					<td>$td6</td>
					<td>$td7</td>
				</tr>
				";
			} else {
				$output .= "
				<tr class='warning'>
					<td>$td1</td>
					<td>$td2</td>
					<td>$td3</td>
					<td>$td4</td>
					<td>$td5</td>
					<td>$td6</td>
					<td>$td7</td>
				</tr>
				";
			}
		}
	}
	mysql_free_result($result);
	
	return $output;
}

function printGender($gender) {
	if($gender == false) {
		$output = "男";
	} else {
		$output = "女";
	}
	
	return $output;
}

function printUpdateIcon($ldw, $cw) {
	if($ldw == $cw) {
		$output = "<i class='icon-ok'></i>";
	} else {
		$output = "<i class='icon-plus'></i>";
	}
	
	return $output;
}


//************************************************************************************************
//Display in data.php
//************************************************************************************************

function printDataTable($student_id, $c_week) {

	$output = "";
	
	$salt = new salt;
	
	//print history rows
	for($i=0; $i<$c_week; $i++) {
		if($salt->getData($student_id, $i)) {
			$output .= createHistoryDataRow($i, $salt->add_salt_weight, $salt->salt_container_weight, $salt->eat_salt_weight,
								            $salt->add_soy_weight, $salt->soy_container_weight, $salt->eat_soy_weight);
		} else {
			$output .= createHistoryEmptyDataRow($i);
		}
	}
	
	//print current row
	if($salt->getData($student_id, $c_week)) {
		$output .= createCurrentDataRow($c_week, $salt->add_salt_weight, $salt->salt_container_weight, $salt->eat_salt_weight,
										$salt->add_soy_weight, $salt->soy_container_weight, $salt->eat_soy_weight);
	} else {
		$output .= createEditableDataRow($student_id, $c_week);
	}
	
	//print future row
	for($i=$c_week+1; $i<=8; $i++) {
		$output .= createFutureDataRow($i);
	}
	
	return $output;
	
	
	/* Old Function
	
	$query = sprintf('select week,add_salt_weight,salt_container_weight,eat_salt_weight,add_soy_weight,soy_container_weight,eat_soy_weight from data where student_id = %d',$student_id);
	mysql_query("set names 'gbk'");
	$result = mysql_query($query,$GLOBALS['DB']);
	
	$output = "";
	
	//First sort the query result
	$count = mysql_num_rows($result);
	if($count > 0) {
	//If there is query data.
		for($i=0; $i<$count; $i++) {
			//get all the result row by row
			$row = mysql_fetch_assoc($result);
			$array['week'][$i] = $row['week'];
			$array['add_salt_weight'][$i] = $row['add_salt_weight'];
			$array['add_soy_weight'][$i] = $row['add_soy_weight'];
			$array['salt_container_weight'][$i] = $row['salt_container_weight'];
			$array['soy_container_weight'][$i] = $row['soy_container_weight'];
			$array['eat_salt_weight'][$i] = $row['eat_salt_weight'];
			$array['eat_soy_weight'][$i] = $row['eat_soy_weight'];
		}
		
		//sort by week row
		array_multisort($array['week'], $array['add_salt_weight'], $array['salt_container_weight'], $array['eat_salt_weight'],
						$array['add_soy_weight'], $array['soy_container_weight'], $array['eat_soy_weight']);
		
		//2nd, print history rows
		$index = 0;
		for($i=0; $i<$c_week; $i++) {
			if($i == $array['week'][$index]) { //If week data exist, print it out.
				$output .= createHistoryDataRow($i, $array['add_salt_weight'][$index], $array['salt_container_weight'][$index], $array['eat_salt_weight'][$index],
								$array['add_soy_weight'][$index], $array['soy_container_weight'][$index], $array['eat_soy_weight'][$index]);
				$index++;
			} else {
				$output .= createHistoryEmptyDataRow($i); //If week data does not exist, print empty row for that line.
			}
		}
		
		//3rd, print current rows
		$index = count($array['week']) - 1;
		if($array['week'][$index] < $c_week) {
			$output .= createEditableDataRow($student_id, $c_week);
		} else {
			createCurrentDataRow($c_week, $array['add_salt_weight'][$index], $array['salt_container_weight'][$index], $array['eat_salt_weight'][$index],
								$array['add_soy_weight'][$index], $array['soy_container_weight'][$index], $array['eat_soy_weight'][$index]);
		}
		
		//4th, print future rows
		for($i=$c_week+1; $i<=8; $i++) {
			$output .= createFutureDataRow($i);
		}
			
	} else {
	//If there is not query data.
		for($i=0; $i<$c_week; $i++) {
			$output .= createHistoryEmptyDataRow($i);
		}
		
		$output .= createEditableDataRow($student_id, $c_week);
		
		for($i=$c_week+1; $i<=8; $i++) {
			$output .= createFutureDataRow($i);
		}
	}
	
	mysql_free_result($result);
	
	return $output;
	*/

}

function createHistoryDataRow($w, $a_salt_w, $salt_c_w, $e_salt_w, $a_soy_w, $soy_c_w, $e_soy_w) {
	
	$output = "<tr class='info'>
					<td>$w</td>
					<td>$salt_c_w</td>
					<td>$a_salt_w</td>
					<td>$e_salt_w</td>
					<td>$soy_c_w</td>
					<td>$a_soy_w</td>
					<td>$e_soy_w</td>
				</tr>
				";
	
	return $output;
}

function createHistoryEmptyDataRow($week) {
	$output = "<tr class='info'>
					<td>$week</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
				";
	
	return $output;
}

function createCurrentDataRow($w, $a_salt_w, $salt_c_w, $e_salt_w, $a_soy_w, $soy_c_w, $e_soy_w) {
	$output = "<tr class='success'>
					<td>$w</td>
					<td>$salt_c_w</td>
					<td>$a_salt_w</td>
					<td>$e_salt_w</td>
					<td>$soy_c_w</td>
					<td>$a_soy_w</td>
					<td>$e_soy_w</td>
				</tr>
				";
	
	return $output;
}

function createEditableDataRow($student_id, $week) {
	$output = "<tr class='success'>
					<td>$week</td>
					<td><input type='text' name='salt_container_weight' class='input-mini' required></input>
						<input type='hidden' name='week' value=$week>
						<input type='hidden' name='student_id' value=$student_id>
					</td>
					<td><input type='text' name='add_salt_weight' required class='input-mini' value='0'></input></td>
					<td><span class='input-mini uneditable-input'>自动计算</span></td>
					<td><input type='text' name='soy_container_weight' required class='input-mini'></input></td>
					<td><input type='text' name='add_soy_weight' required class='input-mini' value='0'></input></td>
					<td><span class='input-mini uneditable-input'>自动计算</span></td>
				</tr>
				";
	
	return $output;
}

function createCurrentEmptyDataRow($week) {
	$output = "<tr class='success'>
					<td>$week</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
				";
	
	return $output;
}

function createFutureDataRow($week) {
	$output = "<tr class='warning'>
					<td>$week</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				";
	
	return $output;
}

function checkIfCurrentDataExist($student_id, $c_week) {
	$salt = new salt;
	
	return $salt->getData($student_id, $c_week);
}


//************************************************************************************************
//Display in print.php
//************************************************************************************************

//*****************************print Data Table***********************************************
function printDataTableInPrint($student_id, $c_week) {

	$output = "";
	
	$salt = new salt;
	
	//print history rows
	for($i=0; $i<$c_week; $i++) {
		if($salt->getData($student_id, $i)) {
			$output .= createHistoryDataRowInPrint($i, $salt->add_salt_weight, $salt->salt_container_weight, $salt->eat_salt_weight,
								            $salt->add_soy_weight, $salt->soy_container_weight, $salt->eat_soy_weight);
		} else {
			$output .= createHistoryEmptyDataRowInPrint($i);
		}
	}
	
	//print current row
	if($salt->getData($student_id, $c_week)) {
		$output .= createCurrentDataRowInPrint($c_week, $salt->add_salt_weight, $salt->salt_container_weight, $salt->eat_salt_weight,
										$salt->add_soy_weight, $salt->soy_container_weight, $salt->eat_soy_weight);
	} else {
		$output .= createCurrentEmptyDataRowInPrint($c_week);
	}
	
	return $output;

}

function createHistoryDataRowInPrint($w, $a_salt_w, $salt_c_w, $e_salt_w, $a_soy_w, $soy_c_w, $e_soy_w) {
	
	$output = "<tr>
					<td>$w</td>
					<td>$salt_c_w</td>
					<td>$a_salt_w</td>
					<td>$e_salt_w</td>
					<td>$soy_c_w</td>
					<td>$a_soy_w</td>
					<td>$e_soy_w</td>
				</tr>
				";
	
	return $output;
}

function createHistoryEmptyDataRowInPrint($week) {
	$output = "<tr>
					<td>$week</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
				";
	
	return $output;
}

function createCurrentDataRowInPrint($w, $a_salt_w, $salt_c_w, $e_salt_w, $a_soy_w, $soy_c_w, $e_soy_w) {
	$output = "<tr>
					<td>$w</td>
					<td>$salt_c_w</td>
					<td>$a_salt_w</td>
					<td>$e_salt_w</td>
					<td>$soy_c_w</td>
					<td>$a_soy_w</td>
					<td>$e_soy_w</td>
				</tr>
				";
	
	return $output;
}

function createCurrentEmptyDataRowInPrint($week) {
	$output = "<tr>
					<td>$week</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
				";
	
	return $output;
}

//*****************************print Diagram***********************************************

function printDiagram($stu_id, $c_week, $target_data) {
	
	$output = "";
	$salt = new salt;
	
	//generate real line data
	for($i=0; $i<=$c_week; $i++) {
		$salt->getData($stu_id, $i);
		$real_data['salt'][$i] = $salt->eat_salt_weight;
		$real_data['soy'][$i] = $salt->eat_soy_weight;
	}
	
	//generate lines
	$realline = createDiagramRealLine($real_data, $c_week);
	$base['salt'] = $real_data['salt'][0];
	$base['soy'] = $real_data['soy'][0];
	$targetline = createDiagramTargetLine($target_data, $base);
	
	//draw diagrams,first targetline and then realine to cover the targetline with the latter
	$output .= "
	<div class='row-fluid'>
			<div class='span6 jpchart'>
				<div id='chart_salt'>
					<script>
					$(document).ready(function(){
					var line1 = {$targetline['salt']};
					var line2 = {$realline['salt']};
					var plot1 = \$.jqplot('chart_salt', [line1, line2], {
					  title: '食盐量', 
					  seriesColors: ['#FFCC33', '#FF6666'],
					  axes:{xaxis:{min:0, max:8, ticks: [0,1,2,3,4,5,6,7,8,9], tickOptions:{ formatString:'%d'}},
							yaxis:{tickOptions:{ formatString:'%d'}}},
					  legend: { show: true, labels: ['食用量（克）', '目标量（克）'] },
					  series: [
					  { 
						showLine:false,
						showMarker:true,
						pointLabels: { show:false } 
					  },
					  { 
						showMarker:true,
						pointLabels: { show:true },
						markerOptions: { style:'filledSquare' }						
					  },
					  ]
					  });
					});
					</script>
				</div>
			</div>
			<div class='span6 jpchart'>
				<div id='chart_soy'>
					<script>
					$(document).ready(function(){
					var line1 = {$targetline['soy']};
					var line2 = {$realline['soy']};
					var plot1 = \$.jqplot('chart_soy', [line1, line2], {
					  title: '酱油量', 
					  seriesColors: ['#FFCC33', '#9966CC'],
					  axes:{xaxis:{min:0, max:8, ticks: [0,1,2,3,4,5,6,7,8,9], tickOptions:{ formatString:'%d'}},
							yaxis:{tickOptions:{ formatString:'%d'}}},
					  legend: { show: true, labels: ['食用量（克）', '目标量（克）'] },
					  series: [
					  { 
						showLine:false,
						showMarker:true,
						pointLabels: { show:false } 
					  },
					  { 
						showMarker:true,
						pointLabels: { show:true },
						markerOptions: { style:'filledSquare' }						
					  },
					  ]
					  });
					});
					</script>
				</div>
			</div>
		</div>
	";
	
	
	return $output;
}

function createDiagramTargetLine($target_data, $base) {
	//这个函数实现了产生目标折线数据的功能，参数中$base为数组，key分为salt和soy
	$line['salt'] = "[";
	$line['soy'] = "[";
	
	$line['salt'] .= "[0, {$base['salt']}], ";
	$line['soy'] .= "[0, {$base['soy']}], ";
	
	for($i=1; $i<=7; $i++) {
		$line['salt'] .= "[{$i}, {$target_data['stated_target_salt'][$i]}], ";
		$line['soy'] .= "[{$i}, {$target_data['stated_target_soy'][$i]}], ";
	}
	
	$line['salt'] .= "[8, {$target_data['stated_target_salt'][8]}]";
	$line['soy'] .= "[8, {$target_data['stated_target_soy'][8]}]";
	
	$line['salt'] .= "]";
	$line['soy'] .= "]";
	
	return $line;

}

function createDiagramRealLine($data, $c_week) {
	//这个函数用于产生真实情况的数据折线数据，$line中存储的是0-c_week的数据数组，分为两维，key分别是salt和soy
	
	$line['salt'] = "[";
	$line['soy'] = "[";
	
	for($i=0; $i<$c_week; $i++) {
		$line['salt'] .= "[{$i}, {$data['salt'][$i]}], ";
		$line['soy'] .= "[{$i}, {$data['soy'][$i]}], ";
	}
	
	$line['salt'] .= "[{$c_week}, {$data['salt'][$c_week]}]";
	$line['soy'] .= "[{$c_week}, {$data['soy'][$c_week]}]";
	
	$line['salt'] .= "]";
	$line['soy'] .= "]";
	
	return $line;
	
}


//*****************************print Flowers***********************************************

function printFlowerTable($stu_id, $c_week, $target_data) { //$target_data是getTarget()函数返回的数组
	
	$output = "";
	$salt = new salt;
	
	//print rows with flowers: history and current
	for($i=1; $i<=$c_week; $i++) {
		if($salt->getData($stu_id, $i)) {
			$output .= createFlowerRow($i, $target_data['stated_target_salt'][$i], $target_data['expected_target_salt'][$i], $salt->eat_salt_weight,
								            $target_data['stated_target_soy'][$i], $target_data['expected_target_soy'][$i], $salt->eat_soy_weight);
		} else {
			$output .= createEmptyFlowerRow($i);
		}
	}
	//print rows full of transparent pics: future
	for($i=$c_week+1; $i<=8; $i++) {
		$output .= createEmptyFlowerRow($i);
	}
	
	return $output;
}

function createFlowerRow($week, $s_t_salt, $e_t_salt, $r_salt, $s_t_soy, $e_t_soy, $r_soy) {
	
	$output = "";
	
	//如果是第1或5周，需要输出表格起始标签
	if($week == 1 || $week == 5) {
		$output .= "
				<div class='row-fluid'>
					<div class='span6'>
						<table class='table'>
		"; 
	}
	
	if($r_salt <= $s_t_salt) {
		$red_number = 4;
	} else if($r_salt <= $e_t_salt) {
		$red_number = 2;
	} else {
		$red_number = 1;
	}
	if($r_soy <= $s_t_soy) {
		$purple_number = 4;
	} else if($r_soy <= $e_t_soy) {
		$purple_number = 2;
	} else {
		$purple_number = 1;
	}
	
	$trans_number = 8 - $red_number - $purple_number; //透明占位图片的数量
	
	$output .= "<tr>
				<th>第{$week}周</th>";
	//print red flowers
	for($i=1; $i<=$red_number; $i++) {
		$output .= "<td><img src='./img/red_flower.png'></td>";
	}
	for($i=1; $i<=$purple_number; $i++) {
		$output .= "<td><img src='./img/purple_flower.png'></td>";
	}
	for($i=1; $i<=$trans_number; $i++) {
		$output .= "<td><img src='./img/transparent.png'></td>";
	}
	
	$output .= "</tr>";
	
	//如果是4或8周，需要输出表格结束标签
	if($week == 4 || $week == 8) {
		$output .= "
					</tr>
				</table>
			</div>
		";
	}
	
	return $output;
}

function createEmptyFlowerRow($week) {
	
	$output = "";
	//如果是第1或5周，需要输出表格起始标签
	if($week == 1 || $week == 5) {
		$output .= "
				<div class='row-fluid'>
					<div class='span6'>
						<table class='table'>
		"; 
	}
	
	//输出全透明表格
	$output .= "<tr>
				<th>第{$week}周</th>";
	//print all trans
	for($i=1; $i<=8; $i++) {
		$output .= "<td><img src='./img/transparent.png'></td>";
	}
	
	$output .= "</tr>";
	
	//如果是4或8周，需要输出表格结束标签
	if($week == 4 || $week == 8) {
		$output .= "
					</tr>
				</table>
			</div>
		";
	}
	
	return $output;
}

//*****************************print Feedback***********************************************

function printFeedback($stu_id, $c_week, $target_data) {
	
	$output = "";
	$warning_yes = "<span class='text-success'><strong><U>达到预定的目标</U> <img src='./img/smile.png'></strong></span>";
	$warning_no = "<span class='text-success'><strong><U>未达到预定的目标</U> <img src='./img/warning.png'></strong></span>";
	$suggestion_yes = "<span class='text-warning'><strong><U>继续努力，降低每日食盐摄入量，少吃高盐食物，再接再厉</U></strong></span>";
	$suggestion_no = "<span class='text-warning'><strong>努力克服困难，降低每日食盐摄入量，少吃高盐食物，达到下一周的目标</strong></span>";
	
	$salt = new salt;
	$salt->getData($stu_id, $c_week);
	
	//这里第0周的情况要单独处理，因为
	//1，stated_target_salt没有第0周数据
	//2，第0周的反馈仅给出下周目标
	if(isset($salt->eat_salt_weight) && $c_week==0) {
		$output .= "
		<div class='well'>
			<h4>尊敬的家长：您好！</h4>
			<p>非常感谢您参与我们的项目，从这一周开始我们将对您的食盐量进行监督，并给出合理建议。</p>
			<p>您和您的家人下周的目标是：<strong>食盐{$target_data['stated_target_salt'][$c_week+1]}克，酱油{$target_data['stated_target_soy'][$c_week+1]}毫升</strong>。</p>
			<div class='container'>
				<h4 class='pull-right'>家长签字 ______________</h4>
			</div>
		  </div>
		";
	} elseif(isset($salt->eat_salt_weight) && $c_week!=0 && $salt->eat_salt_weight <= $target_data['stated_target_salt'][$c_week]) {
		$output .= "
		<div class='well'>
			<h4>尊敬的家长：您好！</h4>
			<p>根据上一周您孩子的食盐酱油记录表，发现上一周您家的减盐量 {$warning_yes}。
			为了您和您的孩子的健康，希望您和孩子 {$suggestion_yes}。 您孩子将继续对家庭食盐摄入量进行记录，我们下一周还会把您食盐摄入情况及意见反馈给您。</p>
			<div class='container'>
				<h4 class='pull-right'>家长签字 ______________</h4>
			</div>
		  </div>
		";
	} elseif(isset($salt->eat_salt_weight) && $c_week!=0 && $salt->eat_salt_weight > $target_data['stated_target_salt'][$c_week]) {
		$output .= "
		<div class='well'>
			<h4>尊敬的家长：您好！</h4>
			<p>根据上一周您孩子的食盐酱油记录表，发现上一周您家的减盐量 {$warning_no}。
			为了您和您的孩子的健康，希望您和孩子 {$suggestion_no}。 您孩子将继续对家庭食盐摄入量进行记录，我们下一周还会把您食盐摄入情况及意见反馈给您。</p>
			<h4>您和您的家人下周的目标是：食盐{$target_data['stated_target_salt'][$c_week+1]}克，酱油{$target_data['stated_target_soy'][$c_week+1]}毫升。</h4>
			<div class='container'>
				<h4 class='pull-right'>家长签字 ______________</h4>
			</div>
		  </div>
		";
	} else {
		$output .= "
		<div class='well'>
			<p>无法给出反馈，请检查数据。</p>
		  </div>
		";
	}
	
	return $output;
}


?>