<?php
/**
 * 共14张表， 14个对应关系
 * 数组的命名规则是$map_拼接各个excel文件名的前几个字符
 */
$map = array(
	array(//AM 
		'product_ID' => 'category_c',//货号
		'PrNo' => 'category_c',//删
		'Vector' => 'vector_c',//载体

		'PlasmidID' => 'volumn_clone_c',
		'StockID' => 'stock_id_c',
		'Cell' => 'host_cell_c',

		'StockPlateNO.' => 'stock_plate_no_c',
		'Antibiotic' => 'antibiotics_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	array(//$map_GC = 
		'product_id' => 'category_c',//货号
		'group_name' => 'enzyme_c',
		'Plate_no' => 'stock_plate_no_c',

		'clonestatus' => 'pm_status_c',
		'NEW_PRIMER_ID' => 'primer_no_c',
		'orf_len' => 'length_c',

		'PLATEWELL' => 'plate_well_c',
		'jpw' => 'vector_c',//vector_c
		'Placeno' => 'place_no_c',

		'Antibiotic' => 'antibiotics_c',
		'StockPlateNO.' => 'stock_plate_no_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	array(//大部分都可以输入$map_Lv105 = 
		'stockID' => 'stock_id_c',
		'placeNo.' => 'place_no_c',
		'Vector' => 'vector_c',

		'Cell' => 'host_cell_c',
		'PrimerID' => 'primer_no_c',
		'GC-No' => '',//category_c

		'Length' => 'length_c',
		'Plate_Well' => 'plate_well_c',
		'Status' => 'pm_status_c',

		'StockPlateNO.' => 'stock_plate_no_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	/***********************************************************/
	array(//$map_Lv242_0 = 
		'Catalog Number' => 'category_c',
		'PrimerID' => 'primer_no_c',
		'description' => 'description',

		'LocusID' => 'place_no_c',
		'Length' => 'length_c',
		'Plate_Well' => 'plate_well_c',

		'Status' => 'pm_status_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	array(//$map_Lv242_1 = 
		'Catalog Number' => 'category_c',
		'PrimerID' => 'primer_no_c',
		'gb_acc' => 'description',

		'LocusID' => 'place_c',
		'Length' => 'length_c',
		'stockID.' => 'stock_id_c',

		'placeNo.' => 'place_no_c',
		'Vector' => 'vector_c',
		'host' => 'host_cell_c',

		'Plate_Well' => 'plate_well_c',
		'Status' => 'pm_status_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	array(//$map_Lv242_2 = 
		'Catalog Number' => 'category_c',
		'stock_plate_no_c',
		'PrimerID' => 'primer_no_c',
		'description' => 'description',

		'LocusID' => 'place_c',
		'Length' => 'length_c',
		'stockID.' => 'stock_id_c',

		'placeNo.' => 'place_no_c',
		'Vector' => 'vector_c',
		'host' => 'host_cell_c',

		'Plate_Well' => 'plate_well_c',
		'Status' => 'pm_status_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	array(//$map_Lv242_3 = 
		'Catalog Number' => 'category_c',
		'stock_plate_no_c',
		'PrimerID' => 'primer_no_c',
		'description' => 'description',

		'LocusID' => 'place_c',
		'orf_len' => 'length_c',
		'stockID.' => 'stock_id_c',

		'placeNo.' => 'place_no_c',
		'Vector' => 'vector_c',
		'host' => 'host_cell_c',

		'Plate_Well' => 'plate_well_c',
		'Status' => 'pm_status_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	/**********************************/
	array(//$map_M02 = 
		'stockID' => 'stock_id_c',
		'placeNo.' => 'place_no_c',
		'Vector' => 'vector_c',

		'Cell' => 'host_cell_c',
		'PrimerID' => 'primer_no_c',
		'GC-No' => 'category_c',//重要

		'Length' => 'length_c',
		'Plate_Well' => 'plate_well_c',
		'Status' => 'pm_status_c',

		'StockPlateNO.' => 'stock_plate_no_c',
		'Date' => 'shipping_date_c'
	),

	array(//$map_M13_all = 
		'stock_place' => 'stock_id_c',
		'vector' => 'vector_c',
		'host' => 'host_cell_c',

		'primerid' => 'primer_no_c',
		'gene_id' => 'category_c',//把gc改为ex  ，category_c
		'orf_len' => 'length_c',

		'plate_well' => 'plate_well_c',
		'PM_status' => 'pm_status_c',
		'M13_plate_well' => 'stock_plate_no_c',

		'shipping_date_c' => 'shipping_date_c'
	),
	array(//$map_M13_new = 
		'stock_place' => 'stock_id_c',
		'vector' => 'vector_c',
		'host' => 'host_cell_c',

		'plate_well' => 'plate_well_c',
		'M13_plate_well' => 'stock_plate_no_c',
		'gene_id' => 'category_c',//把gc改为ex  ，category_c

		'primerid' => 'primer_no_c',
		'description' => 'description',
		'orf_len' => 'length_c',

		'shipping_date_c' => 'shipping_date_c'
	),

	array(//$map_M98 = 
		'stock_place' => 'stock_id_c',
		'vector' => 'vector_c',
		'host' => 'host_cell_c',

		'plate_well' => 'plate_well_c',
		'M98_plate_well' => 'stock_plate_no_c',
		'gene_id' => 'category_c',//category_c    ex-  -  

		'primerid' => 'primer_no_c',
		'description' => 'description',
		'orf_len' => 'length_c',

		'shipping_date_c' => 'shipping_date_c'
	),
	/*********************/
	array(//$map_MR03_2011 = 
		'miRNA' => 'category_c',//前面加EX-
		'vector' => 'vector_c',
		'description' => 'description',//合并了2列
		'StockID' => 'stock_id_c',
		'StockPlateNO.' => 'stock_plate_no_c',

		'Antibiotic' => 'antibiotics_c',
		'shipping_date_c' => 'shipping_date_c'
	),

	array(//$map_MR03_2013 = 
		'miRNA' => 'category_c',//前面加EX-
		'vector' => 'vector_c',
		'description_0' => 'description',

		'description_1' => 'description',
		'StockID' => 'stock_id_c',
		'StockPlateNO.' => 'stock_plate_no_c',
		
		'Antibiotic' => 'antibiotics_c',
		'shipping_date_c' => 'shipping_date_c'
	),
	/*********************/
	array(//$map_MR04 = 
		'MR04-PlateID' => 'stock_plate_no_c',
		'PrNo' => 'category_c',//拼接-mr04
		'Vector' => 'vector_c',

		'StockID' => 'stock_id_c',
		'description' => 'description',

		'shipping_date_c' => 'shipping_date_c'
	),
);