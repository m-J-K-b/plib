<?php
/* hier kommt der Code für die Topic-Geschichten hinein */

function Topic($action, $id) {

	include(PLUGIN.'specialchars.inc.php');

	$id = explode('_',$id);
	switch($action)
	{
	case 'del_entry';
	$db = new DB_AdminClass();
	$now = $db->DeleteTopicID($_POST['topic_id']);
	if ($now==TRUE) return $content=$now->Error;
	$db->close();
	break;
	case 'edit_entry';
	$db = new DB_AdminClass();
	if ($_POST['sort_id']!='') {
		$sort = $db->UpdateSortTopic($_POST['old_sort'], $_POST['sort_id'], $_POST['subnav_id']);
	}
	$now = $db->UpdateTopicID($_POST['topic_id'], $_POST['sort_id'], $_POST['head'],$_POST['text'],$_POST['visible']);
	$message = $now->Error;
	if ($now==TRUE) return $content = $message;
	$db->close();
	case 'save_entry';
	$db = new DB_AdminClass();
	$now = $db->MakeTopic($_POST['subnav_id'], $_POST['head'], $_POST['text'],$_POST['visible']);
	$message = $now->Error;
	if ($now==TRUE) return $content = $message;
	$db->close();
	break;
	case 'del';
	$db = new DB_AdminClass();
	$topic = $db->GetTopicID($id[2]);
	$new_action = 'del_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/themen_icon.gif" alt="Files" /></div><h3 class="red">Vorsicht! Sie l&ouml;schen einen Themenpunkt!</h3>';
	$submit_text = 'l&ouml;schen';
	break;
	case 'edit';
	$db = new DB_AdminClass();
	$topic = $db->GetTopicID($id[2]);
	$new_action = 'edit_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/themen_icon.gif" alt="Files" /></div><p class="editheadline">Thema bearbeiten!</p>';
	$submit_text = '&auml;ndern';
	$db->close();
	break;
	default:
	$new_action = 'save_entry';
	$intro = '<div class="spaltenheadline"><img src="../_templates/_img/themen_icon.gif" alt="Files" /></div><p class="editheadline">Erstellen Sie einen neuen Themenpunkt!</p>';
	$submit_text = 'erstellen';
	break;
	}

	if ($topic['visible']=='0') {$check = '';} else {$check = ' checked="checked"';}

	$content = '<form method="post" action="_admin.php?cmd=topic&amp;id='.$id[0].'_'.$id[1].'_'.$id[2].'&amp;action='.$new_action.'" accept-charset="UTF-8">
			'.$intro.'
			<input type="hidden" name="nav_id" value="'.$id[0].'" />
			<input type="hidden" name="subnav_id" value="'.$id[1].'" />
			<input type="hidden" name="topic_id" value="'.$id[2].'" />
			<input type="hidden" name="old_sort" value="'.$topic['sort_id'].'" />';
	if (isset($topic['sort_id'])) {
	$content .='<input type="text" name="sort_id" size="2" /> &nbsp;
			Geben Sie hier nur eine Sortiernummer an, <br />wenn dieser Punkt an einer anderen Stelle stehen soll!<br />
			<br />';
	}
	$content .='Name: <br/>
			<input type="text" name="head" value="'.$topic['topic_head'].'" style="width: 230px;" /><br />
			<br />
			<input type="checkbox" name="visible" value="1"'.$check.' /> Dieser Punkt ist sofort sichtbar<br />
			<br />
			Text:<br />
			<textarea name="text">'.$topic['topic_text'].'</textarea><br />
			<br />
			<input type="button" onclick="javascript:history.back(1);" name="back" value="zur&uuml;ck" class="submit" /> &nbsp;
			<input type="submit" name="submit" value="'.$submit_text.'" class="submit" />
			</form>';

	return $content;
	}

function GetTopic($id, $subid) {
	$db = new DB_AdminClass();
	$topic = $db->GetTopic($subid);
	return $topic;
	}

function topicContent($topic, $id0, $id1, $id2) {

	if (isset($topic[0]['topic_id']))
	{
	$topic_content .='<p class="editheadline"><a href="?cmd=topic&amp;id='.$id0.'_'.$id1.'_0&amp;action=make">Neues Thema anlegen </a><br/></p>';
	foreach ($topic as $value)
	{
	$this_topic++;
	if ($value['topic_id'] == $id2) {
			if ($value['topic_head']=='Direkt') {
				$act_topic = $value['topic_head'];
				$topic_content .= '<p class="editheadline">Hier angelegte Einträge werden nur angezeigt, wenn keine Themen vorhanden sind!</p><!-- p class="edit">  <a href="shift.php?shift=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'">verschieben</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=del">l&ouml;schen</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=edit">bearbeiten</a> </p -->
				<p style="background: #9CE;"><a href="?id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'"';
				if ($value['visible']==0) : $topic_content .= ' class="hiddenstandardlink">' ; else: $topic_content .= ' class="standardlink">'; endif;
				$topic_content .= $value['sort_id'].' '.$value['topic_head'].'</a></p>';
			} else {
				$act_topic = $value['topic_head'];
				$topic_content .= '<p class="edit">  <a href="shift.php?shift=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'">verschieben</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=del">l&ouml;schen</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=edit">bearbeiten</a> </p>
				<p><a href="?id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'"';
				if ($value['visible']==0) : $topic_content .= ' class="hiddenstandardlink">' ; else: $topic_content .= ' class="standardlink">'; endif;
				$topic_content .= $value['sort_id'].' '.$value['topic_head'].'</a></p>';
			}
	    }else {
	    	if ($value['topic_head']=='Direkt') {
			    $topic_content .= '<p class="editheadline">Direktordner: PDFs direkt auf Ordnerebene anlegen!</p><!-- p class="edit">  <a href="shift.php?shift=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'">verschieben</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=del">l&ouml;schen</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=edit">bearbeiten</a> </p -->
			    <p style="background: #9CE;"><a href="?id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'"';
			    if ($value['visible']==0) : $topic_content .= ' class="hidden">' ; else: $topic_content .= ' class="grey">'; endif;
				$topic_content .= $value['sort_id'].' '.$value['topic_head'].'</a></p>';
			} else {
				$topic_content .= '<p class="edit">  <a href="shift.php?shift=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'">verschieben</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=del">l&ouml;schen</a> | <a href="?cmd=topic&amp;id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'&amp;action=edit">bearbeiten</a> </p>
			    <p><a href="?id='.$value['nav_id'].'_'.$value['subnav_id'].'_'.$value['topic_id'].'"';
			    if ($value['visible']==0) : $topic_content .= ' class="hidden">' ; else: $topic_content .= ' class="grey">'; endif;
				$topic_content .= $value['sort_id'].' '.$value['topic_head'].'</a></p>';
			}
	   }
	}

	return $topic_content;

	}

}

?>