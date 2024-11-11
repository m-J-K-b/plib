

/* Auslesen des URI-Anhangs bei _cccc wird der Cache geleert */
parse_str($_SERVER[QUERY_STRING]);

function UndoMagicSlashing(&$var) {
	if(is_array($var)) {
		while(list($key, $val) = each($var)) {
			UndoMagicSlashing($var[$key]);
		}
	} else {
		$var = stripslashes($var);
	}
}
/*  Entfernen der MagicQuotes */

	UndoMagicSlashing($_GET);
	UndoMagicSlashing($_POST);
	UndoMagicSlashing($_COOKIES);
	UndoMagicSlashing($_REQUEST);

if (!$_GET['cmd']) {$_GET['cmd'] = 'pdf';}


/* Dies ist eine M�glichkeit f�r eine ganz schnelle ClearCache Funktion */
if (isset($_REQUEST['_cccc']))
{
print 'ClearCache';
/*$clear_all_cache=1;
$URI[0] = '';
$URI[1] = '';*/
}

require_once ('_admin/base.inc.php');

switch ($lang)
{
case 'en':
$lang = en;
break;
case 'de':
$lang = de;
break;
default;
$lang = SITELANG;
break;
}

/*Vorgabe der Templates und des Cachens */

$cache_lifetime = CACHE;
$my_cache_id = $_GET['cmd'];


/* Smarty wird gestartet */
$smarty = new Smarty_Main($my_cache_id ,$cache_lifetime);


/* Wenn keine Cache-Datei vorhanden ist, wird der Content erstellt */
if (!$smarty->is_cached($smarty_template, $my_cache_id))
{

 //$nav = smarty_getnav($_GET['cmd'],$_GET['id'],$lang, $this);
 $content = smarty_getcontent($_GET['cmd'],$_GET['id'],$lang, $this);

 require_once (PLUGIN.'/smartyplugs/head-'.$lang.'.php');
 $head = smarty_head ($params, $this);


//$smarty->load_filter("pre", "comment")

$smarty->assign ('HEAD', $head);
$smarty->assign ('TITLE', SITETITLE.' - '.$content['pagetitle']);
$smarty->assign ('NAV', $content['nav']);
$smarty->assign ('SUBNAV', $content['subnav']);
$smarty->assign ('TOPIC', $content['topic']);
$smarty->assign ('CONTENT', $content['content']);
$smarty->assign ('LANG', $lang);
$smarty->assign ('CMD', $_GET['cmd']);
$smarty->assign ('ID', $_GET['id']);

/* Smarty-Template */
$smarty_template = $content['template'];


/*  $smarty->load_filter("pre", "comment");	*/

}

$smarty->display($smarty_template, $my_cache_id);

?>