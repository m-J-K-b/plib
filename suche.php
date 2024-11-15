<?php

$cap = $_GET['cap'];
$tagid = $_GET['tagid'];
$nameid = $_GET['nameid'];
$tname = urldecode($_GET['tname']);
$nname = urldecode($_GET['nname']);
include_once('_admin/base.inc.php');

$db = new DB_MainClass();
$connect_error = $db->Connect();
 if (!$connect_error) {
	$sql = "SELECT * FROM pdf_nav WHERE visible = 1 ORDER BY sort_id;";
	$res = $db->GetArray($sql);
	if ($res[0]['nav_id']!='') {
		foreach ($res as $nav) {
		$navi .= '<span><a href="index.php?id='.$nav['nav_id'].'_0_0">'.$nav['nav_intname'].'</a></span>';
		}
	}
	}
$db->close();

if (!empty($tagid)) {
    $tresult = '<p>Einträge, die das Schlagwort <b>'.$tname.'</b> verwenden:</p>';
	$db = new DB_MainClass();
	$connect_error = $db->Connect();
	if (!$connect_error) {
		$sql = "SELECT f.files_id, f.topic_id, t.subnav_id, s.nav_id, f.files_head FROM pdf_file f, main_kat k, pdf_topic t, pdf_subnav s, pdf_nav n, pdf_tags p WHERE n.nav_id = s.nav_id AND t.subnav_id = s.subnav_id AND t.topic_id = f.topic_id AND n.visible = 1 AND s.visible = 1 AND (t.visible = 1 OR t.topic_head = 'Direkt') AND f.visible=1 AND k.file_id = f.files_id AND k.tag_id = p.tag_id AND p.tag_id = ".$tagid." AND p.is_name = 0 ORDER BY f.files_head;";
		$res = $db->GetArray($sql);
		if ($res[0]['files_id']!='')
			foreach ($res as $tag) {
				$tresult .= '<p><a href="index.php?id='.$tag['nav_id'].'_'.$tag['subnav_id'].'_'.$tag['topic_id'].'#id'.$tag['files_id'].'" class="subNavilink">'.$tag['files_head'].'</a></p>';
		} else {
		$tresult .= '<p>Es sind keine Artikel mit diesem Stichwort verlinkt!</p>';
		}
	} else {
		$result = '<p>Kein Kontakt zur Datenbank!</p>';
	}
 } else { $tresult = ''; }
 
 if (!empty($nameid)) {
    $nresult = '<p>Einträge, die den Namen <b>'.$nname.'</b> verwenden:</p>';
	$db = new DB_MainClass();
	$connect_error = $db->Connect();
	if (!$connect_error) {
		$sql = "SELECT f.files_id, f.topic_id, t.subnav_id, s.nav_id, f.files_head FROM pdf_file f, main_kat k, pdf_topic t, pdf_subnav s, pdf_nav n, pdf_tags p WHERE n.nav_id = s.nav_id AND t.subnav_id = s.subnav_id AND t.topic_id = f.topic_id AND n.visible = 1 AND s.visible = 1 AND (t.visible = 1 OR t.topic_head = 'Direkt') AND f.visible=1 AND k.file_id = f.files_id AND k.tag_id = p.tag_id AND p.tag_id = ".$nameid." AND p.is_name = 1 ORDER BY f.files_head;";
		$res = $db->GetArray($sql);
		if ($res[0]['files_id']!='')
			foreach ($res as $tag) {
				$nresult .= '<p><a href="index.php?id='.$tag['nav_id'].'_'.$tag['subnav_id'].'_'.$tag['topic_id'].'#id'.$tag['files_id'].'" class="subNavilink">'.$tag['files_head'].'</a></p>';
		} else {
		$nresult .= '<p>Es sind keine Artikel mit diesem Namen verlinkt!</p>';
		}
	} else {
		$result = '<p>Kein Kontakt zur Datenbank!</p>';
	}
  } else { $nresult = ''; }
  
 

