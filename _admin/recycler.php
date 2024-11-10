<?php
/*
 * Created on 22.03.2009
 *
 * Tool zum Loeschen und Umbennene der nicht verwendeten Bilder, PDFs und Stichwort-Tags
 */
 require_once('base.inc.php');
 require_once('_functions/main_admin.php');


 function getForm($file, $action, $name) {
 	if (isset($_POST['submit'])) {
 		if ($action == 'del' && $file == 'pic') {
            // Loeschen der verwaisten Bilder
			$db = new DB_AdminClass();
 			$connect_error=$db->Connect();
 			$del = $db->DelPic($_POST['file_name']);
 			if ($del == TRUE) {
 				$delpic = unlink(ROOT."_uploads/_img/".$_POST['file_name']);
 				$delthumb = unlink(ROOT."_uploads/_img/_thumbs/".$_POST['file_name']);
				if ($delpic == TRUE && $delthumb == TRUE) {
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>Das Bild wurde gel&ouml;scht!</p>';
				}
			} else {$form .= '<p class="red"><b>Das Bild konnte nicht gel&ouml;scht werden!</b></p>';}
		} elseif ($action == 'del' && $file == 'pdf') {
            // Loeschen der verwaisten PDFs
 			$delpic = unlink(ROOT."_uploads/_pdfs/".$_POST['file_name']);
				if ($delpic == TRUE) {
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>Die Datei wurde gel&ouml;scht!</p>';
				} else {$form .= '<p class="red"><b>Das PDF konnte nicht gel&ouml;scht werden!</b></p>';}
		} elseif ($action == 'del' && $file == 'tag') {
            // Loeschen der Stichworte
			$db = new DB_AdminClass();
 			$connect_error=$db->Connect();
 			$deltag = $db->DelTag($_POST['id']);
 			if ($deltag->Error == TRUE) {
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>'.$deltag->Error.'</p>';
				} else {$form .= '<p class="red"><b>Der Tag konnte nicht gel&ouml;scht werden!</b></p>';}
 			$db->close();
		} elseif ($action == 'del' && $file == 'name') {
            // Loeschen der Namen
			$db = new DB_AdminClass();
 			$connect_error=$db->Connect();
 			$delname = $db->DelName($_POST['id']);
 			if ($delname->Error == TRUE) {
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>'.$delname->Error.'</p>';
				} else {$form .= '<p class="red"><b>Der Name konnte nicht gel&ouml;scht werden!</b></p>';}
 			$db->close();
		} elseif ($action == 'edit' && $file == 'pic') {
            // Aendern der verwaisten Bilder
			$editpic = @rename(ROOT."_uploads/_img/".$_POST['old_file_name'], ROOT."_uploads/_img/".$_POST['file_name']);
			$editthumb = @rename(ROOT."_uploads/_img/_thumbs/".$_POST['old_file_name'], ROOT."_uploads/_img/_thumbs/".$_POST['file_name']);
				if ($editpic == TRUE && $editthumb == TRUE) {
				    $db = new DB_AdminClass();
 					$connect_error=$db->Connect();
 					$db->RenamePic($_POST['old_file_name'],$_POST['file_name']);
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>Das Bild wurde umbenannt!</p>';
				} else {$form .= '<p class="red"><b>Das Bild konnte nicht umbenannt werden!</b><br />
				Entweder ist das Bild schreibgesch&uuml;tzt oder ein Bild mit dem Namen existiert bereits</p>';}
		} elseif ($action == 'edit' && $file == 'pdf') {
            // Aendern der verwaisten PDFs
 			$editpdf = @rename(ROOT."_uploads/_pdfs/".$_POST['old_file_name'], ROOT."_uploads/_pdfs/".$_POST['file_name']);
				if ($editpdf == TRUE) {
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>Die Datei wurde umbenannt!</p>';
				} else {$form .= '<p class="red"><b>Das PDF konnte nicht umbenannt werden!</b><br />
				Entweder ist das PDF schreibgesch&uuml;tzt oder eine Datei mit dem Namen existiert bereits</p>';}
		} elseif ($action == 'edit' && $file == 'tag') {
            // Aendern der Stichworte
			$db = new DB_AdminClass();
 			$connect_error=$db->Connect();
 			$rename = $db->RenameTag($_POST['id'], $_POST['file_name'], $_POST['is_name']);
 			if ($rename->Error == TRUE) {
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>Der Tag wurde umbenannt!</p>';
				} else {$form .= '<p class="red"><b>Der Tag konnte nicht umbenannt werden!</b></p>';}
 			$db->close();
		} elseif ($action == 'edit' && $file == 'name') {
            // Aendern  der Namen
			$db = new DB_AdminClass();
 			$connect_error=$db->Connect();
 			$rename = $db->RenameName($_POST['id'], $_POST['file_name'], $_POST['is_name']);
 			if ($rename->Error == TRUE) {
					header('Location: recycler.php?files='.$file.'&amp;'.$action.'_'.file);
					$form .= '<p>Der Name wurde umbenannt!</p>';
				} else {$form .= '<p class="red"><b>Der Name konnte nicht umbenannt werden!</b></p>';}
 			$db->close();
		}
 	}else {
        // Die dritte Spalte mit den Formularfeldern zum Aendern oder Loeschen
	 	if ($file == 'tag') $submit_name = 'Stichwort';
        if ($file == 'name') $submit_name = 'Name';
	 	if ($file == 'pic') $submit_name = 'Bild';
	 	if ($file == 'pdf') $submit_name = 'PDF-Datei';
	 	if ($action == 'edit') $submit_action = 'umbenennen';
	 	if ($action == 'del') $submit_action = 'l&ouml;schen';
	 	$form ='<div class="spaltenheadline">&nbsp;</div>';
	 	if ($file != 'tag' && $file != 'name') {
            // Formular fuer Bilder oder PDFs
		 	$form .= '<form method="post" action="recycler.php?files='. $file .'&amp;'.$action.'_'.$file.'" accept-charset="UTF-8">';
			    if ($action == 'edit' && $file != 'tag') {
			    	$form .= '<p>Bitte verwenden Sie keine Leer- oder Sonderzeichen, sondern Binde- oder Unterstriche,
			    	sowie ae, oe, ue und ss.</p>';
			    }
		    $form .= '&nbsp; <input type="hidden" name="old_file_name" value="'.$name.'" />';
		    $form .= '&nbsp;<input type="text" name="file_name" value="'.$name.'" style="width: 250px" /><br /><br /><br />';
		    $form .= '&nbsp;<input type="submit" name="submit" value="'.$submit_name.' '.$submit_action.'" /></form>';
		 } else {
            // Formular fuer Stichworte oder Namen
		 	$db = new DB_AdminClass();
 			$connect_error=$db->Connect();
            if($file == 'tag') {
                $tname = $db->GetTag($name);
            }
            if($file == 'name') {
                $tname = $db->GetName($name);
            }
            
		 	$form .= '<form method="post" action="recycler.php?files='. $file .'&amp;'.$action.'_'.$file.'" accept-charset="UTF-8">';
		 	$form .= '<input type="hidden" name="id" value="'.$name.'" /> <input type="hidden" name="old_file_name" value="'.$tname['tag_name'].'" />';
            ($tname['is_name'] == 1) ? $check = ' checked="checked"' : $check = FALSE;
            $form .= '<span style="padding-left: 5px">Angabe</span><span style="padding-left: 150px">(ist ein Name)</span><br /><br />';
		    $form .= '&nbsp;<input type="text" name="file_name" value="'.$tname['tag_name'].'" class="tag_name" /> <input type="checkbox" name="is_name" value="1"'.$check.' class="tag_check" /><br /><br />';
		    $form .= '&nbsp;<input type="submit" name="submit" value="'.$submit_name.' '.$submit_action.'" /></form>';
			$db->close();
		 }
	}

    return $form;
 	}

// Methode fuer die Bilder
 function show_allpics() {
 	$db = new DB_AdminClass();
 	$connect_error=$db->Connect();
 	if ($connect_error){
		$con .=  '<div><h2>Kein Connect zur Datenbank!</h2>
		<p>Versuchen Sie es sp&auml;ter noch einmal</p></div>';
	}else{
 		$a = 0;
 		$all_pics = $db->GetAllPics();
 		if ($all_pics[0]['pic_id']!='') {
		foreach($all_pics as $pic) {
 			$subnav = $db->CheckPic($pic['pic_name']);
	 			if ($subnav == FALSE) {
	 			$a++;
	 			/* Hier wird ein Thumbnail erzeugt */
	 			$img_size = getimagesize(ROOT."_uploads/_img/".$pic['pic_name']);
	 			if ($img_size[0]>=$img_size[1]) {
	 				$height = 40;
	 				$width = round($img_size[0] / ($img_size[1]/40));
	 			} else {
					$width = 40;
	 				$height = round($img_size[1] / ($img_size[0]/40));
	 			}
	 			$con .= '<p class="edit"><a href="?files=pic&amp;del_pic='.$pic['pic_name'].'">Bild l&ouml;schen</a> | <a href="?files=pic&amp;edit_pic='.$pic['pic_name'].'">Bild umbenennen</a></p>';
	 			$con .= '<p><a href="../_uploads/_img/'.$pic['pic_name'].'" target="new"><img src="../_uploads/_img/'.$pic['pic_name'].'" width="'.$width.'" height="'.$height.'"  alt="'.$entry.'" style="vertical-align: middle" /> &nbsp;'. $entry .'</a></p>';
	 			}
 			}
 		  if ($a==0) {$con = '<p>Keine verwaisten Bilder vorhanden!</p>';}
 		  } else {
 		  $con .= '<p>Keine Bilder vorhanden!</p>';
 		  }

	$db->close();
	}
 	return $con;
 }

 // Methode fuer die PDFs
 function show_allpdfs() {
 	$db = new DB_AdminClass();
 	$connect_error=$db->Connect();
 	if ($connect_error){
		$con .=  '<div><h2>Kein Connect zur Datenbank!</h2>
		<p>Versuchen Sie es sp&auml;ter noch einmal</p></div>';
	}else{
 		$path = ROOT."_uploads/_pdfs";
 		chdir($path);
 		$a == 0;
		$cdir = dir($path);
 		while ($entry = $cdir->read()) {
 			if(eregi('\.(pdf)$',$entry)) {
 			$subnav = $db->CheckPDF($entry);
	 			if ($subnav == FALSE) {
	 			$a++;
	 			$con .= '<p class="edit"><a href="?files=pdf&amp;del_pdf='.$entry.'">PDF l&ouml;schen</a> | <a href="?files=pdf&amp;edit_pdf='.$entry.'">PDF umbenennen</a></p>';
	 			$con .= '<p><a href="../_uploads/_pdfs/'.$entry.'" target="new"><img src="../_templates/_img/pdf.gif" width="8" height="11"  alt="'.$entry.'" border="0" style="vertical-align: middle" /> '. $entry .'</a></p>';
	 			}
 			}
 		}
 		if ($a==0) {
 			$con .= '<p>Keine verwaisten PDFs vorhanden!</p>';
 			}
	$db->close();
	}
 	return $con;
 }

// Methode fuer die Stichworte
 function show_alltags() {
 	$db = new DB_AdminClass();
 	$connect_error=$db->Connect();
 	if ($connect_error) {
		$con .=  '<div><h2>Kein Connect zur Datenbank!</h2>
		<p>Versuchen Sie es sp&auml;ter noch einmal</p></div>';
	}else{
	$res = $db->GetAllTags();
        if($res[0]['tag_id']!=FALSE) {
            foreach ($res as $tag) {
                $con .= '<p class="edit"><a href="?files=tag&amp;del_tag='.$tag['tag_id'].'">Tag l&ouml;schen</a> | <a href="?files=tag&amp;edit_tag='.$tag['tag_id'].'">Tag umbennen</a></p>';
                $con .= '<p>'.$tag['tag_name'].'</p>';
            }
        }
	$db->close();
	}
 	return $con;
 }
 
 // Methode fuer die Namen
 function show_allnames() {
 	$db = new DB_AdminClass();
 	$connect_error=$db->Connect();
 	if ($connect_error) {
		$con .=  '<div><h2>Kein Connect zur Datenbank!</h2>
		<p>Versuchen Sie es sp&auml;ter noch einmal</p></div>';
	}else{
	$res = $db->GetAllNames();
        if($res[0]['tag_id']!=FALSE) {
            foreach ($res as $tag) {
                $con .= '<p class="edit"><a href="?files=name&amp;del_name='.$tag['tag_id'].'">Name l&ouml;schen</a> | <a href="?files=name&amp;edit_name='.$tag['tag_id'].'">Name umbennen</a></p>';
                $con .= '<p>'.$tag['tag_name'].'</p>';
            }
        }
	$db->close();
	}
 	return $con;
 }

 $cmd_file = stripslashes($_GET['files']);

 switch ($cmd_file) {
 case 'pic':
 	// $all_files = '<div class="spaltenheadline"><img src="../_templates/_img/pics_icon.png" alt="verwaiste Bilder" /><br /><br /></div>';
 	$all_files .= show_allpics();
 break;
  case 'pdf':
 	// $all_files = '<div class="spaltenheadline"><img src="../_templates/_img/dateien_icon.gif" alt="verwaiste PDF-Files" /><br /><br /></div>';
 	$all_files .= show_allpdfs();
 break;
 case 'tag':
 	// $all_files = '<div class="spaltenheadline"><img src="../_templates/_img/tags_icon.png" alt="verwendete Stichworte" /><br /><br /></div>';
	$all_files .= show_alltags();
 break;
 case 'name':
 	// $all_files = '<div class="spaltenheadline"><img src="../_templates/_img/tags_icon.png" alt="verwendete Namen" /><br /><br /></div>';
	$all_files .= show_allnames();
 break;
 default:
 	$all_files = '<br /><p>Bitte w&auml;hlen Sie eine Funktion!</p>';
 break;
 }

 if (isset($_GET['del_pic'])) {
 $form_files = getForm('pic', 'del', $_GET['del_pic']);
 } elseif (isset($_GET['edit_pic'])) {
 $form_files = getForm('pic', 'edit', $_GET['edit_pic']);
 } elseif (isset($_GET['del_pdf'])) {
 $form_files = getForm('pdf', 'del', $_GET['del_pdf']);
 } elseif (isset($_GET['edit_pdf'])) {
 $form_files = getForm('pdf', 'edit', $_GET['edit_pdf']);
 } elseif (isset($_GET['del_tag'])) {
 $form_files = getForm('tag', 'del', $_GET['del_tag']);
 }elseif (isset($_GET['edit_tag'])) {
 $form_files = getForm('tag', 'edit', $_GET['edit_tag']);
 }elseif (isset($_GET['del_name'])) {
 $form_files = getForm('name', 'del', $_GET['del_name']);
 }elseif (isset($_GET['edit_name'])) {
 $form_files = getForm('name', 'edit', $_GET['edit_name']);
 }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
<head>
	<title>Admin-Oberfl&auml;che</title>
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

<!--a href="<? echo '?cmd=logout&amp;id='.$id[0].'_'.$id[1].'_'.$id[2].'"';?> class="hl"><b>Logout</b></a--> <a href="_admin.php"><b>Admin</b></a></p>

<p class="Ueberschrift" >HK PLib v.03 Editing Area</p>
</div>
<div class="headbg">
</div>

<div class="clear"></div>

<div class="container2">
	<div class="spalte">
	<div class="spaltenheadline"><!-- <img src="../_templates/_img/dateien_icon.gif" alt="Dateien" /> --></div>
	<!-- p class="editheadline">verweiste Bilder bearbeiten</p>
	<p><a href="recycler.php?files=pic" class="grey">Alle verwaisten Bilder anzeigen</a><br />
	<br /></p --><!-- diese funktion ist in dieser Plib-Version noch nicht implementiert -->
	<!--p class="editheadline">verweiste PDFs bearbeiten</p>
	<p><a href="recycler.php?files=pdf" class="grey">Alle verwaisten PDFs anzeigen</a><br />
	<br /></p --><!-- diese funktion ist in dieser Plib-Version noch nicht implementiert -->
	<p class="editheadline">Tags bearbeiten</p>
	<p><a href="recycler.php?files=tag" class="grey">Vergebene Stichworte bearbeiten</a><br />
	<br /></p>
    <p class="editheadline">Namen bearbeiten</p>
	<p><a href="recycler.php?files=name" class="grey">Vergebene Namen bearbeiten</a><br />
	<br /></p>
	</div>
	<div class="spalte extra" style="width: 240px">
	<? print $all_files; ?>
	</div>
	<div class="spalte3">
	<? print $form_files; ?>
	</div>
</div>
</body>
</html>