
    <?php
/**
 * Smarty plugin
 * @selfmade Smarty
 * @directory plugins
 */

/**
 * Smarty trimwhitespace outputfilter plugin
 *
 * File: head.php
 * Type: filter
 * Name: head
 * Date:     July 15, 2004<br>
 * Purpose:  Create a header
 */
function smarty_head($source, &$smarty)
{

 return $source.'
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<meta http-equiv="pragma" content="cache" />
	<meta name="Keywords" lang="de" content="PDF" />
	<meta name="Description" lang="de" content="Text" />
	<meta name="author" content="" />
	<meta http-equiv="Content-Language" content="de" />
	<link rel="shortcut icon" href="favicon.ico" />
	';
	}
	?>