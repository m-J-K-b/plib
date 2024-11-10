<?php

require_once(PLUGIN."/db_mainclass.php");
class DB_AdminClass extends DB_MainClass {

	function DB_AdminClass () {
	$this->myDB();
	}


	function GetTest()
	{
	$sql = "SELECT * FROM test ORDER BY nav_id;";
	$result = $this->GetArray($sql);
	return $result;
	}

	

	/* DB QUERY fuer Nav und Subnav */
    function GetNav()
	{
	$sql = "SELECT * FROM pdf_nav ORDER BY sort_id, nav_name;";
	$result = $this->GetArray($sql);
	return $result;
	}

	function GetSubnav($id)
	{
	$sql = "SELECT subnav_id, nav_id, sort_id, subnav_title, subnav_text, visible FROM pdf_subnav WHERE nav_id = ".$id." ORDER BY sort_id, subnav_title;";
	$result = $this->GetArray($sql);
	return $result;
	}
    
    function MakeNav($name, $intname, $text, $visible) {
	$max = "SELECT max(sort_id) AS maxid FROM pdf_nav;";
	$result = $this->GetRow($max);

	$new_sort = $result['maxid']+1;

	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;

	$now = date(Y.'-'.m.'-'.d);

	$nsql ="INSERT INTO pdf_nav (nav_id, sort_id, nav_name, nav_intname, nav_text, nav_date, visible) VALUES  ('', ".$new_sort.", '".$name."','".$intname."','".$text."','".$now."',".$visible.");";
	if (!$this->Execute($nsql)) {return $this->Error[0]='gehtnich';}
	else {
	$sql = "SELECT max(nav_id) as maxid FROM pdf_nav;";
	$max_id = $this->GetRow($sql);
	$new_id = $max_id['maxid'];
	$this->Entry = $this->MakeSubnav($new_id,'Allgemein','automatischer Punkt',0);
	if ($this->Entry == TRUE) : $this->Error='Das hat geklappt! Eintrag wurde gespeichert!'; else: $this->Error='Eintrag konnte nicht erstellt werden!!'; endif;
	}
	return $this;
	}

	function MakeSubnav($nav_id,$subnav_head, $subnav_text, $visible) {
	$max = "SELECT max(sort_id) AS maxid FROM pdf_subnav WHERE nav_id = ".$nav_id.";";
	$result = $this->GetRow($max);

	$new_sort = $result['maxid']+1;

	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;

	$now = date(Y.'-'.m.'-'.d);

	$ssql ="INSERT INTO pdf_subnav (subnav_id, nav_id, sort_id, subnav_title, subnav_text, date, visible) VALUES ('',".$nav_id.",".$new_sort.",'".$subnav_head."','".$subnav_text."','".$now."',".$visible.");";
	if (!$this->Execute($ssql)) {$this->Error = 'Eintrag konnte nicht erstellt werden!';}
	else {
	$topic = "SELECT * FROM pdf_topic WHERE subnav_id = ".$this->insert_id.";";
	$result = $this->GetArray($topic);
		if ($result[0]['topic_id']=='') {
			$this->Entry = $this->MakeTopic($this->insert_id,'Direkt','','');
			if ($this->Entry == TRUE) : $this->Error='Das hat geklappt! Eintrag wurde gespeichert!'; else: $this->Error='Eintrag konnte nicht erstellt werden!!'; endif;
		} else {
		$this->Error='Das hat geklappt! Eintrag wurde gespeichert!';
		}
	}
	return $this;
	}

	function GetNavID($id) {
	$sql = 'SELECT nav_id, sort_id, nav_name, nav_intname, nav_text, visible FROM pdf_nav WHERE nav_id = '.$id.';';
	$result = $this->GetRow($sql);
	return  $result;
	}

