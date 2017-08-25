<?php
include_once("xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);


function getData() {
	$servername = "localhost";
	$username = "username";
	$password = "password";
	$dbname = "dbname";
 	$data = array();

// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "SELECT * FROM mailid";
	$result = mysqli_query($conn, $sql);


    function mk_headers($val) {
    	return $val->name;
    }

    $sheet_titles = array_map("mk_headers", mysqli_fetch_fields($result));
 	array_push($data, $sheet_titles);

	if (mysqli_num_rows($result) > 0) {
    // output data of each row
		while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			 array_push($data, $row);
		}

	} else {
		echo "0 results";
	}

	mysqli_free_result($result);
	mysqli_close($conn);
	return $data;
}

// print_r(getData());




$data = getData();
$filename = "example.xlsx";

$writer = new XLSXWriter();
$writer->writeSheet($data);
$writer->writeToFile($filename);


header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
    readfile($filename);
// exit(0);


?>
