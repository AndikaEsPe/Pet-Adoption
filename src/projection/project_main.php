<?php
// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "";		// change "cwl" to your own CWL
$config["dbpassword"] = "";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors
$table_name="";
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
//            print_r($tuple);
            echo $val;
            oci_bind_by_name($statement, $bind, $val);
            unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }
        $r = oci_execute($statement, OCI_DEFAULT);
        printResult($statement);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        }
        return $statement;
    }
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


function handleProject(): void
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $table_name = $_POST['table'];
    $cmdstr = sprintf("SELECT column_name
FROM USER_TAB_COLUMNS
WHERE table_name = '%s'", $table_name);
    $columns = executePlainSQL($cmdstr);
    getColumns($columns);
}

function getColumns($columns)
{
    global $db_conn;
    echo "<br> <br>";
    echo "<form method='POST' action='project_main.php'>";
    if (connectToDB()) {
        while ($row = OCI_Fetch_Array($columns, OCI_ASSOC)) {
            $column_name = $row["COLUMN_NAME"];
            echo "<input type='checkbox' id='$column_name' name='$column_name' value='$column_name'>
    <label for='$column_name'>$column_name</label><br>";
        }
        disconnectFromDB();
    }
    echo "<input type='submit' value='Submit' name='columnSubmit'>";
    echo "</form>";
}

function getProjection()
{
    if (connectToDB()) {
        global $db_conn;
        $table_name = $_POST["table"];
        //Getting the values from user and insert data into the table
        $projection = "";
        if (count($_POST) == 2) {
            echo "No column selected. Please make sure you've selected columns.";
        } else {
            $projection = current(array_slice($_POST, 1, 1, true));
            foreach (array_slice($_POST, 2, -1) as $item) {
                if ($item == "DATE") {
                    $projection .= ", \"" . $item . "\"";
                } else {
                    $projection .= ", " . $item;
                }
            }
            $cmdstr = sprintf("SELECT %s FROM %s", $projection, $table_name);
            $result = executePlainSQL($cmdstr);
            printResult($result, $projection);
            oci_commit($db_conn);
        }
    }
}


if (isset($_POST['projectSubmit'])) {
    getProjection();
}
if (isset($_POST['columnSubmit'])) {
    getProjection();
}
function handleProjectRequest(): void
{
    if (connectToDB()) {
        handleProject();
        disconnectFromDB();
    }
}


?>