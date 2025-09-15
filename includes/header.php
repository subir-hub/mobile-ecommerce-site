<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap');
    
    .navbar-brand {
  font-family: 'Poppins', sans-serif;
  font-size: 1.8rem;
  font-weight: 700;
  background: linear-gradient(135deg, #6366f1, #4f46e5);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: -0.5px;
}
</style>
<nav class="navbar bg-white shadow-sm navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fs-4" href="index.php">
            <img src="./images/logoipsum-245 (2).svg" alt="Logo" height="40"> TechHaven
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- Center Menu -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fs-5">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>

                <!-- Cart -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="./cart.php">
                        <span class="position-relative me-2">
                            🛒
                            <span id="cartCount"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white"
                                style="font-size: 12px;">
                                <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                            </span>
                        </span>
                        Cart
                    </a>
                </li>
            </ul>

            <!-- Right Menu -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fs-5">
                <?php if (isset($_SESSION['user_id'])) { ?>

                    <!-- Profile Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user me-2" style="font-size: 22px;"></i>
                            <span>Account</span>
                        </a>
                        <ul class="dropdown-menu shadow-sm dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="./my-orders.php">My Orders</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>

                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Login</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>