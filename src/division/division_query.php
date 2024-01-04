<?php
include 'division_main.php'
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
Below is a button that will return all branches that are sponsored by all the registered pet retailers.
This is an example of relational division.
<form method="POST" action="division_query.php">
    <input type="hidden" id="divisionRequest" name="divisionRequest">
    <button type="submit" class="button" name="divisionSubmit">Cheese of Truth!</button
</form>
</body>
</html>

<?php
if (isset($_POST['divisionSubmit'])) {
    handleDivisionRequest();
}
?>
