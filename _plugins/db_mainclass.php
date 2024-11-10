<?php
#-------------------------------------------------------------------------
# Class: PiDatabase
# Version: 1.05
#
#-------------------------------------------------------------------------
# The CMS Powerweb and Standardcms is (c) 2006 by Jan Alfred Czarnowski (piratos@coftware.de)
# This project's homepage is: http://www.piratos.byethost33.com  and http://standardcms.de
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------


class DB_MainClass
{
	var $dbdriver = DBDRIVER;
   	var $dbhost = DBHOST;
	var $dbuser = DBUSER;
	var $dbpass = DBPASS;
	var $dbname	= DBNAME;
	var $dbprefix = DB_PRE;
	var $c = null;
	var $count=0;
   	var $total =0;
   	var $lastsql =null;
	var $num_queries = 0;
   	var $db_selected = null;
   	var $lasterror = 0;
   	var $lasterrortext = '';
   	var $backupdir = '';
   	var $os = '';
   	var $crlf = "\n";
   	var $fields = 0;
	var $query  = null;
   	var $types = array();
   	var $names = array();
   	var $dbTimeStamp = "Y-m-d H:i:s";
   	var $insert_id = null;
	var $rows_affected = null;
	var $num_rows = null;
	var $num_fields = null;
	var $explain = false;
	var $debug = false;


	function myDB()	{return true;}


   	// Herstellung der Verbindung zu Mysql und der Datenbank
   	function Connect()
   	{

	/* if (empty($prefix))die ('Es wird <b>zwingend</b> die Benutzung eines Tabellenprefixes erwartet! Prefix ist nicht vorhanden.'); */
	$this->c = @mysql_connect ($this->dbhost,$this->dbuser,$this->dbpass) ;
	if (!$this->dbname) $error='Keine Verbindung zu Mysql - bitte die Angaben zu Host, Username und Passwort und Name der Datenbank pr&uuml;fen';
	$this->dbname_selected   = @mysql_select_db($this->dbname,$this->c);
	if (!$this->dbname_selected) $error='Kann keine Verbindung herstellen zur Datenbank mit dem Namen '.$this->dbname;
	return $error;
    }



	//Ausf�hren einer SQL Anweisung
	function Execute($sql,$inputarr=false)
	{
		if ($inputarr && is_array($inputarr))
		{
		$c=count($inputarr);
		$sqlarr = explode('?', $sql);
		$s=count($sqlarr);
		if ($c+1 <> $s)return false;

		$sql ='';
		$i = 0;$j=0;
		while ($j<$s)
         {
          $sql .= $sqlarr[$i];
          if ($j<$c)
          {
            $v=$inputarr[$j];
            switch(gettype($v))
            {
             case 'string':$sql .= $this->Qstr($v);break;
             case 'double':$sql .= str_replace(',', '.', $v);break;
             case 'boolean':$sql .= $v ? 1 : 0;break;
             default:if ($v === null) $sql .= 'NULL';else $sql .= $v;
            }
         }
         $i++;$j++;
       }

      }
      $this->count++;
      $start = array_sum(explode(' ', microtime()));
      $this->lastsql=$sql;
      if ($this->explain ==true) {$temp ='EXPLAIN '.$sql;$sql =$temp;}
       if ($this->debug)
         echo 'Real SQL - String:<br />'.$sql.'<br /><hr />';

      $result = @mysql_query($sql);
      $this->total += (array_sum(explode(' ', microtime())) - $start);
      $this->lasterror =@mysql_errno();$this->lasterrortext= @mysql_error();
      if ( preg_match("/^(insert|delete|update|replace)\s+/i",$sql) )
      {
        $this->rows_affected = @mysql_affected_rows($this->c);

        if ( preg_match("/^(insert|replace)\s+/i",$sql) )

		$this->insert_id = @mysql_insert_id();

      	}elseif ( preg_match("/^(select|explain|show|describe)\s+/i",$sql) )
		{
		$this->num_rows = @$num_rows =@mysql_num_rows($result);
		$this->num_fields= @mysql_num_fields($result);
		}

		if ($this->debug)
		{
		echo "Dump of the result: <br />";
		while ($r =$this->Fetch($result,0)) {print_r($r);echo '<br />';};
		echo "<br />Last Errortext (if there is something):$this->lasterrortext <br /><hr /> End of Dump result<hr />";
		}
	return $result;
	}

// F�hrt eine SQL Abfrage durch und gibt das Ergebnis als Object Array ** zur�ck - kann direkt an Smarty �bergeben werden.
// ** Ausgabe kann mit setzen des Parameters $method �berschrieben werden
   function GetArray($sql,$inputarr=false,$method=0)
   {
     $result = $this->Execute($sql,$inputarr);
     $data = false;
     if ($result && $this->num_rows>0)
     {
       $data =array();
       while ($row = $this->Fetch($result,$method))
         $data[] = $row;
       $this->Free($result);
     }
       return $data;
   }


