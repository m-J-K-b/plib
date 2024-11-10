<?php
/**
 * Smarty plugin
 * @selfmade Smarty
 * @directory plugins
 */

/**
 * Smarty linker modifier plugin
 *
 * File:         modifier.linker.php
 * Type:         modifier
 * Name:         linker
 * Date:         February 28, 2009<br>
 * Purpose:      transform urls in text to clickable links
 */
function smarty_modifier_linker($link)
{

$link = str_replace("http://www.","www.",$link);
$link = str_replace("www.","http://www.",$link);
$link = preg_replace(
"/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a href=\"$1\" target=\"_blank\">$1</a>", $link);
$link = preg_replace(
"/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.
([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>",$link);

return $link;
}
?>
