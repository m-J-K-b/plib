<?php
/* hier kommt der Code für die File-Geschichten hinein */

function Files($action, $id, $files_id) {

	$id = explode('_',$id);
	switch($action)
	{
	case 'del_entry';
		$db = new DB_AdminClass();
		$now = $db->DeleteFilesID($_REQUEST['files_id']);
		if ($now==TRUE) return $content=$now->Error;
		$db->close();
	break;
	case 'edit_entry';
		$db = new DB_AdminClass();
		/* Anlegen neuer Tags und deren IDs ermitteln */

		$delTags = $db->DeleteTagByFilesID($_POST['files_id']);
		if ($delTags->Error == FALSE) {
            $anz = count($_POST['newtag']);
            // Die neuen Tags werden in die DB geschrieben
			for ($i=0;$i<=$anz;$i++) {
				if (!empty($_POST['newtag'][$i]['tag_name'])) {
                    $db->MakeTag($_POST['files_id'], $_POST['newtag'][$i]['tag_name'],$_POST['newtag'][$i]['is_name']);
				}
			}
		}


		//print $tag1.$tag2.$tag3.$tag4.$tag5;



		/* Hier werden die Bilder verarbeitet */
		if ($_FILES['img_file']['size'] < $_POST['MaxFileSize'] && $_FILES['img_file']['size']>0) {
				$img_filename = str_replace(' ','_', $_FILES['img_file']['name']);
				@chmod(ROOT.'_uploads/_img/', 0777);
				$upload = @copy($_FILES['img_file']['tmp_name'], ROOT.'_uploads/_img/'.$img_filename);
				if ($upload==TRUE) @chmod(ROOT.'_uploads/_img/'.$img_file, 0777);
				$success_img = 'Das Bild wurde hochgeladen!';
			} else {$img_filename=$_REQUEST['img_name']; $success_img= 'Ein Bild wurde nicht hochgeladen!';}
			if ($_REQUEST['img_set']==TRUE) : $img_set='short'; else: $img_set='long'; endif;
		/* Hier werden die PDFs verarbeitet */
		if ($_FILES['pdf_file']['size'] < $_POST['MaxFileSize'] && $_FILES['pdf_file']['size']>0) {
				$pdf_filename = str_replace(' ','_', $_FILES['pdf_file']['name']);
				@chmod(ROOT.'_uploads/_pdfs/', 0777);
				$upload = @copy($_FILES['pdf_file']['tmp_name'], ROOT.'_uploads/_pdfs/'.$pdf_filename);
				if ($upload==TRUE) @chmod(ROOT.'_uploads/_pdfs/'.$pdf_file, 0777);
				$success_file = 'Die Datei wurde hochgeladen!';
			}else {$pdf_filename=$_REQUEST['files_name']; $success_file= 'Eine Datei wurde nicht hochgeladen!';}


		$now = $db->UpdateFilesID($_POST['files_id'], $_POST['topic_id'], $_POST['head'], $_POST['text'], $_POST['long_head'], $_POST['long_text'], $_POST['files_name'], $_POST['files_date'],$img_filename, $img_set, $_POST['visible']);
		if ($now==TRUE) return $content=$now->Error;

		$db->close();
	break;
	case 'save_entry';
		$db = new DB_AdminClass();

		/* Hier werden die Bilder verarbeitet */
		if ($_FILES['img_file']['size'] < $_POST['MaxFileSize'] && $_FILES['img_file']['size']>0) {
				$img_filename = str_replace(' ','_', $_FILES['img_file']['name']);
				@chmod(ROOT.'_uploads/_img/', 0777);
				$upload = @copy($_FILES['img_file']['tmp_name'], ROOT.'_uploads/_img/'.$img_filename);
				if ($upload==TRUE) @chmod(ROOT.'_uploads/_img/'.$img_file, 0777);
				$success_img = 'Das Bild wurde hochgeladen!';
			} else {$img_filename=$_REQUEST['img_name']; $success_img= 'Ein Bild wurde nicht hochgeladen!';}
			if ($_REQUEST['img_set']==TRUE) : $img_set='short'; else: $img_set='long'; endif;
		/* Hier werden die PDFs verarbeitet */
		if ($_FILES['pdf_file']['size'] < $_POST['MaxFileSize'] && $_FILES['pdf_file']['size']>0) {
				$pdf_filename = str_replace(' ','_', $_FILES['pdf_file']['name']);
				@chmod(ROOT.'_uploads/_pdfs/', 0777);
				$upload = @copy($_FILES['pdf_file']['tmp_name'], ROOT.'_uploads/_pdfs/'.$pdf_filename);
				if ($upload==TRUE) @chmod(ROOT.'_uploads/_pdfs/'.$pdf_file, 0777);
				$success_file = 'Die Datei wurde hochgeladen!';
			}else {$pdf_filename=$_REQUEST['files_name']; $success_file= 'Eine Datei wurde nicht hochgeladen!';}

		$now = $db->MakeFiles($_POST['topic_id'], $_POST['head'], $_POST['text'], $_POST['long_head'], $_POST['long_text'], $pdf_filename , $_POST['files_date'], $img_filename, $img_set, $_POST['visible']);
		$message = $now->Error;


		/* Anlegen neuer Tags und deren IDs ermitteln */
		for ($i=0;$i<=count($_POST['newtag']);$i++) {
			if (!empty($_POST['newtag'][$i]['tag_name'])) {
                $db->MakeTag($_POST['files_id'], $_POST['newtag'][$i]['tag_name'],$_POST['newtag'][$i]['is_name']);
			}
		}

		if ($now==TRUE) return $content = $message.'<br />'.$success_file.'<br />'.$success_img;
		/*}else{return $content='<h3>Fehler!</h3><p>Die Datei ist leider gr&ouml;&szlig;er als 10MB - sie wurde nicht hochgeladen!</p>';}
		*/

		$db->close();
	break;
	case 'del';
		$new_action = 'del_entry';
		$db = new DB_AdminClass();
		$files = $db->GetFilesID($files_id);
		$intro = '<h3 class="red">Vorsicht! Sie l&ouml;schen nun eine Datei!</h3>';
		$img_form = ImgForm($files['img'], $files['img_set']);
		$file_form = FileForm($files['files_name']);
		$pform = 'V&Ouml;-Datum:<br /> <input type="text" name="files_date" value="'.$files['files_date'].'" style="width: 120px;" /><br /><br />';
		$tag_form = TagForm($files_id);
		$submit_text = 'l&ouml;schen';
		$content = ContentForm($id[0], $id[1], $id[2], $new_action, $intro, $sort=FALSE, $img_form, $file_form, $pform, $tag_form, $submit_text, $files['files_id'], $files['files_name'], $files['files_head'], $files['files_text'], $files['long_head'], $files['long_text'], $files['visible']) ;
		$db->close();
	break;
	case 'edit';
		$new_action = 'edit_entry';
		$db = new DB_AdminClass();
		$files = $db->GetFilesID($files_id);
		$intro = '<p class="editheadline">Eintrag bearbeiten!</p>';
		$img_form = ImgForm($files['img'], $files['img_set']);
		$file_form = FileForm($files['files_name']);
		$pform = 'V&Ouml;-Datum:<br /> <input type="text" name="files_date" value="'.$files['files_date'].'" style="width: 120px;" /><br /><br />';
		$tag_form = TagForm($files_id);
		$submit_text = '&auml;ndern';
		$content = ContentForm($id[0], $id[1], $id[2], $new_action, $intro, $sort=FALSE, $img_form, $file_form, $pform, $tag_form, $submit_text, $files['files_id'], $files['files_name'], $files['files_head'], $files['files_text'], $files['long_head'], $files['long_text'], $files['visible']) ;

		$db->close();
	break;
	case 'shift';
		$intro = '<h3>PDF-Datei verschieben</h3>';
	break;
	default:
		$new_action = 'save_entry';
		$intro = '<p class="editheadline">Legen Sie einen neuen Eintrag an!</p>';
		$img_form = ImgForm($name=FALSE, $show=TRUE);
		$file_form = FileForm($name=FALSE);
		$pform = 'V&Ouml;-Datum:<br /> <input type="text" name="files_date" value="'.date(Y.'-'.m.'-'.d).'" style="width: 120px;" /><br /><br />';
		$tag_form = TagForm(FALSE, FALSE, FALSE, FALSE, FALSE);
		$submit_text = 'speichern';
		$content = ContentForm($id[0], $id[1], $id[2], $new_action, $intro, $sort=FALSE, $img_form, $file_form, $pform, $tag_form, $submit_text, $files_id, FALSE, FALSE, FALSE, FALSE, FALSE, 1) ;
	break;
	}

	return $content;
	}

    
