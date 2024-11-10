<?php

include_once('base.inc.php');
include_once('_functions/main_admin.php');

$shift = $_REQUEST['shift'];
$id = $_REQUEST['id'];
if (isset($_REQUEST['file'])) : $file = $_REQUEST['file']; else: $file=''; endif;

if ($_REQUEST['cmd'] == 'shift_subnav') {
	$id = explode('_',$_REQUEST['old_link']);

	$db = new DB_AdminClass();
	$connect_error = $db->Connect();
	$change = $db->ShiftSubnav($_REQUEST['old_id'], $_REQUEST['navfolder']);

	header("Location: _admin.php?id=".$_REQUEST['navfolder']."_0_0");
	$db->close();
}

if ($_REQUEST['cmd'] == 'shift_topic') {
	$id = explode('_',$_REQUEST['old_link']);

	$db = new DB_AdminClass();
	$connect_error = $db->Connect();
	$change = $db->ShiftTopic($_REQUEST['old_id'], $_REQUEST['subnavfolder']);

	$backlink = $db->GetTopicBack($_REQUEST['subnavfolder']);

	$backlink = $backlink['nav_id'].'_'.$backlink['subnav_id'].'_0';

	header("Location: _admin.php?id=".$backlink);
	$db->close();
}

if ($_REQUEST['cmd'] == 'shift_file') {
	$id = explode('_',$_REQUEST['old_link']);

	$db = new DB_AdminClass();
	$connect_error = $db->Connect();
	$change = $db->ShiftFile($_REQUEST['old_id'], $_REQUEST['topicfolder']);

	$backlink = $db->GetFileBack($_REQUEST['topicfolder']);

	$backlink = $backlink['nav_id'].'_'.$backlink['subnav_id'].'_'.$backlink['topic_id'];


	header("Location: _admin.php?id=".$backlink);
	$db->close();
}



