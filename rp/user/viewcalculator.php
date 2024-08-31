<!-- table -->
<?php
include('./include/header.php');
include('../connection.php');

$sql = "SELECT calculator.sell , calculator.disc , calculator.category , calculator.shipping , calculator.free_shipping, calculator.vat ,add_sale.Price from 
     calculator
     INNER JOIN  add_sale
     on calculator.id = 'add_sale.id'";
$res = $con->query($sql);
$totalcom = 0;
if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $fprice = $row['sell'] - $row['disc'];
        $cate_com_without_VAT = ($row['category'] * $fprice) / 100;
        $cate_com_VAT = ($row['category'] * 0.16);
        $total_commision_charged = ($cate_com_without_VAT + $cate_com_VAT);


?>

        <div class="row">
            <div class="col-sm-8 mt-5">
                <h2 class="text-center">Detail</h2>
                <table class="table table-bordered border-1">
                    <thead>
                        <tr class="text-center text-white bg-secondary">
                            <th scope="col">Total Commission Charged</th>
                            <td><?php echo $total_commision_charged; ?></td>
                        </tr>

                        <tr class="text-center text-white bg-secondary">
                            <th scope="col">Total Payment Handling Charged</th>
                            <td>cell</td>
                        </tr>

                        <tr class="text-center text-white bg-secondary">
                            <th scope="col">VAT on Shipping Charged</th>
                            <td>cell</td>
                        </tr>
                        <tr class="text-center text-white bg-secondary">
                            <th scope="col">Total Deduction</th>
                            <td>cell</td>
                        </tr>


                <?php

            }
        }
                ?>
                    </thead>

                </table>

            </div>
        </div>



        <?php
        include('./include/footer.php');
        ?>

        </div>