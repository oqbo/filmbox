<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>The "FilmBox" Project</title>
<link href="css/filmbox.css" rel="stylesheet" type="text/css" />
<link href="css/css.css" rel="stylesheet" type="text/css" />
<link href="css/oqbo.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Cardo' rel='stylesheet' type='text/css'>
</head>

<?php
// Connects to $dbinfo['host']
// Uses $dbinfo['password'] and $dbinfo['username']
// Selects $dbinfo['dbname']

// Returns an array:
// ['error']: Null if everything went fine, othervise error string
// ['id']: The connection number to be used in mysql statements
function db_open( ) {
$dbinfo['username']="username";
$dbinfo['password']="password";
$dbinfo['dbname']="dbname";
$dbinfo['host']="host";
        // Connecting
        $ret['id'] = mysql_connect( $dbinfo['host'], $dbinfo['username'], $dbinfo['password'] );
        if ( !$ret['id'] ) {
                $ret['error'] = "ERROR: Cannot connect to database!";
                return $ret;
        }

        // Selecting DB
        if ( !mysql_select_db( $dbinfo['dbname'], $ret['id'] ) ) {
                $ret['error'] = "ERROR: Cannot select database!";
                return $ret;
		}

        return $ret;
}

function filmtable ( ) {
$rec=14;
if (empty($_GET['end'])){
    $end = 0; // record da cui partire... cioè il primo se non è
    } else {
    $end=$_GET['end'];
    }
$limit = $rec; // record da visualizzare per pagina...

	// Database related information

	// Try to open the database, die on error
	$connect = db_open( );
//	if ( $connect['error'] ) { die ( $connect['error'] ); }

	// At this point we should run the query
	$query = "SELECT * FROM film LIMIT ".$end.",".$limit.";";
	   
    $data = mysql_query( $query );

	echo "<table style='width: 100%;' cellspacing='0'>
		<tr>
		<th>Name</th>
		<th>Genre</th>
		<!--<th>Director</th>-->
		</tr>\n";
	$td = "<td bgcolor='#cccccc'>";
	$phase = 1;
    $i=0;
	while ( $row = mysql_fetch_array( $data ) ) {
	   $i++;
		echo "<tr>\n";
		echo $td . "<a href=\"table.php?film=" . $row['id_film'] . "\">" . $row['title'] . "</a></td>\n";
		echo $td . $row['genre'] . "</td>\n";
//		echo $td . $row['id_director'] . "</td>\n";
		echo "</tr>\n";
		if ( $phase == 1 ) {
			$phase = 0; $td = "<td>";
		} else {
			$phase = 1; $td = "<td bgcolor='#cccccc'>";
	   	}
	}

//		if ($end>1){
//		$prev = intval($end-$rec);
//		} else {
//		$prev = 'F';
//		}
//		
//		if ($i >=$rec){
//		$next = intval($end+$rec);
//		} else {
//		$next = 'F';
//		}
		echo "</table>";
		echo "<br />";

if ($end>0){
$prev = intval($end-$rec);
echo "<a href=\"".$_SERVER['PHP_SELF']."?end=".$prev."\">Prev </a>";

}else{
$prev = 'F';
echo "No Prev ";
}

if ($i >=$rec){
$next = intval($end+$rec);
echo "<a href=\"".$_SERVER['PHP_SELF']."?end=".$next."\">Next</a>";
}else{
$next = 'F';
echo " No Next";
}


// echo '{"prev":"'.$prev.'","next":"'.$next.'","curr":"'.$rec.'"}';
	// Finally close the db
	mysql_close ( $connect['id'] );
}


// EXECUTION
?>

<body>
<div id="wrapper">
<div id="header"></div>
<div id="container">

	<div id="center" style="width: 653px; margin: 0 12px; padding: 0;">

	<h1>List of films</h1><br />

	<?php
	//<p>
	filmtable();
	//</p>
	?>

	</div>
	
<?php /*
	<div id="right">Some content</div>
 */ ?>
	<div class="clearer"></div>
	
	<div id="footer">
	<p>&copy; labox &middot; design: <a href="http://rps-italy.org/fs" style="color:#3b7da2" target="_blank">franklin soler</a></p>
	</div>
</div>

</body>
</html>
