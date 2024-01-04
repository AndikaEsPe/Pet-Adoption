<!--UPDATE PAGE-->

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

$success = true;	// keep traek of errors so page redirects only if there are no errors

$pattern = '/^[a-zA-Z0-9_]+$/';

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP
?>

<html>

<head>
	<title>UPDATE page</title>
	<style>
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
	<a href="../../index.html" class="mainPage">&laquo; Main Dashboard</a>
	<br /><br />
	<h2>Update Pet Adopter Info</h2>
	<p>Update if a pet is adopted by an adopter</p>
	<form method="POST" action="update.php">
		<input type="hidden" id="updatePetRequest" name="updatePetRequest">
		Adopter ID: <input type="text" name="adopterId"> <br /><br />
		Pet ID: <input type="text" name="petId"> <br /><br />

		<input type="submit" value="Update" name="updateSubmit"></p>
	</form>
    <!-- TO DO : combine projection of Adopter and PetInfo for user-friendly purpose -->

	<hr />
    
    <h2>Update Adopter Info</h2>
	<p>Update personal info of an adopter</p>
	<form method="POST" action="update.php">
        <input type="hidden" id="updateAdopterRequest" name="updateAdopterRequest">
        Adopter ID: <input type="text" name="adopterId"> <br /><br />

        <input type="checkbox" id="adopterName" name="adopterNameOpted" value="Name">
        <label for="adopterName">Adopter Name: </label> <input type="text" name="adopterName"> <br />

        <input type="checkbox" id="adopterEmail" name="adopterEmailOpted" value="Email">
        <label for="adopterEmail"> Email: </label>
        <input type="text" name="adopterEmail"> <br />

        <input type="checkbox" id="adopterPhone" name="adopterPhoneOpted" value="Phone">
        <label for="adopterPhone"> Phone Number: </label>
        <input type="text" name="adopterPhone"> <br />

        <input type="checkbox" id="adopterStreet" name="adopterStreetOpted" value="Street">
        <label for="adopterStreet"> Street Adress: </label>
        <input type="text" name="adopterStreet"> <br /><br />

        <input type="checkbox" id="adopterPostalCode" name="adopterPostalCodeOpted" value="PostalCode">
        <label for="adopterPostalCode"> Postal Code: </label>
        <input type="text" name="adopterPostalCode"> <br />

        <input type="checkbox" id="adopterCity" name="adopterCityOpted" value="city">
        <label for="adopterCity"> City: </label>
        <input type="text" name="adopterCity"> <br /> <br />

		<input type="submit" value="Update" name="updateSubmit"></p>
	</form>
	
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

	function disconnectFromDB()
	{
		global $db_conn;

		debugAlertMessage("Disconnect from Database");
		oci_close($db_conn);
	}

	function handleUpdatePetRequest()
	{
		global $db_conn, $success, $pattern;

		$adopter_id = $_POST['adopterId'];
		$pet_id = $_POST['petId'];

		if (empty($adopter_id) || empty($pet_id) || !preg_match($pattern, $adopter_id) || !preg_match($pattern, $pet_id)) {
			echo "<br/><b>Input invalid!</b>";
			return;
		} 
        // if a pet is adopted, update the adopterID, and set fosterFamilyID to NULL (in case fostered)
		executePlainSQL("UPDATE petinfo SET adopterId='" . $adopter_id . "', fosterFamilyID=NULL WHERE petid='" . $pet_id . "'");
		oci_commit($db_conn);
        // $success=false; 
		echo "<h3>Success! ".$pet_id." is adopted by " .$adopter_id.".</h3>";
	}

    function handleUpdateAdopterRequest(): void
    {
        global $db_conn, $pattern;
		$optionPattern = '/^[a-zA-Z0-9_@\-=.,\'\s]+$/';

        $adopter_id = $_POST['adopterId'];

		if (empty($adopter_id) || !preg_match($pattern, $adopter_id)) {
			echo "<br/><b>Adopter ID Input invalid!</b>";
			return;
		} 

        $options = "";

        if(isset($_POST['adopterNameOpted'])) {
            if (strlen($options) != 0) {
                $options .= ", ";
            }
            $options .= "name='" . $_POST['adopterName'] . "'";
        }
        if(isset($_POST['adopterEmailOpted'])) {
            if (strlen($options) != 0) {
                $options .= ", ";
            }
            $options .= "email='" . $_POST['adopterEmail'] . "'";
        }
        if(isset($_POST['adopterPhoneOpted'])) {
            if (strlen($options) != 0) {
                $options .= ", ";
            }
            $options .= "phone='" . $_POST['adopterPhone'] . "'";
        }
        
        if(isset($_POST['adopterStreetOpted'])) {
            if (strlen($options) != 0) {
                $options .= ", ";
            }
            $options .= "street='" . $_POST['adopterStreet'] . "'";
        }
       
        if(isset($_POST['adopterPostalCodeOpted']) and isset($_POST['adopterCityOpted'])) {
            if (strlen($options) != 0) {
                $options .= ", ";
            }
			$postalCode = $_POST['adopterPostalCode'];
			$city = $_POST['adopterCity'];

			$addrPattern = '/^[a-zA-Z0-9\s]+$/';

			if (empty($postalCode) || empty($city) || !preg_match($addrPattern, $postalCode) || !preg_match($addrPattern, $city)) {
				echo "<br/><b>Address Input invalid!</b>";
				return;
			} 

            $options .= "postalcode='" . $postalCode . "'";

            $tuple = array(
                ":bind1" => $postalCode,
                ":bind2" => $city
            );
    
            $alltuples = array(
                $tuple
            );
    
            executeBoundSQL("insert into ADOPTERADDRESS values (:bind1, :bind2)", $alltuples);
            oci_commit($db_conn);
			
        } else if (isset($_POST['adopterPostalCodeOpted']) or isset($_POST['adopterCityOpted'])) {
			echo "<br/><b>Both Postal Code and City are REQUIRED!</b>";
			return;
		}

		if (empty($options) || !preg_match($optionPattern, $options)) {
			echo "<br/><b>Input Format invalid!</b>";
			return;
		} 

        $cmdstr = sprintf("UPDATE adopterpersonal SET " . $options . " WHERE adopterid='" . $adopter_id . "'");
        // echo $cmdstr;
        $result = executePlainSQL($cmdstr);
        oci_commit($db_conn);
		echo "<h3>Update Success! </h3>";
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
			} elseif (array_key_exists('displayTuples', $_GET)) {
				handleDisplayRequest();
			}

			disconnectFromDB();
		}
	}

	if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
		handlePOSTRequest();
	} else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTuplesRequest'])) {
		handleGETRequest();
	}

	// End PHP parsing and send the rest of the HTML content
	?>
</body>

</html>
