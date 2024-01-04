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
    <form method="POST" action="select_adopter.php">
        <input type="hidden" id="selectAdopterRequest" name="selectAdopterRequest">
        AdopterID (Unique): <input type="text" name="adopterID"> 
        <select name="selectAdopterID">
            <option value="AND">And</option>
            <option value="OR">Or</option>
        </select><br /><br />
        Name: <input type="text" name="adopterName"> 
        <select name="selectAdopterName">
            <option value="AND">And</option>
            <option value="OR">Or</option>
        </select><br /><br />
        Email: <input type="text" name="adopterEmail"> 
        <select name="selectAdopterEmail">
            <option value="AND">And</option>
            <option value="OR">Or</option>
        </select><br /><br />
        Phone: <input type="text" name="adopterPhone"> 
        <select name="selectAdopterPhone">
            <option value="AND">And</option>
            <option value="OR">Or</option>
        </select><br /><br />
        Postal Code: <input type="text" name="adopterPostalCode"> 
        <select name="selectAdopterPostalCode">
            <option value="AND">And</option>
            <option value="OR">Or</option>
        </select><br /><br />
        Street: <input type="text" name="adopterStreet"> 
        <select name="selectAdopterStreet">
            <option value="AND">And</option>
            <option value="OR">Or</option>
        </select><br /><br />
        <input type="submit" value="Search" name="selectSubmit">
    </form>
    <div>
        <form method="POST" action="select_adopter.php">
            <input type="hidden" id="groupByRequest" name="groupByRequest">
            <input type="submit" value="View number of pets" name="selectSubmit">
        </form>
    </div>
    </body>
</html>

<?php
if (isset($_POST['selectSubmit'])) {
    handleSelectRequest();
}
?>
