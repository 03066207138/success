<?php

include_once('include/header.php');
// define('PAGE', 'addsale.php');
include('../connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (($_REQUEST['name1'] == '') || ($_REQUEST['desc'] == '') || ($_REQUEST['cate'] == '') || ($_REQUEST['email'] == '') || ($_REQUEST['pass'] == '')) {
        } else {

            $name = $_REQUEST['name1'];
            $desc = $_REQUEST['desc'];
            $cate = $_REQUEST['cate'];
            $email = $_REQUEST['email'];
            $pass = $_REQUEST['pass'];

            // echo "$id";

            $sql = "INSERT INTO `store`(`storename`, `description`, `category`, `email`, `password`) VALUES ('$name', '$desc', '$cate', '$email', '$pass')";
            // $sql = "INSERT INTO `add_sale`(`name`, `phone_no`, `city`, `order_type`, `order_no`, `item_id`, `price`, `tracking_id`, `order_date`, `discount`, `shipping`, `free_shipping`, `cate_id`) VALUES('$name','$phone','$city','$radio','$orderno','$id','$price','$id','$date','$disc',
            //      '$shipping','$checkbox','$id1')";
            $res = $con->query($sql);
            if ($res) {
                $rmsg = "<div class='alert alert-success mt-3'>Request Added Successfully...</div>";
                echo "<script> location.href = '../admin/dash.php?addsale'</script>";
                // } else {
                //     $rmsg = "<div class='alert alert-warning mt-3'>Unable to Add...</div>";
            }
        }
    }


?>
<div class="row justify-content-center">
    <div class="col-sm-6" style="margin: 5px;">
        <h2 class="text-center mt-2">Add Store</h2>
        <form action="" method="post" class="m-2 border border-2 p-4 bg-light">
            <div class="row">
                <div class="form-group col-md-4 mt-2">
                    <label for="name"><b>Store Name</b></label>
                    <input type="text" class="form-control" name="name1" id="name1" placeholder="Enter the Name">
                </div>
                <div class="form-group col-md-4 mt-2">
                    <label for="desc"><b>Description</b></label>
                    <input type="text" class="form-control" name="desc" id="desc" placeholder="Enter the Description">
                </div>

                <div class="form-group col-md-4 mt-2">
                    <label for="cate"><b>Category</b></label>
                    <input type="text" class="form-control" name="cate" id="cate" placeholder="Enter the Category">
                </div>

            </div>
            <div class="row mt-2">
                <div class="form-group col-md-4 mt-2">
                    <label for="number"><b>Email</b></label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter the Email">
                </div>

                <div class="form-group col-md-4 mt-2">
                    <label for="price"><b>Password</b></label>
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Enter the Password">
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <button class="btn btn-info mt-5 float-end text-bold" type="submit" name="submit">Submit</button>
                </div>
            </div>
            <?php if (isset($rmsg)) {
                echo $rmsg;
            } ?>
        </form>
    </div>