<?PHP
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>DSSC - Dayz standalone server customizer</title>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body class="uploads">

<?PHP

$uploadOk = 1;
$filetype = strtolower(pathinfo($_FILES["uploadedfile"]["name"], PATHINFO_EXTENSION));
$uploadDir = 'uploads/' . $_COOKIE['cookiemonster'] . '/';

// Check file size
if ($_FILES["uploadedfile"]["size"] > 10000000) {
	die("Sorry, your file is too large. It cannot exceed 1 mb in size");
	$uploadOk = 0;
}

// Allow certain file formats
if($filetype != "xml") {
	die("Sorry, only XML files are allowed.");
	$uploadOk = 0;
}

$approvedFile = FALSE;
$tmpxml = simplexml_load_file($_FILES["uploadedfile"]["tmp_name"]) or die("Upload one of these files: <strong>economy.xml , events.xml , globals.xml , types.xml.</strong> Or your XML file is corrupt or containing errors");
if($tmpxml->getName() == "types") { $filename = 'types_'; $approvedFile = TRUE; }
if($tmpxml->getName() == "economy") { $filename = 'economy_'; $approvedFile = TRUE; }
if($tmpxml->getName() == "events") { $filename = 'events_'; $approvedFile = TRUE; }
if($tmpxml->getName() == "variables") { $filename = 'globals_'; $approvedFile = TRUE; }

if(!$approvedFile) {
	die("Upload one of these files: <strong>economy.xml , events.xml , globals.xml , types.xml. </strong>Or your XML file is corrupt or containing errors");
}

// if everything is ok, try to upload file
$target_file = $uploadDir . basename($_FILES["uploadedfile"]) . $filename . $_SESSION["hash"].'.'.$filetype;

if($uploadOk) {
	if (!file_exists($uploadDir)) {
		mkdir($uploadDir, 0777, true);
		chmod($uploadDir, 0777);
	}

	if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $target_file)) {
		chmod($target_file, 0777);
		header('Location: xml.php?upload');
	} else {
		die("Sorry, there was an error uploading your file.");
	}
}

?>

</body>
</html>