// Methode fuer die Stichworte und Namen
function TagForm ($files_id) {

	$db = new DB_AdminClass();
   
	$tags = $db->GetTags($files_id);
	$a=1;
	$tag_no = count($tags);
	//print_r($tags);
	if ($tag_no<10) {
		for($a=0;$a<=10;$a++) {
        ($tags[$a]['is_name'] == 1) ? $check = ' checked="checked"' : $check = FALSE;
		$tag_form .= '<input type="text" name="newtag['. $a .'][tag_name]" value="'.$tags[$a]['tag_name'].'" class="tag_name" />&nbsp;<input type="checkbox" name="newtag['. $a .'][is_name]" value="1"'.$check.' class="tag_check" /><br />';
		}
	} else {
		for($a=0;$a<=$tag_no;$a++) {
		$tag_form .= '<input type="text" name="newtag['. $a .'][tag_name]" value="'.$tags[$a]['tag_name'].'" class="tag_name" />&nbsp;<input type="checkbox" name="newtag['. $a .'][is_name]" value="1"'.$check.' class="tag_check" /><br />';
		}
	}
	$tag_form .= '<div id="short1" class="show"><a href="#tags" onclick="SwapLayer(\'long1\',\'short1\')">+ more</a></div><div id="long1" class="hide">';
	$tag_form .= '<input type="text" name="newtag['. $a++ .'][tag_name]" value="" class="tag_name" />&nbsp;<input type="checkbox" name="newtag['. $a++ .'][is_name]" value="1"'.$check.' class="tag_check" /><br />';
	$tag_form .= '<input type="text" name="newtag['. $a++ .'][tag_name]" value="" class="tag_name" />&nbsp;<input type="checkbox" name="newtag['. $a++ .'][is_name]" value="1"'.$check.' class="tag_check" /><br />';
	$tag_form .= '<input type="text" name="newtag['. $a++ .'][tag_name]" value="" class="tag_name" />&nbsp;<input type="checkbox" name="newtag['. $a++ .'][is_name]" value="1"'.$check.' class="tag_check" /><br />';
	$tag_form .= '<input type="text" name="newtag['. $a++ .'][tag_name]" value="" class="tag_name" />&nbsp;<input type="checkbox" name="newtag['. $a++ .'][is_name]" value="1"'.$check.' class="tag_check" /><br />';
	$tag_form .= '<input type="text" name="newtag['. $a++ .'][tag_name]" value="" class="tag_name" />&nbsp;<input type="checkbox" name="newtag['. $a++ .'][is_name]" value="1"'.$check.' class="tag_check" /><br />';

	$tag_form .= '<a href="#tags" onclick="SwapLayer(\'short1\',\'long1\')">- less</a></div>';

	$db->close();

	$form = '<div id="tags">Schlagworte: <span style="padding-left: 140px;">(ist Name)</span><br />'.$tag_form.'<br />
	<br />';
    // Liste der Stichworte
	$tags = $db->GetAllTags();
    $tagliste = '';
	if ($tags[0]['tag_name'] !='') {
		foreach ($tags as $tag) {
			$tagliste .= $tag['tag_name'].'<br />';
		}
	} 
	$form .= 'Liste aller Schlagworte (auch nicht mehr verwendete!)
	</div><div style="border:1px solid #DDDDDD;height:150px;overflow:scroll;text-align:left; font-size: 11px;">'.$tagliste.'</div>';
    
    // Liste der Namen
	$names = $db->GetAllNames();
    $nameliste = '';
	if ($names[0]['tag_name'] !='') {
		foreach ($names as $name) {
			$nameliste .= $name['tag_name'].'<br />';
		}
	} 
	$form .= '<div id="names"><br /><br />Liste aller Namen (auch nicht mehr verwendete!)
	</div><div style="border:1px solid #DDDDDD;height:150px;overflow:scroll;text-align:left;; font-size: 11px;">'.$nameliste.'</div>';

	return $form;
}

