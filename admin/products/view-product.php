<?php
session_start();

if ($_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}

include_once __DIR__ . '/../config.php';
require '../includes/header.php';
require '../includes/topbar.php';
require '../includes/sidebar.php';
require '../config/db.php';

// Pagination setup
$limit = 4; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total products count
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit);

// Fetch products for current page
$sql = "SELECT * FROM products ORDER BY id ASC LIMIT $limit OFFSET $offset";
$query = $conn->query($sql);

?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php">Home</a></li>
                        <li class="breadcrumb-item active">View Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container py-3 text-center">
        <h3 class="mb-4">Manage Products</h3>

        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Brand</th>
                    <th>Created at</th>
                    <th colspan="2" class="px-5">Action</th>
                </tr>
            </thead>

            <tbody class="bg-white">
                <?php
                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td>
                                <img src="../uploads/<?= $row['image'] ?>" width="100">
                            </td>
                            <td><?= $row['product_name'] ?></td>
                            <td><?= $row['model'] ?></td>
                            <td>₹<?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['brand'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['id'] ?>">
                                    <i class="fa-solid fa-edit text-success"></i>
                                </a>
                            </td>
                            <td>
                                <a href="#" onclick="deleteRecord(<?= $row['id'] ?>)">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>

                    <tr>
                        <td class="text-danger" colspan="9">No records found</td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1) { ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                <?php } ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages) { ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>

</div>

<?php
require '../includes/footer.php'
?>

<script>
    function deleteRecord(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    id: id,
                    action: 'delete'
                },
                dataType: "json",
                success: function(response) {
                    if (response.code === 200) {
                        location.reload();
                    }
                },
                error: function() {
                    alert("Something went wrong! Please try again");
                }
            });
        }
    }
</script>

</body>

</html>