	function GetSubnavID($id) {
	$sql = 'SELECT subnav_id, nav_id, sort_id, subnav_title, subnav_text, visible FROM pdf_subnav WHERE subnav_id = '.$id.' ORDER BY sort_id, subnav_title;';
	$result = $this->GetRow($sql);
	return  $result;
	}

   
	function UpdateNavID($id, $sort_id, $name, $int_name, $text, $visible) {
	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;

	$now = date(Y.'-'.m.'-'.d);
	if ($sort_id!='') {
		$sql = "UPDATE pdf_nav SET sort_id = ".$sort_id.", nav_name= '".$name."', nav_intname = '".$int_name."', nav_text = '".$text."', nav_date = '".$now."', visible = ".$visible." WHERE nav_id = ".$id.";";
	} else {
		$sql = "UPDATE pdf_nav SET nav_name= '".$name."', nav_intname = '".$int_name."', nav_text = '".$text."', nav_date = '".$now."', visible = ".$visible." WHERE nav_id = ".$id.";";
	}
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht ge&auml;ndert werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde aktualisiert';}
	return $this;
	}

	function UpdateSubnavID($id, $sort_id, $title, $text, $visible) {
	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;
	$now = date(Y.'-'.m.'-'.d);
	if ($sort_id!='') {
		$sql = "UPDATE pdf_subnav SET sort_id = ".$sort_id.", subnav_title = '".$title."', subnav_text = '".$text."', date = '".$now."', visible = ".$visible." WHERE subnav_id = ".$id.";";
	} else {
		$sql = "UPDATE pdf_subnav SET subnav_title = '".$title."', subnav_text = '".$text."', date = '".$now."', visible = ".$visible." WHERE subnav_id = ".$id.";";
	}
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht ge&auml;ndert werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde aktualisiert';}
	return $this;
	}

	function DeleteNavID($id) {
	$sql = "DELETE FROM pdf_nav WHERE nav_id ='".$id."';";
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht gel&ouml;scht werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde gel&ouml;scht';}
	return $this;
	}

	function DeleteSubnavID($id) {
	$sql = "DELETE FROM pdf_subnav WHERE subnav_id ='".$id."';";
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht gel&ouml;scht werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde gel&ouml;scht';}
	return $this;
	}

	function ShiftSubnav($id, $nav_id) {
	$max = "SELECT max(sort_id) AS maxid FROM pdf_subnav WHERE nav_id = ".$nav_id.";";
	$result = $this->GetRow($max);

	$new_sort = $result['maxid']+1;
	$sql = "UPDATE pdf_subnav SET nav_id = ".$nav_id.", sort_id = ".$new_sort." WHERE subnav_id = ".$id.";";
	if (!$this->Execute($sql)) { $this->msg = 'Eintrag konnte nicht ge&auml;ndert werden!';}

	return $this;
	}

	function UpdateSortNav ($old_sort, $new_sort) {
	if ($old_sort>$new_sort) {
		$absort = $new_sort-1;
		$sql = "UPDATE pdf_nav SET sort_id = sort_id+1 WHERE sort_id >".$absort." AND sort_id < ".$old_sort.";";
	} else {
		$sql = "UPDATE pdf_nav SET sort_id = sort_id-1 WHERE sort_id >".$old_sort." AND sort_id <= ".$new_sort.";";
	}
	if (!$this->Execute($sql)) {return FALSE;}
	return $this;
	}

	function UpdateSortSubnav ($old_sort, $new_sort, $nav_id) {
	if ($old_sort>$new_sort) {
		$absort = $new_sort-1;
		$sql = "UPDATE pdf_subnav SET sort_id = sort_id+1 WHERE sort_id >".$absort." AND sort_id < ".$old_sort." AND nav_id = ".$nav_id.";";
	} else {
		$sql = "UPDATE pdf_subnav SET sort_id = sort_id-1 WHERE sort_id >".$old_sort." AND sort_id <= ".$new_sort." AND nav_id = ".$nav_id.";";
	}
	if (!$this->Execute($sql)) {return FALSE;}
	return $this;
	}

	
    /* nav und subnav  end */
    
    /* DB QUERY fuer Topic */
   
    function GetTopic($id)
	{
	$sql = "SELECT s.nav_id, t.subnav_id, t.topic_id, t.sort_id, t.topic_head, t.topic_text, t.visible FROM pdf_topic t, pdf_subnav s WHERE t.subnav_id = ".$id." AND s.subnav_id = t.subnav_id ORDER BY t.sort_id, t.topic_head;";
	$result = $this->GetArray($sql);
	return $result;
	}
    
