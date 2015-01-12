<?php

$images = array(
	'gang' => array(
		'name' => 'Gang',
		'url' => 'http://192.168.1.15/snapshot.cgi?user=anon&pwd=anon',
	),
	'garage' => array(
		'name' => 'Garage',
		'url' => 'http://192.168.1.16/snapshot.cgi?user=anon&pwd=anon',
	),
);

if (!empty($_GET['image']) && isset($images[$_GET['image']])) {
	$url = $images[$_GET['image']]['url'];
	header('Content-Type: image/jpeg');

	$c = curl_init($url);
	curl_exec($c);
	curl_close($c);

	exit;
}

foreach ($images as $id => $image) {
	$url = $_SERVER['PHP_SELF'] . '?image=' . $id . '&t=' . time();

	echo '<h1>' . $image['name'] . '</h1>';
	echo '<p><img src="' . $url . '" name="' . $image['name'] . '" /></p>';
}
