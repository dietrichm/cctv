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
    curl_setopt($c, CURLOPT_TIMEOUT, 2);
    $success = curl_exec($c);
    curl_close($c);

    if (!$success) {
        readfile('./offline.jpg');
    }

    exit;
}

echo <<<EOT
<style type="text/css">
html, body, p, img {
    margin: 0; padding: 0;
}
img {
    width: 100%;
}
@media screen and (min-device-width: 800px) {
    img {
        width: 50%;
    }
}
</style>
EOT;

foreach ($images as $id => $image) {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?image=' . $id . '&t=' . time();
    echo '<img src="' . $url . '" name="' . $image['name'] . '" />';
}
