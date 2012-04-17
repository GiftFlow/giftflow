<?php

define('BASEPATH', str_replace("\\", "/", realpath(dirname(__FILE__)))); 

include_once('../../application/config/database.php');
$config = $db['default'];

// TODO: add option to delete photos


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

		$result = mysql_query ($query)
		or die ('Query failed: ' . $query .' MySQL error: ' . mysql_error());
	}

}


function dropTables() {

	$sql = "SHOW TABLES";
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);

//	print "dropping tables ($num_rows)\n";

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
	echo 'Could not connect to database';
	exit;
}

mysql_select_db($config['database']);

# drop the foriegn keys

$result = mysql_query("SELECT DISTINCT table_name, constraint_name"
  . " FROM information_schema.key_column_usage"
  . " WHERE constraint_schema = '".$config['database']."'"
  . " AND referenced_table_name IS NOT NULL");
  
echo "dropping foreign keys...\n";

while($row = mysql_fetch_assoc($result)) {

#  echo "ALTER TABLE `$row[table_name]`". " DROP FOREIGN KEY `$row[constraint_name]`";
  
  mysql_query("ALTER TABLE `$row[table_name]`"
    . " DROP FOREIGN KEY `$row[constraint_name]`")
    or die(mysql_error());
}

echo "dropping tables...\n";

# drop the tables
dropTables();

# recreate and populate

echo "creating tables...\n";
mysql_import_file2("gift.sql");

echo "populating default values...\n";
mysql_import_file2("defaults.sql");

if (in_array("--fakedata", $argv)) {
  echo "populating tables with fake test data...\n";
  mysql_import_file2("Summoner/Balrog.sql");
}
?>
