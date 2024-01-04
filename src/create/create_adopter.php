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
    <form method="POST" action="create_adopter.php">
        <input type="hidden" id="createAdopterRequest" name="createAdopterRequest">
        AdopterID (Unique): <input type="text" name="adopterID"> <br /><br />
        Name: <input type="text" name="adopterName"> <br /><br />
        Email: <input type="text" name="adopterEmail"> <br /><br />
        Phone: <input type="text" name="adopterPhone"> <br /><br />
        Postal Code: <input type="text" name="adopterPostalCode"> <br /><br />
        Street: <input type="text" name="adopterStreet"> <br /><br />
        City: <input type="text" name="adopterCity"> <br /><br />

        <input type="submit" value="Insert" name="insertSubmit">
    </form>
    </body>
</html>

<?php
if (isset($_POST['insertSubmit'])) {
    handleCreateRequest();
}
?>
