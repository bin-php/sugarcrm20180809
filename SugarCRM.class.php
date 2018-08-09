<?php
include './config.php';
/**
 * 方法
 *     查
 *      retrievingAListOfFieldsFromAModule 查出模块中的字段名
 *      retrievingAListOfRecords           查出字段中的结果      Line66
 *      retrievingEmailAttachments         
 *     增
 *     createOrUpdateARecord               插入一条记录          Line481
 *     删
 *     改
 *     辅助                                                      Line547
 */
class SugarCRM
{
	//属性
	/**
	 * 登录信息的对象
	 * @var object
	 */
	private $login_result;
	/**
	 * 登录信息
	 * @var string
	 */
	private $session_id;

	//方法
	//构造方法
	public function __construct()
	{
		//登录
		$this->login();
		//get session id
		$this->session_id = $this->login_result->id;
	}

	/********************查的方法***************************/
	/**
	 * 查询模块中的所有字段
	 * @param  string $moduleName               模块名
	 * @param  array  $fields                   要查询的字段，空数组则返回所有字段
	 * @return object $get_module_fields_result 所有字段的详细信息
	 */
	public function retrievingAListOfFieldsFromAModule($fields = array(), $moduleName = 'inv_inventory')
	{
		$get_module_fields_parameters = array(
		    //session id
		    'session' => $this->session_id,
		    //The name of the module from which to retrieve records
		    // 'module_name' => 'Accounts',
		    'module_name' => $moduleName,
		     //可选的，空数组会返回全部字段Optional. Returns vardefs for the specified fields. An empty array will return all fields.
		    'fields' => $fields
		);
		return $get_module_fields_result = $this->call("get_module_fields", $get_module_fields_parameters, URL);
	}

	/**
	 * 查询字段的所有值
	 * @param  string $max_results                  要获取的记录条数
	 * @param  string $offset                       数据表偏移量，会从偏移量的下一个
	 * @param  array  $select_fields                要查询的字段
	 * @return object $get_entry_list_result        字段的所有值
	 */
	public function retrievingAListOfRecords($max_results, $offset = '0', $select_fields = array('id'))
	{
		//get list of records
		$get_entry_list_parameters = array(
		    //session id
		    'session' => $this->session_id,
		    //The name of the module from which to retrieve records区分大小写
		    'module_name' => 'inv_inventory',
		    //不带where这个词的SQL语句中的where从句 The SQL WHERE clause without the word "where".
		    'query' => "",
		    //The SQL ORDER BY clause without the phrase "order by".
		    'order_by' => "",
		    //The record offset from which to start.
		    'offset' => $offset,
		    //可选的，Optional. A list of fields to include in the results.
		    // 'select_fields' => array(
		        // 'id',
		        // 'name',
		        // 'title',
		        // 'product ID',
		        // 'PrNo',
		        // 'Vector',
		        // 'PlasmidID',
		        // 'StockID',
		        // 'Note',
		        // 'Cell',
		        // 'StockPlateNO.',
		        // 'PlasmidPlateNO.',
		        // 'Amount',
		        // 'Antibiotic',
		        // 'Sequence',
		        // 'description'
		        // 'antibiotics_c'
		    // ),
		    'select_fields' => $select_fields,
			/*
			A list of link names and the fields to be returned for each link name.
			Example: 'link_name_to_fields_array' => array(array('name' => 'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')))
			*/
		    'link_name_to_fields_array' => array(
		    ),
		    //返回的结果条数The maximum number of results to return
		    // 'max_results' => '1',
		    'max_results' => $max_results,
		    //排除已删除记录 To exclude deleted records排除已删除记录
		    'deleted' => '0',

		    //If only records marked as favorites should be returned.
		    'Favorites' => false
		);

		return $get_entry_list_result = $this->call('get_entry_list', $get_entry_list_parameters, URL);
		// var_dump($get_entry_list_result);exit;
		// echo '<pre>';
		// print_r($get_entry_list_result);
	}

