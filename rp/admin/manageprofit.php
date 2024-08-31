<?php
// Include necessary files
include('./include/header.php');
include('../connection.php');

// initialize values
$totalsell = 0;
$totaldisc = 0;
$subtotal = 0;
$totalpprice = 0;
$totalprofit = 0;

if (isset($_POST['view1']) && isset($_POST['date1']) && isset($_POST['date2'])) {
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];


    // Sanitize inputs to prevent SQL injection
    $date1 = mysqli_real_escape_string($con, $date1);
    $date2 = mysqli_real_escape_string($con, $date2);

    // Constructing the SQL query using prepared statements
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
                WHERE `order_date` BETWEEN '$date1' AND '$date2'";

    // Execute the query
    $res = $con->query($sql);
}
?>

<div class="col-sm-9">
    <?php
    echo '<div class="mt-3 mx-5 text-center">';
    echo '<h3 class="bg-dark text-white p-2">Profit and Loss Report</h3>';
    echo '</div>';
    ?>
    <div class="mt-4">
        <a href="dash.php?addsale" class="btn btn-danger text-white float-end no-print">Add Sale</a>
    </div>
    <form method="POST" action="">
        <div class="row mt-3 mx-5">
            <div class="form-group col-md-3">
                <input type="date" class="form-control" name="date1" id="date1" value="" placeholder="Enter the Date1">
            </div>
            <div class="form-group col-md-3">
                <?php
                $currentDate = date('Y-m-d');
                echo '<input type="date" class="form-control" name="date2" id="date2" value="' . $currentDate . '" placeholder="Enter the Date2" required>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <button class="btn btn-info text-bold" type="submit" name="view1">Search</button>
            </div>
        </div>
    </form>
    <?php

    echo '<thead>';
    echo '<table class="table table-bordered mt-4">';
    echo '<tr class="text-center text-white bg-secondary">';
    echo '<th class="bg-secondary text-white" scope="col">Sr No</th>';
    echo '<th class="bg-secondary text-white" scope="col">Order no</th>';
    echo '<th class="bg-secondary text-white" scope="col">Order Date</th>';
    echo '<th class="bg-secondary text-white" scope="col">Item</th>';
    echo '<th class="bg-secondary text-white" scope="col">Sale Price</th>';
    echo '<th class="bg-secondary text-white" scope="col">Discount</th>';
    echo '<th class="bg-secondary text-white" scope="col">Sub Total</th>';
    echo '<th class="bg-secondary text-white" scope="col">Purchase Price</th>';
    echo '<th class="bg-secondary text-white" scope="col">Profit</th>';
    echo '<th class="bg-secondary text-white" scope="col">Detail</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Fetch product data from the database
    if (isset($res)) {
        if ($res->num_rows > 0) {
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
                $sell1 = 0;

                // For total of all values
                $totalsell += $sell;
                $totaldisc += $disc;
                $subt = $row['price'] - $row['discount'];
                $subtotal += $subt;
                $pprice = $row['product_price'];
                $totalpprice += $pprice;

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
                $final_payout = round($netSell - $total_deduction);

                $profit = ($final_payout - $row['product_price']);
                $totalprofit += $profit;

                // Output table rows
                echo '<tr class="text-center">';
                echo '<td>' . htmlspecialchars($id) . '</td>';
                echo '<td>' . htmlspecialchars($row['order_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['productname']) . '</td>';
                echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                echo '<td>' . htmlspecialchars($row['discount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['price'] - $row['discount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['product_price']) . '</td>';
                echo '<td>' . htmlspecialchars($profit) . '</td>';
                echo '<td class="no-print"><a href="new.php?id=' . $row['id'] . '"><i class="fas fa-eye"></i></a></td>';
                echo '</tr>';
            }
            echo '<tr class="text-center text-dark">';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col">' . $totalsell . '</th>';
            echo '<th class=" text-dark" scope="col">' . $totaldisc . '</th>';
            echo '<th class=" text-dark" scope="col">' . $subtotal . '</th>';
            echo '<th class=" text-dark" scope="col">' . $totalpprice . '</th>';
            echo '<th class=" text-dark" scope="col">'.$totalprofit.'</th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '</tr>';
        } else {
            // No rows found
            echo '<tr>
                            <td colspan="7">No data found.</td>
                        </tr>';
        }
    } else {
        // Constructing the SQL query using prepared statements
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
        ON a.item_id = b.id";

        // Execute the query
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

                // For total of all values
                $totalsell += $sell;
                $totaldisc += $disc;
                $subt = $row['price'] - $row['discount'];
                $subtotal += $subt;
                $pprice = $row['product_price'];
                $totalpprice += $pprice;

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
                $final_payout = round($netSell - $total_deduction);

                $profit = ($final_payout - $row['product_price']);
                $totalprofit += $profit;
                // Output table rows
                echo '<tr class="text-center">';
                echo '<td>' . htmlspecialchars($id) . '</td>';
                echo '<td>' . htmlspecialchars($row['order_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['productname']) . '</td>';
                echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                echo '<td>' . htmlspecialchars($row['discount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['price'] - $row['discount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['product_price']) . '</td>';
                echo '<td>' . htmlspecialchars($profit) . '</td>';
                echo '<td class="no-print"><a href="new.php?id=' . $row['id'] . '"><i class="fas fa-eye"></i></a></td>';
                echo '</tr>';
            }
            echo '<tr class="text-center text-dark">';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '<th class=" text-dark" scope="col">' . $totalsell . '</th>';
            echo '<th class=" text-dark" scope="col">' . $totaldisc . '</th>';
            echo '<th class=" text-dark" scope="col">' . $subtotal . '</th>';
            echo '<th class=" text-dark" scope="col">' . $totalpprice . '</th>';
            echo '<th class=" text-dark" scope="col">'.$totalprofit.'</th>';
            echo '<th class=" text-dark" scope="col"></th>';
            echo '</tr>';
        } else {
            // No rows found
            echo '<tr>
                            <td colspan="7">No data found.</td>
                        </tr>';
        }
    }
    echo '</tbody>';
    echo '</table>';

    echo '<div>';
    echo '<button onclick="window.print();" class="no-print btn btn-danger float-end">Print</button>';
    echo '</div>';


    // Include footer
    include('./include/footer.php');
