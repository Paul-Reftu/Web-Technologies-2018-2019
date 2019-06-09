<?php

class Publisher {
	public function update($msg, $server){
		$server->forward($msg);
	}
}

class Subscriber {
	private $id;
	public $topics;
	public $vulnerabilities;

	function __construct($client_id) {
        $id = $client_id;
        $this->topics = array();
        $this->vulnerabilities = array();
    }

	public function subscribe($msg) {
		$this->topics[] = $msg;
	}

	public function unsubscribe($msg) {
		if (($key = array_search($msg, $topics)) !== false) {
    		unset($topics[$key]);
		}
	}

	public function add($msg) {
		$this->vulnerabilities[] = $msg;
	}

	public function notify(){
		if (count($this->vulnerabilities) > 0){
			foreach ($this->vulnerabilities as $v) {
				echo json_encode($v) . '\n';
			}
		}
	}

	public function reset(){
		unset($vulnerabilities);
	}
}

class PubSubServer {
	public $subscribers;

	function __construct() {
        $this->subscribers = array();
    }

	public function register($sub) {
		$this->subscribers[] = $sub;
	}

	public function forward($msg) {
		$vuln = json_decode($msg, true);
		foreach ($this->subscribers as $sub) {
			foreach ($sub->topics as $topic) {
				if (array_search($topic, $vuln['tags']) !== false){
					$sub->add($msg);
				}
			}
		}
	}
}

function compare(){
	$new = array();
	$data = json_decode(file_get_contents('http://localhost/Web-Tehnologies-2018-2019/application/api/v1.php/exploits?description="all"'),true);
	$conn = new mysqli("localhost","root","","proiect");
	$result = $conn->query('select total,date from total');
	$row = $result->fetch_assoc();
	$total = $row['total'];
	$date = $row['date'];
	if ($total < $data['total']){
		foreach ($data['matches'] as $vuln) {
			$an = explode('-', $vuln['_id']);
			$nr = $an[1];
			$an = $an[0];
			$old_an = explode('-', $date);
			$old_nr = $old_an[1];
			$old_an = $old_an[0];
			if ($old_an <= $an){
				if($old_an == $an ){
					if($nr > $old_nr){
						$new[] = $vuln;
					}
				}
				else{
					$new[] = $vuln;
				}
			}
		}
	}
	return $new;
}


if($_SERVER['REQUEST_METHOD'] == "POST")
    {
    	session_start();
    	$pub_sub = new PubSubServer();
    	$sub = new Subscriber(1);
    	$conn = new mysqli("localhost","root","","proiect");
    	$result = $conn->query('select category from subs where id_client = ' .$_SESSION['id']);
    	if ($result->num_rows > 0){
    		while($row = $result->fetch_assoc()){
    			$sub->subscribe($row['category']);
    		}
    	}
    	$pub_sub->register($sub);
    	$pub = new Publisher();
  		$vuln = compare();
  		// $json = '{
   	// 		"votes": 3700,
   	// 		"description": "best ip cam search I have found yet.",
   	// 		"title": "Webcam",
   	// 		"timestamp": "2010-03-15T13:32:32",
   	// 		"tags": [
    // 			"webcam",
    // 			"tw",
    // 			"test"
   	// 		],
   	// 		"query": "Server: SQ-WEBCAM"
  		// }';
  		// $vuln[] = $json;
    	
  		foreach ($vuln as $v) {
  			$pub->update($v, $pub_sub);
  		}
    	echo $sub->notify();
    }
?>