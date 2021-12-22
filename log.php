<?
$data = base64_decode($_GET['DATA']);
file_put_contents(__DIR__.'/log.log', "{$data}");

?>