@extends('layouts.app')

@section('title', 'Buat Pesanan - Geolokasi UMKM Kuliner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-plus me-2 text-primary"></i>
            Buat Pesanan Baru
        </h2>
        <p class="text-muted mb-0">Pilih outlet dan menu untuk membuat pesanan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Pesanan
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Outlet Selection -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-store me-2"></i>
                    Pilih Outlet
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" id="outletSearch" class="form-control" placeholder="Cari outlet...">
                </div>
                
                <div class="outlet-list" style="max-height: 400px; overflow-y: auto;">
                    @foreach($outlets as $outlet)
                    <div class="outlet-item card mb-2 cursor-pointer" 
                         data-outlet-id="{{ $outlet->id }}"
                         data-outlet-name="{{ $outlet->name }}"
                         data-outlet-address="{{ $outlet->address }}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    @if($outlet->image)
                                        <img src="{{ asset('storage/' . $outlet->image) }}" 
                                             alt="{{ $outlet->name }}" 
                                             class="rounded" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-store text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $outlet->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($outlet->address, 40) }}
                                    </small>
                                    <br>
                                    <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }} mt-1">
                                        {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Selection -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-utensils me-2"></i>
                    Pilih Menu
                    <span id="selectedOutletName" class="text-muted ms-2"></span>
                </h6>
            </div>
            <div class="card-body">
                <div id="menuSection" class="d-none">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <input type="text" id="menuSearch" class="form-control" placeholder="Cari menu...">
                        </div>
                        <div class="col-md-6">
                            <select id="categoryFilter" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="menuList" class="row g-3">
                        <!-- Menu items will be loaded here -->
                    </div>
                </div>
                
                <div id="noOutletSelected" class="text-center py-5">
                    <i class="fas fa-store fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Pilih Outlet Terlebih Dahulu</h5>
                    <p class="text-muted">Pilih outlet untuk melihat menu yang tersedia</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Keranjang Pesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cartItems">
                    <!-- Cart items will be displayed here -->
                </div>
                
                <div class="cart-summary border-top pt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="orderNotes" class="form-label">Catatan Pesanan</label>
                                <textarea id="orderNotes" class="form-control" rows="3" placeholder="Tambahkan catatan khusus untuk pesanan..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <h6 class="mb-2">Total Item: <span id="totalItems">0</span></h6>
                                <h4 class="text-primary mb-0">Total: Rp <span id="totalAmount">0</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="placeOrderBtn" class="btn btn-primary" disabled>
                    <i class="fas fa-check me-2"></i>Buat Pesanan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cart Button -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1000;">
    <button type="button" id="cartBtn" class="btn btn-primary btn-lg rounded-circle shadow" 
            data-bs-toggle="modal" data-bs-target="#cartModal" disabled>
        <i class="fas fa-shopping-cart"></i>
        <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
            0
        </span>
    </button>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 15px 15px 0 0 !important;
}

.btn {
    border-radius: 8px;
}

.outlet-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.outlet-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.outlet-item.selected {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

.menu-item {
    transition: all 0.3s ease;
}

.menu-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dee2e6;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.quantity-btn:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 0.25rem;
}

.cursor-pointer {
    cursor: pointer;
}

.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    border-radius: 15px 15px 0 0;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    border-radius: 0 0 15px 15px;
}

#cartBtn {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#cartBadge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
let selectedOutletId = null;
let cart = [];
let menus = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    loadMenus();
});

function initializeEventListeners() {
    // Outlet selection
    document.querySelectorAll('.outlet-item').forEach(item => {
        item.addEventListener('click', function() {
            selectOutlet(this);
        });
    });
    
    // Search functionality
    document.getElementById('outletSearch').addEventListener('input', filterOutlets);
    document.getElementById('menuSearch').addEventListener('input', filterMenus);
    document.getElementById('categoryFilter').addEventListener('change', filterMenus);
    
    // Place order
    document.getElementById('placeOrderBtn').addEventListener('click', placeOrder);
}

