<?php

class content_DB extends DB_MainClass {

function Exec_Command($id, $lang) {

	global $topic_nr, $direct_id;

	if (!isset($id) || $id=='' || $id==0) : $id = '1_0_0'; else: $id =$id; endif;
	include(PLUGIN.'/lang-'.$lang.'.inc.php');

	$connect_error = $this->Connect();

	if ($connect_error)
	{
	$this->content[0]['error'] = 'Fehler';
	$this->content[0]['headline'] = $Error;
	$this->content[0]['errortext'] = $Error_NoServer;
	}else{
	$id = explode('_',$id);

	$this->nav['id'] = $id[0];
	if ($this->nav['id'] == 1) : $lang = 'de'; else: $lang = 'en'; endif;

	$this->subnav = $this->GetSubnav($id[0], $id[1], $lang);

	if ($id[1]>0)
	{
	$this->topic = $this->GetTopic($id[0], $id[1], $id[2], $lang);
	}

	if (isset($direct_id)) {
	$this->content = $this->GetFile($id[0], $id[1], $direct_id, $lang);
	} elseif ($id[2]>0)
	{
	$this->content = $this->GetFile($id[0], $id[1], $id[2], $lang);
	}

	}
	$this->close();

	$result = array('headline'=>$this->head, 'nav'=>$this->nav, 'subnav'=>$this->subnav, 'topic'=>$this->topic, 'content'=>$this->content, 'template'=>'index.html');

	return $result;
	}


	function GetSubnav($id, $subnav_id, $lang)
	{

	include(PLUGIN.'lang-'.$lang.'.inc.php');
	//include(PLUGIN.'specialchars.inc.php');


	$ssql = 'SELECT * FROM pdf_subnav WHERE nav_id = '.$id.' AND visible = 1 ORDER BY sort_id, subnav_title;';
	$sub = $this->GetArray($ssql);
	$set_sno = count($sub[0]);
	// print $set_sno;
	if ($set_sno>0)
	{
	foreach ($sub as $row)
	{

		if ($subnav_id==$row['subnav_id'])
		{
		$result[] = array('id'=>$row['subnav_id'],'active'=>1,'num'=>$row['subnav_id'],'name'=>$row['subnav_title'],'text'=>nl2br($row['subnav_text']));
		}else{
		$result[] = array('id'=>$row['subnav_id'],'active'=>0,'num'=>$row['subnav_id'],'name'=>$row['subnav_title']);
		}
	}
	} else {
		$result[] = array('error'=>$Error,'errortext'=>$Error_NoContent);
	}
	return $result;
	}

	function GetTopic($nav_id, $subnav_id, $topic_id, $lang)
	{
	global $topic_nr;

	include(PLUGIN.'lang-'.$lang.'.inc.php');
	include(PLUGIN.'/lang-'.$lang.'.inc.php');
	$tsql = 'SELECT * FROM pdf_topic WHERE subnav_id = '.$subnav_id.' AND visible = 1 ORDER BY sort_id, topic_head;';
	$topic = $this->GetArray($tsql);
	//print_r($topic);
	$set_tno= count($topic[0]);
	if ($set_tno>0)
	{
	foreach ($topic as $row)
	{
		if ($topic_id==$row['topic_id'])
		{
		$result[] = array('id'=>$row['topic_id'],'active'=>1,'name'=>$row['topic_head'],'text'=>nl2br($row['topic_text']));
		}else{
		$result[] = array('id'=>$row['topic_id'],'active'=>0,'name'=>$row['topic_head']);
		}
	$topic_nr++;
	}
	}else{

		$dsql = 'SELECT t.topic_id, f.files_head, f.files_text, f.long_head, f.long_text, f.files_name, f.files_date, f.img, f.img_set, f.tag1, f.tag2, f.tag3, f.tag4, f.tag5  FROM pdf_file f, pdf_topic t WHERE t.subnav_id = '.$subnav_id.' AND t.topic_head = "Direkt" AND t.visible = 0 AND f.visible = 1 AND t.topic_id = f.topic_id ORDER BY f.files_head;';
		$direkt = $this->GetArray($dsql);
		if ($direkt[0]['topic_id']!='') {
		global $direct_id;
		$direct_id = $direkt[0]['topic_id'];

		foreach ($direkt as $row) {
			$tag = new tag_DB();
			$tags = $tag->get_Kat($direkt['files_id']);
			$result['direkt'] =  array('headline'=>$row['files_head'],'content'=>nl2br($row['files_text']),'extrahead'=>$row['long_head'],'extratext'=>nl2br($row['long_text']),'image_position'=>$row['img_set'],'image'=>$row['img'],'file'=>$row['files_name'],'date'=>$ReleaseDate.': '.str_replace('-','.',$row['files_date']), 'tag'=>$tags);
			}
		} else {
			$result[] = array('error'=>$Error,'errortext'=>$Error_NoContent);
			$topic_nr=0;
		}
	}
	return $result;
	}

