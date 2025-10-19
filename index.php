<?php
require_once 'core/functions.php';
require_once 'config/db.php';

// This session function ennsures that the session is started
ensureSession();

// Validate session and login status for the users (Cutomer and the admin/employees)
if (!isLoggedIn()) {
    // Only redirect to login if not already there to prevent loops
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (strpos($currentPath, 'auth/login.php') === false) {
        header("Location: auth/login.php");
        exit();
    }
    return;
}

// Refresh the session timestamp preventing timeout
$_SESSION['logged_in_at'] = time();

// Redirect users based on their role role
if (!isCustomer()) {
    // Default redirect to the index.php
    $redirectPath = 'index.php'; 
    
    switch ($_SESSION['role'] ?? '') {
        case 'admin':
            $redirectPath = 'modules/users/index.php';
            break;
        case 'cashier':
        case 'kitchen':
            $redirectPath = 'modules/orders/index.php';
            break;
        default:
            $redirectPath = 'auth/logout.php';
            break;
    }
    
    // Only redirect if not already on the target page preventing loops or possibly having infinite loops
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (strpos($currentPath, $redirectPath) === false) {
        header("Location: $redirectPath");
        exit();
    }
}

require_once 'views/header.php';
require_once 'views/navbar.php';
?>

<div class="container">
    <!-- Welcoming Section for the users as they browse for the food they desire to order -->
    <section class="welcome-section" style="margin-top:2rem;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Welcoming Messages -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1>Welcome to <span class="text-primary"><br>CHOOSE AND GO</span></h1>
                    <p class="lead">Delicious meals made with love. Order now and experience the best flavors in town!</p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#menuItems" class="btn btn-primary">
                            <i class="bi bi-utensils me-2"></i>View Menu
                        </a>
                        <a href="#categories" class="btn btn-outline-primary">
                            <i class="bi bi-tag me-2"></i>Categories
                        </a>
                    </div>
                </div>
                
                <!-- Image on the Welcoming Session-->
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Delicious Food" class="img-fluid rounded-3 shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- A Search bar where the users can search for meals, snacks, or drinks -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="search-container position-relative">
                <i class="bi bi-search search-icon position-absolute start-0 top-50 translate-middle-y ms-3 text-muted"></i>
                <input type="text" 
                       id="searchInput" 
                       class="form-control ps-5 py-3 border-2 rounded-pill shadow-sm" 
                       placeholder="Search for your favorite dishes..."
                       onkeyup="searchMenuItems()"
                       style="font-size: 1.05rem;">
                <div class="search-loader position-absolute end-0 top-50 translate-middle-y me-3" style="display: none;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category cards to where the users on what product/s they particularly want -->
    <div id="categories" class="row row-cols-2 row-cols-sm-2 row-cols-md-4 g-3 g-md-4 mb-5">
        <!-- All Items Card -->
        <div class="col">
            <div class="card h-100 border-3 category-card animate__animated animate__fadeInUp" 
                 data-category="all"
                 style="cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="ratio ratio-16x9">
                    <img src="assets/images/CHOOSE AND GO LOGO.jpg" 
                         alt="All Items" 
                         class="img-fluid" 
                         style="object-fit: cover;">
                </div>
                <div class="card-body text-center p-2 p-md-3">
                    <h3 class="h5 card-title mb-2">All Items</h3>
                    <p class="small card-text text-muted mb-2">
                        Browse our complete menu with all available items
                    </p>
                    <button class="btn btn-outline-primary btn-sm" 
                            onclick="filterByCategory('all')">
                        View All
                    </button>
                </div>
            </div>
        </div>

        <?php
        // Get categories with descriptions
        $categories = $pdo->query("SELECT * FROM CATEGORY ORDER BY ctgy_id");
        $categoryImages = [
            1 => 'assets/images/Meals.jpg',    // Meals
            2 => 'assets/images/Snacks.jpg',   // Snacks
            3 => 'assets/images/Drinks.jpg'    // Drinks
        ];
        
        while ($category = $categories->fetch()) {
            $imageUrl = $categoryImages[$category['ctgy_id']] ?? 'assets/images/placeholder.jpg';
            ?>
            <div class="col">
                <div class="card h-100 border-3 category-card animate__animated animate__fadeInUp" 
                     data-category="<?php echo $category['ctgy_id']; ?>"
                     style="cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="ratio ratio-16x9">
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" 
                             alt="<?php echo htmlspecialchars($category['ctgy_name']); ?>" 
                             class="img-fluid" 
                             style="object-fit: cover;">
                    </div>
                    <div class="card-body text-center p-2 p-md-3">
                        <h3 class="h5 card-title mb-2"><?php echo htmlspecialchars($category['ctgy_name']); ?></h3>
                        <p class="small card-text text-muted mb-2">
                            <?php echo htmlspecialchars($category['dsrpn'] ?? 'Delicious selection of ' . $category['ctgy_name']); ?>
                        </p>
                        <button class="btn btn-outline-primary btn-sm" 
                                onclick="filterByCategory(<?php echo $category['ctgy_id']; ?>)">
                            View All
                        </button>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- Menu Items -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4" id="menuItems">
        <?php
        $stmt = $pdo->query("SELECT m.*, c.ctgy_name 
                            FROM MENU_ITEM m 
                            JOIN CATEGORY c ON m.ctgy_id = c.ctgy_id 
                            WHERE m.avbl = 1 
                            ORDER BY c.ctgy_name, m.itm_nm");
        while ($item = $stmt->fetch()) {
            ?>
            <div class="col menu-item" data-category="<?php echo $item['ctgy_id']; ?>">
                <div class="card h-100">                    <img src="<?php echo htmlspecialchars($item['image_path'] ?? 'assets/images/CHOOSE AND GO LOGO.jpg', ENT_QUOTES, 'UTF-8'); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($item['itm_nm'], ENT_QUOTES, 'UTF-8'); ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item['itm_nm']; ?></h5>
                        <p class="card-text">
                            <small class="text-muted"><?php echo $item['ctgy_name']; ?></small>
                        </p>
                        <p class="card-text"><?php echo $item['dsrpn'] ?? ''; ?></p>
                        <p class="card-text"><strong>₱<?php echo number_format($item['prc'], 2); ?></strong></p>
                        <div class="mb-2">
                            <textarea class="form-control form-control-sm special-instructions mb-2" 
                                      placeholder="Special instructions (optional)" 
                                      rows="2"
                                      style="font-size: 0.8rem;"></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="input-group input-group-sm" style="max-width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="decrementQuantity(this)">-</button>
                                <input type="number" class="form-control text-center" value="1" min="1">
                                <button class="btn btn-outline-secondary" type="button" onclick="incrementQuantity(this)">+</button>
                            </div>
                            <button class="btn btn-primary" onclick="addToOrder(<?php echo $item['mn_itm_id']; ?>, event)">
                                Add to Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- Order Summary Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Summary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="emptyCart" class="text-center py-4" style="display: none;">
                        <i class="bi bi-cart-x" style="font-size: 3rem; color: #6c757d;"></i>
                        <h5 class="mt-3">Your cart is empty</h5>
                        <p>Add some items to get started!</p>
                    </div>
                    <div id="orderItemsContainer">
                    <form id="orderForm">
                        <?php if (isCustomer()): ?>
                            <div class="mb-3">
                                <label class="form-label">Customer</label>
                                <div class="form-control bg-light">
                                    <strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?><br>
                                    <strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>
                                </div>
                                <input type="hidden" id="customerName" value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>">
                                <input type="hidden" id="customerPhone" value="<?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>">
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="customerName" required>
                            </div>
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="customerPhone" required>
                            </div>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="orderItems"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td colspan="2"><strong id="orderTotal">₱0.00</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div class="btn-group w-100">
                                <input type="radio" class="btn-check" name="paymentMethod" id="cash" value="cash" checked>
                                <label class="btn btn-outline-primary" for="cash">Cash</label>
                                <input type="radio" class="btn-check" name="paymentMethod" id="gcash" value="gcash">
                                <label class="btn btn-outline-primary" for="gcash">GCash</label>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="modal-footer d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3">
                    <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto">
                        <button type="button" class="btn btn-outline-primary flex-grow-1" onclick="addMoreItems()">
                            <i class="bi bi-plus-circle me-1"></i> Add More Items
                        </button>
                        <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="modal" onclick="clearOrder()">
                            <i class="bi bi-x-circle me-1"></i> Cancel Order
                        </button>
                    </div>
                    <div class="w-100 w-sm-auto">
                        <button type="button" class="btn btn-primary place-order-btn w-100" onclick="showOrderConfirmation()">
                            <i class="bi bi-cart-check me-1"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="addedToCartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-cart-check me-2 text-success"></i>
            <strong class="me-auto">Added to Cart</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Item has been added to your cart!
        </div>
    </div>
</div>

<!-- Add custom JavaScript -->
<script>
// Global order items array
let orderItems = [];

// Function to update the cart badge
function updateCartBadge() {
    const totalItems = orderItems.reduce((total, item) => total + item.quantity, 0);
    const badge = document.getElementById('cartBadge');
    if (badge) {
        badge.textContent = totalItems;
        badge.style.display = totalItems > 0 ? 'block' : 'none';
    }
}

// Function to show the order modal
function showOrderModal() {
    updateOrderModal();
    const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
    orderModal.show();
}

function incrementQuantity(button) {
    const input = button.previousElementSibling;
    input.value = parseInt(input.value) + 1;
}

function decrementQuantity(button) {
    const input = button.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function addToOrder(menuItemId, event) {
    const card = event.target.closest('.card');
    const quantity = parseInt(card.querySelector('input[type="number"]').value);
    const name = card.querySelector('.card-title').textContent.trim();
    const price = parseFloat(card.querySelector('strong').textContent.replace(/[^0-9.-]+/g,""));
    const specialInstructions = card.querySelector('.special-instructions')?.value || '';
    
    // Reset input to 1 after adding to order
    card.querySelector('input[type="number"]').value = 1;
    
    // Check if item already exists in order
    const existingItem = orderItems.find(item => item.id == menuItemId && item.specialInstructions === specialInstructions);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        orderItems.push({
            id: menuItemId,
            name: name,
            quantity: quantity,
            price: price,
            specialInstructions: specialInstructions
        });
    }
    
    // Update the cart badge
    updateCartBadge();
    
    // Show success message
    const toast = new bootstrap.Toast(document.getElementById('addedToCartToast'));
    toast.show();
    
    // Update the order modal
    updateOrderModal();
}

function updateOrderModal() {
    const tbody = document.getElementById('orderItems');
    const emptyCart = document.getElementById('emptyCart');
    const orderTable = document.querySelector('#orderItemsContainer');
    
    // Update the cart badge whenever the order modal is updated
    updateCartBadge();
    
    // Show empty message if no items
    if (orderItems.length === 0) {
        emptyCart.style.display = 'block';
        orderTable.style.display = 'none';
        return;
    }
    
    // Show order items
    emptyCart.style.display = 'none';
    orderTable.style.display = 'table';
    
    tbody.innerHTML = '';
    let total = 0;
    
    orderItems.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        
        tbody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>
                    <div class="input-group input-group-sm" style="max-width: 120px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(${index}, -1)">-</button>
                        <input type="number" class="form-control text-center" value="${item.quantity}" min="1" onchange="updateItemQuantity(${index}, this.value)">
                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                </td>
                <td>₱${item.price.toFixed(2)}</td>
                <td>₱${subtotal.toFixed(2)}</td>
                <td>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control form-control-sm special-instructions" 
                               placeholder="Special instructions..." 
                               value="${item.specialInstructions || ''}"
                               onchange="updateSpecialInstructions(${index}, this.value)">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeItem(${index})">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </td>
            </tr>
        `;
    });
    
    document.getElementById('orderTotal').textContent = `₱${total.toFixed(2)}`;
}

function removeItem(index) {
    orderItems.splice(index, 1);
    updateOrderModal();
}

function updateSpecialInstructions(index, instructions) {
    if (orderItems[index]) {
        orderItems[index].specialInstructions = instructions;
    }
}

function updateItemQuantity(index, newQuantity) {
    if (orderItems[index]) {
        const quantity = parseInt(newQuantity);
        if (!isNaN(quantity) && quantity > 0) {
            orderItems[index].quantity = quantity;
            updateOrderModal();
        }
    }
}

function updateQuantity(index, change) {
    if (orderItems[index]) {
        const newQuantity = orderItems[index].quantity + change;
        if (newQuantity > 0) {
            orderItems[index].quantity = newQuantity;
            updateOrderModal();
        }
    }
}

function addMoreItems() {
    // Close the modal
    const orderModal = bootstrap.Modal.getInstance(document.getElementById('orderModal'));
    orderModal.hide();
    
    // Scroll to the menu items section
    const menuItemsSection = document.getElementById('menuItems');
    if (menuItemsSection) {
        menuItemsSection.scrollIntoView({ behavior: 'smooth' });
    }
}

function showThankYouMessage(message, isSuccess = true) {
    // Create or get the toast element
    let toastEl = document.getElementById('thankYouToast');
    if (!toastEl) {
        toastEl = document.createElement('div');
        toastEl.id = 'thankYouToast';
        toastEl.className = 'toast align-items-center text-white border-0';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.style.position = 'fixed';
        toastEl.style.top = '20px';
        toastEl.style.right = '20px';
        toastEl.style.zIndex = '1100';
        
        const toastContent = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="${isSuccess ? 'bi bi-check-circle-fill' : 'bi bi-info-circle-fill'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastEl.innerHTML = toastContent;
        document.body.appendChild(toastEl);
    }
    
    // Set the message and style
    const toastBody = toastEl.querySelector('.toast-body');
    const iconClass = isSuccess ? 'bi-check-circle-fill' : 'bi-info-circle-fill';
    toastBody.innerHTML = `<i class="bi ${iconClass} me-2"></i>${message}`;
    toastEl.className = `toast align-items-center text-white border-0 ${isSuccess ? 'bg-success' : 'bg-info'}`;
    
    // Show the toast
    const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
    toast.show();
    
    // Remove the toast from DOM after it's hidden
    toastEl.addEventListener('hidden.bs.toast', function() {
        toastEl.remove();
    });
}

// Function to show the order confirmation modal
function showOrderConfirmation() {
    // Check if there are items in the order
    if (orderItems.length === 0) {
        showThankYouMessage('Please add items to your order before placing an order.', false);
        return;
    }
    
    // Validate customer information for non-logged in users
    if (!isCustomerLoggedIn()) {
        const name = document.getElementById('customerName').value.trim();
        const phone = document.getElementById('customerPhone').value.trim();
        
        if (!name || !phone) {
            showThankYouMessage('Please enter your name and phone number', false);
            return;
        }
    }

    // Update the confirmation modal with order summary
    const confirmItemsContainer = document.getElementById('confirmOrderItems');
    confirmItemsContainer.innerHTML = '';
    
    let total = 0;
    orderItems.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const itemElement = document.createElement('div');
        itemElement.className = 'd-flex justify-content-between mb-2';
        itemElement.innerHTML = `
            <div>
                <div class="fw-medium">${item.quantity}x ${item.name}</div>
                ${item.specialInstructions ? `<small class="text-muted">${item.specialInstructions}</small>` : ''}
            </div>
            <div>₱${itemTotal.toFixed(2)}</div>
        `;
        confirmItemsContainer.appendChild(itemElement);
    });
    
    document.getElementById('confirmOrderTotal').textContent = `₱${total.toFixed(2)}`;
    
    // Reset the confirmation checkbox
    document.getElementById('confirmTerms').checked = false;
    
    // Show the confirmation modal
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmOrderModal'));
    confirmModal.show();
}

// Function to handle the actual order placement
function placeOrder() {
    // Hide the confirmation modal and remove backdrop
    const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmOrderModal'));
    confirmModal.hide();
    
    // Remove any existing modal backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Re-enable body scrolling
    document.body.style.overflow = '';

    const orderData = {
        items: orderItems,
        customerName: document.getElementById('customerName').value,
        customerPhone: document.getElementById('customerPhone').value,
        paymentMethod: document.querySelector('input[name="paymentMethod"]:checked').value,
        csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
    };

    // Show loading state
    const confirmBtn = document.getElementById('confirmOrderBtn');
    const originalBtnText = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

    fetch('modules/orders/place_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Failed to place order');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show thank you modal with order details
            const thankYouModal = new bootstrap.Modal(document.getElementById('thankYouModal'));
            const orderNumber = data.orderId ? data.orderId : '';
            
            // Update the thank you message with order details
            document.getElementById('thankYouOrderId').textContent = `#${orderNumber}`;
            
            // Show the thank you modal
            thankYouModal.show();
            
            // Reset the order
            orderItems = [];
            updateOrderModal();
            
            // Close the order modal if it's open
            const orderModal = bootstrap.Modal.getInstance(document.getElementById('orderModal'));
            if (orderModal) {
                orderModal.hide();
            }
            
            // Redirect to order history after 5 seconds if the page exists
            setTimeout(() => {
                if (typeof orderHistoryPageExists !== 'undefined' && orderHistoryPageExists) {
                    window.location.href = 'order_history.php';
                }
            }, 2000);
        } else {
            throw new Error(data.message || 'Failed to place order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showThankYouMessage('Error: ' + (error.message || 'Failed to place order. Please try again.'), false);
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
}

function addMoreItems() {
    // Just close the modal, keeping the current order items
    const modal = bootstrap.Modal.getInstance(document.getElementById('orderModal'));
    modal.hide();
}

function clearOrder() {
    if (confirm('Are you sure you want to clear your order?')) {
        orderItems = [];
        updateOrderModal();
        updateCartBadge();
    }
}

function updateQuantity(index, change) {
    const newQuantity = orderItems[index].quantity + change;
    if (newQuantity > 0) {
        orderItems[index].quantity = newQuantity;
        updateOrderModal();
    }
}

function updateItemQuantity(index, value) {
    const quantity = parseInt(value) || 1;
    if (quantity > 0) {
        orderItems[index].quantity = quantity;
        updateOrderModal();
    }
}

function isCustomerLoggedIn() {
    return <?php echo isset($_SESSION['cust_id']) ? 'true' : 'false'; ?>;
}

// Show order modal when clicking the cart button
function showOrderModal() {
    updateOrderModal();
    const modal = new bootstrap.Modal(document.getElementById('orderModal'));
    modal.show();
}

// Filter menu items by category
function searchMenuItems() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const menuItems = document.querySelectorAll('.menu-item');
    const loader = document.querySelector('.search-loader');
    
    if (loader) loader.style.display = 'block';
    
    menuItems.forEach(item => {
        const title = item.querySelector('.card-title')?.textContent.toLowerCase() || '';
        const description = item.querySelector('.card-text')?.textContent.toLowerCase() || '';
        const price = item.querySelector('.price')?.textContent.toLowerCase() || '';
        
        if (title.includes(searchTerm) || description.includes(searchTerm) || price.includes(searchTerm)) {
            item.style.display = '';
            item.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            item.style.display = 'none';
        }
    });
    
    if (loader) loader.style.display = 'none';
}

function filterByCategory(categoryId) {
    const menuItems = document.querySelectorAll('.menu-item');
    const buttons = document.querySelectorAll('[data-category]');
    const categoryCards = document.querySelectorAll('.category-card');
    
    // Update active button
    buttons.forEach(button => {
        if (button.dataset.category == categoryId || 
            (categoryId === 'all' && button.dataset.category === 'all')) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
    
    // Update active category card
    categoryCards.forEach(card => {
        if (card.dataset.category == categoryId || categoryId === 'all') {
            card.classList.add('border-3', 'border-primary');
            card.style.transform = 'translateY(-5px)';
        } else {
            card.classList.remove('border-3', 'border-primary');
            card.style.transform = 'none';
        }
    });
    
    // Show/hide menu items with animation
    menuItems.forEach(item => {
        if (categoryId === 'all' || item.dataset.category == categoryId) {
            item.style.display = 'block';
            item.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            item.style.display = 'none';
        }
    });
    
    // Scroll to menu section
    const menuSection = document.getElementById('menuItems');
    if (menuSection) {
        menuSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Add click event to category cards
document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            const categoryId = this.dataset.category;
            filterByCategory(categoryId);
        });
    });
    
    // Initialize category buttons
    const categoryButtons = document.querySelectorAll('[data-category]');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.dataset.category;
            filterByCategory(categoryId);
        });
    });
    
    // Add event listener for the place order button
    document.querySelector('.place-order-btn').addEventListener('click', function() {
        showOrderConfirmation();
    });

    // Add event listener for the confirm order button
    document.getElementById('confirmOrderBtn').addEventListener('click', function() {
        // Check if terms are confirmed
        if (!document.getElementById('confirmTerms').checked) {
            showThankYouMessage('Please confirm that all details are correct before placing your order.', false);
            return;
        }
        placeOrder();
    });

    // Add event listener for the thank you modal close
    document.getElementById('thankYouModal').addEventListener('hidden.bs.modal', function () {
        // Reset the order form
        orderItems = [];
        updateOrderModal();
        
        // Close and reset the order modal if it's open
        const orderModal = bootstrap.Modal.getInstance(document.getElementById('orderModal'));
        if (orderModal) {
            orderModal.hide();
        }
        
        // Remove any remaining modal backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Re-enable body scrolling
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Reset the order modal state
        const orderModalElement = document.getElementById('orderModal');
        if (orderModalElement) {
            orderModalElement.style.display = 'none';
            orderModalElement.classList.remove('show');
        }
        
        // Reset the confirmation modal state
        const confirmModalElement = document.getElementById('confirmOrderModal');
        if (confirmModalElement) {
            confirmModalElement.style.display = 'none';
            confirmModalElement.classList.remove('show');
        }
    });
});

