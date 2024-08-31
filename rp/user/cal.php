<?php
include('./include/header.php');
include('../connection.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['sell']) || empty($_POST['disc']) || empty($_POST['cate']) || empty($_POST['ship']) || empty($_POST['vat'])) {
        $rmsg = "<div class='alert alert-warning mt-3'>Fill All Fields</div>";
    } else {
        // Sanitize and extract form inputs
        $sell = $_POST['sell'];
        $disc = $_POST['disc'];
        $cate = $_POST['cate'];
        $shipping = $_POST['ship'];
        $checkbox = isset($_POST['checkbox']) ? $_POST['checkbox'] : '';
        $vat = $_POST['vat'];

        // Insert data into database (assuming calculator table structure and connection $con)
        $sql = "INSERT INTO `calculator`(`sell`, `disc`, `category`, `shipping`, `free_shipping`, `vat`) 
                VALUES ('$sell', '$disc', '$cate', '$shipping', '$checkbox', '$vat')";
        $res = $con->query($sql);

        if ($res) {
            $rmsg = "<div class='alert alert-success mt-3'>Request Added Successfully...</div>";
            // Redirect to view page after successful submission
            echo "<script> location.href = 'dash.php?calculator'</script>";
            exit; // Ensure script stops execution after redirection
        } else {
            $rmsg = "<div class='alert alert-warning mt-3'>Unable to Add...</div>";
        }
    }
}

?>

<div class="col-sm-9" style="margin: 5px;">
    <h2 class="text-center mt-2">Calculator</h2>
    <form action="" method="post" class="m-2 border border-2 p-4 bg-light">
        <div class="row">
            <div class="form-group col-md-4 mt-2">
                <label for="sell"><b>Selling</b></label>
                <input type="number" class="form-control" name="sell" id="sell" placeholder="Enter the Selling" value="">
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="disc"><b>Discount</b></label>
                <input type="number" class="form-control" name="disc" id="disc" placeholder="Enter the discount">
            </div>
            <div class="form-group col-md-4">
                <label for="cate" class="form-label"><b>Category</b></label>
                <select class="form-select" name="cate" aria-label="Default select example">
                    <?php
                    $sql = "SELECT id, categoryname, commission_per FROM category";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['commission_per'] . "'>" . $row['categoryname'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <a href="addcategory.php" class="link link-primary float-end">Add Category</a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="form-group col-md-4">
                <label for="ship"><b>Shipping</b></label>
                <input type="number" class="form-control" name="ship" id="ship" placeholder="PKR 119">
            </div>
            <div class="form-check col-md-4 mt-3">
                <input class="form-check-input" name="checkbox" type="checkbox" value="Free Shipping" id="defaultCheck1">
                <label class="form-check-label" for="defaultCheck1">Free Shipping</label>
            </div>
            <div class="form-group col-md-4">
                <label for="vat"><b>Province</b></label>
                <input type="text" class="form-control" name="vat" id="vat" placeholder="">
            </div>
        </div>
        <button type="reset" class="btn btn-danger mt-5" a href="delete?=<?php echo $row['id']; ?>" name="delete">Clear</button>
        <button type="submit" class="btn btn-info mt-5 float-end" name="submit">Submit</button>
        <?php if (isset($rmsg)) echo $rmsg; ?>
    </form>

    <?php
    // Display calculated results

    $sql = "SELECT calculator.id, calculator.sell, calculator.disc, calculator.category, calculator.shipping, calculator.free_shipping, calculator.vat 
            FROM calculator";
    $res = $con->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {

            $sell = $row['sell'];
            $disc = $row['disc'];
            $category = $row['category'];
            $shipping = $row['shipping'];
            $vat = $row['vat'];

            // Calculate total commission charged
            $ship = $row['free_shipping'] ? $disc + $row['shipping'] : $disc;
            $netSell = $sell - $ship;
            $commissionWithoutVAT = ($category) * $netSell / 100;
            $vatOnCommission = ($commissionWithoutVAT) * $vat / 100;
            $totalCommission = round($commissionWithoutVAT + $vatOnCommission);


            // calculate payment handling
            $payment_handling_without_vat = ($netSell) * 1.75 / 100;
            $payment_handling_vat = $payment_handling_without_vat * 0.16;
            $total_TH = round($payment_handling_without_vat + $payment_handling_vat);


            // shipping
            $ship = $row['free_shipping'] ? $disc + $row['shipping'] : $disc;
            $shipping_payment = $row['free_shipping'] ? "paid by seller" : "paid by customer";
            $vat_on_ship = round(($ship) * $vat/100);


            // total deduction
            $total_deduction = round($totalCommission + $total_TH + $vat_on_ship);

            // final deduction
            $final_payout = round($netSell - $total_deduction);

    ?>

            <div class="row">
                <div class="col-sm-8 mt-3">
                    <!-- <h2 class="text-center">Detail</h2> -->
                    <table class="table table-bordered border-1">
                        <thead>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total Commission Charged</th>
                                <td><?php echo number_format($totalCommission, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total Payment Handling Charged</th>
                                <td><?php echo number_format($total_TH, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">VAT on Shipping Charged</th>
                                <td><?php echo number_format($vat_on_ship, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total Deduction</th>
                                <td><?php echo number_format($total_deduction, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Final Payout</th>
                                <td><?php echo number_format($final_payout, 2); ?></td>
                            </tr>
                            <!-- Other rows for Total Payment Handling Charged, VAT on Shipping Charged, Total Deduction -->
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Action</th>
                                <td class="no-print"><a href="delete.php?id=<?php echo $row['id']; ?>"><i class="fas fa-trash"></i></a></td>
                            </tr>

                    <?php
                }
            } else {
                echo "new record";
            }
                    ?>
                        </thead>

                    </table>
                </div>
            </div>

</div>

<?php
include('./include/footer.php');
?>