if (!empty($cap)) {
    
	$db = new DB_MainClass();
	$connect_error = $db->Connect();
    $tresult = $nresult = '';
    
	if (!$connect_error) {
        $tresult = '<p>Schlagworte beginnend mit <b>'.$cap.'</b> :</p>';
		$tsql = "SELECT DISTINCT t.tag_id, t.tag_name FROM pdf_tags t, main_kat k, pdf_file f, pdf_topic o, pdf_subnav s, pdf_nav n WHERE t.tag_name LIKE '".$cap."%' AND f.topic_id = o.topic_id AND o.subnav_id = s.subnav_id AND n.nav_id = s.nav_id AND n.visible = 1 AND s.visible = 1 AND (o.visible = 1 OR o.topic_head = 'Direkt') AND f.visible = 1 AND k.tag_id = t.tag_id AND f.files_id = k.file_id AND t.is_name = 0 ORDER BY t.tag_name;";
		$tres = $db->GetArray($tsql);

		if ($tres[0]['tag_id']!='') {
			foreach ($tres as $tag) {
                $ttsql= "SELECT f.files_id FROM pdf_file f, pdf_topic t WHERE f.topic_id = t.topic_id AND t.subnav_id = ".$tag['subnav_id']." AND (t.topic_head!='Direkt' OR t.visible =1);";
                $count = $db->Records($ttsql);
                if ($count<2) {
                    $tresult .= '<p><a href="?tagid='.$tag['tag_id'].'&amp;tname='.urldecode($tag['tag_name']).'" class="subNavilink">'.$tag['tag_name'].'</a></p>';
                }
            }
		} else {
		$tresult .= '<p>Es gibt noch keine Stichworte mit '.$cap.'!</p>';
		}
        
        $nresult = '<p>Namen beginnend mit <b>'.$cap.'</b> :</p>';
        $nsql = "SELECT DISTINCT t.tag_id, t.tag_name FROM pdf_tags t, main_kat k, pdf_file f, pdf_topic o, pdf_subnav s, pdf_nav n WHERE t.tag_name LIKE '".$cap."%' AND f.topic_id = o.topic_id AND o.subnav_id = s.subnav_id AND n.nav_id = s.nav_id AND n.visible = 1 AND s.visible = 1 AND (o.visible = 1 OR o.topic_head = 'Direkt') AND f.visible = 1 AND k.tag_id = t.tag_id AND f.files_id = k.file_id AND t.is_name = 1 ORDER BY t.tag_name;";
		$nres = $db->GetArray($nsql);

		if ($nres[0]['tag_id']!='') {
			foreach ($nres as $tag) {
                $ntsql= "SELECT f.files_id FROM pdf_file f, pdf_topic t WHERE f.topic_id = t.topic_id AND t.subnav_id = ".$tag['subnav_id']." AND (t.topic_head!='Direkt' OR t.visible =1);";
                $count = $db->Records($ntsql);
                if ($count<2) {
                    $nresult .= '<p><a href="?nameid='.$tag['tag_id'].'&amp;nname='.urldecode($tag['tag_name']).'" class="subNavilink">'.$tag['tag_name'].'</a></p>';
                }
            }
		} else {
		$nresult .= '<p>Es gibt noch keine Namen mit '.$cap.'!</p>';
		}
        
       
	} else {
		$tresult = '<p>Kein Kontakt zur Datenbank!</p>';
	}
    
 }

 $db = new DB_MainClass();
	$connect_error = $db->Connect();
	if (!$connect_error) {

		$tsql = "SELECT DISTINCT t.tag_id, t.tag_name FROM pdf_tags t, main_kat k, pdf_file f, pdf_topic o, pdf_subnav s, pdf_nav n WHERE f.files_id = k.file_id AND f.topic_id = o.topic_id AND o.subnav_id = s.subnav_id AND n.nav_id = s.nav_id AND n.visible = 1 AND s.visible = 1 AND (o.visible = 1 OR o.topic_head = 'Direkt') AND f.visible = 1 AND k.tag_id = t.tag_id AND t.is_name = 0 ORDER BY t.tag_name ASC;";
		$res = $db->GetArray($tsql);
		if ($res[0]['tag_id']!='') {
			foreach ($res as $tag) {
				$tstart .= '<li>'.$sub.'<a href="?tagid='.$tag['tag_id'].'&amp;tname='.urlencode($tag['tag_name']).'" class="subNavilink">'.$tag['tag_name'].'</a></li>';
			}
		} else {
		$tstart = '<li>Es gibt noch keine Stichworte!</li>';
		}
        
        $nsql = "SELECT DISTINCT t.tag_id, t.tag_name FROM pdf_tags t, main_kat k, pdf_file f, pdf_topic o, pdf_subnav s, pdf_nav n WHERE f.files_id = k.file_id AND f.topic_id = o.topic_id AND o.subnav_id = s.subnav_id AND n.nav_id = s.nav_id AND n.visible = 1 AND s.visible = 1 AND (o.visible = 1 OR o.topic_head = 'Direkt') AND f.visible = 1 AND k.tag_id = t.tag_id AND t.is_name = 1 ORDER BY t.tag_name ASC;";
		$res = $db->GetArray($nsql);
		if ($res[0]['tag_id']!='') {
			foreach ($res as $tag) {
				$nstart .= '<li>'.$sub.'<a href="?nameid='.$tag['tag_id'].'&amp;nname='.urlencode($tag['tag_name']).'" class="subNavilink">'.$tag['tag_name'].'</a></li>';
			}
		} else {
		$nstart = '<li>Es gibt noch keine Namen!</li>';
		}
	} else {
		$tstart = '<li>Kein Kontakt zur Datenbank!</l>';
	}





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$LANG}">
<head>
  <title>HK's Files Index Search </title>

	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<meta http-equiv="pragma" content="cache" />
	<meta name="Keywords" lang="de" content="PDF" />

	<meta name="Description" lang="de" content="Text" />
	<meta name="author" content="" />
	<meta http-equiv="Content-Language" content="de" />
	<link rel="shortcut icon" href="favicon.ico" />

  <script type="text/javascript" language="JavaScript" src="_templates/_script/main.js"></script>
  <link rel="stylesheet" href="_css/css.css" type="text/css" />

  	<!--[if IE 7]>
	<style type="text/css">@import url(http://horstkaechele.de/plib/_templates/_css/plib_IE7.css);</style>
	<![endif]-->
	<!--[if lte IE 6]>
	<style type="text/css">@import url(http://horstkaechele.de/plib/_templates/_css/plib_IE6.css);</style>
	<![endif]-->

</head>

<body>

<div class="container1">
<a name="top" id="top"> </a>

<p class="navpos">
	<a href="../index.html" class="hl">Home</a>

	<a href="../plib/" class="hl">HK's Files</a>

	<a href="../fotagen_pics/phpslideshow.php" class="hl">Nonverbales</a>

	<a href="../pg/cv.html" class="hl">HK's CV</a>

	<a href="../../gb2008work/gb.php" class="hl">Guestbook</a>

	<a href="../pg/impressum.html" class="hl">Impressum</a>
	
	<a href="javascript:history.back()">zur&uuml;ck</a>
</p>

<p class="Ueberschrift" >HK's Files <b style="font-size:10px; float:right;margin-top:-22px;margin-right:5px;">PLib v.03</b></p>


<div class="Navigation" >
 	<?php print $navi; ?>
</div>

</div>

<div class="headbg">
</div>

<div class="clear"></div>

<div class="container2">

<div class="introtext2">Index Search</div>

<div class="alphabet">

 <h2></h2>
 <ul>
 	<li><a href="?cap=A" class="subNavilink">[ A ]</a></li>
 	<li><a href="?cap=B" class="subNavilink">[ B ]</a></li>
 	<li><a href="?cap=C" class="subNavilink">[ C ]</a></li>
 	<li><a href="?cap=D" class="subNavilink">[ D ]</a></li>
 	<li><a href="?cap=E" class="subNavilink">[ E ]</a></li>
 	<li><a href="?cap=F" class="subNavilink">[ F ]</a></li>
 	<li><a href="?cap=G" class="subNavilink">[ G ]</a></li>
 	<li><a href="?cap=H" class="subNavilink">[ H ]</a></li>
 	<li><a href="?cap=I" class="subNavilink">[ I ]</a></li>
 	<li><a href="?cap=J" class="subNavilink">[ J ]</a></li>
 	<li><a href="?cap=K" class="subNavilink">[ K ]</a></li>
 	<li><a href="?cap=L" class="subNavilink">[ L ]</a></li>
 	<li><a href="?cap=M" class="subNavilink">[ M ]</a></li>
 	<li><a href="?cap=N" class="subNavilink">[ N ]</a></li>
 	<li><a href="?cap=O" class="subNavilink">[ O ]</a></li>
 	<li><a href="?cap=P" class="subNavilink">[ P ]</a></li>
 	<li><a href="?cap=Q" class="subNavilink">[ Q ]</a></li>
 	<li><a href="?cap=R" class="subNavilink">[ R ]</a></li>
 	<li><a href="?cap=S" class="subNavilink">[ S ]</a></li>
 	<li><a href="?cap=T" class="subNavilink">[ T ]</a></li>
 	<li><a href="?cap=U" class="subNavilink">[ U ]</a></li>
 	<li><a href="?cap=V" class="subNavilink">[ V ]</a></li>
 	<li><a href="?cap=W" class="subNavilink">[ W ]</a></li>
 	<li><a href="?cap=X" class="subNavilink">[ X ]</a></li>
 	<li><a href="?cap=Y" class="subNavilink">[ Y ]</a></li>
 	<li><a href="?cap=Z" class="subNavilink">[ Z ]</a></li>
 	<li> <b>|</b><br /> </li>
 	<li><a href="?cap=Ä" class="subNavilink">[ Ä ]</a></li>
 	<li><a href="?cap=Ö" class="subNavilink">[ Ö ]</a></li>
 	<li><a href="?cap=Ü" class="subNavilink">[ Ü ]</a></li>
 </ul>

<div class="results">
<!-- <h2>Ergebnis</h2> -->
<?php
if(!empty($tresult)) {	
    print $tresult;	
    print '<p>&nbsp;</p>';
  } ?>
  </div>
  <div class="results" style="float:right;">
  <?php
  if (!empty($nresult)) {	  
    print $nresult;    
} else {
	print '<!-- p>Bitte w&auml;hlen Sie ein Schlagwort oder einen Namen aus!</p -->';
	}
?>
</div>
<div class="clear"></div>

<div class="alltags">
<h2>Stichwortliste</h2>
<ul>
 <!-- PHP-TAG-LIST START -->
 	<?php print $tstart; ?>
<!-- PHP-TAG-LIST END -->

</ul>
</div>

<div class="alltags" style="float:right;">
<h2>Liste aller Namen</h2>
<ul>
 <!-- PHP-TAG-LIST START -->
 	<?php print $nstart; ?>
<!-- PHP-TAG-LIST END -->
<br style="clear: both" />
</ul>
</div>


</div>
</div>


<div id="footer"><p class="left">Plib version 02 | 10-2008</p><p class="right">PLib + visual concept + design <a href="http://www.jcc-hamburg.com" target="_blank">JCC-hamburg 2008</a></p></div>

</body>
</html>