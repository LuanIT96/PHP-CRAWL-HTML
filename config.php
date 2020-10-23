<?php 

if(!function_exists('add_action')) {
	exit;
}

class Config {
	protected $list4dUrl = "https://www.singaporepools.com.sg/DataFileArchive/Lottery/Output/fourd_result_draw_list_en.html";
	protected $item4dUrl = "https://www.singaporepools.com.sg/en/product/pages/4d_results.aspx";
	protected $listTotoUrl = "https://www.singaporepools.com.sg/DataFileArchive/Lottery/Output/toto_result_draw_list_en.html";
	protected $itemTotoUrl = "https://www.singaporepools.com.sg/en/product/sr/Pages/toto_results.aspx";
	protected $connection;
	protected $dbhost = ''; //host name
	protected $dbuser = ''; // user name
	protected $dbpass = ''; // password
	protected $dbname = ''; // database name
	protected $charset = 'utf8'; // unicode
}

?>