    function MakeTopic($subnav_id, $topic_head, $topic_text, $visible) {
	$max = "SELECT max(sort_id) AS maxid FROM pdf_topic WHERE subnav_id = ".$subnav_id.";";
	$result = $this->GetRow($max);

	$new_sort = $result['maxid']+1;

	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;
	$now = date(Y.'-'.m.'-'.d);

	$ssql ="INSERT INTO pdf_topic (topic_id, subnav_id, sort_id, topic_head, topic_text, topic_date, visible) VALUES ('', ".$subnav_id.", ".$new_sort.", '".$topic_head."','".$topic_text."','".$now."',".$visible.");";
	if (!$this->Execute($ssql)){ $this->Error = 'Eintrag konnte nicht erstellt werden!';}
	else {
	$this->Error='Das hat geklappt! Eintrag wurde gespeichert!';
	}
	return $this;
	}
    
    function GetTopicID($id) {
	$sql = 'SELECT sort_id, topic_head, topic_text, visible FROM pdf_topic WHERE topic_id = '.$id.';';
	$result = $this->GetRow($sql);
	return  $result;
	}
    
    function UpdateTopicID($topic_id, $sort_id, $head, $text, $visible) {
	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;
	$now = date(Y.'-'.m.'-'.d);
	if ($sort_id!='') {
		$sql = "UPDATE pdf_topic SET sort_id = ".$sort_id.", topic_head = '".$head."', topic_text = '".$text."', topic_date = '".$now."', visible = ".$visible." WHERE topic_id = ".$topic_id.";";
	} else {
		$sql = "UPDATE pdf_topic SET topic_head = '".$head."', topic_text = '".$text."', topic_date = '".$now."', visible = ".$visible." WHERE topic_id = ".$topic_id.";";
	}
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht ge&auml;ndert werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde aktualisiert';}
	return $this;
	}
    
    function DeleteTopicID($id) {
	$sql = "DELETE FROM pdf_topic WHERE topic_id ='".$id."';";
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht gel&ouml;scht werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde gel&ouml;scht';}
	return $this;
	}
	    
    function ShiftTopic($id, $subnav_id) {
	$max = "SELECT max(sort_id) AS maxid FROM pdf_topic WHERE subnav_id = ".$subnav_id.";";
	$result = $this->GetRow($max);

	$new_sort = $result['maxid']+1;
	$sql = "UPDATE pdf_topic SET subnav_id = ".$subnav_id.", sort_id = ".$new_sort." WHERE topic_id = ".$id.";";
	if (!$this->Execute($sql)) { $this->msg = 'Eintrag konnte nicht ge&auml;ndert werden!';}

	return $this;
	}

	function GetTopicBack($subnav_id) {
	$sql = "SELECT nav_id, subnav_id FROM pdf_subnav WHERE subnav_id =".$subnav_id.";";
	$result = $this->GetRow($sql);
	return $result;
	}
    
    function UpdateSortTopic ($old_sort, $new_sort, $subnav_id) {
	if ($old_sort>$new_sort) {
		$absort = $new_sort-1;
		$sql = "UPDATE pdf_topic SET sort_id = sort_id+1 WHERE sort_id >".$absort." AND sort_id < ".$old_sort." AND subnav_id = ".$subnav_id.";";
	} else {
		$sql = "UPDATE pdf_topic SET sort_id = sort_id-1 WHERE sort_id >".$old_sort." AND sort_id <= ".$new_sort." AND subnav_id = ".$subnav_id.";";
	}
	if (!$this->Execute($sql)) {return FALSE;}
	return $this;
	}
    
    
    /* topic  end */
   
    /* DB QUERY fuer Files und Pics */
   
    function GetFiles($topic_id)
	{
	$sql = "SELECT f.files_id, f.topic_id, f.files_head, f.files_text, f.files_name, f.files_date, f.visible FROM pdf_file f, pdf_topic t WHERE f.topic_id = ". $topic_id ." AND f.topic_id = t.topic_id ORDER BY f.files_head;";
	$result = $this->GetArray($sql);
	return $result;
	}
    
