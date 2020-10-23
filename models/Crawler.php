<?php

if(!function_exists('add_action')) {
	exit;
}

class Crawler extends Config {
	
	private $wpdb;

	function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		if ($this->connection->connect_error) {
			die('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		$this->connection->set_charset($this->charset);
		if (is_admin()) {
			add_action('init', array($this,'activate'));
		}
		add_filter('cron_schedules', array($this, 'wpcron_schedules'));
		add_action('example_event', array($this, 'crawlList4d'));
		add_action('example_event', array($this, 'crawlItem4d'));
		add_action('example_event', array($this, 'crawlListToto'));
		add_action('example_event', array($this, 'crawlItemToto'));
	}

	function activate() {
		//table 4d
		$table_4d = $this->wpdb->prefix . "4d";
		$charset_collate = $this->wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table_4d (
			id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			prizes tinyint(1) DEFAULT '0' NULL,
			value varchar(20) NULL,
			schedule_4d_id  int(20) DEFAULT '0' NULL,
			date date DEFAULT '0000-00-00' NULL,
			PRIMARY KEY(id)
		)$charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
		//table schedule_4d
		$table_schedule_4d = $this->wpdb->prefix . "schedule_4d";
		$sql = "CREATE TABLE IF NOT EXISTS $table_schedule_4d (
			id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			craw_id int(20) DEFAULT '0' NULL,
			query_param text NULL,
			status tinyint(1) DEFAULT '0' NULL,
			date date DEFAULT '0000-00-00' NULL,
			PRIMARY KEY (id),
			UNIQUE KEY craw_id (craw_id)
		)$charset_collate;";
		dbDelta($sql);
		//table toto
		$table_toto = $this->wpdb->prefix . "toto";
		$sql = "CREATE TABLE IF NOT EXISTS $table_toto (
			id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			toto_name tinyint(1) DEFAULT '0' NULL,
			value varchar(20) NULL,
			schedule_toto_id  int(20) DEFAULT '0' NULL,
			date date DEFAULT '0000-00-00' NULL,
			PRIMARY KEY (id)
		)$charset_collate;";
		dbDelta($sql);
		//table schedule toto
		$table_schedule_toto = $this->wpdb->prefix . "schedule_toto";
		$sql = "CREATE TABLE IF NOT EXISTS $table_schedule_toto (
			id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			craw_id int(20) DEFAULT '0' NULL,
			query_param text NULL,
			status tinyint(1) DEFAULT '0' NULL,
			date date DEFAULT '0000-00-00' NULL,
			PRIMARY KEY (id),
			UNIQUE KEY craw_id (craw_id)
		)$charset_collate;";
		dbDelta($sql);
	}

	function wpcron_schedules($schedules) {
		//one minute
		$one_minute = array(
			'interval'=> 60,
			'display' => 'One Minute'
		);
		$schedules['one_minute'] = $one_minute;

		return $schedules;
	}
	//crawl List 4d
	function crawlList4d() {
		$table_schedule_4d = $this->wpdb->prefix . "schedule_4d";
		$html = file_get_contents($this->list4dUrl);
		$doc = new DOMDocument();
		$internalErrors = libxml_use_internal_errors(true);
		$doc->loadHTML($html);
		$doc->saveHTML();
		$data = $doc->getElementsByTagName('option');
		foreach($data as $value) {
			$craw_id = $value->getAttribute('value');
			$query_param = $value->getAttribute('querystring');
			$mysql_date = $value->nodeValue;
			$mysql_date = str_replace("/", "-", $mysql_date);
			$date = strtotime($mysql_date);
			$store_mysql_date = date("Y-m-d", $date);
			if($this->wpdb->get_var("SHOW TABLES LIKE '$table_schedule_4d'") === $table_schedule_4d) {
				$this->wpdb->get_results("INSERT INTO $table_schedule_4d(craw_id, query_param, status, date) VALUES('$craw_id','$query_param','0','$store_mysql_date') ON DUPLICATE KEY UPDATE craw_id = '$craw_id'");
			}
		}
	}

	//Crawl Item 4d
	function crawlItem4d() {
		$table_schedule_4d = $this->wpdb->prefix . "schedule_4d";
		$table_4d = $this->wpdb->prefix . "4d";
		$sql = "SELECT id,query_param,date  FROM $table_schedule_4d WHERE status = 0";
		$result = mysqli_query($this->connection, $sql);
		if($result) {
			while($row = $result->fetch_assoc()) {
				$date = $row['date'];
				$query_param = $row['query_param'];
				$schedule_4d_id = $row['id'];
				$html = file_get_contents($this->item4dUrl.'?' . $query_param);
				$doc = new DOMDocument();
				$internalErrors = libxml_use_internal_errors(true);
				$doc->loadHTML($html);
				$doc->saveHTML();
				//1st Prize
				$finder = new DomXPath($doc);
				$prize1 = $finder->query("//*[contains(@class, 'tdFirstPrize')]");
				if($this->wpdb->get_var("SHOW TABLES LIKE '$table_4d'") === $table_4d){
					foreach($prize1 as $p1) {
						$value = $p1->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_4d(prizes, value, schedule_4d_id, date) VALUES('1','$value','$schedule_4d_id','$date')");
					}
					//2nd Prize
					$prize2 = $finder->query("//*[contains(@class, 'tdSecondPrize')]");
					foreach($prize2 as $p2) {
						$value = $p2->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_4d(prizes, value, schedule_4d_id, date) VALUES('2','$value','$schedule_4d_id','$date')");
					}
					//3rd Prize
					$prize3 = $finder->query("//*[contains(@class, 'tdThirdPrize')]");
					foreach($prize3 as $p3) {
						$value = $p3->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_4d(prizes, value, schedule_4d_id, date) VALUES('3','$value','$schedule_4d_id','$date')");
					}
					//Starter Prizes
					$Starter_Prizes = $this->getTags($doc, 'tbody', 'class','tbodyStarterPrizes');
					$Starter_Prizes =$doc->loadHTML($Starter_Prizes);
					$Starter_Prizes = $doc->getElementsByTagName('td');
					foreach($Starter_Prizes as $value) {
						$value =  $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_4d(prizes, value, schedule_4d_id, date) VALUES('4','$value','$schedule_4d_id','$date')");
					}
					//Consolation Prizes
					$html = file_get_contents($this->item4dUrl.'?'. $query_param);
					$doc = new DOMDocument();
					$doc->loadHTML($html);
					$doc->saveHTML();
					$Consolation_Prizes = $this->getTags($doc, 'tbody', 'class','tbodyConsolationPrizes');
					$Consolation_Prizes = $doc->loadHTML($Consolation_Prizes);
					$Consolation_Prizes = $doc->getElementsByTagName('td');
					foreach ($Consolation_Prizes as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_4d(prizes, value, schedule_4d_id, date) VALUES('5','$value','$schedule_4d_id','$date')");
					}
					//update status
					$this->wpdb->get_results("UPDATE $table_schedule_4d SET status = 1");
				}
			}
		}
	}

	//crawl List Toto
	function crawlListToto() {
		$table_schedule_toto = $this->wpdb->prefix . "schedule_toto";
		$html = file_get_contents($this->listTotoUrl);
		$doc = new DOMDocument();
		$internalErrors = libxml_use_internal_errors(true);
		$doc->loadHTML($html);
		$doc->saveHTML();
		$data = $doc->getElementsByTagName('option');
		foreach($data as $value) {
			$craw_id = $value->getAttribute('value');
			$query_param = $value->getAttribute('querystring');
			$mysql_date = $value->nodeValue;
			$mysql_date = str_replace("/", "-", $mysql_date);
			$date = strtotime($mysql_date);
			$store_mysql_date = date("Y-m-d", $date);
			if($this->wpdb->get_var("SHOW TABLES LIKE '$table_schedule_toto'") == $table_schedule_toto) {
				$this->wpdb->get_results("INSERT INTO $table_schedule_toto(craw_id, query_param, status, date) VALUES('$craw_id','$query_param','0','$store_mysql_date') ON DUPLICATE KEY UPDATE craw_id = '$craw_id'");
			}
		}
	}

	//Crawl Item toto
	function crawlItemToto() {
		$table_schedule_toto = $this->wpdb->prefix . "schedule_toto";
		$table_toto = $this->wpdb->prefix . "toto";
		$sql = "SELECT id,query_param,date  FROM $table_schedule_toto WHERE status = 0";
		$result = $this->connection->query($sql);
		if($result) {
			while($row = $result->fetch_assoc()) {
				$date = $row["date"];
				$query_param = $row['query_param'];
				$schedule_toto_id = $row['id'];
				$html = file_get_contents($this->itemTotoUrl.'?'.$query_param);
				$doc = new DOMDocument();
				$internalErrors = libxml_use_internal_errors(true);
				$doc->loadHTML($html);
				$doc->saveHTML();
				$finder = new DomXPath($doc);
				$winning_additional = $finder->query("//*[contains(@class, 'win1')]");
				if($this->wpdb->get_var("SHOW TABLES LIKE '$table_toto'") === $table_toto) {
					foreach($winning_additional as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_toto(toto_name, value, schedule_toto_id, date) VALUES('1','$value','$schedule_toto_id','$date')");
					}	
					$winning_additional = $finder->query("//*[contains(@class, 'win2')]");
					foreach($winning_additional as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_toto(toto_name, value, schedule_toto_id, date) VALUES('1','$value','$schedule_toto_id','$date')");
					}
					$winning_additional = $finder->query("//*[contains(@class, 'win3')]");
					foreach($winning_additional as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_toto(toto_name, value, schedule_toto_id, date) VALUES('1','$value','$schedule_toto_id','$date')");
					}
					$winning_additional = $finder->query("//*[contains(@class, 'win4')]");
					foreach($winning_additional as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_toto(toto_name, value, schedule_toto_id, date) VALUES('1','$value','$schedule_toto_id','$date')");
					}
					$winning_additional = $finder->query("//*[contains(@class, 'win5')]");
					foreach($winning_additional as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_toto(toto_name, value, schedule_toto_id, date) VALUES('1','$value','$schedule_toto_id','$date')");
					}
					$winning_additional = $finder->query("//*[contains(@class, 'win6')]");
					foreach($winning_additional as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_toto(toto_name, value, schedule_toto_id, date) VALUES('1','$value','$schedule_toto_id','$date')");
					}
					//Additional 
					$additional = $finder->query("//*[contains(@class, 'additional')]");
					foreach($additional as $value) {
						$value = $value->nodeValue;
						$this->wpdb->get_results("INSERT INTO $table_toto(toto_name, value, schedule_toto_id, date) VALUES('2','$value','$schedule_toto_id','$date')");
					}
					//update status
					$this->wpdb->get_results("UPDATE $table_schedule_toto SET status = 1");
				}
			}
		}
	}
	//get Tags
	function getTags($dom, $tagName, $attrName, $attrValue) {
		$html = '';
		$domxpath = new DOMXPath($dom);
		$newDom = new DOMDocument;
		$newDom->formatOutput = true;

		$filtered = $domxpath->query("//$tagName" . '[@' . $attrName . "='$attrValue']");
		$i = 0;
		while($myItem = $filtered->item($i++) ) {
			$node = $newDom->importNode( $myItem, true );    
			$newDom->appendChild($node);
		}
		$html = $newDom->saveHTML();
		return $html;
	}
}
//end class Crawler