	function GetFile ($nav_id, $subnav_id, $topic_id, $lang)
	{
	global $topic_nr;

	include(PLUGIN.'lang-'.$lang.'.inc.php');
	include(PLUGIN.'/lang-'.$lang.'.inc.php');

	$fsql = 'SELECT f.files_id, f.files_head, f.files_text, f.long_head, f.long_text, f.files_name, f.files_date, f.img, f.img_set, f.tag1, f.tag2, f.tag3, f.tag4, f.tag5 FROM pdf_file f, pdf_topic t WHERE f.topic_id = '.$topic_id.' AND f.topic_id = t.topic_id AND f.visible = 1 ORDER BY f.files_head;';

	$file = $this->GetArray($fsql);
	//print_r($file);
	$set_fno= count($file[0]);
	if ($set_fno>0) {
		foreach ($file as $row) {
		$tag = new tag_DB();
		$tags = $tag->get_Kat($row['files_id']);
        $name = new name_DB();
		$names = $name->get_Kat($row['files_id']);
        // Hier wuerden die Bilder eingebunden werden
		/*$pic = new pic_DB();
		$pics = $pic->get_Pic($row['files_id']);*/
		$result[] = array('id'=>$row['files_id'], 'topic_id'=>$row['topic_id'], 'headline'=>$row['files_head'], 'content'=>nl2br($row['files_text']),'extrahead'=>$row['long_head'],'extratext'=>nl2br($row['long_text']),'image_position'=>$row['img_set'],'image'=>$row['img'],'file'=>$row['files_name'],'date'=>$ReleaseDate.': '.str_replace('-','.',$row['files_date']),'tag'=>$tags, 'names'=>$names);
		}
        
	}else{
		$result[] = array('error'=>$Error,'errortext'=>$Error_NoContent);
	}
	return $result;
	}


}

// Class fuer die Stichworte
class tag_DB extends DB_MainClass {

function get_Kat($id) {

$tsql = 'SELECT t.tag_id, t.tag_name FROM pdf_tags t, main_kat k WHERE k.file_id = '.$id.' AND k.tag_id = t.tag_id AND t.is_name = 0  ORDER BY t.tag_name;';
$trow = $this->GetArray($tsql);
if ($trow[0]['tag_id']!='')  {
	$a = 0;
	foreach ($trow as $tags) {
  		$tag[$a]['id'] = $tags['tag_id'];
  		$tag[$a]['tagname'] = $tags['tag_name'];
  		$a++;
	}
} else {
$tag = FALSE;
}

return $tag;
}
} //end tag_DB Class


// Class fuer die N
class name_DB extends DB_MainClass {

function get_Kat($id) {

$tnsql = 'SELECT t.tag_id, t.tag_name FROM pdf_tags t, main_kat k WHERE k.file_id = '.$id.' AND k.tag_id = t.tag_id AND t.is_name = 1 ORDER BY t.tag_name;';
$trow = $this->GetArray($tnsql);
if ($trow[0]['tag_id']!='')  {
	$a = 0;
	foreach ($trow as $tags) {
  		$tagname[$a]['id'] = $tags['tag_id'];
  		$tagname[$a]['tagname'] = $tags['tag_name'];
  		$a++;
	}
} else {
$tagname = FALSE;
}

return $tagname;
}
} //end name_DB Class

?>