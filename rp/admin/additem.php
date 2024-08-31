<?php
include("../connection.php");
include_once('include/header.php');
// define('PAGE', 'addsale.php');
if(isset($_POST['submit'])){
    if(empty($_POST['item'])){
        $lmsg = "<div class='alert alert-danger mt-3'>Unable to Add Item!!!</div>";
    }
    else{
       $item = $_POST['item'];
       $sql = "INSERT INTO `item`(`item_name`) VALUES ('$item')";
       $res = $con->query( $sql);
       if($res){
        echo "<script>location.href='dash.php?addsale';</script>";
       }
    }
}

?>


<body>

    <div class="container-fluid">
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-md-8 p-4">

                    <form action="" method="post" class="border border-1 p-4 bg-light">
                        <h2>Add Item</h2>
                        <div class="mb-3">
                            <label for="item" class="form-label">Item Name</label>
                            <input type="text" name="item" class="form-control" id="item">
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>