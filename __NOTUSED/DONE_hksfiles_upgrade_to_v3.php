<?php

####################################################################################
#                                                                                  #
# this script transforms plib tags from v2 to v3 by inserting a 'main_cat' table   #
# any existing 'main_cat' table will be dropped before insert! take care :)        #
# NO error-checking except db-connection failure, redundant coding,                #
# NO status-messages: kind of a hack... ;)                                         #
#                                                                                  #
# IMPORTANT:                                                                       #
# SET-UP YOUR DB-CREDENTIALS WITHIN CONFIG-BLOCK!!!                                #
#                                                                                  #
# pretty-print: @tab-size: 2                                                       #
#                                                                                  #
# benjamin@viergleicheins.de 2009/03/04                                            #
#                                                                                  #
####################################################################################



# config ###########################################################################

$server 		= "db1830.1und1.de";
$user 			= "dbo278965046";
$password 	= "kQhrXfFV";
$db 				= "db278965046";

# do not edit below this.... #######################################################


$link = @mysql_connect($server, $user, $password) or die("no connect");
mysql_select_db($db);


$sql = "DROP TABLE IF EXISTS `main_kat`";
mysql_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS `main_kat` (
  `kat_id` int(11) NOT NULL auto_increment,
  `file_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `kat_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`kat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
mysql_query($sql);


$sql = "SELECT files_id, tag1, tag2, tag3, tag4, tag5 FROM pdf_file";
$rs = mysql_query($sql);

while($row = mysql_fetch_assoc($rs)) {
	if($row['tag1'] != "") mysql_query("INSERT INTO main_kat (file_id, tag_id) VALUES(" . $row['files_id'] . ", " . $row['tag1']. ");");
	if($row['tag2'] != "") mysql_query("INSERT INTO main_kat (file_id, tag_id) VALUES(" . $row['files_id'] . ", " . $row['tag2']. ");");
	if($row['tag3'] != "") mysql_query("INSERT INTO main_kat (file_id, tag_id) VALUES(" . $row['files_id'] . ", " . $row['tag3']. ");");
	if($row['tag4'] != "") mysql_query("INSERT INTO main_kat (file_id, tag_id) VALUES(" . $row['files_id'] . ", " . $row['tag4']. ");");
	if($row['tag5'] != "") mysql_query("INSERT INTO main_kat (file_id, tag_id) VALUES(" . $row['files_id'] . ", " . $row['tag5']. ");");
}

# done =8-)
?>