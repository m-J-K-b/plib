<?php
/**
 * Smarty plugin
 * @selfmade Smarty
 * @directory plugins
 */

/**
 * Smarty get content from database
 *
 * File: getdbcontent.php
 * Type: Class
 * Name: smarty_getcontent
 * Date: July 24, 2006<br>
 * Purpose:  get content from database (this requires ADOdb and a special dbclass.php for extended DB-access
 */
function smarty_getcontent($cmd, $id, $lang, &$smarty)
{

/**
* @access public
* @package WACT_CONTROLLERS
*/
class Request {

    function hasParameters() {
        return count($_GET) > 0;
    }

    function hasParameter($name) {
        return isset($_GET[$name]);
    }

    function getParameter($name) {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
    }

    function hasPostProperty($name) {
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            return isset($_POST[$name]);
        } else {
            return isset($_GET[$name]);
        }
    }

    function getPostProperty($name) {
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            if (isset($_POST[$name])) {
                return $_POST[$name];
            }
        } else {
            if (isset($_GET[$name])) {
                return $_GET[$name];
            }
        }
    }

    function exportPostProperties() {
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            return $_POST;
        } else {
            return $_GET;
        }
    }

    function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    function getPathInfo() {
        if (isset($_SERVER['PATH_INFO'])) {
            return $_SERVER['PATH_INFO'];
        }
    }
}


Class MainController
{


/* Hier werden die Seiten-Funktionen zugeordnet  */
function MainCommand($cmd, $id, $lang)
{

switch ($cmd)
{
default:
$cmd='pdf';
break;
}

$error_uri="lang=".$lang."&amp;cmd=".$cmd."&amp;id=".$id;

include(PLUGIN.'/lang-'.$lang.'.inc.php');

// include(PLUGIN.'/tag.inc.php');


if (file_exists(PLUGIN.'/'.$cmd.'.inc.php'))
{
include_once($cmd.'.inc.php');

$db = new content_DB();



$texte=$db->Exec_Command($id, $lang);

$this->subnav = $texte['subnav'];
$this->topic = $texte['topic'];
$this->content = $texte['content'];
$this->template = $texte['template'];

}else{
$this->content['name'] = 'Fehler';
$this->content['headline']= $Error;
$this->content['content'] = $Error_NoContent;
$this->template = 'index.html';

}


$this->MainNav($texte['nav']['id'], $lang);


return $this;
}



function MainNav($id, $lang)
{

$good = array('<','>','ÄÄ');
$bad = array('&gt','&lt','Č');

$db = new DB_MainClass();
$connect_error = $db->Connect();
if (!$connect_error)
	{
	$sql = 'SELECT * FROM pdf_nav WHERE visible=1 ORDER BY sort_id, nav_name;';
	$result = $db->GetArray($sql);

	foreach ($result as $row)
	{
		if ($id==$row['nav_id'])
		{
		$this->nav[] = array('id'=>$row['nav_id'],'active'=>1,'name'=>$row['nav_intname'], 'intro_text' =>nl2br($row['nav_text']));
		} else {
		$this->nav[] = array('id'=>$row['nav_id'],'active'=>0,'name'=>$row['nav_intname']);
		}
	}

	return $this;
	}
}



}

$content = new MainController;
$content->MainCommand($cmd, $id, $lang);


$content = array('nav'=>$content->nav,'subnav'=>$content->subnav,'topic'=>$content->topic, 'content'=>$content->content, 'head'=>$content->headline, 'pagetitle'=>$content->headline, 'template'=>$content->template);

return ($content);

}

?>