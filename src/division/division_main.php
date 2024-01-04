<?php
// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "ora_cwl";        // change "cwl" to your own CWL
$config["dbpassword"] = "a";    // change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;    // login credentials are used in connectToDB()

$success = true;    // keep track of errors so page redirects only if there are no errors

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

function connectToDB(): bool
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

function disconnectFromDB(): void
{
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    oci_close($db_conn);
}

function printResult($result, $projection): void
{ //prints results from a select statement
    $columns = "<th>" . str_replace(", ", "</th><th>", $projection) . "</th>";
    echo "<table>";
    echo sprintf("<tr>%s</tr>", $columns);
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
    }
    echo "</table>";
}

function handleDivision(): void
{
    global $db_conn;
    $cmdstr = "SELECT * FROM BRANCHDETAILS BD WHERE NOT EXISTS 
    (SELECT PETRETAILER.SPONSORID FROM PETRETAILER 
                                  WHERE NOT EXISTS (SELECT S.SPONSORID 
                                                    FROM SPONSORS S
                                                    WHERE S.SPONSORID = PETRETAILER.SPONSORID AND S.BRANCHID = BD.BRANCHID))";
    $result = executePlainSQL($cmdstr);
    $projection = "BranchID, Name, Phone, Email, PostalCode, Street";
    printResult($result, $projection);
    oci_commit($db_conn);
}

function handleDivisionRequest(): void
{
    if (connectToDB()) {
        if (array_key_exists('divisionRequest', $_POST)) {
            handleDivision();
        }
        disconnectFromDB();
    }
}

?>
