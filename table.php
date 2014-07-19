<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
        if( !$ret['id'] ) {
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

function getfilmdata ( $filmid ) {

	// Database related information


	// Try to open the database, die on error
	$connect = db_open( );
	// if ( $connect['error'] ) { die ( $connect['error'] ); }

	// At this point we should run the query
	$query = "SELECT * FROM film WHERE id_film='$filmid'";
	$data = mysql_query( $query );
	$ret['film_title'] = mysql_result( $data, 0, "title" );
	$ret['genre'] = mysql_result( $data, 0, "genre" );
	$ret['language'] = mysql_result( $data, 0, "language" );
	$ret['time'] = mysql_result( $data, 0, "time" );
	$ret['description'] = mysql_result( $data, 0, "description" );
	$ret['id_director'] = mysql_result( $data, 0, "id_director" );

    if ($ret['id_director']!=""){
	// Get director name
	$directorquery = "SELECT * FROM directors WHERE id_director='$ret[id_director]'";
	$directordata = mysql_query( $directorquery );
	
	$ret['director_name'] = mysql_result( $directordata, 0,'name' );
    } else {
    $ret['director_name'] =  "";  
    }

	// Finally close the db
	mysql_close( $connect['id'] );

	return $ret;
}
/********************************************************
*			Execution actually starts here
********************************************************/

$film_id = trim($_GET['film']);
if ( !is_numeric( $film_id ) ) { $film_id = 1; }
if ( $film_id < 1 ) { $film_id = 1; }

$film_info = getfilmdata( $film_id );
?>

<body>
<div id="wrapper">
<div id="header"></div>
<div id="container">

<div id="left">
	<div id="imgpos">
	  <img src="css/filmbox_logo.png" alt="filmbox logo" /><br>
          <img class="cover" src="css/<?=$film_id?>_cover.jpg" alt="film cover" />
	      </div>
</div>
	
	<div id="center">
	<h1><?php echo $film_info['film_title']; ?>	</h1>

	<p>
	<?php
		echo "<b>Director</b>: " . $film_info['director_name'] . "<br />";
		echo "<b>Genre</b>: " . $film_info['genre'] . "<br />";
		echo "<b>Language</b>: " . $film_info['language'] . "<br />";
		echo "<b>Running time</b>: " . $film_info['time'] . "<br />";
		echo "<b>Description</b>: " . $film_info['description'] . "<br />";

	?>
	</p>

		<a href="table.php?film=<?=$film_id-1?>"><img src="css/lon.png" alt="previous" border="0" /><!--Prev--></a>  
		<a href="index.php"><img src="css/home.png" alt="film list" border="0" /></a> <a href="table.php?film=<?=$film_id+1?>">
		<img src="css/ron.png" alt="next" border="0" /><!--Next--></a>
		
	</div>
	
<?php /*
	<div id="right">Some content</div>
 */ ?>
	<div class="clearer"> </div>
	</div>
	
	<div id="footer">
	<p>&copy; labox &middot; design: <a href="http://rps-italy.org/fs" style="color:#3b7da2" target="_blank">franklin soler</a></p>
	</div>
</div>

</body>
</html>
