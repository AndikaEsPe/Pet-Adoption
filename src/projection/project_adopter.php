<?php
include 'project_main.php'
?>

<html>
<head>
    <title>CPSC 304 Project</title>
</head>
<body>
<form method="POST" action="project_adopter.php">
    <input type="hidden" id="projectAdopterRequest" name="projectAdopterRequest">
    <input type="checkbox" id="adopterID" name="adopterID" value="adopterID">
    <label for="adopterID"> Adopter ID</label><br>
    <input type="checkbox" id="adopterName" name="adopterName" value="Name">
    <label for="adopterName"> Name</label><br>
    <input type="checkbox" id="adopterEmail" name="adopterEmail" value="Email">
    <label for="adopterEmail"> Email</label><br>
    <input type="checkbox" id="adopterPhone" name="adopterPhone" value="Phone">
    <label for="adopterPhone"> Phone Number</label><br>
    <input type="checkbox" id="adopterPostalCode" name="adopterPostalCode" value="PostalCode">
    <label for="adopterPostalCode"> Postal Code</label><br>
    <input type="checkbox" id="adopterStreet" name="adopterStreet" value="Street">
    <label for="adopterStreet"> Street Adress</label><br>

    <input type="submit" value="Submit" name="projectSubmit">
</form>
</body>
</html>

<?php
if (isset($_POST['projectSubmit'])) {
    handleProjectRequest();
}
?>