// Methode fuer die Bilder
function ImgForm ($name, $show) {
	$img_folder = opendir(ROOT.'_uploads/_img');

	if ($show=='short') {
	$set = ' checked="checked"';
	}

	while ($imgs = readdir($img_folder))
		{
		if(eregi('\.(jpg)$', $imgs) || eregi('\.(gif)$', $imgs) || eregi('\.(png)$', $imgs)) {
			if ($name==$imgs) {
				$pics .= '<option value="'.$imgs.'" title="'.$imgs.'" selected="selected">'.$imgs.'</option>';
			} else {
				$pics .= '<option value="'.$imgs.'" title="'.$imgs.'">'.$imgs.'</option>';
			}
		}
	}

	$form = 'verwendete Bilder:<br />
	<select name="img_name" style="width: 250px"><option value="">&nbsp;</option>'.$pics.'</select><br/>
	neues Bild uploaden (JPG | GIF | PNG):
	<input type="hidden" name="MaxFileSize" value="10000000" />
	<input type="file" name="img_file" style="width: 220px;" /><br /><br />
	<input type="checkbox" name="img_set" value="1"'.$set.' style="vertical-align: middle" /> &nbsp;Bild immer anzeigen<br /><br />';

	return $form;
}

// Methode fuer die PDFs
function FileForm ($name) {
	$pdf_folder = opendir(ROOT.'_uploads/_pdfs');


	while ($pdfs = readdir($pdf_folder))
		{
		if(eregi('\.(pdf)$', $pdfs)) {
			if ($name==$pdfs) {
				$files .= '<option value="'.$pdfs.'" title="'.$pdfs.'" " selected="selected">'.$pdfs.'</option>';
			} else {
				//$files .= '<option value="'.$pdfs.'">'.$pdfs.'</option>';
				$files .= '<option value="'.$pdfs.'" title="'.$pdfs.'">'.$pdfs.'</option>';
			}
		}
	}

	$form = 'verwendete PDF-Datei:<br />
	<select name="files_name" style="width: 250px"><option value="">&nbsp;</option>'.$files.'</select><br/>
	neue Datei hochladen <br /> (ACHTUNG: Der PDF-Dateiname darf keine Umlaute und/oder Sonderzeichen enthalten!) :<br />
	<input type="hidden" name="MaxFileSize" value="10000000" />
	<input type="file" name="pdf_file" style="width: 220px;" /><br /><br />';

	return $form;
}

