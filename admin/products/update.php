<?php
session_start();

if ($_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}

require '../includes/header.php';
require '../includes/topbar.php';
require '../includes/sidebar.php';
require '../config/db.php';

// Fetch Data
$id = $_REQUEST['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

$stmt->close();

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
                        <li class="breadcrumb-item active">Update Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg my-4">
                    <div class="card-header bg-primary text-white text-center py-3" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
                        <h4 class="mb-0">Edit Product Details</h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="updateForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" value="<?= $data['product_name'] ?>" name="productName" id="productName" placeholder="Enter product name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Product Model</label>
                                <input type="text" class="form-control" value="<?= $data['model'] ?>" name="model" id="model" placeholder="Enter product model" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" value="<?= $data['price'] ?>" name="price" id="price" placeholder="Enter price" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" value="<?= $data['brand'] ?>" name="brand" id="brand" placeholder="Enter brand" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Current Product Image:</label>
                                <img src="../uploads/<?= $data['image'] ?>" width="100" class="d-block mt-2 rounded shadow-sm">
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Upload New Product Image (optional):</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>

                            <input type="hidden" name="id" value="<?= $data['id'] ?>">

                            <button type="submit" class="btn btn-primary w-100 py-2" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">Update</button>

                            <div id="updateMsg" class="fw-semibold text-center"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../includes/footer.php'
?>

<script>
    $(document).ready(function() {
        $("#updateForm").on("submit", function(e) {
            e.preventDefault();

            let updateFormData = new FormData(this);
            updateFormData.append("action", "updateProduct");

            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: updateFormData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.code === 200) {
                        $("#updateMsg").text(response.msg)
                            .css("color", "green")
                            .fadeIn()
                            .delay(4000)
                            .fadeOut();
                        
                            setTimeout(() => {
                                window.location.href = './view-product.php';
                            }, 4000);
                    } else {
                        $("#updateMsg").text(response.msg)
                            .css("color", "red")
                            .fadeIn()
                            .delay(4000)
                            .fadeOut();
                    }
                },
                error: function(xhr, status, error) {
                    $("#updateMsg").text("AJAX error: " + error)
                        .css("color", "red")
                        .fadeIn()
                        .delay(4000)
                        .fadeOut();
                }
            });
        });
    });
</script>