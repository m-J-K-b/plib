<?php

include_once('base.inc.php');
include_once('_functions/main_admin.php');

$db = new DB_AdminClass();
$connect_error = $db->Connect();
 if ($connect_error){
	$tagliste .=  '<h3>Kein Connect zur Datenbank!</h2>
	<p>Versuchen Sie es sp&auml;ter noch einmal</p>';
 } else {
 	$result = $db->GetAllTags();
 	if ($result[0]['tag_name']!='') {
		foreach ($result as $tag) {
			$tagliste .= '<p>'.$tag['tag_name'].'</p>';
		}
	} else {
		$tagliste .= '<p>Keine Tags vorhanden!</p>';
	}
	$db->close();
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
<head>
	<title>PLib v02 - Taglist</title>
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="content-style-type" content="text/css" />
	<link rel="stylesheet" href="../_css/css.css" type="text/css" />
</head>

<body>
<div class="container2" style="display: block; width: 550px; margin: 10px 5px 20px 10px;">
<h3>Liste der verwendeten Schlagworte:</h3>
<p>Verschaffen Sie sich hier einen Überblick über die bisher verwendeten Schlagworte.</p>

<p>Für den aktuell im Backend bearbeiteten Eintrag können Sie die hier gelisteten Schlagworte
mit der Maus selektieren (ganz so, als wäre dies ein Dokument Ihres gewohnten Textprogramms wie z.B. Word). <br />
Kopieren Sie das selektierte Schlagwort und setzen Sie es dann in die Schlagwortliste Ihres neuen Eintrags ein
(Copy + Paste). Es ist durchaus ratsam, Schlagworte mehrfach zu verwenden, da erst so Themengruppen entstehen können.</p>
<p>&nbsp;</p>
<?php print $tagliste; ?>
</div>
</body>
</html>