<?php
include('./include/header.php');
include('../connection.php');

if (isset($_POST['view1'])) {
    $orderno = $_POST['num'];
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];


    // Sanitize inputs to prevent SQL injection (important for security)
    $orderno = mysqli_real_escape_string($con, $orderno);
    $date1 = mysqli_real_escape_string($con, $date1);
    $date2 = mysqli_real_escape_string($con, $date2);

    // Constructing the SQL query using prepared statements
    $sql = "SELECT `id`, `order_no`, `order_type`, `order_date`, `item_id`,  
                `tracking_id`, `price`, `discount`, `shipping`, `free_shipping`
                FROM `add_sale`
                WHERE `order_no` = '$orderno'
                AND `order_date` BETWEEN '$date1' AND '$date2'";

    // Execute the query
    $res = $con->query($sql);
}
?>
<div class="col-sm-9">
    <div class="mt-3 mx-5 text-center">
        <h3 class="bg-dark text-white p-2">Sale</h3>
    </div>
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
                <input type="number" class="form-control" name="num" id="num" value="" placeholder="Enter the Order no">
            </div>
            <div class="form-group col-md-3">
                <button class="btn btn-info text-bold" type="submit" name="view1">Search</button>
            </div>
        </div>
    </form>


    <table class="table table-bordered mt-4">
        <thead>
            <tr class="text-center text-white bg-secondary">
                <th class='bg-secondary text-white' scope="col">Id</th>
                <th class='bg-secondary text-white' scope="col">Order no</th>
                <th class='bg-secondary text-white' scope="col">Order date</th>
                <th class='bg-secondary text-white' scope="col">Order type</th>
                <th class='bg-secondary text-white' scope="col">Tracking id</th>
                <th class='bg-secondary text-white' scope="col">Item id</th>
                <th class='bg-secondary text-white' scope="col">Price</th>
                <th class='bg-secondary text-white' scope="col">Discount</th>
                <th class='bg-secondary text-white' scope="col">Percentage</th>
                <th class='bg-secondary text-white' scope="col">Total Price</th>
                <th class='bg-secondary text-white no-print' scope="col">Edit</th>
            </tr>
        </thead>
        <tbody>

            <!-- api api -->
            <?php
            // API configuration
            $apiUrl = "https://api.daraz.pk/rest/order/get"; // Replace with correct API endpoint
            $apiKey = '502462'; // Replace with your API key

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ]);

            // Execute the request and get the response
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                $rmsg = '<div class="alert alert-danger mt-3">Error: ' . curl_error($ch) . '</div>';
            } else {
                // Print response for debugging
                echo '<pre>' . print_r($response, true) . '</pre>';

                $response1 = json_decode($response, true);
                if (isset($response1['data'])) {
                    $data = $response1['data'];

                    // Prepare and bind
                    $stmt = $con->prepare("INSERT INTO `add_sale1` (`order_number`, `price`, `payment_method`, `buyer_note`, `customer_first_name`, `customer_last_name`, `shipping_fee`, `address_shipping`, `address_billing`, `extra_attributes`, `order_id`, `gift_message`, `remarks`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    if (!$stmt) {
                        $rmsg = "<div class='alert alert-danger mt-3'>Error preparing statement: " . $con->error . "</div>";
                    } else {
                        // Bind parameters
                        $orderNumber = $data['order_number'] ?? '';
                        $price = $data['price'] ?? 0.0;
                        $paymentMethod = $data['payment_method'] ?? '';
                        $buyerNote = $data['buyer_note'] ?? '';
                        $customerFirstName = $data['customer_first_name'] ?? '';
                        $customerLastName = $data['customer_last_name'] ?? '';
                        $shippingFee = $data['shipping_fee'] ?? 0.0;
                        $addressShipping = json_encode($data['address_shipping'] ?? []);
                        $addressBilling = json_encode($data['address_billing'] ?? []);
                        $extraAttributes = $data['extra_attributes'] ?? '';
                        $orderId = $data['order_id'] ?? '';
                        $giftMessage = $data['gift_message'] ?? '';
                        $remarks = $data['remarks'] ?? '';

                        $stmt->bind_param('sdsssssssssss', $orderNumber, $price, $paymentMethod, $buyerNote, $customerFirstName, $customerLastName, $shippingFee, $addressShipping, $addressBilling, $extraAttributes, $orderId, $giftMessage, $remarks);
                        if (!$stmt->execute()) {
                            $rmsg = "<div class='alert alert-danger mt-3'>Error executing query: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }
                }
            }
            curl_close($ch);
            ?>

            <!-- end api -->
            <?php
            if (isset($res)) {
                if ($res->num_rows > 0) {
                    // Array to track seen order numbers
                    $seenOrderNumbers = [];

                    while ($row = $res->fetch_assoc()) {
                        // Check if this order_no has been seen before
                        if (in_array($row['order_no'], $seenOrderNumbers)) {
                            // Mark this row as a duplicate with red background
                            echo '<tr class="table-danger text-white">';
                        } else {
                            echo '<tr>';
                            // Add this order_no to the seen list
                            $seenOrderNumbers[] = $row['order_no'];
                        }
                        // Calculate total price and shipping
                        $ship = $row['free_shipping'] ? $row['discount'] + $row['shipping'] : $row['discount'];
                        $percentage = round(($ship / $row['price']) * 100);
                        $total = $row['price'] - $ship;

                        // Output table data
                        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['order_no']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['order_type']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['tracking_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['item_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                        echo '<td>' . htmlspecialchars($ship) . '</td>';
                        echo '<td>' . htmlspecialchars($percentage) . ' %</td>';
                        echo '<td>' . htmlspecialchars($total) . '</td>';
                        echo '<td class="no-print"><a href="view.php?id=' . htmlspecialchars($row['id']) . '"><i class="fas fa-edit"></i></a></td>';
                        echo '</tr>';
                    }
                } else {
                    // No rows found
                    echo '<tr><td colspan="12">No data found for the selected date.</td></tr>';
                }
            } else {
                // Display all sales data by default
                $sql_all = "SELECT `id`, `order_no`, `order_type`, `order_date`, `item_id`,  
                            `tracking_id`, `price`, `discount`, `shipping`, `free_shipping` 
                            FROM `add_sale`";

                $res_all = $con->query($sql_all);

                // Array to track seen order numbers
                $seenOrderNumbers = [];

                if ($res_all->num_rows > 0) {
                    while ($row_all = $res_all->fetch_assoc()) {
                        // Check if this order_no has been seen before
                        if (in_array($row_all['order_no'], $seenOrderNumbers)) {
                            // Mark this row as a duplicate with red background
                            echo '<tr class="table-danger text-white">';
                        } else {
                            echo '<tr>';
                            // Add this order_no to the seen list
                            $seenOrderNumbers[] = $row_all['order_no'];
                        }

                        // Calculate total price and shipping
                        $ship = $row_all['free_shipping'] ? $row_all['discount'] + $row_all['shipping'] : $row_all['discount'];
                        $percentage = round(($ship / $row_all['price']) * 100);
                        $total = $row_all['price'] - $ship;

                        // Output table data
                        echo '<td>' . htmlspecialchars($row_all['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row_all['order_no']) . '</td>';
                        echo '<td>' . htmlspecialchars($row_all['order_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row_all['order_type']) . '</td>';
                        echo '<td>' . htmlspecialchars($row_all['tracking_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row_all['item_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row_all['price']) . '</td>';
                        echo '<td>' . htmlspecialchars($ship) . '</td>';
                        echo '<td>' . htmlspecialchars($percentage) . ' %</td>';
                        echo '<td>' . htmlspecialchars($total) . '</td>';
                        echo '<td class="no-print"><a href="view.php?id=' . htmlspecialchars($row_all['id']) . '"><i class="fas fa-edit"></i></a></td>';
                        echo '</tr>';
                    }
                } else {
                    // No rows found for all data
                    echo '<tr><td colspan="12">No data found.</td></tr>';
                }
            }
            ?>
        </tbody>
    </table>
    <div>


        <button onclick="window.print();" class="no-print btn btn-danger float-end">
            Print
        </button>
    </div>
</div>

<?php
include('./include/footer.php');
?>