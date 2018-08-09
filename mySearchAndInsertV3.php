<?php
//引入读取excel文件类
	include_once('./classes/PHPExcel/IOFactory.php');
	echo 123;exit;
	//读取excel文件
	$objPHPExcel = PHPExcel_IOFactory::load($filepath);exit;
/**
 * 查重，插入
 */
// var_dump(ini_get('error_reporting'));
//设置当前脚本错误模式
// ini_set('error_reporting', 1);
//设置当前脚本最大运行时间，全局查询耗时约1min，比对(比对14个文件)后插入耗时约5 * 14min
set_time_limit(60 + 60 * 5 * 14);
//设置当前脚本最大内存，因为有一个excel文件的读取消耗内存超过默认值128M
ini_set('memory_limit', '512M');
//引入接口类
include './SugarCRM.class.php';

//实例化接口类
$sugar = new SugarCRM();
/***************************从数据库获取数据开始******************************/
$link = mysql_pconnect(HOST, USERNAME, PASSWORD) or trigger_error(mysql_error(), E_USER_ERROR); 
mysql_select_db(DB, $link);
//缓存stock_plate_no_c
$sql = "SELECT `category_c` FROM `inv_inventory_cstm`";
$result = mysql_query($sql, $link) or die(mysql_error());
if ($result && mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		$category_c[] = $row;
	}
}
$num_rows = mysql_num_rows($result);
// var_dump($num_rows);
// echo '<pre>';
// print_r($category_c);
mysql_free_result($result);
unset($row);
//缓存stock_plate_no_c
$sql = "SELECT `stock_plate_no_c` FROM `inv_inventory_cstm`";
$result = mysql_query($sql, $link) or die(mysql_error());
if ($result && mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		$stock_plate_no_c[] = $row;
	}
}
// echo '<pre>';
// print_r($stock_plate_no_c);
mysql_free_result($result);
/*********************从数据库获取数据结束*******************************/
//遍历存放excel文件的目录
$path = './Lists for Fulengen 3.2.18';
//打开目录
$handle = opendir($path);

//循环读取文件名
while ($filename = readdir($handle)) {
	//排除2个特殊文件
	if ($filename == '.' || $filename == '..') {
		continue;
	}
    //拼接一个路径
    $filepath = $path.'/'.$filename;
    // var_dump($filepath);
    //加载一个excel文件，和数据库比对后，插入数据库不存在的记录
    queryAndInsert($filepath);
    
}
//关闭目录
closedir($handle);

/**
 * 加载一个excel文件，和数据库比对后，插入数据库不存在的记录
 * @param  string $filepath   excel文件路径
 * @return null
 */
function queryAndInsert($filepath)
{
	//把全局变量中的接口对象传进来
	global $sugar;
	//把不比对而直接插入的文件放进一个数组，方便判断
	$arr = array(
		'./Lists for Fulengen 3.2.18/MR03_2013.xls',
		'./Lists for Fulengen 3.2.18/MR03_2011.xls',
		'./Lists for Fulengen 3.2.18/MR04.xls'
	);
	
	
	
	foreach ($objPHPExcel->getWorksheetIterator() as $sheet) {//取sheet
		//excel的每一行读进数组的第二维度，由此初始化循环变量
		
		$i = -1;
		foreach ($sheet->getRowIterator() as $row) {//逐行处理
			if ($row->getRowIndex() < 2) {//是表头
				foreach ($row->getCellIterator() as $headCell) {//读取表头列
					$heads[] = $headCell->getValue();//将表头读入数组
				}
				// var_dump($heads);
			} else {//不是表头
				foreach ($row->getCellIterator() as $cell) {//逐列处理
					$data[] = $cell->getValue();//读取单元格数据
				}
				// var_dump($data);
				if (in_array($filepath, $arr)) {
					//不比对，直接插入
					//组装成接口支持的数组，步骤一
					$insertData = array_combine($heads, $data);
					// var_dump($insertData);
					//组装成接口支持的数组，步骤二
					foreach ($insertData as $key => $value) {
						//组装数组的第三维
						$name_value_list[$i][] = array('name' => $key, 'value' => $value);
					}
					// var_dump($name_value_list);
					unset($data);
					unset($insertData2);
				} else {
					//比对前2列
					if (in_array($data[0], $category_c) && in_array($data[1], $stock_plate_no_c)) {
						unset($data);continue;
					}
					//组装成接口支持的数组，步骤一
					$insertData = array_combine($heads, $data);
					// var_dump($insertData);
					//组装成接口支持的数组，步骤二
					foreach ($insertData as $key => $value) {
						//组装数组的第三维
						$name_value_list[$i][] = array('name' => $key, 'value' => $value);
					}
					// var_dump($name_value_list);
					unset($data);
					unset($insertData);
				}
			}
			$i++;
		}//逐行读取结束
	}//逐个sheet读取结束
	unset($objPHPExcel);
	//excel读取到的数据组装成参数
	var_dump($name_value_list);
}