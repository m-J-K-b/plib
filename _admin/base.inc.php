<?php
 /* Konfigurationsvariablen */

 	define('ROOT', $_SERVER['DOCUMENT_ROOT'].'/'); // Rootverzeichnis
	define('SERVER', 'http://horstkaechele.de/plib/'); // Domainverzeichnis

	define('MAILACCOUNT','email@myserver.de');

	define('CACHE',0);  //Angabe der Cachezeit in Tagen

	define('SITELANG','de');
	define('SITETITLE','HKs Files V03');

	/* Datenbank Angaben */
  	define('STORRAGE', 'database'); // Datenverwaltung (file oder database)

  	// define('DBDRIVER', 'mysql'); // Databasetype (z.B. MySQL,Postgres,MSSql, Oracle...)
  	// define('DBHOST', 'db1830.1und1.de'); //Host Angabe  db1318.1und1.de
  	// define('DBUSER', 'dbo278965046'); //Datenbank User  dbo233658686
  	// define('DBPASS', 'kQhrXfFV'); //Passwort  Paxfbh3h
  	// define('DBNAME', 'db278965046'); //Datenbank-Name db233658686
  	// define('DB_PRE', ''); // DB-Pr�fix port3306
	define('DBDRIVER', getenv('DB_DRIVER')); // Database type
	define('DBHOST', getenv('DB_HOST')); // Hostname of the MySQL service in Docker Compose
	define('DBUSER', getenv('DB_USER')); // MySQL username defined in docker-compose
	define('DBPASS', getenv('DB_PASS')); // MySQL password defined in docker-compose
	define('DBNAME', getenv('DB_NAME')); // Database name defined in docker-compose
	define('DB_PRE', getenv('DB_PREFIX')); // DB prefix, if any

  	define ('ADMINPASS', 'c3284d0f94606de1fd2af172aba15bf3');   // Passwort f�r den Admin


 /* Verzeichnisse einlesen */
 	define ('LIBS', ROOT.'_libs/');
 	define('PLUGIN', ROOT.'_plugins/'); //Plugin Orden


	if (STORRAGE == 'database')
	{
	include_once (PLUGIN.'db_mainclass.php');
	require_once (PLUGIN.'MainFunctions_db.php');
	}
	else
	{
	require_once(PLUGIN.'MainFunctions_file.php');
	}


 /* Erweiterung der Smarty-Class f�r Cache-Funktion */
 	require_once (LIBS.'smarty/Smarty.class.php');	//LIBS.'smarty/libs/Smarty.class.php'
 	class Smarty_Main extends Smarty
 	{
 	function Smarty_Main($my_cache_id, $cache_lifetime)
   	{
		// Konstruktor. Diese Werte werden f�r jede Instanz automatisch gesetzt

		$this->Smarty();

		$this->template_dir = ROOT.'_templates/';
		$this->compile_dir = LIBS.'smarty/templates_c/';
		$this->config_dir = LIBS.'smarty/config/';
		$this->cache_dir = LIBS.'smarty/cache/';

		$this->compile_check = true;

		$this->setCacheTime($my_cache_id, $cache_lifetime);
		$this->assign('app_name','Smarty_Main');
   }

   function setCacheTime($id, $time)
   	{
		if ($time > 0) {
			if ($id != 'contact')
			{
			$this->caching = true;
			$this->cache_lifetime = $time*60*60*24;
			}else{
			$this->caching = false;
			$this->cache_lifetime = 0;
			}
		}
		return true;
	}

 }
?>