// Methode fuer das allgemeine Formular
function ContentForm ($id0, $id1, $id2, $new_action, $intro, $sort, $img_form, $file_form, $pform, $tag_form, $submit_text, $files_id=FALSE, $files_name=FALSE, $files_head=FALSE, $files_text=FALSE, $long_head=FALSE, $long_text=FALSE, $visible) {
 if ($visible=='0') : $check = ''; else: $check = ' checked="checked"'; endif;

 if ($sort != FALSE) {$sform ='<input type="text" name="sort" style="width: 20px" /> Sortierung &nbsp; &nbsp;';}

 $form = '<form method="post" action="_admin.php?cmd=files&amp;id='.$id0.'_'.$id1.'_'.$id2.'&amp;action='.$new_action.'&amp;files_id='.$files_id.'" enctype="multipart/form-data" accept-charset="UTF-8">
			'.$intro.'
			<input type="hidden" name="subnav_id" value="'.$id1.'" />
			<input type="hidden" name="topic_id" value="'.$id2.'" />
			<input type="hidden" name="files_id" value="'.$files_id.'" />
			Name: <br/>
			<input type="text" name="head" value="'.$files_head.'" class="full" /><br />
			<br />
			'.$sform.'
			<input type="checkbox" name="visible" value="1"'.$check.' /> Dieser Punkt ist sofort sichtbar<br />
			<br />
			Text (Kurzbeschreibung):<br />
			<textarea name="text" style="height: 90px;">'.$files_text.'</textarea><br />
			<br />
			'.$img_form.'<br />
			Langtext Ueberschrift: <br/>
			<input type="text" name="long_head" value="'.$long_head.'" class="full" /><br />
			Langext:<br />
			<textarea name="long_text">'.$long_text.'</textarea>WRKNPRGRSS Start<br /><br />
			'.$file_form.'WRKNPRGRSS End<br />
			'.$tag_form.'<br /><br />
			'.$pform.'<br /><br />
			<input type="button" onclick="javascript:history.back(1);" name="back" value="zur&uuml;ck" class="submit" /> &nbsp;
			<input type="submit" name="submit" value="'.$submit_text.'" class="submit" />
			<p>&nbsp;</p>
			</form>';

 return $form;
}


function GetFiles($topicid) {
	$db = new DB_AdminClass();
	$files = $db->GetFiles($topicid);
	return $files;
	}

function GetAllTags() {
	$db = new DB_AdminClass();
	$tags = $db->GetAllTags();
	return $tags;
	}

function make_file($id) {
	$content = $id;
	return $content;
	}

function edit_file($id) {
	$content = $id;
	return $content;
	}

function delete_file($id) {
	$content = $id;
	return $content;
	}



?>