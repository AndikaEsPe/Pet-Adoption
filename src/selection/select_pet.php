<?php
include 'select_main.php'
?>

<html>
    <head>
        <title>CPSC 304 Project</title>
        <style>
        /* table,tr,th,td {
           border:1px solid black;
           border-collapse: collapse;
           text-align: center;
           padding:5px;
        } */
        </style>
    </head>
    <body>
    <!-- <button onclick="history.back()">Previous page</button> -->
    <form action="./selection_UI.html">
        <input type="submit" value="<< Previous" />
    </form>
    <form action="../../index.html">
        <input type="submit" value="Go Home!" />
    </form>
    <form method="POST" action="select_pet.php">
        <input type="hidden" id="selectPetRequest" name="selectPetRequest">
        <input type="submit" value="View all pets" name="selectPetSubmit">
    </form>
    </body>
</html>

<?php
if (isset($_POST['selectPetSubmit'])) {
    // echo "Here is your result:";
    handleSelectRequest();
}
?>
