<?php
require_once('./tmhOAuth/tmhOAuth.php');

$connection = new tmhOAuth(array(
	'consumer_key'    => 'pnOp52Wn4eMprgYVkoYg',
	'consumer_secret' => 'NWoA0YnzP2TBdSXYnNh7EMox9Obm4CIpO5emufK7s',
	'user_token'      => '2171620446-A0zGrmUQMPIT8ToZveFe1unYKSn1e9OiUTbreUI',
	'user_secret'     => 'IECdtLFjOvzAyfpjtsVREdG8e2aWlrYbT1TiCp0P1qUMg'
));

$code = $connection->request("GET", $connection->url("1.1/statuses/mentions_timeline.json"), array(
		'count' => 200
	)
);

$file = 'data.json';
$data = $connection->response['response'];
file_put_contents($file, $data);
?>