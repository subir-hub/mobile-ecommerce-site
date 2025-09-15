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
                        <li class="breadcrumb-item active">Add Product</li>
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
                        <h4 class="mb-0">Add New Product</h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="productForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="productName" id="productName" placeholder="Enter product name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Product Model</label>
                                <input type="text" class="form-control" name="model" id="model" placeholder="Enter product model" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Enter price" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" name="brand" id="brand" placeholder="Enter brand" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="image" id="image" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">Save Product</button>

                            <div id="responseMsg" class="fw-semibold text-center"></div>
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
        $("#productForm").on("submit", function(e) {
            e.preventDefault();

            let productFormData = new FormData(this);
            productFormData.append("action", "insertProduct");

            $.ajax({
                type: "POST",
                url: "./ajax.php",
                data: productFormData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.code === 200) {
                        $("#responseMsg").text(response.msg)
                            .css("color", "green")
                            .fadeIn()
                            .delay(4000)
                            .fadeOut();
                        $("#productForm")[0].reset();
                    } else {
                        $("#responseMsg").text(response.msg)
                            .css("color", "red")
                            .fadeIn()
                            .delay(4000)
                            .fadeOut();
                    }
                },
                error: function(xhr, status, error) {
                    $("#responseMsg").text("AJAX error: " + error)
                        .css("color", "red")
                        .fadeIn()
                        .delay(4000)
                        .fadeOut();
                }
            });
        });
    });
</script>
</body>

</html>