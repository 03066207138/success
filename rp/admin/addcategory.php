<?php
include("../connection.php");
include_once('include/header.php');
// define('PAGE', 'addsale.php');
if(isset($_POST['submit'])){
    if(empty($_POST['category']) || empty($_POST['lev1']) || empty($_POST['lev2']) || empty($_POST['lev3']) || empty($_POST['lev4']) || empty($_POST['lev5']) || empty($_POST['cate_id'])){
        $lmsg = "<div class='alert alert-danger mt-3'>Unable to Add Category!!!</div>";
    }
    else{
       $cate = $_POST['category'];
       $lev1 = $_POST['lev1'];
       $lev2 = $_POST['lev2'];
       $lev3 = $_POST['lev3'];
       $lev4 = $_POST['lev4'];
       $lev5 = $_POST['lev5'];
       $cateid = $_POST['cate_id'];
       $sql = "INSERT INTO `category`(`categoryname`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `commission_per`) VALUES  ('$cate' , '$lev1', '$lev2', '$lev3', '$lev4', '$lev5', '$cateid')";
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
                        <h2>Add Category</h2>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category Name</label>
                            <input type="text" name="category" class="form-control" id="category">
                        </div>
                        <div class="mb-3">
                            <label for="lev1" class="form-label">Level 1</label>
                            <input type="text" name="lev1" class="form-control" id="lev1">
                        </div>
                        <div class="mb-3">
                            <label for="lev2" class="form-label">Level 2</label>
                            <input type="text" name="lev2" class="form-control" id="lev2">
                        </div>
                        <div class="mb-3">
                            <label for="lev3" class="form-label">Level 3</label>
                            <input type="text" name="lev3" class="form-control" id="lev3">
                        </div>
                        <div class="mb-3">
                            <label for="lev4" class="form-label">Level 4</label>
                            <input type="text" name="lev4" class="form-control" id="lev4">
                        </div>
                        <div class="mb-3">
                            <label for="lev5" class="form-label">Level 5</label>
                            <input type="text" name="lev5" class="form-control" id="lev5">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category Id</label>
                            <input type="text" name="cate_id" class="form-control" id="cate_id">
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>