    function GetFilesID($id) {
	$sql = 'SELECT * FROM pdf_file WHERE files_id = '.$id.';';
	$result = $this->GetRow($sql);
	return $result;
	}
    
    function MakeFiles($topic_id, $files_head, $files_text, $long_head, $long_text, $files_name, $files_date, $img_name, $img_set, $visible) {

	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;

	$now = date(Y.'-'.m.'-'.d);

	$ssql ="INSERT INTO pdf_file (files_id, topic_id, files_head, files_text, long_head, long_text, files_name, files_date, img, img_set, date, visible) VALUES ('', ".$topic_id.", '".$files_head."', '".$files_text."', '".$long_head."', '".$long_text."', '".$files_name."', '".$files_date."', '".$img_name."', '".$img_set."', '".$now."', ".$visible.");";
	if (!$this->Execute($ssql)){ $this->Error = 'Eintrag konnte nicht erstellt werden!';}
	else {$this->Error = 'Das hat geklappt! Eintrag wurde gespeichert!<br />';
	$this->ID = $this->insert_id;}
	return $this;
	}
    
    function UpdateFilesID($files_id, $topic_id, $head, $text, $long_head, $long_text, $file_name, $date, $img_name, $img_set, $visible) {
	if ($visible==TRUE) : $visible=1; else: $visible=0; endif;

	$now = date(Y.'-'.m.'-'.d);
	$sql = "UPDATE pdf_file SET topic_id = ".$topic_id.", files_head = '".$head."', files_text = '".$text."', long_head = '".$long_head."', long_text = '".$long_text."', files_name = '".$file_name."', files_date = '".$date."', img = '".$img_name."', img_set = '".$img_set."', date = '".$now."', visible = ".$visible." WHERE files_id = ".$files_id.";";
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht ge&auml;ndert werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde aktualisiert';}
	return $this;
	}
    
