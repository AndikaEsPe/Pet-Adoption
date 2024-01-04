<?php
// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "ora";		// change "cwl" to your own CWL
$config["dbpassword"] = "a";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())
function debugAlertMessage($message): void
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

function printResult($result): void
{ //prints results from a select statement
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Postal Code</th><th>Street</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
    }
    echo "</table>";
}

function handleSelectAdopter(): void
{
    global $db_conn;
    $query = " ";
    if (preg_match('/[";\'^£$%&*()}{#~?><>,|=+¬]/', $_POST['adopterID'])) {
        echo "<br/>Special characters are not allowed!";
        return;
    } else if(strlen($_POST['adopterID']) != 0) {
        $query .= (" " . $_POST['selectAdopterID'] . " adopterID='" . $_POST['adopterID'] . "' ");  
    }
    if (preg_match('/[";\'^£$%&*()}{#~?><>,|=+¬]/', $_POST['adopterName'])) {
        echo "<br/>Special characters are not allowed!";
        return;
    } else if(strlen($_POST['adopterName']) != 0) {
        $query .= (" " . $_POST['selectAdopterName'] . " name='" . $_POST['adopterName'] . "' ");
    }
    if (preg_match('/[";\'^£$%&*()}{#~?><>,|=+¬]/', $_POST['adopterEmail'])) {
        echo "<br/>Special characters are not allowed!";
        return;
    } else if(strlen($_POST['adopterEmail']) != 0) {
        $query .= " " . $_POST['selectAdopterEmail'] . " email='" . $_POST['adopterEmail'] . "' ";
    }
    if (preg_match('/[";\'^£$%&*()}{#~?><>,|=+¬]/', $_POST['adopterPhone'])) {
        echo "<br/>Special characters are not allowed!";
        return;
    } else if(strlen($_POST['adopterPhone']) != 0) {
        $query .= " " . $_POST['selectAdopterPhone'] . " phone='" . $_POST['adopterPhone'] . "' ";
    }
    if (preg_match('/[";\'^£$%&*()}{#~?><>,|=+¬]/', $_POST['adopterPostalCode'])) {
        echo "<br/>Special characters are not allowed!";
        return;
    } else if(strlen($_POST['adopterPostalCode']) != 0) {
        $query .= " " . $_POST['selectAdopterPostalCode'] . " postalCode='" . $_POST['adopterPostalCode'] . "' ";
    }
    if (preg_match('/[";\'^£$%&*()}{#~?><>,|=+¬]/', $_POST['adopterStreet'])) {
        echo "<br/>Special characters are not allowed!";
        return;
    } else if(strlen($_POST['adopterStreet']) != 0) {
        $query .= " " . $_POST['selectAdopterStreet'] . " street='" . $_POST['adopterStreet'] . "' ";
    }
    $result = executePlainSQL("SELECT * FROM adopterPersonal WHERE 1=1 " . $query);
    // echo $query . "<br>";
    // echo "SELECT * FROM adopterPersonal WHERE 1=1 " . $query;
    echo "Here is your result:";
    printResult($result);
    oci_commit($db_conn);
}

function printGroupBy($result): void
{ //prints results from a select statement
    echo "<table>";
    echo "<tr><th>Adopter ID</th><th>Number of pets</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
    }
    echo "</table>";
}

function handleGroupByAdopter(): void
{
    global $db_conn;
    $result = executePlainSQL("SELECT adopterID, count(adopterID) FROM PETINFO WHERE adopterID IS NOT NULL GROUP BY adopterID");
    printGroupBy($result);
    oci_commit($db_conn);
}

function printAllPet($result): void
{ //prints results from a select statement
    echo "<table>";
    echo "<tr><th>Pet ID</th><th>Name</th><th>Weight</th><th>Color</th><th>Age</th><th>Breed</th><th>Adopter ID</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
    }
    echo "</table>";
}

function handleSelectPet(): void
{
    global $db_conn;
    $result = executePlainSQL("SELECT petID, Name, weight, color, age, breed, adopterID FROM PETINFO");
    printAllPet($result);
    oci_commit($db_conn);
}

function handleSelectRequest(): void
{
    if (connectToDB()) {
        if (array_key_exists('selectAdopterRequest', $_POST)) {
            handleSelectAdopter();
        } 
        if (array_key_exists('groupByRequest', $_POST)) {
            handleGroupByAdopter();
        }
        if (array_key_exists('selectPetRequest', $_POST)) {
            handleSelectPet();
        } 
        disconnectFromDB();
    }
}
?>
