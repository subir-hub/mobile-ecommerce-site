<?php
require './admin/config/db.php';
session_start();

$id = $_REQUEST['id'];
$sql = "SELECT * FROM user_address WHERE id = ?";
$stmt_address = $conn->prepare($sql);
$stmt_address->bind_param("i", $id);
$stmt_address->execute();

$result = $stmt_address->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Address</title>
  <?php require './includes/links.php' ?>
  <link rel="stylesheet" href="./assets/css/profile.css">
</head>

<body style="padding-top: 70px;">

  <?php require './includes/header.php' ?>

  <div class="container my-5">
    <div class="row justify-content-center">

      <!-- Update Form -->
      <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="bi bi-pencil-square me-2"></i>
            <h5 class="mb-0">Update Delivery Address</h5>
          </div>
          <div class="card-body p-4">

            <!-- Success / Error Message -->
            <div id="result" class="alert d-none" role="alert"></div>

            <form id="UpdateUserAddress">

              <!-- Full Name & Mobile -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="fullname" class="form-label">Full Name</label>
                  <input type="text" class="form-control" value="<?= $row['user_name'] ?>" id="fullname" name="fullname" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="phone" class="form-label">Mobile Number</label>
                  <input type="tel" class="form-control" value="<?= $row['mobile_no'] ?>" id="phone" name="phone" maxlength="10" required>
                </div>
              </div>

              <!-- Address Line -->
              <div class="mb-3">
                <label for="address1" class="form-label">Address Line</label>
                <input type="text" class="form-control" value="<?= $row['address_line1'] ?>" id="address1" name="address1" placeholder="House No, Street, Landmark" required>
              </div>

              <!-- City & State -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="city" class="form-label">City</label>
                  <input type="text" class="form-control" value="<?= $row['city'] ?>" id="city" name="city" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="state" class="form-label">State</label>
                  <input type="text" class="form-control" value="<?= $row['state'] ?>" id="state" name="state" required>
                </div>
              </div>

              <!-- Pincode & Country -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="pincode" class="form-label">Pincode</label>
                  <input type="text" class="form-control" value="<?= $row['pincode'] ?>" id="pincode" name="pincode" maxlength="6" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="country" class="form-label">Country</label>
                  <input type="text" class="form-control" value="<?= $row['country'] ?>" id="country" name="country" required>
                </div>
              </div>

              <!-- Address Type -->
              <div class="mb-3">
                <label class="form-label">Address Type</label><br>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="address_type" id="home" value="Home" <?= $row['address_type'] == 'Home' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="home">Home</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="address_type" id="work" value="Work" <?= $row['address_type'] == 'Work' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="work">Work</label>
                </div>
              </div>

              <input type="hidden" name="id" value="<?= $row['id'] ?>">

              <!-- Submit -->
              <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="./checkout.php" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left"></i> Back to Checkout
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save me-1"></i> Update Address
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>

    <?php require './includes/footer.php' ?>


  <script>
    $(document).ready(function() {
      $("#UpdateUserAddress").on("submit", function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
          type: "POST",
          url: "ajax/auth.php",
          data: formData + "&action=updateUserAddress",
          dataType: "json",
          success: function(response) {
            let resultBox = $("#result");

            if (response.code === 200) {
              resultBox
                .removeClass("d-none alert-danger")
                .addClass("alert alert-info")
                .text(response.msg).css('color', 'green');

              
              setTimeout(() => {
                window.location.href = './checkout.php';
              }, 2000);

            } else {
              resultBox
                .removeClass("d-none alert-info")
                .addClass("alert alert-danger")
                .text(response.msg).css('color', 'red');
            }
          },
          error: function() {
            $("#result")
              .removeClass("d-none alert-info")
              .addClass("alert alert-danger")
              .text("Something went wrong. Please try again later.").css('color', 'red');
          },
        });
      });
    });
  </script>
</body>
</html>
