<?php 
define('INCLUDED', true);
require_once('connect.php');

require "vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Write\PngWriter;

$writer = new PngWriter;

$text = $_POST["text"];

$qr_code = QrCode::create($text);

$result = $writer->write($qr_code);

header("Content Type: " . $result->getMimeType());

echo $result->getString();
?>