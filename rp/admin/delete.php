<?php
include("../connection.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo $id;
    $sql = "DELETE FROM `calculator` WHERE `id` = {$id}";

    if ($con->query($sql) === true) {
        // Redirect to a confirmation page or refresh the current page
        header("Location: dash.php?calculator");
        exit();
    } else {
        echo "Unable to Delete: " . $con->error;
    }
} else {
    echo "ID parameter not provided.";
}

?>