    function DeleteFilesID($id) {
	$sql = "DELETE FROM pdf_file WHERE files_id ='".$id."';";
	if (!$this->Execute($sql)) { $this->Error = 'Eintrag konnte nicht gel&ouml;scht werden!';}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde gel&ouml;scht';
	$tsql = "DELETE FROM main_kat WHERE file_id =".$id.";";
	$this->Execute($tsql);
	}
	return $this;
	}

	function DeleteTagByFilesID($files_id) {
	$sql = "DELETE FROM main_kat WHERE file_id =".$files_id.";";
	if (!$this->Execute($sql)) { $this->Error = TRUE;}
	else {$this->Error = FALSE;}
	return $this->Error;
	}
    
    function ShiftFile($id, $topic_id) {
	$sql = "UPDATE pdf_file SET topic_id = ".$topic_id." WHERE files_id = ".$id.";";
	if (!$this->Execute($sql)) { $this->msg = 'Eintrag konnte nicht ge&auml;ndert werden!';}
	return $this;
	}
    
    function GetFileBack($topic_id) {
	$sql = "SELECT s.nav_id, s.subnav_id, t.topic_id FROM pdf_topic t, pdf_subnav s WHERE t.subnav_id = s.subnav_id AND t.topic_id =".$topic_id.";";
	$result = $this->GetRow($sql);
	return $result;
	}
    
    /* fiels und pics end */

    /* DB QUERY fuer Tags und Namen */
	function GetAllTags() {
	$sql = "SELECT * FROM pdf_tags WHERE is_name = 0 ORDER BY tag_name;";
	$result = $this->GetArray($sql);
   
	return $result;
	}
    
    function GetAllNames() {
	$sql = "SELECT * FROM pdf_tags WHERE is_name = 1 ORDER BY tag_name;";
	$result = $this->GetArray($sql);
   
	return $result;
	}

	function GetTags($id) {
	$sql = "SELECT t.tag_id, t.tag_name, t.is_name FROM pdf_tags t, main_kat k WHERE k.file_id = ".$id." AND k.tag_id = t.tag_id ORDER BY t.tag_name;";
	$result = $this->GetArray($sql);
	return $result;
	}
    
    function GetNames($id) {
	$sql = "SELECT t.tag_id, t.tag_name, t.is_name FROM pdf_tags t, main_kat k WHERE k.file_id = ".$id." AND k.tag_id = t.tag_id AND t.is_name = 1 ORDER BY t.tag_name;";
	$result = $this->GetArray($sql);
	return $result;
	}

	function GetTag($id) {
	$sql = "SELECT tag_name, is_name FROM pdf_tags WHERE is_name = 0 AND tag_id = ".$id.";";
	$result = $this->GetRow($sql);
	return $result;
	}
    
    function GetName($id) {
	$sql = "SELECT tag_name, is_name FROM pdf_tags WHERE is_name = 1 AND tag_id = ".$id.";";
	$result = $this->GetRow($sql);
	return $result;
	}
    
    function DelTag ($id) {
	$sql = "DELETE FROM pdf_tags WHERE tag_id ='".$id."';";
	if (!$this->Execute($sql)) { $this->Error = FALSE;}
	else {
		$ksql = "DELETE FROM main_kat WHERE tag_id ='".$id."';";
		if (!$this->Execute($ksql)) { $this->Error = FALSE; }
		 else { $this->Error = 'Alle Verweise wurden gel&ouml;scht!'; }
		}
	return $this;
	}
    
    function DelName ($id) {
	$sql = "DELETE FROM pdf_tags WHERE tag_id ='".$id."';";
	if (!$this->Execute($sql)) { $this->Error = FALSE;}
	else {
		$ksql = "DELETE FROM main_kat WHERE tag_id ='".$id."';";
		if (!$this->Execute($ksql)) { $this->Error = FALSE; }
		 else { $this->Error = 'Alle Verweise wurden gel&ouml;scht!'; }
		}
	return $this;
	}

	function RenameTag ($id, $new_name, $is_name) {
    ($is_name == 1) ? $isname = 1 : $is_name = 0;
	$sql = "UPDATE pdf_tags SET tag_name = '".$new_name."', is_name = ".$is_name." WHERE tag_id = ".$id.";";
	if (!$this->Execute($sql)) { $this->Error = FALSE;}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde aktualisiert';}
	return $this;
	}
    
    function RenameName ($id, $new_name, $is_name) {
    ($is_name == 1) ? $isname = 1 : $is_name = 0;
	$sql = "UPDATE pdf_tags SET tag_name = '".$new_name."', is_name = ".$is_name." WHERE tag_id = ".$id.";";
	if (!$this->Execute($sql)) { $this->Error = FALSE;}
	else {$this->Error = 'Das hat geklappt! Der Eintrag wurde aktualisiert';}
	return $this;
	}

	function MakeTag($files_id, $name, $is_name) {
        //echo $files_id.$name.$is_name;break;
        
        ($is_name==1) ? $is_name = 1 : $is_name = 0;
        $now = date(Y.'-'.m.'-'.d);
        if ($name!='') {
            // print $files_id.' - '.$name;
                $gsql = "SELECT * FROM pdf_tags WHERE tag_name = '".$name."' AND is_name = ".$is_name.";";
                $result = $this->GetRow($gsql);
                //echo $result['tag_name'].'<br />';
                if ($result['tag_id']!='') {
                    $sql = "UPDATE pdf_tags SET tag_name = '".$name."', is_name= ".$is_name." WHERE tag_id = ".$result['tag_id'].";";
                    $this->Execute($sql);
                    $id = $result['tag_id'];
                } else {                    
                    $sql ="INSERT INTO pdf_tags (tag_id, tag_name, is_name, date) VALUES  ('','".$name."',".$is_name.",'".$now."');";
                    if ($id=$this->Execute($sql)) {
                        $id = $this->insert_id;
                    }
                }
                
            $tsql ="INSERT INTO main_kat (kat_id, file_id, tag_id, kat_name) VALUES  ('',".$files_id.",".$id.", 'Stichwort');";
            $result =$this->Execute($tsql);

        }
        return $result;
	}
    
    /* Tags und Names Ende */

	

}
?>