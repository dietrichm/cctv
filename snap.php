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

echo '<style type="text/css">html, body, p, img { margin: 0; padding: 0; }</style>';

foreach ($images as $id => $image) {
    $url = $_SERVER['PHP_SELF'] . '?image=' . $id . '&t=' . time();
    echo '<p><img src="' . $url . '" name="' . $image['name'] . '" style="width: 100%" /></p>';
}
