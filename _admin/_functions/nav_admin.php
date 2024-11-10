<?php
/* hier kommt der Code für die Navigations-Geschichten hinein */

function Nav($action, $id) {

	$id = explode('_',$id);
	switch($action)
	{
	case 'del_entry';
	$db = new DB_AdminClass();
	$now = $db->DeleteNavID($_POST['id']);
	if ($now==TRUE) {
		header("Location: _admin.php");
	}
	$db->close();
	break;
	case 'edit_entry';
	$db = new DB_AdminClass();
	if ($_POST['sort_id']!='') {
		$sort = $db->UpdateSortNav($_POST['old_sort'], $_POST['sort_id']);
	}
	$now = $db->UpdateNavID($_POST['id'],$_POST['sort_id'],$_POST['name'], $_POST['intname'], $_POST['text'], $_POST['visible']);
	if ($now==TRUE) return $content=$now->Error;
	$db->close();
	break;
	case 'save_entry';
	$db = new DB_AdminClass();
	$now = $db->MakeNav($_POST['name'], $_POST['intname'], $_POST['text'], $_POST['visible']);
	if ($now==TRUE) {
		header("Location: _admin.php");
	}
	$db->close();
	break;
	case 'del';
	$db = new DB_AdminClass();
	$nav = $db->GetNavID($id[0]);
	$new_action = 'del_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/nav_icon.gif" alt="Files" /></div><h3 class="red">Vorsicht! Sie l&ouml;schen einen Navigationspunkt!</h3>';
	$submit_text = 'l&ouml;schen';
	$db->close();
	break;
	case 'edit';
	$db = new DB_AdminClass();
	$nav = $db->GetNavID($id[0]);
	$new_action = 'edit_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/nav_icon.gif" alt="Files" /></div><p class="editheadline">Navigationspunkt bearbeiten!</p>';
	$submit_text = '&auml;ndern';
	$db->close();
	break;
	default:
	$new_action = 'save_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/nav_icon.gif" alt="Files" /></div><p class="editheadline">Neuen Navigationspunkt anlegen!</p>';
	$submit_text = 'speichern';
	break;
	}

	if ($nav['visible']=='0') {$check = '';} else {$check = ' checked="checked"';}

	$content = '<form method="post" action="_admin.php?cmd=nav&amp;id='.$id[0].'_0_0&amp;action='.$new_action.'" accept-charset="UTF-8">
			'.$intro.'
			<input type="hidden" name="id" value="'.$nav['nav_id'].'" />
			<input type="hidden" name="old_sort" value="'.$nav['sort_id'].'" />';
	if (isset($nav['sort_id'])) {
		$content .= '<input type="text" name="sort_id" size="2" /> &nbsp;
			Geben Sie hier nur eine Sortiernummer an, <br />wenn dieser Punkt an einer anderen Stelle stehen soll!<br />
			<br />';
	}
	$content .= 'Name: <br/>
			<input type="text" name="name" value="'.$nav['nav_name'].'" class="full" /><br />
			Name in der Landessprache (Wird im Frontend angezeigt): <br/>
			<input type="text" name="intname" value="'.$nav['nav_intname'].'" class="full" /><br /><br />
			<input type="checkbox" name="visible" value="1"'.$check.' /> Dieser Punkt ist sofort sichtbar<br />
			<br />
			Text (Kurzbeschreibung - bei HTML validen Code eingeben!):<br />
			<textarea name="text">'.$nav['nav_text'].'</textarea><br />
			<br />
			<input type="button" onclick="javascript:history.back(1);" name="back" value="zur&uuml;ck" class="submit" /> &nbsp;
			<input type="submit" name="submit" value="'.$submit_text.'" class="submit" />
			</form>';

	return $content;
	}

function GetNav() {
	$db = new DB_AdminClass();
	$connect_error=$db->Connect();
	if ($connect_error){
		$stat_ccontent .=  '<div><h2>Kein Connect zur Datenbank!</h2>
		<p>Versuchen Sie es sp&auml;ter noch einmal</p></div>';
	}else{
		$nav = $db->GetNav();
		return $nav;
	}
}

function edit($id) {
	$content .= $id;
	$content .= $nav_form;
	return $content;
	}

function delete($id) {
	$content .= $id;
	$content .= $nav_form;
	return $content;
	}

function navContent($nav, $id0, $id1, $id2) {
	global $nav_active;
	foreach ($nav as $value)
	{
	if ($value['nav_id'] == $id0) {
	$nav_active = $value['nav_name'];
	$nav_content .= '<span><a href="?id='.$value['nav_id'].'_0_0"';
	if ($value['visible'] == 0) : $nav_content .= ' class="hiddenactive">'; else: $nav_content .= 'class="active">'; endif;
	$nav_content .= $value['nav_name'].'</a></span> ';
	}else {
	$nav_content .= '<span><a href="?id='.$value['nav_id'].'_0_0"';
	if ($value['visible'] == 0) : $nav_content .= ' class="hidden">'; else: $nav_content .= '>'; endif;
	$nav_content .= $value['nav_name'].'</a></span> ';
	}}

	return $nav_content;
}

?>