<?php
include_once('include/header.php');
// Assuming 'include/header.php' sets up necessary configurations or includes
include('../connection.php');
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['productName']) || empty($_POST['productSKU']) || empty($_POST['salePrice']) || empty($_POST['purchasePrice']) || empty($_POST['categoryId'])) {
        $rmsg = "<div class='alert alert-danger mt-3'>Fill All Feilds...</div>";
    } else {
        // Validate and sanitize inputs (you should implement more robust validation)
        $productName = htmlspecialchars($_POST['productName']);
        $productSKU = htmlspecialchars($_POST['productSKU']);
        $salePrice = floatval($_POST['salePrice']); // Assuming sale price is numeric
        $purchasePrice = floatval($_POST['purchasePrice']); // Assuming purchase price is numeric
        $categoryId = intval($_POST['categoryId']); // Assuming categoryId is numeric
        $status = $_POST['status'];

        // File upload handling (example; adjust as per your needs)
        $productImage = $_FILES['productImage']['name'];
        $tempimage = $_FILES['productImage']['tmp_name'];
        $targetDir = "../images/" . $productImage;
        // Check if file has been uploaded
        move_uploaded_file($tempimage, $targetDir);
        $sql = "INSERT INTO `addproduct`(`productname`, `product_sku`, `product_price`, `product_sale_price`, `category`, `image`, `status`) VALUES  ('$productName', '$productSKU', '$salePrice', '$purchasePrice', '$categoryId', '$targetDir','$status')";

        $res = $con->query($sql);
        if ($res) {
            $rmsg = "<div class='alert alert-success mt-3'>Product Added Successfully...</div>";
            // echo "<script> location.href = 'dash.php?addproduct'</script>";
            // } else {
            //     $rmsg = "<div class='alert alert-warning mt-3'>Unable to Add...</div>";
        }
    }
}

?>

<div class="col-sm-6" style="margin: 5px;">
    <h2 class="text-center mt-2">Add Product</h2>
    <form action="" method="post" class="m-2 border border-2 p-4 bg-light" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group col-md-6 mt-2">
                <label for="name"><b>Product Name</b></label>
                <input type="text" class="form-control" name="productName" id="productName" placeholder="Enter the Product Name">
            </div>
            <div class="form-group col-md-6 mt-2">
                <label for="salePrice"><b>Product Sku</b></label>
                <input type="text" class="form-control" name="productSKU" id="productSKU" placeholder="Enter the Product SKU">
            </div>

        </div>
        <div class="row mt-3">
            <div class="form-group col-md-4 mt-2">
                <label for="salePrice"><b>Product Sale Price</b></label>
                <input type="text" class="form-control" name="salePrice" id="salePrice" placeholder="Enter the Sale Price">
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="purchasePrice"><b>Product Purchase Price</b></label>
                <input type="text" class="form-control" name="purchasePrice" id="purchasePrice" placeholder="Enter the Purchase Price">
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="vat"><b>Status</b></label>
                <select class="form-select" name="status" aria-label="Default select example">
                    <option selected>Select Status</option>
                    <option value="inactive">Pending</option>
                    <option value="active">Approved</option>
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="form-group col-md-8 mt-2">
                <label for="category" class="form-label"><b>Category</b></label>
                <select class="form-select" name="categoryId" aria-label="Category Select">
                    <?php
                    // Example of safe SQL query using prepared statements
                    $sql = "SELECT id, categoryname, level_1, level_2, level_3, level_4, level_5, commission_per FROM category";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['commission_per'] . '">' . $row['categoryname'] . ' / ' . $row['level_1'] . ' / ' . $row['level_2'] . ' / ' . $row['level_3'] . ' / ' . $row['level_4'] . ' / ' . $row['level_5'] . '</option>';
                    }
                    ?>
                </select>
            </div>

        </div>
        <div class="row">
            <div class="form-group col-md-8 mt-2">
                <label for="image"><b>Image</b></label>
                <input type="file" class="form-control" name="productImage" id="productImage">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12 mt-4 float-end">
                <button class="btn btn-info float-end text-bold" type="submit" name="submit">Add Product</button>
            </div>
        </div>
        <?php if (isset($rmsg)) {
            echo $rmsg;
        } ?>
    </form>
</div>