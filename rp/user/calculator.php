<?php
include('./include/header.php');
include('../connection.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['sell']) || empty($_POST['disc']) || empty($_POST['cate']) || empty($_POST['ship']) || empty($_POST['vat'])) {
        $rmsg = "<div class='alert alert-warning mt-3'>Fill All Fields</div>";
    } else {
        // Sanitize and extract form inputs
        $cate = $_POST['cate'];
        $vat = $_POST['vat'];
        $cost = $_POST['cost'];
        $sell = $_POST['sell'];
        $disc = $_POST['disc'];
        $minor = $_POST['minor'];
        $shipping = $_POST['ship'];
        $checkbox = isset($_POST['checkbox']) ? $_POST['checkbox'] : '';

        // Insert data into database (assuming calculator table structure and connection $con)
        $sql = "INSERT INTO `calculator`(`sell`, `disc`, `category`, `shipping`, `free_shipping`, `vat`, `cost`, `expenses`) 
                VALUES ('$sell', '$disc', '$cate', '$shipping', '$checkbox', '$vat', '$cost', '$minor')";
        $res = $con->query($sql);

        if ($res) {
            $rmsg = "<div class='alert alert-success mt-3'>Request Added Successfully...</div>";
            echo "<script> location.href = 'dash.php?calculator'</script>";
            // Redirect to view page after successful submission
            $sql = "DELETE FROM calculator WHERE created_at < NOW() - INTERVAL 1 MINUTES";
            $res = $con->query($sql);
            exit; // Ensure script stops execution after redirection
        } else {
            $rmsg = "<div class='alert alert-warning mt-3'>Unable to Add...</div>";
        }
    }

}


?>
<div class="col-sm-9">
    <div class="row">
        <div class="col-sm-9">
            <h2 class="text-center mt-2">Calculator</h2>
            <form action="" method="post" class="m-2 border border-2 p-4 bg-secondary rounded-2">
                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="cate" class="form-label"><b>Category</b></label>
                        <select class="form-select " name="cate" aria-label="Default select example">
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
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="vat"><b>Province</b></label>
                        <select class="form-select" name="vat" aria-label="Default select example">
                            <option value="16">Punjab</option>
                            <option value="2">sindh</option>
                            <option value="3">KPK</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="cost"><b>Product Cost</b></label>
                        <input type="number" class="form-control " name="cost" id="cost" placeholder="Enter Product Cost">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="sell" class=""><b>Selling</b></label>
                        <input type="number" class="form-control " name="sell" id="sell" placeholder="Enter the Selling">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="disc"><b>Discount</b></label>
                        <input type="number" class="form-control " name="disc" id="disc" placeholder="Enter the discount">
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="minor"><b> Expenses</b></label>
                        <input type="text" class="form-control " name="minor" id="minor" placeholder="Enter Expenses">
                        <p class="text-center">(Print + Packing + Petrol)</p>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="ship"><b>Shipping</b></label>
                        <input type="number" class="form-control " name="ship" id="ship" placeholder="PKR 119">
                    </div>
                    <div class="form-check col-md-4 mt-4">
                        <input class="form-check-input" name="checkbox" type="checkbox" value="Free Shipping" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1"><b>Free Shipping</b></label>
                    </div>

                </div>
                <button type="" class="btn btn-danger mt-5" name="delete_records">Clear</button>
                <button type="submit" class="btn btn-success mt-5 float-end" name="submit">Calculator</button>

            </form>
        </div>
        <?php
        // Display calculated results
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if delete button is clicked
            if (isset($_POST['delete_records'])) {
                // Perform deletion query
                $sql = "DELETE FROM calculator "; // Delete all records

                if ($con->query($sql) === TRUE) {
                    $rmsg = "<div class='alert alert-success mt-3'>All records deleted successfully.</div>";
                } else {
                    $rmsg = "<div class='alert alert-warning mt-3'>Error deleting records: " . $con->error . "</div>";
                }
            } else {
                // Handle form submission for adding records
                // Sanitize and process form inputs as per your existing logic
            }
        }



        $sql = "SELECT calculator.id, calculator.sell, calculator.disc, calculator.category, calculator.shipping, calculator.free_shipping, calculator.vat, calculator.cost, calculator.expenses 
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
                $package = 0;
                if ($sell <= 1000) {
                    $package = 7;
                } else if ($sell > 1000 && $sell < 2000) {
                    $package = 10;
                } else if ($sell > 2000) {
                    $package = 50;
                }
                $package_on_vat = $package * $vat / 100;
                $total_package = round($package + $package_on_vat);

                $ship = $row['free_shipping'] ? $disc + $row['shipping'] : $disc;
                $netSell = $sell - $ship;
                $commissionWithoutVAT = ($category) * $netSell / 100;
                $vatOnCommission = ($commissionWithoutVAT) * $vat / 100;
                $totalCommission = round($commissionWithoutVAT + $vatOnCommission);


                // calculate payment handling
                $payment_handling_without_vat = $netSell * 1.75 / 100;
                $payment_handling_vat = $payment_handling_without_vat * $vat / 100;
                $total_TH = round($payment_handling_without_vat + $payment_handling_vat);


                // shipping
                $ship = $row['free_shipping'] ? $disc + $row['shipping'] : $disc;
                $shipping_payment = $row['free_shipping'] ? "Paid by Seller" : "Paid by Customer";
                $vat_on_ship = round($row['shipping'] * ($vat / 100));


                // total deduction
                $total_deduction = round($totalCommission + $total_TH + $vat_on_ship + $total_package);

                // final deduction
                $final_payout = round($netSell - $total_deduction);

                // profit
                $profit = ($row['sell'] - $row['cost']) - $final_payout;
             
        ?>


                <div class="col-sm-3 mt-5">
                    <!-- <h2 class="text-center">Detail</h2> -->
                    <table class="table table-border">
                        <thead>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col" class="bg-dark text-white">
                                    Final Payout
                                </th>
                                <td class="bg-dark text-white"><?php echo number_format($final_payout, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">
                                    Total Deduction
                                </th>
                                <td><?php echo number_format($total_deduction, 2); ?></td>
                            </tr>
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
                            <!-- <tr class="text-center text-white bg-secondary">
                                <th scope="col">Package handling fee</th>
                                <td><?php echo $package; ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Vat on package handling</th>
                                <td><?php echo $package_on_vat; ?></td>
                            </tr> -->
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total package handling</th>
                                <td><?php echo $total_package; ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Shipping</th>
                                <td><?php echo $shipping_payment; ?></td>
                            </tr>
                            <!-- Other rows for Total Payment Handling Charged, VAT on Shipping Charged, Total Deduction -->


                    <?php
                }
            } else {
                if (isset($rmsg)) echo '<div class="col-sm-3 mt-5">' . $rmsg . '</div>';
            }
                    ?>
                        </thead>

                    </table>
                </div>
    </div>
</div>
</div>

<?php
include('./include/footer.php');
?>