<!--SEARCH PAGE-->

<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Set some parameters

// Database access configuration
$config["dbuser"] = "ora_cwl";			// change "cwl" to your own CWL
$config["dbpassword"] = "a";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()
$success = false;	// keep traek of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())
// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP
?>

<html>

<head>
	<title>search page</title> 
	<style>
      table,tr,th,td {
         border:1px solid black;
         border-collapse: collapse;
         text-align: center;
         padding:5px;
      }
	  a {
		text-decoration: none;
		display: inline-block;
		padding: 5px 8px;
		}

	a:hover {
		background-color: #aaa;
		color: black;
		}

	.mainPage {
		background-color: lightblue;
		color: black;
		}
   </style>
</head>

<body>
	<a href="../selection/selection_UI.html" class="mainPage">&laquo; Previous</a>
	<br /><br />

    <h2>Find Pet Adoption/Foster Status Info</h2>
	<p>Choose an option to check on the pets that are adopted or in foster home</p>
    Pet Status:
    <form method="GET" action="search.php" >
        <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
        <input type="radio" name="petStatus" value="adopterpersonal" >Adopted
        <input type="radio" name="petStatus" value="fosterfamilydetails">In Foster
        <br/><br/>
        <input type="submit" name="joinSubmit"> 
	</form>

	<hr />
	
    <h2>Find the specific branch</h2>
	<p>Choose an option to check which branch has only young or adult pets pending adoption</p>
    age range:<br/><br/>
    <form method="GET" action="search.php" >
        <input type="hidden" id="havingQueryRequest" name="havingQueryRequest">
        <input type="radio" name="petAge" value="<=1">Only Young (kittens, puppies, etc.)
        <input type="radio" name="petAge" value=">1">Only Non-Senior
        <br/><br/>
        <input type="submit" name="havingSubmit"> 
	</form>

	<hr />

    <h2>Find a specific foster family</h2>
	<p>Choose an option to check which foster family has most or fewest pets</p>
    option:<br/><br/>
    <form method="GET" action="search.php" >
        <input type="hidden" id="nestedAggregationQueryRequest" name="nestedAggregationQueryRequest">
        <input type="radio" name="nestedChoice" value="min" >fewest
        <input type="radio" name="nestedChoice" value="max">most
        <br/><br/>
        <input type="submit" name="nestedSubmit"> 
	</form>

	<hr />


	<?php
	// The following code will be parsed as PHP

	function debugAlertMessage($message)
	{
		global $show_debug_alert_messages;

		if ($show_debug_alert_messages) {
			echo "<script type='text/javascript'>alert('" . $message . "');</script>";
		}
	}

	function executePlainSQL($cmdstr)
	{ //takes a plain (no bound variables) SQL command and executes it
		//echo "<br>running ".$cmdstr."<br>";
		global $db_conn, $success;

		$statement = oci_parse($db_conn, $cmdstr);
		//There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
			echo htmlentities($e['message']);
			$success = False;
		}

		$r = oci_execute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = oci_error($statement); // For oci_execute errors pass the statementhandle
			echo htmlentities($e['message']);
			$success = False;
		}
		return $statement;
	}

	function executeBoundSQL($cmdstr, $list)
	{
		/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

		global $db_conn, $success;
		$statement = oci_parse($db_conn, $cmdstr);

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn);
			echo htmlentities($e['message']);
			$success = False;
		}

		foreach ($list as $tuple) {
			foreach ($tuple as $bind => $val) {
				//echo $val;
				//echo "<br>".$bind."<br>";
				oci_bind_by_name($statement, $bind, $val);
				unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
			}

			$r = oci_execute($statement, OCI_DEFAULT);
			if (!$r) {
				echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
				$e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
				echo htmlentities($e['message']);
				echo "<br>";
				$success = False;
			}
		}
	}

	function printJoinResult($result)
	{ //prints results from a select statement
		echo "<h3><em>Retrieved data from table :</em></h3>";
		echo "<table>";
		echo "<tr><th>Pet ID</th><th>Pet Name</th>
                <th>Breed</th><th>Adopter/Foster ID</th><th>Adopter/Foster Name</th></tr>";

		while (($row = oci_fetch_row($result)) != false) {
			echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></tr>"; //or just use "echo $row[0]"
		}

		echo "</table>";
	}

    function printHavingResult($result)
	{ //prints results from a select statement
		echo "<h3><em>Retrieved data from table :</em></h3>";
		echo "<table>";
		echo "<tr><th>Branch ID</th><th>Available No.</th></tr>";

		while (($row = oci_fetch_row($result)) != false) {
			echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
		}
		echo "</table>";
	}

	function printNestedResult($result)
	{ //prints results from a select statement
		echo "<h3><em>Retrieved data from table :</em></h3>";
		echo "<table>";
		echo "<tr><th>Foster ID</th><th>Foster Name</th><th>Total Pets</th></tr>";

		while (($row = oci_fetch_row($result)) != false) {
			echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; 
		}

		echo "</table>";
	}

	function connectToDB()
	{
		global $db_conn;
		global $config;

		// Your username is ora_(CWL_ID) and the password is a(student number). For example,
		// ora_platypus is the username and a12345678 is the password.
		// $db_conn = oci_connect("ora_cwl", "a12345678", "dbhost.students.cs.ubc.ca:1522/stu");
		$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

		if ($db_conn) {
			debugAlertMessage("Database is Connected");
			return true;
		} else {
			debugAlertMessage("Cannot connect to Database");
			$e = OCI_Error(); // For oci_connect errors pass no handle
			echo htmlentities($e['message']);
			return false;
		}
	}
    
    function handlePetStatusJoinRequest() {
        global $db_conn;
		$petStatus = $_GET['petStatus'];
        $criteria = "";
        if ($petStatus == "adopterpersonal") {
            $criteria = "adopterid"; 
        } else {
            $criteria = "fosterfamilyid"; 
        }
		$joinQuery = "
				SELECT P.petid, P.name, P.breed, S.". $criteria .", S.name
				FROM petinfo P, " .$petStatus. " S
				WHERE P.". $criteria ." IS NOT NULL 
					AND P.". $criteria ." = S.". $criteria ."
					";
        // echo $joinQuery;
		$result = executePlainSQL($joinQuery);	
        // echo $result;
        printJoinResult($result);
    }

    function handleAvailablePetHavingRequest() {
        global $db_conn;
		$petAge = $_GET['petAge'];

        $measure = "";
        if ($petAge == "<=1") {
            $measure = "max"; 
        } else {
            $measure = "min"; 
        }

		$havingQuery = "
                SELECT branchid, count(*)	
				FROM petinfo
				WHERE adopterid IS NULL 
                GROUP BY branchid
				HAVING " .$measure. "(age)". $petAge ."
                ORDER BY branchid";
        // echo $havingQuery;
		$result = executePlainSQL($havingQuery);	
        // echo $result;
        printHavingResult($result);
    }

    function handleNestedRequest() {
        global $db_conn;
		$nestedOption = $_GET['nestedChoice'];

		$nestedQuery = "
			WITH FosterSummary AS (
			SELECT fosterFamilyID, COUNT(petID) AS totalPets
			FROM PetInfo
			WHERE fosterFamilyID IS NOT NULL
			GROUP BY fosterFamilyID
			)  
		
			SELECT S.fosterFamilyID, F.name, S.totalPets
			FROM FosterSummary S, fosterfamilydetails F
			WHERE S.fosterFamilyID = F.fosterFamilyID
			AND S.totalPets =  (SELECT ".$nestedOption ."(totalPets) FROM FosterSummary)
			";

        // echo $nestedQuery;
		$result = executePlainSQL($nestedQuery);	
        printNestedResult($result); 
    }

	function disconnectFromDB()
	{
		global $db_conn;

		debugAlertMessage("Disconnect from Database");
		oci_close($db_conn);
	}


	// HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handlePOSTRequest()
	{
        global $success;
		if (connectToDB()) {
			if (array_key_exists('updatePetRequest', $_POST)) {
				handleUpdatePetRequest();
			} else if (array_key_exists('updateAdopterRequest', $_POST)) {
				handleUpdateAdopterRequest();
			}
            if ($success==true) {
                echo "<script>alert('Succeed!')</script>";
            } 
			disconnectFromDB();
		}
	}

	// HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handleGETRequest()
	{
		if (connectToDB()) {
			if (array_key_exists('countTuples', $_GET)) {
				handleCountRequest();
			} else if (array_key_exists('displayTuples', $_GET)) {
				handleDisplayRequest();
			} 
            else if(array_key_exists('joinSubmit', $_GET)) {
				handlePetStatusJoinRequest();
			} 
            else if(array_key_exists('havingSubmit', $_GET)) {
				handleAvailablePetHavingRequest();
			} 
            else if(array_key_exists('nestedSubmit', $_GET)) {
				handleNestedRequest();
			} 
			disconnectFromDB();
		}
	}

	if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
		handlePOSTRequest();
	} else if (isset($_GET['nestedAggregationQueryRequest']) ||isset($_GET['havingQueryRequest']) || isset($_GET['joinQueryRequest']) || isset($_GET['countTupleRequest']) || isset($_GET['displayTuplesRequest'])) {
		handleGETRequest();
	}

	// End PHP parsing and send the rest of the HTML content
	?>
</body>

</html>
