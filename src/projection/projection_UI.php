<html>
<head>
    <title>CPSC 304 Project</title>
</head>

<body>
<button onclick="history.back()">Previous page</button>
<form action="../../index.html">
    <input type="submit" value="Go Home!" />
</form>
<?php
include "project_main.php";
echo "<form method='POST' action='projection_UI.php'>";
echo "<label for='table'>Choose a table: </label>
    <select name='table' id='table'>";
    if (connectToDB()) {
        $all_tables = executePlainSQL("SELECT TABLE_NAME FROM USER_TABLES");
//        printResult($all_tables, "table_name");
        while ($row = OCI_Fetch_Array($all_tables, OCI_ASSOC)) {
            $table_name = $row["TABLE_NAME"];
            echo "<option value='$table_name'>$table_name</option>";
        }
        echo "</select>";
        disconnectFromDB();
    }
?>
<br>
<input type="submit" value="Select" name="Select">
<?php
if (isset($_POST['Select'])) {
    handleProjectRequest();
}
echo "</form>";
?>
</body>
</html>
