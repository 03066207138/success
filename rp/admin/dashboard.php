<?php

include('./include/header.php');
include('../connection.php');
$totalsell = 0;
$totaldisc = 0;
$subtotal = 0;
$totalpprice = 0;
$totalprofit = 0;
$total_active_delivered = 0;

if (isset($_POST['view1']) && isset($_POST['date1']) && isset($_POST['date2'])) {
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $month = $_POST['month'];


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
                ,b.status
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
    <form method="POST" action="">
        <div class="row mt-3 mx-5">
            <div class="form-group col-md-3">
                <?php
               $currentMonth = date('m');
               $currentYear = date('Y');
               $startDate = date('Y-m-01'); // First day of the current month
               $endDate = date('Y-m-t'); // Last day of the current month
               $currentMonthName = date('F');
               
                echo'<input type="text" class="form-control" name="month" id="month" value="' . $currentMonthName.' '.$currentYear . '">';
                ?>
            </div>
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
    <div class="row text-center mx-5 no-print">
        <?php
        if (isset($res)) {
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {

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
                    if ($row['status'] == "active") {
                        $total_active_delivered++;
                    }
                }
            }
        ?>
            <div class="col-sm-2 mt-5">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header text-center"><b>Total Sale</b></div>
                    <div class="card-body">
                        <a href="#" class="btn text-white"><?php echo $totalsell; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 mt-5">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header text-center"><b>Total Purchase</b></div>
                    <div class="card-body">
                        <a href="#" class="btn text-white"><?php echo $totalpprice; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 mt-5">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header text-center"><b>Total Expenses</b></div>
                    <div class="card-body">
                        <a href="#" class="btn text-white"><?php echo $totalsell; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 mt-5">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header"><b>Pack Expenses</b></div>
                    <div class="card-body">
                        <a href="#" class="btn text-white"><?php echo $totalsell; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 mt-5">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header text-center"><b>Total Return</b></div>
                    <div class="card-body">
                        <a href="#" class="btn text-white"><?php echo $totalprofit; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 mt-5">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header"><b>Total Delivered</b></div>
                    <div class="card-body">
                        <a href="#" class="btn text-white"><?php echo $total_active_delivered; ?></a>
                    </div>
                </div>
            </div>
        <?php
        } else {
            $sql = "SELECT
                a.id
                ,a.order_no
                ,a.order_date
                ,b.productname
                ,b.status
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
                WHERE order_date LIKE '%$currentMonth%'";

            // Execute the query
            $res = $con->query($sql);

        ?>


            <div class="row text-center no-print">
                <?php
                if (isset($res)) {
                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {

                            // Calculate financial metrics                                                                                          `    
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
                            if ($row['status'] == "active") {
                                $total_active_delivered++;
                            }
                        }
                    }
                }
                ?>
                <div class="col-sm-2 mt-5">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-header text-center"><b>Total Sale</b></div>
                        <div class="card-body">
                            <a href="#" class="btn text-white"><?php echo $totalsell; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 mt-5">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header text-center"><b>Total Purchase</b></div>
                        <div class="card-body">
                            <a href="#" class="btn text-white"><?php echo $totalpprice; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 mt-5">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header text-center"><b>Total Expenses</b></div>
                        <div class="card-body">
                            <a href="#" class="btn text-white"><?php echo $totalsell; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 mt-5">
                    <div class="card text-white bg-secondary mb-3">
                        <div class="card-header"><b>Pack Expenses</b></div>
                        <div class="card-body">
                            <a href="#" class="btn text-white"><?php echo $totalsell; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 mt-5">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-header text-center"><b>Total Return</b></div>
                        <div class="card-body">
                            <a href="#" class="btn text-white"><?php echo $totalprofit; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 mt-5">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header"><b>Delivered</b></div>
                        <div class="card-body">
                            <a href="#" class="btn text-white"><?php echo $total_active_delivered; ?></a>
                        </div>
                    </div>
                </div>
            
    
<?php
        }

?>
<?php
include('./include/footer.php');

?>