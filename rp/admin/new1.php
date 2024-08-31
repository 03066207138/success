<?php
// Include necessary files
include('./include/header.php');
include('../connection.php');

echo '<div class="row justify-content-center">';
echo '<div class="col-md-9 mt-4">';
echo '<div class="mt-3 mx-5 text-center">';
echo '</div>';
?>

<form action="" method="get">
    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $row['id']; ?>">
</form>
<?php
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
            $total_TH = ($payment_handling_without_vat + $payment_handling_vat);


            // shipping
            $ship = $row['free_shipping'] ? $disc + $row['shipping'] : $disc;
            $shipping_payment = 0;
            $vat_on_ship = round($row['shipping'] * ($vat / 100));


            // total deduction
            $total_deduction = round($totalCommission + $total_TH + $vat_on_ship + $total_package);

            // final deduction
            $final_payout = round($netSell - $total_deduction);

            $profit = ($final_payout - $row['product_price']);

?>

            <div class="row justify-content-center">
                <div class="col-sm-9 ">
                    <div class="row text-center mx-5 justify-content-center">
                       
                       <?php if($profit <= 0.3){
                        
                       echo '<div class="col-sm-3 mt-5">';
                        echo    '<div class="card text-white bg-danger mb-3">';
                           echo     '<div class="card-header text-center">';
                                echo '</div>';
                              echo  '<div class="card-body">';
                                   echo ' <h4><b>Order No: '.$row['order_no'] .'</b></h4>';
                                   echo ' <card class="card-title"><b> Profit: '. $profit .'</b>';
                                   echo '     <a href="#" class="btn text-white"><b>Final Payout: '.$final_payout.'</b></a>';
                               echo ' </div>';
                            echo '</div>';
                        echo '</div>';
                        }
                        else{
                            echo '<div class="col-sm-3 mt-5">';
                            echo    '<div class="card text-white bg-success mb-3">';
                               echo     '<div class="card-header text-center">';
                                    echo '</div>';
                                  echo  '<div class="card-body">';
                                       echo ' <h4><b>Order No: '.$row['order_no'] .'</b></h4>';
                                       echo ' <card class="card-title"><b> Profit: '. $profit .'</b>';
                                       echo '     <a href="#" class="btn text-white"><b>Final Payout: '.$final_payout.'</b></a>';
                                   echo ' </div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    ?>
                        <div class="col-sm-3 mt-5">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header text-center">
                                    </div>
                                    <div class="card-body">
                                        <h4><b>Cost Price: <?php echo $row['product_price']; ?></b></h4>
                                        <!-- <card class="card-title"><b>Profit: <?php echo $profit; ?></b>
                                        <a href="#" class="btn text-white"><b>Final Payout: <?php echo $final_payout; ?></b></a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 mt-5">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-header text-center">
                                    </div>
                                    <div class="card-body">
                                    <h4><b>Sale Price: <?php echo $row['price']; ?></b></h4>
                                    <!-- <card class="card-title"><b>Profit: <?php echo $profit; ?></b>
                                        <a href="#" class="btn text-white"><b>Final Payout: <?php echo $final_payout; ?></b></a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 mt-5">
                            <button onclick="window.print();" class="no-print btn btn-danger float-end">Print</button>
                        </div>
                    </div>


                    <div class="row text-center mx-5">
                        <div class="col-sm-9">
                            <div class="dropdown mt-3">
                                <a class="btn btn-secondary btn-lg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Total Commission: <?php echo $totalCommission; ?>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Commission Without Vat: <?php echo $commissionWithoutVAT; ?></a></li>
                                    <li><a class="dropdown-item" href="#">Commission With Vat: <?php echo $vatOnCommission; ?></a></li>
                                    <li><a class="dropdown-item" href="#">Total Commission: <?php echo $totalCommission; ?></a></li>
                                </ul>
                            </div>
                            <div class="dropdown mt-4">
                                <a class="btn btn-secondary btn-lg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Vat on Shipping: <?php echo $vat_on_ship; ?>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Vat on Shipping: <?php echo $vat_on_ship; ?></a></li>
                                    <li><a class="dropdown-item" href="#">Shipping: <?php echo $shipping; ?></a></li>
                                </ul>
                            </div>
                            <div class="dropdown mt-4">
                                <a class="btn btn-secondary btn-lg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Total Payment Fee: <?php echo $total_TH; ?>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Payment Fee Without Vat: <?php echo $payment_handling_without_vat; ?></a></li>
                                    <li><a class="dropdown-item" href="#">Payment Fee With Vat: <?php echo $payment_handling_vat; ?></a></li>
                                    <li><a class="dropdown-item" href="#">Total Payment Fee: <?php echo $total_TH; ?></a></li>
                                </ul>
                            </div>
                            <div class="dropdown mt-4">
                                <a class="btn btn-secondary btn-lg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Total Package: <?php echo $total_package; ?>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Package Without Vat: <?php echo $package; ?></a></li>
                                    <li><a class="dropdown-item" href="#">Package With Vat: <?php echo $package_on_vat; ?></a></li>
                                    <li><a class="dropdown-item" href="#">Total Package: <?php echo $total_package; ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">
                                <a href="dash.php?manageprofit" class="no-print btn btn-info mx-5 mt-4">Back</a>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="/js/bootstrap.min.js"></script>
                <script src="/js/pooper.min.js"></script>
                <!-- fontawsome -->
                <script src="/js/all.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
                </body>

                </html>

    <?php

        }
    }
}
    ?>