	function GetCol($sql,$inputarr=false,$trim=false,$method=0)
    {
    $result = $this->Execute($sql,$inputarr,$trim);
    $data = false;
    if ($result) {
   		$data=array();
    	while ($row = $this->Fetch($result,$method))
        $data[] = ($trim) ? trim(reset($row)) : reset($row);
       	$this->Free($result);
     	}
 	return $data;
   	}
   // Gibt die erste Zeile einer Abfrage zur�ck, normal als  assoziatives array, kann aber mit dem setzen von $method �berschrieben werden
// bei Nichterfolg ist der R�ckgabewert false
   function GetRow($sql,$inputarr=false,$method=0)
   {
     $result = $this->Execute($sql,$inputarr); //removed & befor &$sql,&$intputarr

     if ($result)
     {
        $row = $this->Fetch($result,$method);
        $this->Free($result);
        return $row;
     }
     else
        return false;
   }
   function GetOne($sql,$inputarr=false)
   {
      $row =$this->GetRow($sql,$inputarr,1);
      if ($row)
      {
        return $row[0];
      }
      else
        return false;

   }
   // h�ngt eine Limitanweisung an SQL dran, wenn vorhanden
   function SelectLimit( $sql, $nrows=-1, $offset=-1, $inputarr=false )
   {
      $limit = '';
      if ($offset != -1 || $nrows != -1)
      {
         $offset = ($offset>=0) ? $offset . "," : '';
         $sql .= ' LIMIT ' . $offset . ' ' . $nrows;
       }
      $result =& $this->Execute( $sql, $inputarr);
      return $result;
   }

   // Dient dazu einfach die Existenz eines Datensatzes zu pr�fen
   function Exists($sql,$inputarr=false)
   {
     $ok = false;
     $result = $this->GetRow($sql,$inputarr);
     if ($result)
      {
       $ok=true;
       unset($result);
      }
     return $ok;
   }

   function Escape($string)
   {
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    if (!is_int($string)) $string = @mysql_real_escape_string($string);
    return $string;
   }

   function Qstr($string)
   {
     return "'".$this->Escape($string)."'";
   }

   function IfNull( $field, $ifNull )
   {
		return " IFNULL($field, $ifNull) ";
   }


	//Holt alle Tabellennamen der Datenbank und gibt sie als Array aus
	function Tables()
	{
	$r = @mysql_list_tables($this->dbname, $this->c);
   	$out=array();
    while ($row = @mysql_fetch_row($r))
 		if (substr($row[0],0,strlen($this->dbprefix)) == $this->dbprefix) {$out[] =$row[0];}
	$this->Free($r);
	return $out;
	}


   // Fetched ein Execute - Ergebnis
   // method=0 Standard liefert ein associatives Array  zeile['feldname']
   // method=1 liefert ein Array mit numerischem Index  zeile[0]]
   // method=2 liefert ein Array bestehend aus Objecten zeile->feldname
	function Fetch($result, $method=0)
	{
	switch ($method) {
		case 0:
		return @mysql_fetch_assoc($result);
		break;
		case 1:
		return @mysql_fetch_row($result);
		break;
		case 2:
		return @mysql_fetch_object($result);
		break;
    	}
	}