if (isset($shift) && isset($id)) {

	function ShiftThis ($shift, $link, $file) {
	$id = explode('_',$link);

		$db = new DB_AdminClass();
		$connect_error = $db->Connect();
		if ($connect_error){
			$content .=  '<h3>Kein Connect zur Datenbank!</h3>
			<p>Versuchen Sie es sp&auml;ter noch einmal</p>';
		} else {
		switch($shift) {
		case 'file':
			$this .=  '<form action="shift.php?cmd=shift_file" method="post">';
			$this .=  '<input type="hidden" name="old_id" value="'.$file.'"><input type="hidden" name="old_link" value="'.$link.'">';
			$this .=  "<ul class=\"shift\">\n";
			$navs = $db->GetNav();
			$a=0;
			$b=0;
			$c=0;
			foreach ($navs as $nav) {
				$a++;
				$this .= "<li><a href=\"#\" onclick=\"showElement('subnav".$a."')\">  ".$nav['nav_name']."</a></li>\n";
				$subs = $db->GetSubnav($nav['nav_id']);
				if (isset($subs[0]['subnav_id'])) {
					$this .= "<li><ul style=\"display:none\" id=\"subnav".$a."\" class=\"klapp1\">\n";
					foreach ($subs as $sub) {
					$b++;
					$this .= "<li>   <a href=\"#\" onclick=\"showsubElement('topic".$b."')\">".$sub['subnav_title']."</a></li>\n";

					$topics = $db->GetTopic($sub['subnav_id']);
					if (isset($topics[0]['topic_id'])) {
						$this .= "<li><ul style=\"display:none\" id=\"topic".$b."\" class=\"klapp2\">\n";
						foreach ($topics as $topic) {
							$c++;
							if ($topic['topic_id']==$id[2]) {
								$this .= "<li>   <a href=\"#\" onclick=\"showsubsubElement('file".$c."')\">".$topic['topic_head']."</a> &nbsp; <input type=\"radio\" name=\"topicfolder\" value=\"".$topic['topic_id']."\" checked=\"checked\" /></li>\n";
							} else {
								$this .= "<li>   <a href=\"#\" onclick=\"showsubsubElement('file".$c."')\">".$topic['topic_head']."</a> &nbsp; <input type=\"radio\" name=\"topicfolder\" value=\"".$topic['topic_id']."\" /></li>\n";
							}
							$files = $db->GetFiles($topic['topic_id']);
							if (isset($files[0]['files_id'])) {
								$this .= "<li><ul style=\"display:none\" id=\"file".$c."\" class=\"klapp3\">\n";
								foreach ($files as $file) {
									$this .= "<li>".$file['files_head']."</li>\n";
								}
								$this .= "</ul>\n</li>\n";
							}
						}
						$this .= "</ul>\n</li>\n";
					}
					}
					$this .= "</ul>\n</li>\n";
				}
			}
			$this .=  "</ul>\n";
			$this .=  '<div style="margin:5px 0 0 0px;color:#ffffff;"><span><input type="submit" name="submit" value="verschieben" style="font-size:14px; border:1px solid #d3d3d3; color: #777; padding: 2px 6px 2px 6px; background: #FFF;" /></span> &nbsp; <span><a href="_admin.php?id='.$link.'" style="font-size:12px; border:1px solid #d3d3d3; color:#777; padding: 4px 6px 4px 6px; background: #FFF; text-decoration: none;">abbrechen</a></span></div>';
			$this .=  "</form>";
		break;
		case 'topic':
			$this .=  '<form action="shift.php?cmd=shift_topic" method="post">';
			$this .=  '<input type="hidden" name="old_id" value="'.$id[2].'"><input type="hidden" name="old_link" value="'.$link.'">';
			$this .=  "<ul class=\"shift\">\n";
			$navs = $db->GetNav();
			$a=0;
			$b=0;
			$c=0;
			foreach ($navs as $nav) {
				$a++;
				$this .= "<li><a href=\"#\" onclick=\"showElement('subnav".$a."')\">  ".$nav['nav_name']."</a></li>\n";
				$subs = $db->GetSubnav($nav['nav_id']);
				if (isset($subs[0]['subnav_id'])) {
					$this .= "<li><ul style=\"display:none\" id=\"subnav".$a."\" class=\"klapp1\">\n";
					foreach ($subs as $sub) {
					$b++;
					if ($sub['subnav_id'] == $id[1]) {
						$this .= "<li>   <a href=\"#\" onclick=\"showsubElement('topic".$b."')\">".$sub['subnav_title']."</a> &nbsp; <input type=\"radio\" name=\"subnavfolder\" value=\"".$sub['subnav_id']."\" checked=\"checked\" /></li>\n";
					} else {
						$this .= "<li>   <a href=\"#\" onclick=\"showsubElement('topic".$b."')\">".$sub['subnav_title']."</a> &nbsp; <input type=\"radio\" name=\"subnavfolder\" value=\"".$sub['subnav_id']."\" /></li>\n";
					}
					$topics = $db->GetTopic($sub['subnav_id']);
					if (isset($topics[0]['topic_id'])) {
						$this .= "<li><ul style=\"display:none\" id=\"topic".$b."\" class=\"klapp2\">\n";
						foreach ($topics as $topic) {
							$c++;
							$this .= "<li>   <a href=\"#\" onclick=\"showsubsubElement('file".$c."')\">".$topic['topic_head']."</a></li>\n";
							$files = $db->GetFiles($topic['topic_id']);
							if (isset($files[0]['files_id'])) {
								$this .= "<li><ul style=\"display:none\" id=\"file".$c."\" class=\"klapp3\">\n";
								foreach ($files as $file) {
									$this .= "<li>".$file['files_head']."</li>\n";
								}
								$this .= "</ul>\n</li>\n";
							}
						}
						$this .= "</ul>\n</li>\n";
					}
					}
					$this .= "</ul>\n</li>\n";
				}
			}
			$this .=  "</ul>\n";
			$this .=  '<div style="margin:5px 0 0 0px;color:#ffffff;"><span><input type="submit" name="submit" value="verschieben" style="font-size:14px; border:1px solid #d3d3d3; color: #777; padding: 2px 6px 2px 6px; background: #FFF;" /></span> &nbsp; <span><a href="_admin.php?id='.$link.'" style="font-size:12px; border:1px solid #d3d3d3; color:#777; padding: 4px 6px 4px 6px; background: #FFF; text-decoration: none;">abbrechen</a></span></div>';
			$this .=  "</form>";
		break;
		case 'subnav':
			$this .=  '<form action="shift.php?cmd=shift_subnav" method="post">';
			$this .=  '<input type="hidden" name="old_id" value="'.$id[1].'"><input type="hidden" name="old_link" value="'.$link.'">';
			$this .=  "<ul class=\"shift\">\n";
			$navs = $db->GetNav();
			$a=0;
			$b=0;
			$c=0;
			foreach ($navs as $nav) {
			if ($nav['nav_id'] == $id[0]) : $sel = " checked=\"checked\""; else: $sel=""; endif;
				$a++;
				$this .= "<li><a href=\"#\" onclick=\"showElement('subnav".$a."')\">  ".$nav['nav_name']."</a> &nbsp; <input type=\"radio\" name=\"navfolder\" value=\"".$nav['nav_id']."\"".$sel." /></li>\n";
				$subs = $db->GetSubnav($nav['nav_id']);
				if (isset($subs[0]['subnav_id'])) {
					$this .= "<li><ul style=\"display:none\" id=\"subnav".$a."\" class=\"klapp1\">\n";
					foreach ($subs as $sub) {
					$b++;
					$this .= "<li>    <a href=\"#\" onclick=\"showsubElement('topic".$b."')\">".$sub['subnav_title']."</a></li>\n";
					$topics = $db->GetTopic($sub['subnav_id']);
					if (isset($topics[0]['topic_id'])) {
						$this .= "<li><ul style=\"display:none\" id=\"topic".$b."\" class=\"klapp2\">\n";
						foreach ($topics as $topic) {
							$c++;
							$this .= "<li>   <a href=\"#\" onclick=\"showsubsubElement('file".$c."')\">".$topic['topic_head']."</a></li>\n";
							$files = $db->GetFiles($topic['topic_id']);
							if (isset($files[0]['files_id'])) {
								$this .= "<li><ul style=\"display:none\" id=\"file".$c."\" class=\"klapp3\">\n";
								foreach ($files as $file) {
									$this .= "<li>".$file['files_head']."</li>\n";
								}
								$this .= "</ul>\n</li>\n";
							}
						}
						$this .= "</ul>\n</li>\n";
					}
					}
					$this .= "</ul>\n</li>\n";
				}
			}
			$this .=  "</ul>\n";
			$this .=  '<div style="margin:5px 0 0 0px;color:#ffffff;"><span><input type="submit" name="submit" value="verschieben" style="font-size:14px; border:1px solid #d3d3d3; color: #777; padding: 2px 6px 2px 6px; background: #FFF;" /></span> &nbsp; <span><a href="_admin.php?id='.$link.'" style="font-size:12px; border:1px solid #d3d3d3; color:#777; padding: 4px 6px 4px 6px; background: #FFF; text-decoration: none;">abbrechen</a></span></div>';
			$this .=  "</form>";
		break;
		default:
			$this .=  '<p><b>Diese Anfrage ist nicht erlaubt!</b></p>';
		break;
		} // end switch
		}
		$db->close();
		return $this;
		}
	$content = ShiftThis ($shift, $id, $file);
 } else {
 $content = '<p><b>Keine Angaben zum Verschieben vorhanden!</b></p>';
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
<head>
	<title>PLib v02 - Verschiebebahnhof</title>
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="content-style-type" content="text/css" />
	<link rel="stylesheet" href="../_css/css.css" type="text/css" />
	<script type="text/javascript" language="JavaScript" src="../_templates/_script/main.js"></script>
</head>

<body>
<div class="container2" style="display: block; width: 550px; margin: 10px 5px 20px 10px;">
<h3>Dynamische Sitemap des PLips</h3>
<p>Verschieben einzelner Einträge oder ganzer Gruppen.</p>

<p>Markieren Sie den Zielort des zu verschiebenden Elements in der unten angezeigten Sitemap.</p>

<p>Die Sitemap ist eine schematische Darstellung der aktuellen PLib-Struktur.</p>

<p>Es werden nur logisch m&ouml;gliche Ziele zur Auswahl angeboten.</p>
<p>&nbsp;</p>
<?php print $content; ?>
</div>
</body>
</html>