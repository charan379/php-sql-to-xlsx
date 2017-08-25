<?php
require_once("db-class.php");
require_once("xlsxwriter.class.php");
ini_set("display_errors", 1);
ini_set("log_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);


function getData() {
	$db = new MY_SQLDB();
	$sql = "SELECT * FROM mailid";
	$rows = $db->get_rows($sql);
	$sheet_titles = $db->get_column_names();
	$data = array_merge(array(), $rows);
	array_unshift($data , $sheet_titles);
	$db->close_connection();
	return $data;
}

$data = getData();
$filename = "data.xlsx";

$writer = new XLSXWriter();
$writer->writeSheet($data);
$writer->writeToFile($filename);


header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile($filename);
exit(0);

?>