   //liefert die Anzahl der Felder in der Ergebnismenge, die mit dem Parameter $result angegeben wurde.
   function NumFields($result)
   {
     return @mysql_num_fields ($result);

   }
   //liefert die Anzahl der Datens�tze einer Ergebnismenge von $result aus einer SELECT Anfrage
   function RecordCount($result)
   {
     return @mysql_num_rows($result);
   }

   // liefert NUR die Anzahl der Datens�tze aus einer SQL Abfrage
   function Records($sql,$inputarr=false)
   {
     $result = $this->Execute($sql,$inputarr=false);
     $sum=$this->num_rows;
     $this->Free($result);
     return $sum;
   }

   //gibt den Mysql - Speicher frei, der mit der $result assoziert ist (es handelt sich NICHT um den Speicher von PHP)
   function Free($result)
   {
     @mysql_free_result($result);
   }

   //liefert die ID, die bei der letzten INSERT-Operation f�r ein Feld vom Typ AUTO_INCREMENT vergeben wurde
   function LastID()
   {
      return $this->insert_id;
   }

   function Ping()
   {
     return @mysql_ping($this->c);
   }

   function Close()
   {
      if ($this->Ping()) @mysql_close($this->c);
   }

   function SetCrlf()
   {
     if (strstr($HTTP_USER_AGENT, 'Win'))
     $this->os  = 'Win';
     elseif (strstr($HTTP_USER_AGENT, 'Mac'))
     $this->os = 'Mac';
     elseif (strstr($HTTP_USER_AGENT, 'Linux'))
            $this->os = 'Linux';
     elseif (strstr($HTTP_USER_AGENT, 'Unix'))
            $this->os = 'Unix';
     elseif (strstr($HTTP_USER_AGENT, 'OS/2'))
            $this->os = 'OS/2';
     else
            $this->os = 'Other';
     $this->crlf = "\n";
     // Win case
     if ($this->os == 'Win') $this->crlf = "\r\n";
        // Mac case
     elseif ($this->os == 'Mac')$this->crlf = "\r";
   }


   function UnixTimeStamp($v)
   {
    if (!preg_match("|^([0-9]{4})[-/\.]?([0-9]{1,2})[-/\.]?([0-9]{1,2})[ ,-]*(([0-9]{1,2}):?([0-9]{1,2}):?([0-9\.]{1,4}))?|", ($v), $rr))return false;
    if ($rr[1] <= 100 && $rr[2]<= 1) return 0;
    // h-m-s-MM-DD-YY
    return mktime($rr[5], $rr[6], $rr[7], $rr[2], $rr[3], $rr[1]);
   }

   function DBTimeStamp($timestamp)
   {
     if (empty($timestamp) && $timestamp !== 0) return 'null';
     # strlen(14) allows YYYYMMDDHHMMSS format
     if (!is_string($timestamp) || (is_numeric($timestamp) && strlen($timestamp)<14)) return date($this->fmtTimeStamp, $timestamp);
     if ($timestamp === 'null')return $timestamp;
     return date($this->fmtTimeStamp, $this->UnixTimeStamp($timestamp));
   }


