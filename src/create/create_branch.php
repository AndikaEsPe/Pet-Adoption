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
<form method="POST" action="create_branch.php">
    <input type="hidden" id="createBranchRequest" name="createBranchRequest">
    BranchID (Unique): <input type="text" name="branchID"> <br /><br />
    Name: <input type="text" name="branchName"> <br /><br />
    Email: <input type="text" name="branchEmail"> <br /><br />
    Phone: <input type="text" name="branchPhone"> <br /><br />
    Postal Code: <input type="text" name="branchPostalCode"> <br /><br />
    Street: <input type="text" name="branchStreet"> <br /><br />
    City: <input type="text" name="branchCity"> <br /><br />

    <input type="submit" value="Insert" name="insertSubmit">
</form>


</body>
</html>

<?php
if (isset($_POST['insertSubmit'])) {
    handleCreateRequest();
}
?>
