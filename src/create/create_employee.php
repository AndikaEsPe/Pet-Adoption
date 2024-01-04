<?php
include 'create_main.php'
?>

<html>
<head>
    <title>CPSC 304 Project</title>
</head>
<body>
<button onclick="history.back()">Previous page</button>
<form action="../../index.html">
    <input type="submit" value="Go Home!" />
</form>
<form method="POST" action="create_employee.php">
    <input type="hidden" id="createEmployeeRequest" name="createEmployeeRequest">
    EmployeeID (Unique): <input type="text" name="employeeID"> <br /><br />
    Name: <input type="text" name="employeeName"> <br /><br />
    Role: <input type="text" name="employeeRole"> <br /><br />
    Email: <input type="text" name="employeeEmail"> <br /><br />
    Phone: <input type="text" name="employeePhone"> <br /><br />
    Postal Code: <input type="text" name="employeePostalCode"> <br /><br />
    Street: <input type="text" name="employeeStreet"> <br /><br />
    City: <input type="text" name="employeeCity"> <br /><br />
    BranchID: <input type="text" name="employeeBranchID"> <br /><br />

    <input type="submit" value="Insert" name="insertSubmit">
</form>


</body>
</html>

<?php
if (isset($_POST['insertSubmit'])) {
    handleCreateRequest();
}
?>
