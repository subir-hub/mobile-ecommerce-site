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

// Join tables
$sql = "SELECT o.id AS order_id, o.total_amount, o.status, o.created_at, u.id AS user_id,u.name AS user_name
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
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
                        <li class="breadcrumb-item active">Manage Orders</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container text-center py-3">
        <h3 class="mb-4">Manage Orders</h3>
        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>User</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="bg-white">
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= $row['user_name'] ?></td>
                        <td>₹<?= number_format($row['total_amount'], 2) ?></td>

                        <!-- Status Column with Badge -->
                        <td>
                            <?php
                            $status = $row['status'];
                            $badgeClass = 'secondary'; 

                            if ($status === 'Confirmed') $badgeClass = 'success';
                            elseif ($status === 'Rejected') $badgeClass = 'danger';
                            elseif ($status === 'Cancelled') $badgeClass = 'warning';
                            elseif ($status === 'Pending') $badgeClass = 'info';
                            ?>
                            <span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span>
                        </td>

                        <!-- Created At -->
                        <td><?= $row['created_at'] ?></td>

                        <!-- Action Column -->
                        <td>
                            <?php if ($row['status'] === 'Pending') { ?>
                                <button class="btn btn-success btn-sm update-status" data-id="<?= $row['order_id'] ?>" data-action="confirm">Confirm</button>
                                <button class="btn btn-danger btn-sm update-status" data-id="<?= $row['order_id'] ?>" data-action="reject">Reject</button>
                                <button class="btn btn-warning btn-sm update-status" data-id="<?= $row['order_id'] ?>" data-action="cancel">Cancel</button>
                            <?php } else { ?>
                                <span class="text-muted">No Action</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>


</div>

<?php
require '../includes/footer.php'
?>

<script>
    $(document).on("click", ".update-status", function() {
        let orderId = $(this).data("id");
        let action = $(this).data("action");
        let row = $(this).closest("tr");

        if (confirm("Are you sure you want to " + action + " this order?")) {
            $.ajax({
                type: "POST",
                url: "./update-order-status.php",
                data: {
                    id: orderId,
                    action: action
                },
                dataType: "json",
                success: function(response) {
                    if (response.code === 200) {
                        // Update badge
                        let badgeClass = "secondary";
                        if (response.status === "Confirmed") badgeClass = "success";
                        else if (response.status === "Rejected") badgeClass = "danger";
                        else if (response.status === "Cancelled") badgeClass = "warning";
                        else if (response.status === "Pending") badgeClass = "info";

                        row.find("td:nth-child(5)").html('<span class="badge bg-' + badgeClass + '">' + response.status + '</span>');

                        // Replace buttons with "No Action"
                        row.find("td:nth-child(7)").html('<span class="text-muted">No Action</span>');

                        alert("Order " + action + "ed successfully!");
                    } else {
                        alert(response.msg || "Something went wrong");
                    }
                }
            });
        }
    });
</script>


</body>

</html>