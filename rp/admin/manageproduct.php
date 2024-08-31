<?php
include('./include/header.php');
include('../connection.php');

?>

<div class="col-md-9">


    <div class="row">
        <div class="col-md-12">
            <a href="dash.php?addproduct" class="btn btn-danger text-white float-end no-print mt-3">Add Product</a>
        </div>
    </div>

    <table class="table table-bordered">
        <div class="mt-3 mx-5 text-center">
            <h3 class="bg-dark text-white p-2">Manage Product</h3>
        </div>
        <thead>
            <tr class="text-center text-white bg-secondary">
                <th class='bg-secondary text-white' scope="col">Id</th>
                <th class='bg-secondary text-white' scope="col">Image</th>
                <th class='bg-secondary text-white' scope="col">Product name</th>
                <th class='bg-secondary text-white' scope="col">Product SKU</th>
                <th class='bg-secondary text-white' scope="col">Purchase Price</th>
                <th class='bg-secondary text-white' scope="col">Sale Price</th>
                <th class='bg-secondary text-white' scope="col">Category</th>
                <th class='bg-secondary text-white' scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch product data from the database
            $sql = "SELECT `id`, `productname`, `product_sku`, `product_price`, `product_sale_price`, `category`, `image`, `status` FROM `addproduct`";
            $res = $con->query($sql);
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {

                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td><img src="../images/' . $row['image'] . '" style="max-width: 100px; max-height: 100px;"></td>';
                    echo '<td>' . htmlspecialchars($row['productname']) . '</td>'; // Sanitize output
                    echo '<td>' . htmlspecialchars($row['product_sku']) . '</td>'; // Sanitize output
                    echo '<td>' . $row['product_price'] . '</td>';
                    echo '<td>' . $row['product_sale_price'] . '</td>';
                    echo '<td>' . htmlspecialchars($row['category']) . '</td>'; // Sanitize output
                    echo '<td>' . $row['status'] . '</td>';
                    echo '</tr>';
                }
            } else {
                // No rows found
                echo '<tr><td colspan="9">No data found.</td></tr>';
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