	/**
	 * 检索邮件附件
	 * @return [type] [description]
	 */
	public function retrievingEmailAttachments()
	{
		// email id of an email with an attachment
		$email_id = '5826bd75-527a-a736-edf5-5205421467bf';

		// 用get_entry获取邮件内容
		$get_entry_parameters = array(
		    'session' => $session_id,
		    'module_name' => 'Emails',
		    'id' => $email_id,
		    'select_fields' => array(),
		    'link_name_to_fields_array' => array(
		        array(
		            'name' => 'notes',
		            'value' => array(
		                'id',
		                'name',
		                'file_mime_type',
		                'filename',
		                'description',
		            ),
		        ),
		    ),
		    'track_view' => false
		);

		$get_entry_result = $this->call('get_entry', $get_entry_parameters, URL);

		//有无邮件
		if (!isset($get_entry_result->entry_list[0])) {
		    return "Email not found!";
		}

		//邮件有无附件
		if (!isset($get_entry_result->relationship_list) || count($get_entry_result->relationship_list) == 0)
		{
		    return "No attachments found!";
		}

		//有邮件，邮件有附件，检索附件
		foreach ($get_entry_result->relationship_list[0][0]->records as $key => $attachmentInfo) {
		    $get_note_attachment_parameters = array(
		        'session' => $session_id,
		        'id'      => $attachmentInfo->id->value
		    );

		    $get_note_attachment_result = $this->call('get_note_attachment', $get_note_attachment_parameters, URL);

		    //attachment contents
		    // echo "<pre>";
		    // print_r($get_note_attachment_result);
		    // echo "</pre>";

		    $file_name = $get_note_attachment_result->note_attachment->filename;
		    //decode and get file contents
		    $file_contents = base64_decode($get_note_attachment_result->note_attachment->file);

		    //write file
		    $bool = file_put_contents($file_name, $file_contents);
		    if ($bool) {
		    	return true;
		    } else {
		    	return false;
		    }
		}//遍历数组结束
	}//方法结束

	/**
	 * 通过ID检索多条数据
	 * @return [type] [description]
	 */
	public function retrievingMultipleRecordsByID()
	{
		$get_entries_parameters = array(

			//session id
			'session' => $this->session_id,

			//The name of the module from which to retrieve records
			'module_name' => 'Accounts',

			//An array of SugarBean IDs
			'ids' => array(
				'20328809-9d0a-56fc-0e7c-4f7cb6eb1c83',
				'328b22a6-d784-66d9-0295-4f7cb59e8cbb'
			),

			//Optional. The list of fields to be returned in the results
			'select_fields' => array(
				'name',
				'billing_address_state',
				'billing_address_country'
			),

			//A list of link names and the fields to be returned for each link name
			'link_name_to_fields_array' => array(
			),
		);

		return $get_entries_result = $this->call("get_entries", $get_entries_parameters, URL);

	}//方法结束

	/**
	 * When using the search_by_module method, the email address information is not returned in the result. Due to this behavior, we will gather the record ids and pass them back to the get_entries method to fetch our related email addresses.
	 * @return  [description]
	 */
	public function RetrievingRecordsByEmailDomain()
	{
		//search_by_module
	    $search_by_module_parameters = array(
	        "session" => $this->session_id,
	        'search_string' => '%@example.com',
	        'modules' => array(
	            'Accounts',
	            'Contacts',
	            'Leads'
	        ),
	        'offset'              => 0,
	        'max_results'         => 1,
	        'assigned_user_id'    => '',
	        'select_fields'       => array('id'),
	        'unified_search_only' => false,
	        'favorites'           => false
	    );
	   
	    $search_by_module_results = $this->call('search_by_module', $search_by_module_parameters, URL);
	   
	    /*
	    echo '<pre>';
	    print_r($search_by_module_results);
	    echo '</pre>';
	    */
	   
	    $record_ids = array();
	    foreach ($search_by_module_results->entry_list as $results)
	    {
	        $module = $results->name;
	   
	        foreach ($results->records as $records)
	        {
	            foreach($records as $record)
	            {
	                if ($record->name = 'id')
	                {
	                    $record_ids[$module][] = $record->value;
	                    //skip any additional fields
	                    break;
	                }
	            }
	        }
	   
	    }
	   
	    $get_entries_results = array();
	    //返回数组所有的键，组成一个数组
	    $modules = array_keys($record_ids);
	   
	    foreach($modules as $module)
	    {
	        $get_entries_parameters = array(
	            //session id
	            'session' => $session_id,
	   
	            //The name of the module from which to retrieve records
	            'module_name' => $module,
	   
	            //An array of record IDs
	            'ids' => $record_ids[$module],
	   
	            //The list of fields to be returned in the results
	            'select_fields' => array(
	                'name'
	            ),
	   
	            //A list of link names and the fields to be returned for each link name
	            'link_name_to_fields_array' => array(
	                array(
	                    'name' => 'email_addresses',
	                    'value' => array(
	                        'email_address',
	                        'opt_out',
	                        'primary_address'
	                    ),
	                ),
	            ),
	   
	            //Flag the record as a recently viewed item
	            'track_view' => false,
	        );
	   
	        $get_entries_results[$module] = $this->call('get_entries', $get_entries_parameters, URL);
	    }
	   
	    return $get_entries_results;
	}//方法结束

