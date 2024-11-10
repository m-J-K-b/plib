<?php
/* hier kommt der Code für die Subnavigations-Geschichten hinein */

function SubNav($action, $id) {

	$id = explode('_',$id);
	switch($action)
	{
	case 'del_entry';
	$db = new DB_AdminClass();
	$now = $db->DeleteSubnavID($_POST['subnav_id']);
	if ($now==TRUE) return $content=$now->Error;
	$db->close();
	break;
	case 'edit_entry';
	$db = new DB_AdminClass();
	if ($_POST['sort_id']!='') {
		$sort = $db->UpdateSortSubnav($_POST['old_sort'], $_POST['sort_id'], $_POST['nav_id']);
	}
	$now = $db->UpdateSubnavID($_POST['subnav_id'], $_POST['sort_id'],$_POST['head'],$_POST['text'],$_POST['visible']);
	$message = $now->Error;
	if ($now==TRUE) return $content = $message;
	$db->close();break;
	case 'save_entry';
	$db = new DB_AdminClass();
	$now = $db->MakeSubnav($_POST['nav_id'], $_POST['head'],$_POST['text'],$_POST['visible']);
	$message = $now->Error;
	if ($now==TRUE) return $content = $message;
	$db->close();
	break;
	case 'del';
	$db = new DB_AdminClass();
	$subnav = $db->GetSubnavID($id[1]);
	$new_action = 'del_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/ordner_icon.gif" alt="Files" /></div><h3 class="red">Vorsicht! Sie l&ouml;schen einen Ordner!</h3>';
	$submit_text = 'l&ouml;schen';
	$db->close();
	break;
	case 'edit';
	$db = new DB_AdminClass();
	$subnav = $db->GetSubnavID($id[1]);
	$new_action = 'edit_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/ordner_icon.gif" alt="Files" /></div><p class="editheadline">Ordner bearbeiten!</p>';
	$submit_text = '&auml;ndern';
	$db->close();
	break;
	default:
	$new_action = 'save_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/ordner_icon.gif" alt="Files" /></div><p class="editheadline">Neuen Ordner anlegen!</p>';
	$submit_text = 'speichern';
	break;
	}

	if ($subnav['visible']=='0') {$check = '';} else {$check = ' checked="checked"';}

	$content = '<form method="post" action="_admin.php?cmd=subnav&amp;id='.$id[0].'_'.$id[1].'_0&amp;action='.$new_action.'" accept-charset="UTF-8">
			'.$intro.'
			<input type="hidden" name="nav_id" value="'.$id[0].'" />
			<input type="hidden" name="subnav_id" value="'.$id[1].'" />
			<input type="hidden" name="old_sort" value="'.$subnav['sort_id'].'" />';
	if (isset($subnav['sort_id'])) {
		$content .='<input type="text" name="sort_id" size="2" /> &nbsp;
			Geben Sie hier nur eine Sortiernummer an, <br />wenn dieser Punkt an einer anderen Stelle stehen soll!<br />
			<br />';
	}
	$content .= 'Title: <br/>
			<input type="text" name="head" value="'.$subnav['subnav_title'].'" style="width: 230px;" /><br />
			<br />
			<input type="checkbox" name="visible" value="1"'.$check.' /> Dieser Punkt ist sofort sichtbar<br />
			<br />
			Text (Kurzbeschreibung):<br />
			<textarea name="text">'.$subnav['subnav_text'].'</textarea><br />
			<br />
			<input type="button" onclick="javascript:history.back(1);" name="back" value="zur&uuml;ck" class="submit" /> &nbsp;
			<input type="submit" name="submit" value="'.$submit_text.'" class="submit" />
			</form>';

	return $content;
	}


function GetSubnav($id) {
	$db = new DB_AdminClass();
	$subnav = $db->GetSubnav($id);
	return $subnav;
	}

function subnavContent($subnav, $id0, $id1, $id2) {

	if(isset($subnav[0]['subnav_id']))
	{
	$subnav_content .= '<p class="editheadline" ><a href="?cmd=subnav&amp;id='.$id0.'_0_0&amp;action=make">Neuen Ordner anlegen</a></p>';

	foreach ($subnav as $value)
	{
	if ($value['subnav_id'] == $id1) {
		$subnav_content .= '<p class="edit"> <a href="shift.php?shift=subnav&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_0">verschieben</a> | <a href="?cmd=subnav&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_0&amp;action=del">l&ouml;schen</a> | <a href="?cmd=subnav&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_0&amp;action=edit">bearbeiten</a> </p><p><a href="?id='.$value['nav_id'].'_'.$value['subnav_id'].'_0"';
		if ($value['visible']==0) : $subnav_content .= ' class="hiddenstandardlink">' ; else: $subnav_content .= ' class="standardlink">'; endif;
		$subnav_content .= $value['sort_id'].' '.$value['subnav_title'].'</a></p>';
	    }else {
	    $subnav_content .= '<p class="edit"> <a href="shift.php?shift=subnav&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_0">verschieben</a> | <a href="?cmd=subnav&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_0&amp;action=del">l&ouml;schen</a> | <a href="?cmd=subnav&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_0&amp;action=edit">bearbeiten</a> </p><p><a href="?id='.$value['nav_id'].'_'.$value['subnav_id'].'_0"';
	    if ($value['visible']==0) : $subnav_content .= ' class="hidden">' ; else: $subnav_content .= ' class="grey">'; endif;
		$subnav_content .= $value['sort_id'].' '.$value['subnav_title'].'</a></p>';
	   }
	}
	}

	return $subnav_content;
}

?>