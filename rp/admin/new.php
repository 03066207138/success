<?php
// Include necessary files
include('./include/header.php');
include('../connection.php');

echo '<div class="row justify-content-center">';
echo '<div class="col-md-9 mt-4">';
echo '<div class="mt-3 mx-5 text-center">';
echo '<h3 class="bg-dark text-white p-2 mt-6">Details</h3>';
echo '</div>';
?>

<form action="" method="get">
    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $row['id']; ?>">
</form>
<?PHP
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT
       a.id
       ,a.order_no
       ,a.order_date
       ,b.productname
       ,a.price
       ,a.discount
       ,a.cate_id
       ,a.shipping
       ,a.free_shipping
       ,a.price - a.discount
       ,b.product_price 

       FROM add_sale a 
       LEFT JOIN addproduct b 
       ON a.item_id = b.id
       WHERE a.id = {$id}";
    $res = $con->query($sql);
    if ($res === false) {
        // Query execution failed
        echo "Error: " . $con->error;
    } elseif ($res->num_rows > 0) {
        $id = 0;
        // Fetch and display data
        while ($row = $res->fetch_assoc()) {
            $id++;
            // Calculate financial metrics
            $sell = $row['price'];
            $disc = $row['discount'];
            $category = $row['cate_id'];
            $shipping = $row['shipping'];
            $vat = 16;

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
            $total_package = $package + $package_on_vat;

            $ship = 0;
            $netSell = $sell - $disc;
            $commissionWithoutVAT = round(($category) * $netSell / 100);
            $vatOnCommission = round(($commissionWithoutVAT) * $vat / 100);
            $totalCommission = round($commissionWithoutVAT + $vatOnCommission);


            // calculate payment handling
            $payment_handling_without_vat = round($netSell * 1.75 / 100);
            $payment_handling_vat = $payment_handling_without_vat * 0.16;
            $total_TH = round($payment_handling_without_vat + $payment_handling_vat);


            // shipping
            $ship = $row['free_shipping'] ? $disc + $row['shipping'] : $disc;
            $shipping_payment = 0;
            $vat_on_ship = round($row['shipping'] * ($vat / 100));


            // total deduction
            $total_deduction = round($totalCommission + $total_TH + $vat_on_ship + $total_package);

            // final deduction
            $final_payout = round( $netSell - $total_deduction);

            $profit = ($final_payout - $row['product_price']);
?>
            <div class="row justify-content-center">
                <div class="col-sm-6 mt-2">
                    <!-- <h2 class="text-center">Detail</h2> -->
                    <table class="table table-border">
                        <thead>
                            <tr class="text-center">
                                <th scope="col" class="">
                                    Commission_without_VAT
                                </th>
                                <td class=""><?php echo number_format($commissionWithoutVAT, 2); ?></td>
                            </tr>
                            <tr class="text-center">
                                <th scope="col" class="">
                                    commission_with_VAT
                                </th>
                                <td class=""><?php echo number_format($vatOnCommission, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total Commission Charged</th>
                                <td><?php echo number_format($totalCommission, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">VAT on Shipping Charged</th>
                                <td><?php echo number_format($vat_on_ship, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Shipping</th>
                                <td><?php echo number_format($ship, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Payment_handling_fee</th>
                                <td><?php echo number_format($payment_handling_without_vat, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Vat_on_handling</th>
                                <td><?php echo number_format($payment_handling_vat, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total Payment Handling Charged</th>
                                <td><?php echo number_format($total_TH, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Package handling fee</th>
                                <td><?php echo $package; ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Vat on package handling</th>
                                <td><?php echo $package_on_vat; ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total package handling</th>
                                <td><?php echo $total_package; ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">Total Deduction</th>
                                <td><?php echo $total_deduction; ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">
                                    Final Payout
                                </th>
                                <td><?php echo number_format($final_payout, 2); ?></td>
                            </tr>
                            <tr class="text-center text-white bg-secondary">
                                <th scope="col">
                                    Profit
                                </th>
                                <td><?php echo number_format($profit, 2); ?></td>
                            </tr>
                </div>
    <?php
        }
    }
} else {
    // No rows found
    echo '<tr><td colspan="7">No data found.</td></tr>';
}

echo '</tbody>';
echo '</table>';

echo '<div>';
echo '<button onclick="window.print();" class="no-print btn btn-danger float-end">Print</button>';
echo '<a href="dash.php?manageprofit" class="no-print btn btn-info float-end me-2">Back</a>';
echo '</div>';

echo '</div>';


// Include footer
include('./include/footer.php');
    ?>