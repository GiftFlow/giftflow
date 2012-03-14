<?php
define('BASEPATH', str_replace("\\", "/", realpath(dirname(__FILE__)))); 

include_once('system/application/config/database.php');
$config = $db['default'];


/* Accepts a filename and imports the SQL script in it.

   Returns: true if all is well
            false if something is wrong
            (error message is embedded in $errmsg)

   One can also use mysql_error() if this function
   returns an error.

*/
function mysql_import_file2($filename) {

	$sql = explode(";\n", file_get_contents ($filename));
	$n = count ($sql) - 1;
	for ($i = 0; $i < $n; $i++) {
		$query = $sql[$i];

		//echo $query;

		$result = mysql_query ($query)
		or die ('<p>Query: <br><tt>' . $query .
		'</tt><br>failed. MySQL error: ' . mysql_error());
	}

}

function mysql_import_file($filename, &$errmsg)
{
   /* Read the file */
   $lines = file($filename);

   if(!$lines)
   {
      $errmsg = "cannot open file $filename";
      return false;
   }

   $scriptfile = false;

   /* Get rid of the comments and form one jumbo line */
   foreach($lines as $line)
   {
      $line = trim($line);

      if(!ereg('^--', $line))
      {
         $scriptfile.=" ".$line;
      }
   }

   if(!$scriptfile)
   {
      $errmsg = "no text found in $filename";
      return false;
   }

   /* Split the jumbo line into smaller lines */

   $queries = explode(';', $scriptfile);

   /* Run each line as a query */

   foreach($queries as $query)
   {
      $query = trim($query);
      if($query == "") { continue; }
      if(!mysql_query($query.';'))
      {
         $errmsg = "query ".$query." failed";
         return false;
      }
   }

   /* All is well */
   return true;
} 


function dropTables() {

	$sql = "SHOW TABLES";
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);

	print "dropping tables ($num_rows)\n";

	if (!$result) {
		echo "DB Error, could not list tables\n";
		echo 'MySQL Error: ' . mysql_error();
		exit;
	}

	while ($row = mysql_fetch_row($result)) {
		//echo "Table: {$row[0]}\n";
		$table = $row[0];

		$deleteIt=mysql_query("DROP TABLE $table");
/*
		if($deleteIt)
			echo "The table \"$table\" has been deleted with succes!\n";
		else
			echo "An error has occured...please try again\n";
*/
	}
}

# MAIN

# connect to the DB
if (!mysql_connect($config['hostname'], $config['username'], $config['password'])) {
	echo 'Could not connect to mysql';
	exit;
}

mysql_select_db($config['database']);

# drop the foriegn keys

$result = mysql_query("SELECT DISTINCT table_name, constraint_name"
  . " FROM information_schema.key_column_usage"
  . " WHERE constraint_schema = '".$config['database']."'"
  . " AND referenced_table_name IS NOT NULL");
while($row = mysql_fetch_assoc($result)) {
//echo "ALTER TABLE `$row[table_name]`". " DROP FOREIGN KEY `$row[constraint_name]`";
  mysql_query("ALTER TABLE `$row[table_name]`"
    . " DROP FOREIGN KEY `$row[constraint_name]`")
    or die(mysql_error());
}

# drop the tables
dropTables();

# recreate and populate
echo "creating tables\n";

$errors;
mysql_import_file2("gift.sql", $errors);
print_r($errors);

echo "populating tables\n";

$errors="";
mysql_import_file2("data.sql", $errors);
print_r($errors);

?>