function selectOutlet(outletElement) {
    // Remove previous selection
    document.querySelectorAll('.outlet-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selection to clicked item
    outletElement.classList.add('selected');
    
    // Get outlet data
    selectedOutletId = outletElement.dataset.outletId;
    const outletName = outletElement.dataset.outletName;
    
    // Update UI
    document.getElementById('selectedOutletName').textContent = `- ${outletName}`;
    document.getElementById('menuSection').classList.remove('d-none');
    document.getElementById('noOutletSelected').classList.add('d-none');
    
    // Load menus for selected outlet
    loadMenusForOutlet(selectedOutletId);
}

function filterOutlets() {
    const searchTerm = document.getElementById('outletSearch').value.toLowerCase();
    document.querySelectorAll('.outlet-item').forEach(item => {
        const name = item.dataset.outletName.toLowerCase();
        const address = item.dataset.outletAddress.toLowerCase();
        
        if (name.includes(searchTerm) || address.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function loadMenusForOutlet(outletId) {
    // Filter menus by outlet
    const outletMenus = menus.filter(menu => menu.outlet_id == outletId);
    displayMenus(outletMenus);
}

function filterMenus() {
    if (!selectedOutletId) return;
    
    const searchTerm = document.getElementById('menuSearch').value.toLowerCase();
    const categoryId = document.getElementById('categoryFilter').value;
    
    let filteredMenus = menus.filter(menu => menu.outlet_id == selectedOutletId);
    
    if (searchTerm) {
        filteredMenus = filteredMenus.filter(menu => 
            menu.name.toLowerCase().includes(searchTerm) ||
            menu.description.toLowerCase().includes(searchTerm)
        );
    }
    
    if (categoryId) {
        filteredMenus = filteredMenus.filter(menu => menu.category_id == categoryId);
    }
    
    displayMenus(filteredMenus);
}

function displayMenus(menusToShow) {
    const menuList = document.getElementById('menuList');
    menuList.innerHTML = '';
    
    if (menusToShow.length === 0) {
        menuList.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada menu</h5>
                <p class="text-muted">Tidak ada menu yang tersedia untuk outlet ini</p>
            </div>
        `;
        return;
    }
    
    menusToShow.forEach(menu => {
        const menuCard = createMenuCard(menu);
        menuList.appendChild(menuCard);
    });
}

function createMenuCard(menu) {
    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4';
    
    const cartItem = cart.find(item => item.menu_id === menu.id);
    const quantity = cartItem ? cartItem.quantity : 0;
    
    col.innerHTML = `
        <div class="menu-item card h-100">
            <div class="card-body p-3">
                <div class="text-center mb-3">
                    ${menu.image ? 
                        `<img src="/storage/${menu.image}" alt="${menu.name}" class="rounded" style="width: 100%; height: 150px; object-fit: cover;">` :
                        `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 100%; height: 150px;">
                            <i class="fas fa-utensils fa-3x text-muted"></i>
                        </div>`
                    }
                </div>
                <h6 class="mb-2">${menu.name}</h6>
                <p class="text-muted small mb-2">${menu.description || 'Tidak ada deskripsi'}</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-primary">Rp ${numberFormat(menu.price)}</span>
                    <span class="badge bg-secondary">${menu.category?.name || 'Umum'}</span>
                </div>
                
                <div class="quantity-controls">
                    <button type="button" class="quantity-btn" onclick="decreaseQuantity(${menu.id})" ${quantity === 0 ? 'disabled' : ''}>
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" class="quantity-input" value="${quantity}" min="0" 
                           onchange="updateQuantity(${menu.id}, this.value)">
                    <button type="button" class="quantity-btn" onclick="increaseQuantity(${menu.id})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    return col;
}

function increaseQuantity(menuId) {
    const menu = menus.find(m => m.id === menuId);
    if (!menu) return;
    
    const cartItem = cart.find(item => item.menu_id === menuId);
    if (cartItem) {
        cartItem.quantity++;
    } else {
        cart.push({
            menu_id: menuId,
            menu: menu,
            quantity: 1,
            price: menu.price,
            notes: ''
        });
    }
    
    updateCart();
    updateMenuDisplay(menuId);
}

function decreaseQuantity(menuId) {
    const cartItem = cart.find(item => item.menu_id === menuId);
    if (!cartItem || cartItem.quantity <= 1) {
        removeFromCart(menuId);
    } else {
        cartItem.quantity--;
        updateCart();
        updateMenuDisplay(menuId);
    }
}

function updateQuantity(menuId, newQuantity) {
    const quantity = parseInt(newQuantity) || 0;
    
    if (quantity <= 0) {
        removeFromCart(menuId);
    } else {
        const cartItem = cart.find(item => item.menu_id === menuId);
        if (cartItem) {
            cartItem.quantity = quantity;
        } else {
            const menu = menus.find(m => m.id === menuId);
            if (menu) {
                cart.push({
                    menu_id: menuId,
                    menu: menu,
                    quantity: quantity,
                    price: menu.price,
                    notes: ''
                });
            }
        }
        updateCart();
        updateMenuDisplay(menuId);
    }
}

function removeFromCart(menuId) {
    cart = cart.filter(item => item.menu_id !== menuId);
    updateCart();
    updateMenuDisplay(menuId);
}

function updateMenuDisplay(menuId) {
    const menuCard = document.querySelector(`[onclick*="decreaseQuantity(${menuId})"]`).closest('.col-md-6');
    if (menuCard) {
        const menu = menus.find(m => m.id === menuId);
        const cartItem = cart.find(item => item.menu_id === menuId);
        const quantity = cartItem ? cartItem.quantity : 0;
        
        // Update quantity input
        const quantityInput = menuCard.querySelector('.quantity-input');
        quantityInput.value = quantity;
        
        // Update decrease button state
        const decreaseBtn = menuCard.querySelector('[onclick*="decreaseQuantity"]');
        decreaseBtn.disabled = quantity === 0;
    }
}

function updateCart() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    // Update cart badge
    const cartBadge = document.getElementById('cartBadge');
    if (totalItems > 0) {
        cartBadge.textContent = totalItems;
        cartBadge.classList.remove('d-none');
        document.getElementById('cartBtn').disabled = false;
    } else {
        cartBadge.classList.add('d-none');
        document.getElementById('cartBtn').disabled = true;
    }
    
    // Update cart modal
    updateCartModal();
    
    // Update place order button
    document.getElementById('placeOrderBtn').disabled = totalItems === 0;
}

function updateCartModal() {
    const cartItems = document.getElementById('cartItems');
    const totalItems = document.getElementById('totalItems');
    const totalAmount = document.getElementById('totalAmount');
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Keranjang Kosong</h5>
                <p class="text-muted">Pilih menu untuk ditambahkan ke keranjang</p>
            </div>
        `;
        totalItems.textContent = '0';
        totalAmount.textContent = '0';
        return;
    }
    
    let cartHTML = '';
    cart.forEach((item, index) => {
        cartHTML += `
            <div class="d-flex align-items-center py-3 ${index < cart.length - 1 ? 'border-bottom' : ''}">
                <div class="flex-shrink-0 me-3">
                    ${item.menu.image ? 
                        `<img src="/storage/${item.menu.image}" alt="${item.menu.name}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">` :
                        `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-utensils text-muted"></i>
                        </div>`
                    }
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${item.menu.name}</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn btn-sm" onclick="decreaseQuantity(${item.menu_id})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="fw-bold">${item.quantity}x</span>
                            <button type="button" class="quantity-btn btn-sm" onclick="increaseQuantity(${item.menu_id})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <span class="fw-bold text-primary">Rp ${numberFormat(item.price)}</span>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary mb-1">Rp ${numberFormat(item.price * item.quantity)}</div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.menu_id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    cartItems.innerHTML = cartHTML;
    totalItems.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
    totalAmount.textContent = numberFormat(cart.reduce((sum, item) => sum + (item.price * item.quantity), 0));
}

function placeOrder() {
    if (!selectedOutletId || cart.length === 0) {
        alert('Pilih outlet dan menu terlebih dahulu');
        return;
    }
    
    const orderData = {
        outlet_id: selectedOutletId,
        items: cart.map(item => ({
            menu_id: item.menu_id,
            quantity: item.quantity,
            price: item.price,
            notes: item.notes
        })),
        notes: document.getElementById('orderNotes').value,
        total_amount: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)
    };
    
    // Send order to server
    fetch('/orders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pesanan berhasil dibuat!');
            // Reset cart and redirect
            cart = [];
            updateCart();
            window.location.href = '/orders';
        } else {
            alert('Gagal membuat pesanan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membuat pesanan');
    });
}

function loadMenus() {
    // Load menus from server
    fetch('/api/menus')
        .then(response => response.json())
        .then(data => {
            menus = data.data || [];
        })
        .catch(error => {
            console.error('Error loading menus:', error);
        });
}

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}
</script>
@endpush
