<?php
include 'create_main.php'
?>

<html>
    <head>
        <title>CPSC 304 Project</title>
    </head>
    <body>
    <form method="POST" action="create_pet.php">
        <input type="hidden" id="createPetRequest" name="createPetRequest">
        Pet ID (Unique): <input type="text" name="petID"> <br /><br />
        Name: <input type="text" name="petName"> <br /><br />
        Weight: <input type="text" name="petWeight"> <br /><br />
        Color: <input type="text" name="petColor"> <br /><br />
        Age: <input type="text" name="petAge"> <br /><br />
        <!-- Species: <input type="text" name="petSpecies"> <br /><br /> -->
        Breed: <input type="text" name="petBreed"> <br /><br />
        Adopter ID: <input type="text" name="adopterID"> <br /><br />
        Branch ID: <input type="text" name="branchID"> <br /><br />
        Foster Family ID: <input type="text" name="fosterFamilyID"> <br /><br />

        <input type="submit" value="Insert" name="insertSubmit">
    </form>
    </body>
</html>

<?php
if (isset($_POST['insertSubmit'])) {
    handleCreateRequest();
    echo "Pet has been added!";
}
?>