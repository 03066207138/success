<?php

include_once('include/header.php');
define('PAGE', 'addsale.php');
include('../connection.php');
// if (isset($_GET['id'])) {
$id = $_GET['id'];
$sql = "SELECT `id`, `name`, `phone_no`, `city`, `order_type`, `order_no`, `item_id`, `price`, `tracking_id`, `order_date`, `discount`, `shipping`, `free_shipping`, `cate_id` FROM `add_sale` WHERE `id` = '$id'";
$res = $con->query($sql);

if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {


?>
        <?php
        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $name = $_POST['name1'];
            $phone = $_POST['phone'];
            $city = $_POST['city'];
            $radio = $_POST['flexRadioDefault'];
            $orderno = $_POST['number'];
            $id1 = $_POST['id1'];
            $price = $_POST['price'];
            $id3 = $_POST['id'];
            $date = $_POST['date'];
            $disc = $_POST['disc'];
            $shipping = $_POST['ship'];
            $checkbox = $_POST['checkbox'];
            $id2 = $_POST['id2'];

            $sql = " UPDATE `add_sale` SET `name`='$name',`phone_no`='$phone',`city`='$city',`order_type`='$radio',`order_no`='$orderno',
                    `item_id`='$id1',`price`='$price',`tracking_id`='$id3',`order_date`='$date',`discount`='$disc',
                    `shipping`='$shipping',`free_shipping`='$checkbox',`cate_id`='$id2' WHERE `id` = {$_GET['id']}";
            $res = $con->query($sql);
            if ($res) {
                $rmsg = "<div class='alert alert-success mt-3'>Request Updated Successfully...</div>";
                echo "<script> location.href = 'dash.php?managesale'</script>";
            } else {
                $rmsg = "<div class='alert alert-warning mt-3'>Unable to Update...</div>";
            }
            // } else {
            //     echo "<script>location.href='dash.php';</script>";
            // }
        }

        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard</title>
            <!-- Add necessary CSS links -->
            <link rel="stylesheet" href="path/to/bootstrap.min.css">
            <!-- Add necessary JS links -->
            <script src="path/to/bootstrap.bundle.min.js"></script>
            <!-- Add additional CSS or JS files as needed -->
        </head>

        <body>
            <nav class="navbar bg-danger shadow no-print"><a href="" class="navbar-brand text-white mx-3">REYS</a>
                <div class="float-end">
                    <!-- <a href="additem.php" class="btn btn-outline-light text-info">Add Item</a>
            <a href="addcategory.php" class="btn btn-outline-light text-info me-3">Add Category</a> -->
                </div>
            </nav>
            <span class="text-center"></span>
            <div class="container-fluid mt-2">
                <div class="row">
                    <nav class="col-sm-2 bg-dark fixed no-print">
                        <div class="sidebar-sticky">
                            <ul class="nav flex-column">
                                <li class="nav-item"><a href="dash.php?dashboard" class="nav-link text-light text-center mt-2 <?php if (PAGE == 'dashboard') {
                                                                                                                                    echo 'bg-white text-dark';
                                                                                                                                } ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <li class="nav-item"><a href="dash.php?managesale" class="nav-link text-light text-center mt-2 <?php if (PAGE == 'managesale') {
                                                                                                                                    echo 'bg-white text-dark';
                                                                                                                                } ?>"><i class="fa-solid fa-comments-dollar me-2"></i>Manage Sale</a></li>
                                <!-- <li class="nav-item"><a href="dash.php?addsale" class="nav-link text-light text-center mt-2 <?php if (PAGE == 'addsale') {
                                                                                                                                        echo 'bg-white text-dark';
                                                                                                                                    } ?>"><i class="fa-solid fa-pen-to-square me-2"></i>Add Sale</a></li> -->
                                <!-- <li class="nav-item"><a href="dash.php?addproduct" class="nav-link text-light text-center mt-2"><i class="fa-solid fa-pen-to-square me-2"></i>Add Product</a></li> -->
                                <li class="nav-item"><a href="dash.php?manageproduct" class="nav-link text-light text-center mt-2"><i class="fa-solid fa-comment-dollar me-2"></i>Manage Product</a></li>
                                <li class="nav-item"><a href="dash.php?manageprofit" class="nav-link text-light text-center mt-2"><i class="fa-solid fa-comment-dollar me-2"></i>Manage Profit</a></li>
                                <li class="nav-item"><a href="dash.php?calculator" class="nav-link text-light text-center mt-2 <?php if (PAGE == 'calculator') {
                                                                                                                                    echo 'bg-white text-dark';
                                                                                                                                } ?>"><i class="fa-solid fa-comment-dollar me-2"></i>Profit Calculator</a></li>
                                <li class="nav-item"><a href="logout.php?logout" class="nav-link text-light text-center mt-2"><i class="fa-solid  fa-right-to-bracket me-2"></i>logout</a></li>

                            </ul>
                        </div>
                    </nav>
                    <!--start form -->
                        <div class="col-sm-9" style="margin: 5px;">
                            <h2 class="text-center mt-2">Update Sale</h2>
                            <form action="" method="post" class="m-2 border border-2 p-4 bg-light">
                                <h3 class="text-center mt-2">Customer Info</h3>

                                <div class="row">
                                    <div class="form-group mt-2">

                                        <input type="number" class="form-control" name="id" id="id" value="<?php echo $row['id']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="name"><b>Name</b></label>
                                        <input type="text" class="form-control" name="name1" id="name1" value="<?php echo $row['name']; ?>" placeholder="Enter the Name">
                                    </div>
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="phoneno"><b>Phone Number</b></label>
                                        <input type="number" class="form-control" name="phone" id="phone" value="<?php echo $row['phone_no']; ?>" placeholder="Enter the phone">
                                    </div>
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="city"><b>City</b></label>
                                        <select class="form-select" name="city" aria-label="Default select example">
                                            <option selected><?php echo $row['city']; ?></option>
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

                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="Single Item" <?php echo ($row['order_type'] == 'Single Item') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Single Item
                                        </label>
                                    </div>
                                    <div class="form-check col-md-2">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="Multiple Item" <?php echo ($row['order_type'] == 'Multiple Item') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Multiple Item
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="number"><b>Order no</b></label>
                                        <input type="number" class="form-control" name="number" id="number" value="<?php echo $row['order_no']; ?>" placeholder="Enter the number">
                                    </div>
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="item" class="form-label"><b>Item Name</b></label>

                                        <select class="form-select" name="id1" aria-label="Default select example">
                                            <!-- <option selected>Open this select menu</option> -->
                                            <?php
                                            $sql1 = "SELECT id, productname FROM addproduct";
                                            $result1 = $con->query($sql1);

                                            if ($result1->num_rows > 0) {
                                                while ($row1 = $result1->fetch_assoc()) {

                                            ?>
                                                    <option value="<?php echo $row1['id']; ?>" <?php echo ($row1['id'] == $row['item_id']) ? 'selected' : "" ?>><?php echo $row1['productname']; ?></option>
                                            <?php

                                                }
                                            }
                                            ?>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="price"><b>Price</b></label>
                                        <input type="number" class="form-control" name="price" id="price" value="<?php echo $row['price']; ?>">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="id"><b>Tracking Id</b></label>
                                        <input type="number" class="form-control" name="id" id="id" value="<?php echo $row['tracking_id']; ?>">
                                    </div>
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="item"><b>Order Date</b></label>
                                        <input type="date" class="form-control" name="date" id="date" value="<?php echo $row['order_date']; ?>">
                                    </div>
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="price"><b>Discount</b></label>
                                        <input type="number" class="form-control" name="disc" id="disc" value="<?php echo $row['discount']; ?>">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="form-group col-md-4 mt-2">
                                        <label for="Shipping"><b>Shipping</b></label>
                                        <input type="number" class="form-control" name="ship" id="ship" value="<?php echo $row['shipping']; ?>">
                                    </div>
                                    <div class="form-check col-md-4 mt-5">
                                        <input class="form-check-input" name="checkbox" type="checkbox" id="defaultCheck1" value="Free Shipping" <?php echo ($row['free_shipping'] == true) ? 'checked' : ""; ?>>
                                        <label class="form-check-label" for="defaultCheck1">
                                            Free Shipping
                                        </label>
                                    </div>

                                    <div class="form-group col-md-4 mt-2">
                                        <label for="student" class="form-label"><b>Category</b></label>

                                        <select class="form-select" name="id2" aria-label="Default select example">
                                            <!-- <option selected>Open this select menu</option> -->
                                            <?php
                                            $sql = "SELECT id, categoryname FROM category";
                                            $result2 = $con->query($sql);

                                            if ($result2->num_rows > 0) {
                                                while ($row2 = $result2->fetch_assoc()) {
                                            ?>
                                                    <option value="<?php echo $row2['id']; ?>" <?php echo ($row2['id'] == $row['cate_id']) ? 'selected' : "" ?>><?php echo $row2['categoryname']; ?></option>
                                            <?php

                                                }
                                            }
                                            ?>
                                        </select>

                                        <br>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger mt-5" name="clear">Clear</button>
                                <button class="btn btn-info mt-5 float-end text-bold" type="submit" name="update">update</button>
                                <?php if (isset($rmsg)) {
                                    echo $rmsg;
                                } ?>
                            </form>
                        </div>
                    </div>


            <?php
        }
    }
            ?>


            Item