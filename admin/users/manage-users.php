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

$sql = "SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC";
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
                        <li class="breadcrumb-item active">Manage Users</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container text-center py-3">
        <h3 class="mb-3">Manage Users</h3>

        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="bg-white">
                <?php
                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['role'] ?></td>
                            <td>
                                <?php 
                                $status = $row['status'];
                                $badgeClass = ($status === 'Active') ? 'success' : 'danger';
                                ?>

                                <span class="badge bg-<?= $badgeClass ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a href="view-user-orders.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View</a>

                                <button class="btn btn-danger btn-sm delete-user" data-id="<?= $row['id'] ?>">Delete</button>
                                <?php 
                                if($status === 'Active') {
                                ?>
                                <button class="btn btn-warning btn-sm block-user" data-id="<?= $row['id'] ?>">Block</button>
                                <?php 
                                } else {
                                ?>
                                <button class="btn btn-success btn-sm unblock-user" data-id="<?= $row['id'] ?>">Unblock</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>

                    <tr>
                        <td colspan="7" class="text-danger">No records found</td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php require '../includes/footer.php' ?>

<script>
    // Delete user
    $(document).on("click", ".delete-user", function() {
        let userId = $(this).data("id");

        if (confirm("Are you sure you want to delete this user?")) {

            $.post("user-action.php", 
               {
                    id: userId,
                    action: "delete"
                },
                function(response) {
                    alert(response.message);
                    location.reload();
                },

                "json"
            );
        }
    });

    // Block user
    $(document).on("click", ".block-user", function() {
        let userId = $(this).data("id");

        if(confirm("Block this uder?")) {
            $.post("user-action.php", 
               {
                   id: userId,
                   action: "block"
               },
               function(response) {
                   alert(response.message);
                   location.reload();
               },
               "json"
            );
        }
    });

    // Unblock user
        $(document).on("click", ".unblock-user", function() {
        let userId = $(this).data("id");

        if(confirm("Unblock this uder?")) {
            $.post("user-action.php", 
               {
                   id: userId,
                   action: "unblock"
               },
               function(response) {
                   alert(response.message);
                   location.reload();
               },
               "json"
            );
        }
    });
</script>

</body>

</html>