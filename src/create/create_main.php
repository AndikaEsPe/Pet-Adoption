<?php
// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_PARSE);

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
//        echo htmlentities($e['message']);
        $success = False;
    }

    $r = oci_execute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For oci_execute errors pass the statementhandle
//        echo htmlentities($e['message']);
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
    $success = True;
    $statement = oci_parse($db_conn, $cmdstr);
    if (preg_match('/[;"\'^£$%&*}{#~?><>|+¬]/', $cmdstr) and !str_contains($cmdstr, "ADDRESS")) {
        echo "Special characters are not allowed!";
        $success = False;
        return;
    }
    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            if (preg_match('/[;"\'^£$%&*}{#~?><>,|+¬]/', $val) and !str_contains($cmdstr, "ADDRESS")) {
                echo "Special characters are not allowed!";
                $success = False;
                return;
            }
            oci_bind_by_name($statement, $bind, $val);
            unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }
        $r = oci_execute($statement, OCI_DEFAULT);
        if (!$r) {
            $e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
            if (str_contains($e['message'], "ORA-00001") && !str_contains($e['sqltext'], "ADDRESS")) {
                echo "<br>Cannot execute the request<br>";
                echo "An element with the ID already exists.";
            } else if (str_contains($e['message'], "ORA-01400") && !str_contains($e['message'], "ADDRESS")) {
                echo "<br>Cannot execute the request<br>";
                echo "Please make sure all fields are provided.";
            }
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

function handleCreateAdopter(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['adopterID'],
        ":bind2" => $_POST['adopterName'],
        ":bind3" => $_POST['adopterEmail'],
        ":bind4" => $_POST['adopterPhone'],
        ":bind5" => $_POST['adopterPostalCode'],
        ":bind6" => $_POST['adopterStreet']
    );

    $alltuples = array(
        $tuple
    );

    executeBoundSQL("insert into ADOPTERPERSONAL values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
    oci_commit($db_conn);
}

function handleCreateAdopterAddress(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['adopterPostalCode'],
        ":bind2" => $_POST['adopterCity']
    );

    $alltuples = array(
        $tuple
    );

    executeBoundSQL("insert into ADOPTERADDRESS values (:bind1, :bind2)", $alltuples);
    oci_commit($db_conn);
}

function handleCreateBranchAddress(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['branchPostalCode'],
        ":bind2" => $_POST['branchCity']
    );

    $alltuples = array(
        $tuple
    );

    executeBoundSQL("insert into BRANCHADDRESS values (:bind1, :bind2)", $alltuples);
    oci_commit($db_conn);
}

function handleCreateBranch(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['branchID'],
        ":bind2" => $_POST['branchName'],
        ":bind3" => $_POST['branchEmail'],
        ":bind4" => $_POST['branchPhone'],
        ":bind5" => $_POST['branchPostalCode'],
        ":bind6" => $_POST['branchStreet']
    );

    $alltuples = array(
        $tuple
    );

    executeBoundSQL("insert into BRANCHDETAILS values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
    oci_commit($db_conn);
}

function handleCreateEmployeeAddress(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['employeePostalCode'],
        ":bind2" => $_POST['employeeCity']
    );

    $alltuples = array(
        $tuple
    );

    executeBoundSQL("insert into EMPLOYEEADDRESS values (:bind1, :bind2)", $alltuples);
    oci_commit($db_conn);
}

function handleCreateEmployee(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['employeeID'],
        ":bind2" => $_POST['employeeName'],
        ":bind3" => $_POST['employeeRole'],
        ":bind4" => $_POST['employeeEmail'],
        ":bind5" => $_POST['employeePhone'],
        ":bind6" => $_POST['employeePostalCode'],
        ":bind7" => $_POST['employeeStreet'],
        ":bind8" => $_POST['employeeBranchID']
    );

    $alltuples = array(
        $tuple
    );

    executeBoundSQL("insert into EMPLOYEEPERSONAL values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8)", $alltuples);
    oci_commit($db_conn);
}

function handleCreatePetInfo(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['petID'],
        ":bind2" => $_POST['petName'],
        ":bind3" => $_POST['petWeight'],
        ":bind4" => $_POST['petColor'],
        ":bind5" => $_POST['petAge'],
        ":bind6" => $_POST['petBreed'],
        ":bind7" => $_POST['adopterID'],
        ":bind8" => $_POST['branchID'],
        ":bind9" => $_POST['fosterFamilyID']
    );

    $alltuples = array(
        $tuple
    );

    executeBoundSQL("insert into PETINFO values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9)", $alltuples);
    oci_commit($db_conn);
}

function handleCreateRequest(): void
{
    global $success;
    if (connectToDB()) {
        if (array_key_exists('createAdopterRequest', $_POST)) {
            handleCreateAdopterAddress();
            handleCreateAdopter();
            if ($success) {
                echo "NEW ADOPTER CREATED!";
            }
        } else if (array_key_exists('createBranchRequest', $_POST)) {
            handleCreateBranchAddress();
            handleCreateBranch();
            if ($success) {
                echo "NEW BRANCH CREATED!";
            }
        } else if (array_key_exists('createEmployeeRequest', $_POST)) {
            handleCreateEmployeeAddress();
            handleCreateEmployee();
            if ($success) {
                echo "NEW EMPLOYEE CREATED!";
            }
        } else if (array_key_exists('createPetRequest', $_POST)) {
            handleCreatePetInfo();
            if ($success) {
                echo "NEW PET CREATED!";
            }
        }
        disconnectFromDB();
    }
}

?>