   function Backup($backupdir,$compress=true)
   {
   	$this->backupdir = $backupdir;
    $this->SetCrlf();
    $tables = $this->Tables();
	$NumTables=count($tables);

    if ($NumTables> 0)
     {
 	$str = array('char','varchar','blob','text','enum','set','tinytext','tinyblob','text','blob','mediumtext','mediumbob','longblob','timestamp');
       $value = '';
       foreach ($tables as $table)
       {

          $this->Execute('LOCK TABLES ' . $table . ' WRITE');
          $value .= 'DROP TABLE IF EXISTS `' . $table . '`;'.$this->crlf;;
          $result = $this->Execute('SHOW CREATE TABLE ' . $table);
          $row = $this->Fetch($result,0);
          $this->Free($result);
          //$value .= str_replace("\n", '\r\n', $row['Create Table']) . ';';
          $value .= $row['Create Table']. ';'.$this->crlf;
          unset($this->fields);
          $fields = $this->GetArray('SHOW FIELDS FROM '.$table,1);
          if (count($fields) ==0)
          { $this->Execute('UNLOCK TABLES');
            return false;
          }
          $this->types=array();
          foreach ($fields as $f) $this->types[] = $f->Type;
          unset($fields);
          $result = $this->Execute('SELECT * FROM ' . $table);
          if ($result)
          {
             $v='';
              while ($data = $this->Fetch($result,1))
             { $i=0;
               $v = '';
               $x=count($this->types)-1;
               foreach ($data as $d)
               {

                  $p = strrpos($this->types[$i],"(");
                  if (!$p ===false) $s = substr($this->types[$i], 0,strrpos($this->types[$i],"("));
                  else $s = $this->types[$i];
                 if ($s == in_array($s,$str))

                   $v .= "'".$this->Escape(addslashes($d),$this->c)."'";

                 else
                   if ($d == NULL) $v .='NULL';else  $v .=  $d;
                 if ($i<$x) $v .=", ";else $value .= 'INSERT INTO `' . $table . '` VALUES (' . $v . ');'.$this->crlf;
                 $i ++;
               }
             }


          }
          unset($this->types);unset($this->names);
          $this->Free($result);
          $this->Execute('UNLOCK TABLES');
       }

       $fname = $backupdir.'/';
       $fname .= date('d_m_y__H_i_s');
       $fname .= ($compress ? '.sql.gz' : '.sql');
       if (!($zf = gzopen($fname, 'w9')))return false;
       gzwrite($zf, $value);
       gzclose($zf);
       return $fname;

     }
   }
   function Restore($backupdir,$fname)
   {
      $fname = $backupdir.'/'.$fname;
      $this->SetCrlf();
       if ($this->os =='Win') $modus='rb'; else $modus='r';
        if (!($f = gzopen($fname, $modus))) return false;
        while (!gzeof($f)) $buffer .= gzread($f, 16384);
        gzclose ($f);
        if (empty($buffer)) return false;
        $sql = '';
        $start_pos = 0;
        $i = 0;
        $len = strlen($buffer);

        while ($i < $len) {
            $oi = $i;
            $p1 = strpos($buffer, '\'', $i);
            if ($p1 === FALSE) {
                $p1 = 2147483647;
            }
            $p2 = strpos($buffer, '"', $i);
            if ($p2 === FALSE) {
                $p2 = 2147483647;
            }
            $p3 = strpos($buffer, ';', $i);
            if ($p3 === FALSE) {
                $p3 = 2147483647;
            }
            $p4 = strpos($buffer, '#', $i);
            if ($p4 === FALSE) {
                $p4 = 2147483647;
            }
            $p5 = strpos($buffer, '--', $i);
            if ($p5 === FALSE || $p5 >= ($len - 2) || $buffer[$p5 + 2] > ' ') {
                $p5 = 2147483647;
            }
            $p6 = strpos($buffer, '/*', $i);
            if ($p6 === FALSE) {
                $p6 = 2147483647;
            }
            $p7 = strpos($buffer, '`', $i);
            if ($p7 === FALSE) {
                $p7 = 2147483647;
            }
            $i = min ($p1, $p2, $p3, $p4, $p5, $p6, $p7);
            if ($i == 2147483647) {
                $i = $oi;
                if (!$finished) {
                    break;
                }
                if (trim($buffer) == '') {
                    $buffer = '';
                    $len = 0;
                    break;
                }
                $i = strlen($buffer) - 1;
            }
            $ch = $buffer[$i];
            if (!(strpos('\'"`', $ch) === FALSE)) {
                $quote = $ch;
                $endq = FALSE;
                while (!$endq) {
                    $pos = strpos($buffer, $quote, $i + 1);
                    if ($pos === FALSE) {
                        if ($finished) {
                            $endq = TRUE;
                            $i = $len - 1;
                        }
                        break;
                    }

                    $j = $pos - 1;
                    while ($buffer[$j] == '\\') $j--;

                    $endq = (((($pos - 1) - $j) % 2) == 0);

                    $i = $pos;
                }
                if (!$endq) {
                    break;
                }
                $i++;
                if ($finished && $i == $len) {
                    $i--;
                } else {
                    continue;
                }
            }
            if ((($i == ($len - 1) && ($ch == '-' || $ch == '/'))
                || ($i == ($len - 2) && (($ch == '-' && $buffer[$i + 1] == '-') || ($ch == '/' && $buffer[$i + 1] == '*')))
                ) && !$finished) {
                break;
            }
            if ($ch == '#'
                    || ($i < ($len - 1) && $ch == '-' && $buffer[$i + 1] == '-' && (($i < ($len - 2) && $buffer[$i + 2] <= ' ') || ($i == ($len - 1) && $finished)))
                    || ($i < ($len - 1) && $ch == '/' && $buffer[$i + 1] == '*')
                    ) {
                if ($start_pos != $i) {
                    $sql .= substr($buffer, $start_pos, $i - $start_pos);
                }
                $i = strpos($buffer, $ch == '/' ? '*/' : "\n", $i);
                if ($i === FALSE) {
                    if ($finished) {
                        $i = $len - 1;
                    } else {
                        break;
                    }
                }
                // Skip *
                if ($ch == '/') {
                    $i++;
                }
                $i++;
                $start_pos = $i;
                if ($i == $len) {
                    $i--;
                } else {
                    continue;
                }
            }
            if ($ch == ';' || ($finished && ($i == $len - 1))) {
                $tmp_sql = $sql;
                if ($start_pos < $len) {
                    $tmp_sql .= substr($buffer, $start_pos, $i - $start_pos + 1);
                }
                if (!preg_match('/^([\s]*;)*$/', trim($tmp_sql))) {
                    $sql = $tmp_sql;
                    $result = $this->Execute($sql);
                    $buffer = substr($buffer, $i + 1);

                    $len = strlen($buffer);
                    $sql = '';
                    $i = 0;
                    $start_pos = 0;
                    if ((strpos($buffer, ';') === FALSE) && !$finished) {
                        break;
                    }
                } else {
                    $i++;
                    $start_pos = $i;
                }
            }
        } // End of parser loop


        if ($result) return true; else return false;
      }


   // liefert nur die Werte von Mysql uptime, threads, queries, open tables, flush tables und queries pro sec - f�r Testzwecke
   function Statistik()
   {
   	$this->Connect();
    $r=$this->Execute("SHOW STATUS;");
    $out ='<table>';
    while ($one = $this->Fetch($r))
    {
      $out .="<tr><td width='80'>&nbsp;</td><td>".$one['Variable_name']."</td><td>".$one['Value']."</td></tr>";
    }
    $out. '</table>';

	return $out;
   }

   function Optimize()
   {
      $tables = $this->Tables();
      if (count($tables) == 0) return '';
      $out = '<br /><table summary="Ergebnisse der Datenbankoptimierung">';
      foreach ($tables as $table)
      {  $out .='<tr><td width="20%">'.$table.'</td><td>';
        $o = $this->Execute('REPAIR TABLE '.$table);
        if ($o) $out .=' repariert   '; else $out .=' nicht repariert  ||';
        $o = $this->Execute('OPTIMIZE TABLE '.$table);
        if ($o) $out .=' optimiert   '; else $out .=' nicht optimiert';
        $out .= '</td></tr>';
      }
      $out .= '</table>';
      return $out;
   }


}  // Ende der Klasse

?>