</script>

<!-- Order Confirmation Modal -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-cart-check me-2"></i>Confirm Your Order</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="bi bi-cart-check text-primary" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Ready to place your order?</h4>
                    <p class="text-muted">Please review your order before confirming.</p>
                </div>
                <div class="order-summary bg-light p-3 rounded mb-3">
                    <h6 class="mb-3">Order Summary:</h6>
                    <div id="confirmOrderItems" class="mb-2"></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="confirmOrderTotal">₱0.00</span>
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmTerms" required>
                    <label class="form-check-label small" for="confirmTerms">
                        I confirm that all details are correct
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left me-1"></i> Back to Cart
                </button>
                <button type="button" id="confirmOrderBtn" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Confirm & Place Order
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Thank You Modal -->
<div class="modal fade" id="thankYouModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="checkmark-circle">
                        <div class="checkmark"></div>
                    </div>
                </div>
                <h3 class="mb-3">Thank You!</h3>
                <p class="text-muted mb-4" id="thankYouMessage">Your order has been placed successfully!</p>
                <div class="order-details bg-light p-3 rounded mb-4">
                    <p class="mb-1">Order ID: <strong id="thankYouOrderId">#12345</strong></p>
                    <p class="mb-0">Estimated Time: <strong>15-20 minutes</strong></p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="bi bi-house-door me-2"></i>Back to Home
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.checkmark-circle {
    width: 80px;
    height: 80px;
    position: relative;
    display: inline-block;
    vertical-align: top;
}

.checkmark-circle .checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: block;
    stroke: #4bb71b;
    stroke-width: 5;
    stroke-miterlimit: 10;
    margin: 10% auto;
    box-shadow: inset 0px 0px 0px #4bb71b;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}

.checkmark-circle .checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}

@keyframes scale {
    0%, 100% {
        transform: none;
    }
    50% {
        transform: scale3d(1.1, 1.1, 1);
    }
}

@keyframes fill {
    100% {
        box-shadow: inset 0px 0px 0px 40px #4bb71b;
    }
}
</style>

<?php require_once 'views/footer.php'; ?>
