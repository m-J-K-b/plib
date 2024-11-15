<?php


/* if (!$_COOKIE['pass'])
{
header ("LOCATION: ../index.php");
}else{

session_start();*/

include_once('base.inc.php');
include_once('_functions/main_admin.php');
include_once('_functions/nav_admin.php');
include_once('_functions/subnav_admin.php');
include_once('_functions/topic_admin.php');
include_once('_functions/file_admin.php');


/* Funktion fuer den Logout - einfach den Cookie ueberschreiben, fertig! */

function LogOut($id)
{
setcookie ("pass","-",time()+(1),"/_admin");
header ("LOCATION: ../index.php?id=".$id);
}

/* Aufloesung der URI-Anhaenge */

$stat_content = FALSE;

if (!$_GET['id']) : $id = '1_0_0'; else: $id = $_GET['id']; endif;

$id = explode('_',$id);



$nav = GetNav($id[0]);

switch ($_GET['cmd'])
{
case 'nav';
$content = Nav($_REQUEST['action'],$_REQUEST['id']);
break;
case 'subnav';
$content = SubNav($_REQUEST['action'],$_REQUEST['id']);
break;
case 'topic';
$content = Topic($_REQUEST['action'],$_REQUEST['id']);
break;
case 'files';
$content = Files($_REQUEST['action'], $_REQUEST['id'], $_REQUEST['files_id']);
break;
case 'logout';
$content = LogOut($_REQUEST['id']);
break;
default:
$stat_content = '<div><h2>Willkommen im Admin-Bereich!</h2>
<p>Sie haben hier verschiedene M&ouml;glichkeiten Daten anzusehen, zu erstellen und zu manipulieren.</p></div>';
break;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
<head>
	<title>HK_v02_Update_Admin-Oberfl&auml;che</title>
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="content-style-type" content="text/css" />
	<link rel="stylesheet" href="../_css/css.css" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="../_templates/_script/main.js"></script>
</head>
<body>

<div class="container1"><a name="top"></a>

<p class="navpos">

<a href="../../index.html" class="hl">Home</a>

<a href="../../plib/" class="hl">HK's Files</a>

<a href="../../pg/cv.html" class="hl">HK's CV</a>

<a href="../../pg/impressum.html" class="hl">Impressum</a>

<a href="<? echo '?cmd=logout&amp;id='.$id[0].'_'.$id[1].'_'.$id[2].'"';?> class="hl"><b>Logout</b></a> <a href="recycler.php"><b>Archiv</b></a></p>

<p class="Ueberschrift" >HK PLib v.03 Editing Area</p>

<div class="Navigation">
<?php

 $nav_content = navContent($nav, $id[0], $id[1], $id[2]);
 print $nav_content;

?>

</div>
</div>

<div class="headbg">
</div>

<div class="clear"></div>

<div class="container2">

<p class="edit"><a href="?cmd=nav&amp;id=0_0_0&amp;action=make">Navigationspunkt anlegen</a> <?php if($id[0]>0){ print ' | <a href="?cmd=nav&amp;id='.$id[0].'_0_0&amp;action=del">'.$nav_active.' l&ouml;schen</a> | <a href="?cmd=nav&amp;id='.$id[0].'_0_0&amp;action=edit">'.$nav_active.' bearbeiten</a></p>';} ?>

<div class="spalte">
<div class="spaltenheadline"><img src="../_templates/_img/ordner_icon.gif" alt="Subnav" /></div>
	<?php

	$subnav = GetSubnav($id[0]);
	$subnav_content = subnavContent($subnav, $id[0], $id[1], $id[2]);
 	print $subnav_content;

    ?>
</div>

<div class="spalte extra">

	<?php

	if ($id[1]>0) {
		$topic = GetTopic($id[0], $id[1]);
		$topic_content = topicContent($topic, $id[0], $id[1], $id[2]);
		print'<div class="spaltenheadline"><img src="../_templates/_img/themen_icon.gif" alt="Topics" /></div>';
		print $topic_content;
	}
	?></div>
    <div class="spalte3">
	
    <?php
     if (isset($content)) {
	 print '<div class="editcol">'.$content.'</div>';
	 } elseif ($id[2]>0) {
	 print'<div><img src="../_templates/_img/dateien_icon.gif" alt="Files" /></div>';
	 print '<p class="editheadline"><a href="?cmd=files&amp;id='.$id[0].'_'.$id[1].'_'.$id[2].'&amp;action=make">Neuen Eintrag anlegen</a></p>';

		$files = GetFiles($id[2]);

		if (isset($files[0]['files_id'])) {
		foreach ($files as $value)
		{
		print '<p class="edit"><a href="shift.php?shift=file&amp;id='.$id[0].'_'.$id[1].'_'.$value['topic_id'].'&amp;file='.$value['files_id'].'">verschieben</a> | <a href="?cmd=files&amp;id='.$id[0].'_'.$id[1].'_'.$value['topic_id'].'&amp;files_id='.$value['files_id'].'&amp;action=del">l&ouml;schen</a> | <a href="?cmd=files&amp;id='.$id[0].'_'.$id[1].'_'.$value['topic_id'].'&amp;files_id='.$value['files_id'].'&amp;action=edit">bearbeiten</a> </p>';
		if ($value['visible']==0) : print '<p class="hidden">' ; else: print '<p class="pdfdescription">'; endif;
		print '<div class="titel">"'.$value['files_head'].'"</div><div>'.$value['files_text']./*'<br />
		Ver&ouml;ffentlichungsdatum: '.$value['files_date'].*/'</div><div class="pdf">';
        if (file_exists('../_uploads/_pdfs/'.$value['files_name']) && $value['files_name']!='' ) {
              print'<a href="../_uploads/_pdfs/'.$value['files_name'].'" target="_blank"><img src="../_templates/_img/pdf.gif" alt="" />&nbsp;'.$value['files_name'].'</a>';
              } else {print'<b style="color: #F00">Es wurde keine PDF-Datei f&uuml;r diesen Punkt gefunden!</b>';}
         print'</div></p><br />';

		}
	}else{print '<p>Noch sind keine PDF-Files in diesem Bereich vorhanden!</p>';}
	}

    ?></div>


</div>
</body>
</html>
<?php


//}
?>