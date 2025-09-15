<?php
session_start();

require './admin/config/db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-commerce Web</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="./assets/css/style.css">
  <?php
  require './includes/links.php';
  ?>
</head>

<body style="padding-top: 70px;">
  <?php require './includes/header.php' ?>

  <!-- Carousel  -->
  <div id="carouselExampleIndicators" class="carousel slide">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="./images/hero-1.png" class="d-block w-100" height="600" alt="...">
      </div>
      <div class="carousel-item">
        <img src="./images/hero-2.png" class="d-block w-100" height="600" alt="...">
      </div>
      <div class="carousel-item">
        <img src="./images/hero-3.png" class="d-block w-100" height="600" alt="...">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- Products -->
  <section id="products">
    <div class="container pt-5">
      <!-- Product Grid -->
      <div class="row">
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $productId = $row['id'];
        ?>
            <div class="col-md-3 mb-5">
              <div class="card border-0 shadow">
                <img src="admin/uploads/<?= $row['image'] ?>" class="card-img-top" height="300">
                <div class="card-body">
                  <h5 class="card-title"><?= $row['product_name'] ?></h5>
                  <p class="card-text text-muted"><?= $row['model'] ?></p>
                  <p class="price product-price">₹<?= number_format($row['price']) ?></p>
                  <span class="badge product-brand bg-light text-dark"><?= $row['brand'] ?></span>
                  <button type="button" class="btn btn-primary w-100 mt-2 btn-add-cart"
                    onclick="addToCart('<?= $productId ?>', '<?= $row['product_name'] ?>','<?= $row['model'] ?>','<?= $row['price'] ?>','<?= $row['image'] ?>','1')">
                    Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php
          }
        } else {
          ?>
          <div class="col-12 text-center">
            <p class="text-muted">No products found </b></p>
          </div>
        <?php } ?>

      </div>

    </div>
  </section>

  <!-- Chatbot  -->
  <section id="chatbot">
    <div class="chatbot-container">
      <!-- Chat Toggle Button -->
      <button class="chat-toggle" id="chatToggle">
        <i class="fas fa-comments"></i>
        <div class="notification-badge">1</div>
      </button>

      <!-- Chat Window -->
      <div class="chat-window" id="chatWindow">
        <!-- Chat Header -->
        <div class="chat-header">
          <div class="chat-info">
            <div class="chat-avatar">
              <i class="fas fa-robot"></i>
            </div>
            <div class="chat-details">
              <h4>TechBot</h4>
              <p><span class="status-dot"></span>Online</p>
            </div>
          </div>
          <button class="close-chat" id="closeChat">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Chat Body -->
        <div class="chat-body" id="chatBody">
          <div class="welcome-message">
            <h3>👋 Welcome to TechHaven!</h3>
            <p>I'm your virtual assistant</p>
            <p>How can I help you today?</p>
          </div>

          <!-- Sample Bot Message -->
          <div class="message bot-message">
            <div class="message-avatar bot-avatar">
              <i class="fas fa-robot"></i>
            </div>
            <div>
              <div class="message-content">
                Hi! I'm here to help you with any questions about our products or services. Feel free to ask me anything!
              </div>
              <div class="message-time">Just now</div>
            </div>
          </div>

          <!-- Typing Indicator -->
          <div class="message bot-message" id="typingIndicator">
            <div class="message-avatar bot-avatar">
              <i class="fas fa-robot"></i>
            </div>
            <div class="typing-indicator">
              <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Chat Input -->
        <div class="chat-input">
          <form class="input-group" id="chatForm">
            <input
              type="text"
              id="messageInput"
              placeholder="Type your message..."
              autocomplete="off"
              required>
            <button type="submit" class="send-btn" id="sendBtn">
              <i class="fas fa-paper-plane"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Facility Section -->
  <section id="our-facility" class="pt-5 bg-white">
    <div class="container text-center">
      <h2 class="mb-4">Our Facility</h2>
      <p class="mb-5 text-muted">We offer reliable services and secure shopping experiences to meet your needs with convenience and trust.</p>

      <div class="row g-4 justify-content-center">
        <!-- Facility Item 1 -->
        <div class="col-md-4 col-sm-6">
          <div class="facility-card p-4 bg-white shadow rounded">
            <div class="icon mb-3">
              <i class="fas fa-shipping-fast fa-3x text-primary"></i>
            </div>
            <h5 class="mb-2">Fast Shipping</h5>
            <p class="text-muted">Get your orders delivered quickly with trusted logistics partners, right to your doorstep.</p>
          </div>
        </div>

        <!-- Facility Item 2 -->
        <div class="col-md-4 col-sm-6">
          <div class="facility-card p-4 bg-white shadow rounded">
            <div class="icon mb-3">
              <i class="fas fa-headset fa-3x text-primary"></i>
            </div>
            <h5 class="mb-2">24/7 Support</h5>
            <p class="text-muted">Our dedicated team is here to help you anytime with your queries and concerns.</p>
          </div>
        </div>

        <!-- Facility Item 3 -->
        <div class="col-md-4 col-sm-6">
          <div class="facility-card p-4 bg-white shadow rounded">
            <div class="icon mb-3">
              <i class="fas fa-money-bill-wave fa-3x text-primary"></i>
            </div>
            <h5 class="mb-2">Cash on Delivery</h5>
            <p class="text-muted">Pay conveniently at the time of delivery without needing online payments or cards.</p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Testimonials Section -->
  <section id="testimonals">
    <div class="container py-5">
      <h2 class="mt-5 pt-4 mb-4 text-center h-font">What Our Customer Say</h2>

      <div class="swiper swiper-testimonials">
        <div class="swiper-wrapper">

          <!-- Slide 1 -->
          <div class="swiper-slide bg-white p-4 shadow-sm rounded border border-light testimonial-card">
            <div class="d-flex align-items-center mb-3">
              <img src="./images/customer/s2.jpg" width="60" height="60" class="rounded-circle me-3 border border-2 border-primary">
              <div>
                <h6 class="mb-0 fw-semibold">Raj Sharma</h6>
                <small class="text-muted">Customer</small>
              </div>
            </div>
            <p class="mb-3 fst-italic">"I ordered a smartphone from this site and was impressed with the fast delivery! The product matched the description perfectly and the payment process was super smooth. Highly recommended!"</p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="swiper-slide bg-white p-4 shadow-sm rounded border border-light testimonial-card">
            <div class="d-flex align-items-center mb-3">
              <img src="./images/customer/s3.jpg" width="60" height="60" class="rounded-circle me-3 border border-2 border-primary">
              <div>
                <h6 class="mb-0 fw-semibold">Priya Mehta</h6>
                <small class="text-muted">Customer</small>
              </div>
            </div>
            <p class="mb-3 fst-italic">"The accessory options are amazing and the website is easy to navigate. I loved the cash on delivery option — made it worry-free. The customer support team was quick to respond and resolve my queries."</p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star text-warning"></i>
            </div>
          </div>

          <!-- Slide 3 -->
          <div class="swiper-slide bg-white p-4 shadow-sm rounded border border-light testimonial-card">
            <div class="d-flex align-items-center mb-3">
              <img src="./images/customer/s4.jpg" width="60" height="60" class="rounded-circle me-3 border border-2 border-primary">
              <div>
                <h6 class="mb-0 fw-semibold">Rajib Patel</h6>
                <small class="text-muted">Customer</small>
              </div>
            </div>
            <p class="mb-3 fst-italic">"Excellent experience! The product packaging was secure and the phone arrived in perfect condition. Payment was easy, and tracking the order was seamless. Will definitely shop again."</p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star text-warning"></i>
            </div>
          </div>


          <!-- Slide 3 -->
          <div class="swiper-slide bg-white p-4 shadow-sm rounded border border-light testimonial-card">
            <div class="d-flex align-items-center mb-3">
              <img src="./images/customer/s-5.jpg" width="60" height="60" class="rounded-circle me-3 border border-2 border-primary">
              <div>
                <h6 class="mb-0 fw-semibold">Akshar Patel</h6>
                <small class="text-muted">Customer</small>
              </div>
            </div>
            <p class="mb-3 fst-italic">"Excellent experience! The product packaging was secure and the phone arrived in perfect condition. Payment was easy, and tracking the order was seamless. Will definitely shop again."</p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star text-warning"></i>
            </div>
          </div>

        </div>
        <div class="swiper-pagination mt-4"></div>
      </div>
    </div>
  </section>

  <?php require './includes/footer.php' ?>



  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script>
    function addToCart(productId, product_name, model, price, image, qty) {

      $.ajax({
        type: "POST",
        url: "ajax/ajax.php",
        data: {
          productId,
          product_name,
          model,
          price,
          image,
          qty,
          action: 'addToCart'
        },
        dataType: "json",
        success: function(response) {
          if (response.code === 200) {
            $("#cartCount").text(response.count);
            alert(response.msg);
          } else {
            alert(response.msg);
          }
        }
      });
    }

    $(document).ready(function() {
      let isOpen = false;

      // Cache jQuery objects
      const $chatToggle = $('#chatToggle');
      const $chatWindow = $('#chatWindow');
      const $closeChat = $('#closeChat');
      const $chatForm = $('#chatForm');
      const $messageInput = $('#messageInput');
      const $chatBody = $('#chatBody');
      const $typingIndicator = $('#typingIndicator');
      const $notification = $('.notification-badge');

      // Toggle chat window
      $chatToggle.on('click', function() {
        toggleChat();
      });

      // Close chat
      $closeChat.on('click', function() {
        closeWindow();
      });

      // Handle form submission
      $chatForm.on('submit', function(e) {
        sendMessage(e);
      });

      // Close on outside click
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.chatbot-container').length) {
          closeWindow();
        }
      });

      // Auto-focus input when opened
      $messageInput.on('focus', function() {
        hideNotification();
      });

      // Functions
      function toggleChat() {
        if (isOpen) {
          closeWindow();
        } else {
          openWindow();
        }
      }

      function openWindow() {
        $chatWindow.addClass('active');
        isOpen = true;
        hideNotification();

        // Focus input after animation
        setTimeout(function() {
          $messageInput.focus();
        }, 300);
      }

      function closeWindow() {
        $chatWindow.removeClass('active');
        isOpen = false;
      }

      function hideNotification() {
        $notification.hide();
      }

      function sendMessage(e) {
        e.preventDefault();

        const message = $messageInput.val().trim();
        if (!message) return;

        // Add user message
        addMessage(message, 'user');

        // Clear input
        $messageInput.val('');

        // Show typing indicator
        showTyping();

        // Your AJAX call to chatbot.php
        $.ajax({
          type: "POST",
          url: "ajax/chatbot.php",
          data: {
            msg: message
          },
          dataType: "json",
          success: function(response) {
            hideTyping();
            if (response.reply) {
              addMessage(response.reply, 'bot');
            }
          },
          error: function() {
            hideTyping();
            addMessage('Sorry, I could not connect. Please try again.', 'bot');
          }
        });
      }

      function addMessage(text, type) {
        const avatarClass = type === 'user' ? 'user-avatar' : 'bot-avatar';
        const avatarIcon = type === 'user' ? 'fas fa-user' : 'fas fa-robot';

        const messageHtml = `
                    <div class="message ${type}-message">
                        <div class="message-avatar ${avatarClass}">
                            <i class="${avatarIcon}"></i>
                        </div>
                        <div>
                            <div class="message-content">${text}</div>
                            <div class="message-time">${getCurrentTime()}</div>
                        </div>
                    </div>
                `;

        $typingIndicator.before(messageHtml);
        scrollToBottom();
      }

      function showTyping() {
        $typingIndicator.show();
        scrollToBottom();
      }

      function hideTyping() {
        $typingIndicator.hide();
      }

      function scrollToBottom() {
        setTimeout(function() {
          $chatBody.scrollTop($chatBody[0].scrollHeight);
        }, 100);
      }

      function getCurrentTime() {
        return new Date().toLocaleTimeString([], {
          hour: '2-digit',
          minute: '2-digit'
        });
      }
    });

    // Swiper

    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
    });

    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      loop: true,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
  </script>

</body>

</html>