	/**
	 * This example will retrieve a list of leads related to a specific target list.
	 * @return [type] [description]
	 */
	public function retrievingRelatedRecords()
	{
		//retrieve related list

	    $get_relationships_parameters = array(

	         'session' => $this->session_id,

	         //The name of the module from which to retrieve records.
	         'module_name' => 'ProspectLists',

	         //The ID of the specified module bean.
	         'module_id' => '76d0e694-ef66-ddd5-9bdf-4febd3af44d5',

	         //The relationship name of the linked field from which to return records.
	         'link_field_name' => 'leads',

	         //The portion of the WHERE clause from the SQL statement used to find the related items.
	         'related_module_query' => '',

	         //The related fields to be returned.
	         'related_fields' => array(
	            'id',
	            'first_name',
	            'last_name',
	         ),

	         //For every related bean returned, specify link field names to field information.
	         'related_module_link_name_to_fields_array' => array(
	         ),

	         //To exclude deleted records
	         'deleted'=> '0',

	         //order by
	         'order_by' => '',

	         //offset
	         'offset' => 0,

	         //limit
	         'limit' => 5,
	    );

	    $get_relationships_result = $this->call("get_relationships", $get_relationships_parameters, URL);

	    echo "<pre>";
	    print_r($get_relationships_result);
	}

	/**
	 * 搜索某字段下有无某个值
	 * @return object 
	 */
	public function searchingRecords()
	{
		$search_by_module_parameters = array(
	        //Session id
	        "session" => $this->session_id,

	        //The string to search for.
	        'search_string' => 'bf_clone',

	        //The list of modules to query.
	        'modules' => array(
	        'inv_inventory',
	        ),

	        //The record offset from which to start.
	        'offset' => 0,

	        //The maximum number of records to return.
	        'max_results' => 2,

	        //Filters records by the assigned user ID.
	        //Leave this empty if no filter should be applied.
	        'id' => '',

	        //An array of fields to return.
	        //If empty the default return fields will be from the active listviewdefs.
	        'select_fields' => array(
	            // 'id',
	            // 'name',
	            // 'account_type',
	            // 'phone_office',
	            // 'assigned_user_name',
	            'category_c'
	        ),

	        //If the search is to only search modules participating in the unified search.
	        //Unified search is the SugarCRM Global Search alternative to Full-Text Search.
	        'unified_search_only' => false,

	        //If only records marked as favorites should be returned.
	        'favorites' => false
	    );

	    return $search_by_module_result = $this->call('search_by_module', $search_by_module_parameters, URL);
	}

