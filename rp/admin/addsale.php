<?php

include_once('include/header.php');
include('../connection.php');
session_start();

if (isset($_SESSION['is_login'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validating the form input
        $requiredFields = ['storeid', 'name1', 'phone', 'city', 'flexRadioDefault', 'number', 'item', 'price', 'id', 'date', 'disc', 'ship', 'id2'];
        $missingFields = array_filter($requiredFields, fn ($field) => empty($_REQUEST[$field]));

        if (!empty($missingFields)) {
            $rmsg = "<div class='alert alert-warning mt-3'>Fill All Fields</div>";
        } else {
            // Form values
            $store = $_REQUEST['storeid'];
            $name = $_REQUEST['name1'];
            $phone = $_REQUEST['phone'];
            $city = $_REQUEST['city'];
            $radio = $_REQUEST['flexRadioDefault'];
            $orderno = $_REQUEST['number'];
            $item = $_REQUEST['item'];
            $price = $_REQUEST['price'];
            $id = $_REQUEST['id'];
            $date = $_REQUEST['date'];
            $disc = $_REQUEST['disc'];
            $shipping = $_REQUEST['ship'];
            $checkbox = isset($_POST['checkbox']) ? $_POST['checkbox'] : '';
            $id2 = $_REQUEST['id2'];

            // Insert into database
            $sql = "INSERT INTO `add_sale`(`storename`,`name`, `phone_no`, `city`, `order_type`, `order_no`, `item_id`, `price`, `tracking_id`, `order_date`, `discount`, `shipping`, `free_shipping`, `cate_id`) VALUES ('$store', '$name', '$phone', '$city', '$radio', '$orderno', '$item', '$price', '$id', '$date', '$disc', '$shipping', '$checkbox', '$id2')";
            $res = $con->query($sql);
            if ($res) {
                $rmsg = "<div class='alert alert-success mt-3'>Request Added Successfully...</div>";
            } else {
                $rmsg = "<div class='alert alert-danger mt-3'>Unable to Add: " . $con->error . "</div>";
            }
        }

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
    }
} else {
    echo "<script>location.href='adminlogin.php';</script>";
}

?>
<div class="col-sm-8" style="margin: 5px;">
    <h2 class="text-center mt-2">Add Sale</h2>
    <form action="" method="post" class="m-2 border border-2 p-4 bg-light">
        <h3 class="text-center mt-2">Customer Info</h3>
        <div class="row">
            <div class="form-group col-md-6 mt-2">
                <label for="name"><b>Store Name</b></label>
                <select class="form-select" name="storeid" aria-label="Default select example">
                    <!-- <option selected>Open this select menu</option> -->
                    <?php
                    $sql = "SELECT * FROM `store`";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['storename']; ?></option>
                    <?php

                        }
                    }
                    ?>
                </select>
                <a href="../store/addstore.php" class="link link-primary float-end">Add Store</a>
            </div>

        </div>
        <div class="row">
            <div class="form-group col-md-4 mt-2">
                <label for="name"><b>Name</b></label>
                <input type="text" class="form-control" name="name1" id="name1" placeholder="Enter the Name">
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="phoneno"><b>Phone Number</b></label>
                <input type="number" class="form-control" name="phone" id="phone" placeholder="Enter the phone">
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="city"><b>City</b></label>
                <select class="form-select" name="city" aria-label="Default select example">
                    <option selected>Choose from cities</option>
                    <?php
                    $cities = array(
                        0 => 'Islamabad', 1 => 'Ahmed Nager', 2 => 'Ahmadpur East', 3 => 'Ali Khan', 4 => 'Alipur', 5 => 'Arifwala', 6 => 'Attock', 7 => 'Bhera', 8 => 'Bhalwal', 9 => 'Bahawalnagar', 10 => 'Bahawalpur', 11 => 'Bhakkar', 12 => 'Burewala', 13 => 'Chillianwala', 14 => 'Chakwal', 15 => 'Chichawatni', 16 => 'Chiniot', 17 => 'Chishtian', 18 => 'Daska', 19 => 'Darya Khan', 20 => 'Dera Ghazi', 21 => 'Dhaular',
                        22 => 'Dina', 23 => 'Dinga', 24 => 'Dipalpur', 25 => 'Faisalabad', 26 => 'Fateh Jhang', 27 => 'Ghakhar Mandi', 28 => 'Gojra', 29 => 'Gujranwala', 30 => 'Gujrat', 31 => 'Gujar Khan', 32 => 'Hafizabad', 33 => 'Haroonabad', 34 => 'Hasilpur', 35 => 'Haveli', 36 => 'Lakha', 37 => 'Jalalpur', 38 => 'Jattan', 39 => 'Jampur', 40 => 'Jaranwala', 41 => 'Jhang', 42 => 'Jhelum', 43 => 'Kalabagh', 44 => 'Karor Lal',
                        45 => 'Kasur', 46 => 'Kamalia', 47 => 'Kamoke', 48 => 'Khanewal', 49 => 'Khanpur', 50 => 'Kharian', 51 => 'Khushab', 52 => 'Kot Adu', 53 => 'Jauharabad', 54 => 'Lahore', 55 => 'Lalamusa', 56 => 'Layyah', 57 => 'Liaquat Pur', 58 => 'Lodhran', 59 => 'Malakwal', 60 => 'Mamoori', 61 => 'Mailsi', 62 => 'Mandi Bahauddin', 63 => 'mian Channu', 64 => 'Mianwali', 65 => 'Multan', 66 => 'Murree', 67 => 'Muridke',
                        68 => 'Mianwali Bangla', 69 => 'Muzaffargarh', 70 => 'Narowal', 71 => 'Okara', 72 => 'Renala Khurd', 73 => 'Pakpattan', 74 => 'Pattoki', 75 => 'Pir Mahal', 76 => 'Qaimpur', 77 => 'Qila Didar', 78 => 'Rabwah', 79 => 'Raiwind', 80 => 'Rajanpur', 81 => 'Rahim Yar', 82 => 'Rawalpindi', 83 => 'Sadiqabad', 84 => 'Safdarabad', 85 => 'Sahiwal', 86 => 'Sangla Hill', 87 => 'Sarai Alamgir', 88 => 'Sargodha',
                        89 => 'Shakargarh', 90 => 'Sheikhupura', 91 => 'Sialkot', 92 => 'Sohawa', 93 => 'Soianwala', 94 => 'Siranwali', 95 => 'Talagang', 96 => 'Taxila', 97 => 'Toba Tek', 98 => 'Vehari', 99 => 'Wah Cantonment', 100 => 'Wazirabad', 101 => 'Badin', 102 => 'Bhirkan', 103 => 'Rajo Khanani', 104 => 'Chak', 105 => 'Dadu', 106 => 'Digri', 107 => 'Diplo', 108 => 'Dokri', 109 => 'Ghotki', 110 => 'Haala', 111 => 'Hyderabad',
                        112 => 'Islamkot', 113 => 'Jacobabad', 114 => 'Jamshoro', 115 => 'Jungshahi', 116 => 'Kandhkot', 117 => 'Kandiaro', 118 => 'Karachi', 119 => 'Kashmore', 120 => 'Keti Bandar', 121 => 'Khairpur', 122 => 'Kotri', 123 => 'Larkana', 124 => 'Matiari', 125 => 'Mehar', 126 => 'Mirpur Khas', 127 => 'Mithani', 128 => 'Mithi', 129 => 'Mehrabpur', 130 => 'Moro', 131 => 'Nagarparkar', 132 => 'Naudero', 133 => 'Naushahro Feroze',
                        134 => 'Naushara', 135 => 'Nawabshah', 136 => 'Nazimabad', 137 => 'Qambar', 138 => 'Qasimabad', 139 => 'Ranipur', 140 => 'Ratodero', 141 => 'Rohri', 142 => 'Sakrand', 143 => 'Sanghar', 144 => 'Shahbandar', 145 => 'Shahdadkot', 146 => 'Shahdadpur', 147 => 'Shahpur Chakar', 148 => 'Shikarpaur', 149 => 'Sukkur', 150 => 'Tangwani', 151 => 'Tando Adam', 152 => 'Tando Allahyar', 153 => 'Tando Muhammad', 154 => 'Thatta',
                        155 => 'Umerkot', 156 => 'Warah', 157 => 'Abbottabad', 158 => 'Adezai', 159 => 'Alpuri', 160 => 'Akora Khattak', 161 => 'Ayubia', 162 => 'Banda Daud', 163 => 'Bannu', 164 => 'Batkhela', 165 => 'Battagram', 166 => 'Birote', 167 => 'Chakdara', 168 => 'Charsadda', 169 => 'Chitral', 170 => 'Daggar', 171 => 'Dargai', 172 => 'Darya Khan', 173 => 'dera Ismail', 174 => 'Doaba', 175 => 'Dir', 176 => 'Drosh', 177 => 'Hangu',
                        178 => 'Haripur', 179 => 'Karak', 180 => 'Kohat', 181 => 'Kulachi', 182 => 'Lakki Marwat', 183 => 'Latamber', 184 => 'Madyan', 185 => 'Mansehra', 186 => 'Mardan', 187 => 'Mastuj', 188 => 'Mingora', 189 => 'Nowshera', 190 => 'Paharpur', 191 => 'Pabbi', 192 => 'Peshawar', 193 => 'Saidu Sharif', 194 => 'Shorkot', 195 => 'Shewa Adda', 196 => 'Swabi', 197 => 'Swat', 198 => 'Tangi', 199 => 'Tank', 200 => 'Thall',
                        201 => 'Timergara', 202 => 'Tordher', 203 => 'Awaran', 204 => 'Barkhan', 205 => 'Chagai', 206 => 'Dera Bugti', 207 => 'Gwadar', 208 => 'Harnai', 209 => 'Jafarabad', 210 => 'Jhal Magsi', 211 => 'Kacchi', 212 => 'Kalat', 213 => 'Kech', 214 => 'Kharan', 215 => 'Khuzdar', 216 => 'Killa Abdullah', 217 => 'Killa Saifullah', 218 => 'Kohlu', 219 => 'Lasbela', 220 => 'Lehri', 221 => 'Loralai', 222 => 'Mastung', 223 => 'Musakhel',
                        224 => 'Nasirabad', 225 => 'Nushki', 226 => 'Panjgur', 227 => 'Pishin valley', 228 => 'Quetta', 229 => 'Sherani', 230 => 'Sibi', 231 => 'Sohbatpur', 232 => 'Washuk', 233 => 'Zhob', 234 => 'Ziarat',
                    );
                    foreach ($cities as $values) {
                    ?>
                        <option value="<?php echo $values; ?>"><?php echo $values; ?></option>
                    <?php
                    }
                    ?>

                </select>
            </div>
        </div>
        <h3 class="text-center mt-2">Order Details</h3>
        <div class="row justify-content-center mt-3">
            <h5 class="col-md-2">Order type:</h5>
            <div class="form-check col-md-2">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="Single Item">
                <label class="form-check-label" for="flexRadioDefault1">
                    Single Item
                </label>
            </div>
            <div class="form-check col-md-2">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="Multiple Item" required>
                <label class="form-check-label" for="flexRadioDefault2">
                    Multiple Item
                </label>
            </div>
        </div>
        <div class="row mt-2">
            <div class="form-group col-md-4 mt-2">
                <label for="number"><b>Order no</b></label>
                <input type="number" class="form-control" name="number" id="number" placeholder="Enter the number">
            </div>
            <div class="form-group col-md-4">
                <label for="item" class="form-label"><b>Item Name</b></label>
                <select class="form-select" name="item" id="item" onchange="fetchItemPrice()">
                    <!-- Populate options from database -->
                    <?php
                    $sql = "SELECT id, productname, product_sale_price FROM addproduct";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '" data-price="' . $row['product_sale_price'] . '">' . $row['productname'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="price"><b>Price</b></label>
                <?php
                if (!empty($_POST['item'])) {
                    // Fetch the price based on the selected product ID
                    $item = $_POST['item'];
                    $sql1 = "SELECT product_sale_price FROM addproduct WHERE id = $item";
                    $result = $con->query($sql1);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        // Display the price in the input field
                        echo '<input type="number" class="form-control" name="price" id="price" value="' . $row['product_sale_price'] . '">';
                    } else {
                        // Handle case where no price is found for selected ID
                        echo '<input type="number" class="form-control" name="price" id="price" value="">';
                    }
                } else {
                    echo '<input type="number" class="form-control" name="price" id="price" value="">';
                }
                ?>
            </div>
        </div>
        <div class="row mt-2">
            <div class="form-group col-md-4 mt-2">
                <label for="id"><b>Tracking Id</b></label>
                <input type="number" class="form-control" name="id" id="id">
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="item"><b>Order Date</b></label>
                <input type="date" class="form-control" name="date" id="date" value="" placeholder="Enter the Date">
            </div>
            <div class="form-group col-md-4 mt-2">
                <label for="price"><b>Discount</b></label>
                <input type="number" class="form-control" name="disc" id="disc" placeholder="PKR 0">
            </div>
        </div>
        <div class="row mt-2">
            <div class="form-group col-md-4 mt-2">
                <label for="Shipping"><b>Shipping</b></label>
                <input type="number" class="form-control" name="ship" id="ship" placeholder="PKR 119">
            </div>
            <div class="form-check col-md-4 mt-5">
                <input class="form-check-input" name="checkbox" type="checkbox" value="Free Shipping" id="defaultCheck1">
                <label class="form-check-label" for="defaultCheck1">
                    Free Shipping
                </label>
            </div>

            <div class="form-group col-md-4 mt-2">
                <label for="cate" class="form-label"><b>Category</b></label>
                <select class="form-select" name="id2" aria-label="Category Select">
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
                <a href="addcategory.php" class="link link-primary float-end">Add Category</a>
                <br>
            </div>
        </div>
        <button type="submit" class="btn btn-danger mt-5" name="clear">Clear</button>
        <button class="btn btn-info mt-5 float-end text-bold" type="submit" name="submit">Submit</button>
        <?php if (isset($rmsg)) {
            echo $rmsg;
        } ?>
    </form>