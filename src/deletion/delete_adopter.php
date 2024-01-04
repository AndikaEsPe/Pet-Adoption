<?php
include 'delete_main.php'
?>

<html>
    <head>
        <title>CPSC 304 Project</title>
    </head>
    <body>
    <form action="./deletion_UI.html">
        <input type="submit" value="<< Previous" />
    </form>
    <form action="../../index.html">
        <input type="submit" value="Go Home!" />
    </form>
    <form method="POST" action="delete_adopter.php">
        <input type="hidden" id="deleteAdopterRequest" name="deleteAdopterRequest">
        AdopterID (Unique): <input type="text" name="adopterID"> <br /><br />

        <input type="submit" value="Delete" name="deleteSubmit">
    </form>
    </body>
</html>

<?php
if (isset($_POST['deleteSubmit'])) {
    handleDeleteRequest();
    // echo "Adopter has been deleted!";
}
?>