	public function searchRecords($searchString)
	{
		$search_by_module_parameters = array(
	        //Session id
	        "session" => $this->session_id,

	        //要搜索的字符串The string to search for.
	        'search_string' => $searchString,

	        //要查询的模块列表The list of modules to query.
	        'modules' => array(
	        // 'Accounts',
	        'inv_inventory'
	        ),

	        //The record offset from which to start.
	        'offset' => 0,

	        //The maximum number of records to return.
	        'max_results' => 2,

	        //Filters records by the assigned user ID.
	        //Leave this empty if no filter should be applied.
	        'id' => '',

	        //An array of fields to return.
	        //传空数组，默认返回活动的listviewdefs字段 If empty the default return fields will be from the active listviewdefs.
	        'select_fields' => array(
	            // 'id',
	            'name',
	            // 'account_type',
	            // 'phone_office',
	            // 'assigned_user_name'
	        ),

	        //是否只搜索参与统一搜索的模块 If the search is to only search modules participating in the unified search.
	        //统一搜索是Unified search is the SugarCRM Global Search alternative to Full-Text Search.
	        'unified_search_only' => false,

	        //If only records marked as favorites should be returned.
	        'favorites' => false
	    );

	    return $search_by_module_result = $this->call('search_by_module', $search_by_module_parameters, URL);
	    // var_dump($search_by_module_result);
	    // echo '<pre>';
	    // print_r($search_by_module_result);
	}
/*************************增的方法***************************************/
	/**
	 * 插入一条记录或多条数据
	 * @param  array $name_value_list          保存所有表头和值组装成的键值对的二维数组
	 * @return bool  $set_set_entry_result->id 成功返回插入的id，失败返回false
	 */
	public function createOrUpdateARecord($name_value_list)
	{
		$set_entries_parameters = array(
	        //session id
	        "session" => $this->session_id,
	        //The name of the module from which to retrieve records.
	        "module_name" => "inv_inventory",
	        //Record attributes
	        // "name_value_list" => array(
	        //      //to update a record, you will nee to pass in a record id as commented below
	        //      //array("name" => "id", "value" => "9b170af9-3080-e22b-fbc1-4fea74def88f"),
	        //      array("name" => $name, "value" => $value),
	        // ),
	        "name_value_list" => $name_value_list,
	    );

	    $set_entries_result = $this->call("set_entries", $set_entries_parameters, URL);
		//var_dump($set_entries_result);
	    // echo "<pre>";
	    // print_r($set_entries_result);
	    // if ($set_entries_result) {
	    // 	return $set_entries_result;
	    // } else {
	    // 	return false;
	    // }
	}//方法结束

	/**
	 * 插入多条记录，不可用
	 * @return [type] [description]
	 */
	public function createOrUpdateMultipleRecords($name_value_list)
	{
		$set_entries_parameters = array(
	        //session id
	        "session" => $this->session_id,

	        //The name of the module from which to retrieve records.
	        "module_name" => "inv_inventory",

	        //记录的属性Record attributes
	        "name_value_list" => array(
	            array(
	                //to update a record, you will nee to pass in a record id as commented below
	                //array("name" => "id", "value" => "912e58c0-73e9-9cb6-c84e-4ff34d62620e"),
	                array("name" => "first_name", "value" => "John"),
	                array("name" => "last_name", "value" => "Smith"),
	            ),
	            array(
	                //to update a record, you will nee to pass in a record id as commented below
	                //array("name" => "id", "value" => "99d6ddfd-7d52-d45b-eba8-4ff34d684964"),
	                array("name" => "first_name", "value" => "Jane"),
	                array("name" => "last_name", "value" => "Doe"),
	            ),
	        ),
	    );

	    $set_entries_result = $this->call("set_entries", $set_entries_parameters, URL);
		// var_dump($set_entries_result);exit;
	    // echo "<pre>";
	    // print_r($set_entries_result);
	    // echo "</pre>";
	    return $set_entries_result;
	}
/********************************辅助方法******************************/
	/**
	 * 接口方法
	 * @param  string $method     [description]
	 * @param  array  $parameters [description]
	 * @param  string $url        [description]
	 * @return object             [description]
	 */
	public function call($method, $parameters, $url)
	{
	    ob_start();
	    $curl_request = curl_init();

	    curl_setopt($curl_request, CURLOPT_URL, $url);
	    curl_setopt($curl_request, CURLOPT_POST, 1);
	    curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	    curl_setopt($curl_request, CURLOPT_HEADER, 1);
	    curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

	    $jsonEncodedData = json_encode($parameters);

	    $post = array(
	         "method"        => $method,
	         "input_type"    => "JSON",
	         "response_type" => "JSON",
	         "rest_data"     => $jsonEncodedData
	    );

	    curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
	    $result = curl_exec($curl_request);
	    curl_close($curl_request);

	    $result = explode("\r\n\r\n", $result, 2);
	    $response = json_decode($result[1]);
	    ob_end_flush();

	    return $response;
	}

	/**
	 * 登录
	 * @return void
	 */
	public function login()
	{
		$login_parameters = array(
			"user_auth" => array(
			"user_name" => USER,
			"password"  => md5(PASS),
			"version"   => "1"
			),
			"application_name" => "RestTest",
			"name_value_list"  => array()
		);

    	$this->login_result = $this->call("login", $login_parameters, URL);
	}
}//类结束