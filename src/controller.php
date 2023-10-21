<?php

require "./Tile.php";
require "./Download.php";

function show404($code = "404")
{
    header("HTTP/1.0 $code");
    echo "<html lang='en'><head><title>$code</title></head><body><h1>Error $code</h1><p>Error $code: page not found</p></body></html>";
    exit;
}

$config = json_decode(file_get_contents('../config/tileproxy.json'), true);
$tileObj = new Tile($_SERVER['DOCUMENT_URI'], $config["sources"]);
if(! $tileObj->isValid()) { show404();}
$fileObj = new Download($tileObj->getUri(), $config["referrer"]);
if ($fileObj->getHTTPStatusCode() !== 200) { show404($fileObj->getHTTPStatusCode()); }

// return tile:
$headerArr = $fileObj->getHeaders();
foreach($headerArr as $value) { header($value); }
echo $fileObj->getData();

// store tile:
$dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $config["baseDir"] . '/' . $tileObj->getPathName();
if (!file_exists($dir)) { @mkdir($dir, 0777, true); }
file_put_contents($dir . '/' . $tileObj->getFileName(), $fileObj->getData());


