const defaultConfig = {
    site_slogan: "Kualitas Premium, Harga Terjangkau - Pilihan Cerdas untuk Teknologi Masa Depan",
    about_title: "Tentang e-PHONE SHOP",
    about_description: "e-PHONE SHOP adalah toko smartphone terpercaya yang telah melayani ribuan pelanggan di seluruh Indonesia. Kami menyediakan berbagai pilihan smartphone dari brand ternama dengan harga kompetitif dan garansi resmi. Komitmen kami adalah memberikan produk berkualitas tinggi dengan pelayanan terbaik untuk kepuasan pelanggan.",
    contact_title: "Hubungi Kami",
    background_color: "#1a1a1a",
    surface_color: "#2d2d2d",
    text_color: "#ffffff",
    primary_action_color: "#ffd700",
    secondary_action_color: "#ffed4e"
};

// --- DATA PRODUK DARI API --- //
let products = [];
let cart = [];
let selectedProduct = null;
let selectedColorIndex = 0;

// Variabel untuk seri dan filter aktif //
const series = ['Semua', 'C', 'F', 'M', 'X']; 
let activeFilter = 'Semua'; 

// --- API FUNCTIONS --- //

/**
 * Fetch products from API
 */
async function fetchProducts(seriesFilter = null) {
    try {
        let url = 'api/products.php';
        if (seriesFilter && seriesFilter !== 'Semua') {
            url += '?series=' + seriesFilter;
        }
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            products = data.data.map(product => ({
                id: product.id,
                name: product.name,
                price: formatPrice(product.price),
                specs: product.specs,
                colors: product.colors,
                detailSpecs: product.specs,
                seri: product.series
            }));
            renderProducts(seriesFilter);
        } else {
            console.error('Failed to fetch products:', data.message);
            // Fallback to empty array
            products = [];
        }
    } catch (error) {
        console.error('Error fetching products:', error);
        products = [];
    }
}

/**
 * Format price to Indonesian Rupiah
 */
function formatPrice(price) {
    return 'Rp ' + parseInt(price).toLocaleString('id-ID');
}

// Fungsi untuk merender tombol filter kategori //
function renderSeriFilters() {
    const filterContainer = document.getElementById('seriFilter');
    filterContainer.innerHTML = ''; 

    series.forEach(seri => {
        const button = document.createElement('button');
        button.textContent = seri === 'Semua' ? 'Semua Produk' : `SERI ${seri}`;
        button.classList.add('filter-button');
        if (seri === activeFilter) {
            button.classList.add('active');
        }
        button.dataset.seri = seri;
        button.addEventListener('click', () => {
            activeFilter = seri;
            renderSeriFilters(); 
            fetchProducts(seri); 
        });
        filterContainer.appendChild(button);
    });
}

// RenderProducts untuk menerima filter dan menampilkan kategori //
function renderProducts(filterSeri = activeFilter) {
    const grid = document.getElementById('productsGrid');
    
    if (products.length === 0) {
        grid.innerHTML = '<p style="text-align: center; color: #888;">Tidak ada produk tersedia</p>';
        return;
    }
    
    // Filter produk berdasarkan kategori //
    const filteredProducts = products.filter(product => 
        filterSeri === 'Semua' || product.seri === filterSeri
    );

    // Render produk yang telah difilter //
    grid.innerHTML = filteredProducts.map(product => `
        <div class="product-card" data-product-id="${product.id}">
            <div class="product-image">
                <img src="${product.colors[0]?.imageUrl || 'assets/images/placeholder.png'}" alt="Gambar ${product.name} ${product.colors[0]?.name || ''}">
            </div>
            <h3 class="product-name">${product.name}</h3>
            <p class="product-specs">${product.specs}</p>
            <div class="product-seri">Seri: ${product.seri}</div>
            <div class="product-price">${product.price}</div>
            <button class="add-to-cart" data-product-id="${product.id}">Tambah ke Keranjang</button>
        </div>
    `).join('');

    // Tambahkan event listeners //
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (!e.target.classList.contains('add-to-cart')) {
                const productId = parseInt(card.dataset.productId);
                showProductDetail(productId, 0);
            }
        });
    });

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const productId = parseInt(btn.dataset.productId);
            addToCart(productId, 0);
        });
    });
}

function showProductDetail(productId, defaultColorIndex = 0) {
    selectedProduct = products.find(p => p.id === productId);
    if (!selectedProduct) return;
    selectedColorIndex = defaultColorIndex;

    const renderModalContent = () => {
        const currentColor = selectedProduct.colors[selectedColorIndex];
        const modalImageUrl = currentColor ? currentColor.imageUrl : 'assets/images/placeholder.png';

        document.getElementById('modalImage').innerHTML = `<img src="${modalImageUrl}" alt="Detail ${selectedProduct.name} - ${currentColor?.name || ''}">`;
        document.getElementById('modalTitle').textContent = `${selectedProduct.name} - ${currentColor?.name || ''}`;
        document.getElementById('modalPrice').textContent = selectedProduct.price;

        const colorOptionsHTML = `
            <div class="color-options">
                ${selectedProduct.colors.map((color, index) => `
                    <button 
                        class="color-swatch ${index === selectedColorIndex ? 'selected' : ''}" 
                        data-index="${index}" 
                        title="${color.name}" 
                        style="background-image: url('${color.imageUrl}');"
                    ></button>
                `).join('')}
            </div>
        `;
        document.getElementById('colorSelection').innerHTML = colorOptionsHTML;

        document.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.addEventListener('click', function() {
                selectedColorIndex = parseInt(this.dataset.index);
                renderModalContent();
            });
        });

        const detailSpecsHTML = `
            <ul>
                ${selectedProduct.detailSpecs.map(spec => `<li>${spec}</li>`).join('')}
            </ul>
        `;
        document.getElementById('detailSpecs').innerHTML = detailSpecsHTML;
    };

    renderModalContent();
    document.getElementById('productDetailModal').classList.add('active');
}

function closeProductDetail() {
    document.getElementById('productDetailModal').classList.remove('active');
    selectedProduct = null;
    selectedColorIndex = 0;
}

document.getElementById('modalCloseBtn').addEventListener('click', closeProductDetail);
document.getElementById('modalAddToCartBtn').addEventListener('click', () => {
    if (selectedProduct) {
        addToCart(selectedProduct.id, selectedColorIndex);
        closeProductDetail();
    }
});

function addToCart(productId, colorIndex) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    const selectedColor = product.colors[colorIndex] || product.colors[0];
    if (!selectedColor) {
        alert('Produk tidak tersedia dalam warna ini');
        return;
    }
    
    // Check if item already exists in cart
    const existingItem = cart.find(item => 
        item.productId === product.id && item.colorName === selectedColor.name
    );
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        const cartItem = {
            id: Date.now(), 
            productId: product.id,
            name: product.name,
            price: product.price,
            colorName: selectedColor.name,
            colorImageUrl: selectedColor.imageUrl,
            quantity: 1
        };
        cart.push(cartItem);
    }
    
    updateCartDisplay();
    
    // Show success notification
    showNotification(`${product.name} (${selectedColor.name}) ditambahkan ke keranjang!`);
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function updateCartDisplay() {
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cartCount').textContent = cartCount;

    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotalElement = document.getElementById('cartTotal');
    let total = 0;

    cartItemsContainer.innerHTML = cart.map(item => {
        // Konversi harga string ke angka untuk perhitungan total //
        const priceString = item.price.replace(/[Rp.\s]/g, '');
        const priceValue = parseInt(priceString);
        total += priceValue * item.quantity;

        return `
            <div class="cart-item" data-item-id="${item.id}">
                <div class="item-image">
                    <img src="${item.colorImageUrl}" alt="${item.name}">
                </div>
                <div class="item-info">
                    <h4 class="item-name">${item.name} (${item.colorName})</h4>
                    <p class="item-price">${item.price}</p>
                    <div class="item-quantity-control">
                        <button class="quantity-minus" data-id="${item.id}">-</button>
                        <span class="quantity-value">${item.quantity}</span>
                        <button class="quantity-plus" data-id="${item.id}">+</button>
                    </div>
                </div>
                <button class="remove-item" data-id="${item.id}">✕</button>
            </div>
        `;
    }).join('');

    // Format ulang total menjadi mata uang //
    const totalFormatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(total);

    cartTotalElement.textContent = totalFormatted;

    // Tambahkan event listener untuk tombol quantity dan remove //
    document.querySelectorAll('.quantity-minus').forEach(btn => {
        btn.addEventListener('click', () => updateQuantity(parseInt(btn.dataset.id), -1));
    });
    document.querySelectorAll('.quantity-plus').forEach(btn => {
        btn.addEventListener('click', () => updateQuantity(parseInt(btn.dataset.id), 1));
    });
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', () => removeItem(parseInt(btn.dataset.id)));
    });

    // Tampilkan/sembunyikan tombol checkout //
    const checkoutBtn = document.getElementById('checkoutBtn');
    checkoutBtn.style.display = cart.length > 0 ? 'block' : 'none';
}

function updateQuantity(itemId, change) {
    const itemIndex = cart.findIndex(item => item.id === itemId);
    if (itemIndex > -1) {
        cart[itemIndex].quantity += change;
        if (cart[itemIndex].quantity <= 0) {
            removeItem(itemId);
        } else {
            updateCartDisplay();
        }
    }
}

function removeItem(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    updateCartDisplay();
    if (cart.length === 0) {
        closeCartModal();
    }
}

function setupCartModal() {
    const cartModal = document.getElementById('cartModal');
    const cartBtn = document.getElementById('cartBtn');
    const closeBtn = document.getElementById('cartCloseBtn');
    const checkoutBtn = document.getElementById('checkoutBtn');

    cartBtn.addEventListener('click', () => {
        cartModal.classList.add('active');
        updateCartDisplay();
    });
    closeBtn.addEventListener('click', closeCartModal);
    checkoutBtn.addEventListener('click', () => {
        closeCartModal();
        showCheckoutModal();
    });
}

function closeCartModal() {
    document.getElementById('cartModal').classList.remove('active');
}

function showCheckoutModal() {
    if (cart.length === 0) return;
    document.getElementById('checkoutModal').classList.add('active');
    
    const checkoutSummary = document.getElementById('checkoutSummary');
    let total = 0;
    
    const summaryHTML = cart.map(item => {
        const priceString = item.price.replace(/[Rp.\s]/g, '');
        const priceValue = parseInt(priceString);
        const itemTotal = priceValue * item.quantity;
        total += itemTotal;
        
        const itemTotalFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(itemTotal);

        return `
            <p>${item.name} (${item.colorName}) x ${item.quantity} - <span>${itemTotalFormatted}</span></p>
        `;
    }).join('');
    
    const totalFormatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(total);

    checkoutSummary.innerHTML = `
        <div class="checkout-summary-list">${summaryHTML}</div>
        <div class="checkout-total">Total Pembayaran: <span>${totalFormatted}</span></div>
    `;
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.remove('active');
}

function setupCheckoutModal() {
    const checkoutForm = document.getElementById('checkoutForm');
    const cancelCheckoutBtn = document.getElementById('cancelCheckoutBtn');

    cancelCheckoutBtn.addEventListener('click', closeCheckoutModal);

    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const customerName = document.getElementById('customerName').value.trim();
        const customerPhone = document.getElementById('customerPhone').value.trim();
        const customerAddress = document.getElementById('customerAddress').value.trim();
        const paymentMethod = document.getElementById('paymentMethod').value;
        const additionalNotes = document.getElementById('keteranganTambahan').value.trim();
        
        // Validate form
        if (!customerName || !customerPhone || !customerAddress || !paymentMethod) {
            alert('Mohon lengkapi semua field yang wajib diisi!');
            return;
        }
        
        // Prepare items data
        const items = cart.map(item => {
            const priceString = item.price.replace(/[Rp.\s]/g, '');
            return {
                product_id: item.productId,
                product_name: item.name,
                color_name: item.colorName,
                price: parseInt(priceString),
                quantity: item.quantity
            };
        });
        
        // Show loading state
        const submitBtn = document.getElementById('confirmCheckoutBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Memproses...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch('api/checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    items: items,
                    customer_name: customerName,
                    customer_phone: customerPhone,
                    customer_address: customerAddress,
                    payment_method: paymentMethod,
                    additional_notes: additionalNotes
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Reset keranjang //
                cart = [];
                updateCartDisplay();
                closeCheckoutModal();

                // Tampilkan Pop-up Sukses //
                showSuccessPopup(customerName);
                this.reset();
            } else {
                alert(data.message || 'Gagal memproses pesanan. Silakan coba lagi.');
            }
        } catch (error) {
            console.error('Checkout error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });
}

function showSuccessPopup(customerName) {
    const successPopup = document.getElementById('successPopup');
    document.getElementById('successCustomerName').textContent = customerName;
    successPopup.classList.add('active');
    
    // Auto-close after 5 seconds //
    setTimeout(() => {
        successPopup.classList.remove('active');
    }, 5000);
}

function setupContactForm() {
    const contactForm = document.getElementById('contactForm');
    const successMessage = document.getElementById('contactSuccess');
    
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();
        
        // Sembunyikan pesan sukses sebelumnya //
        successMessage.style.display = 'none';
        
        // Validate
        if (!name || !email || !message) {
            alert('Mohon lengkapi semua field!');
            return;
        }
        
        try {
            const response = await fetch('api/contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    message: message
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Tampilkan pesan sukses //
                successMessage.style.display = 'block';
                
                // Kosongkan formulir //
                contactForm.reset();
                
                // Sembunyikan pesan sukses setelah beberapa detik //
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            } else {
                alert(data.message || 'Gagal mengirim pesan. Silakan coba lagi.');
            }
        } catch (error) {
            console.error('Contact form error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
}

function applyDefaultConfig() {
    document.getElementById('heroSlogan').textContent = defaultConfig.site_slogan;
    document.getElementById('aboutTitle').textContent = defaultConfig.about_title;
    document.getElementById('aboutDescription').textContent = defaultConfig.about_description;
    document.getElementById('contactTitle').textContent = defaultConfig.contact_title;

    const root = document.documentElement;
    root.style.setProperty('--background-color', defaultConfig.background_color);
    root.style.setProperty('--surface-color', defaultConfig.surface_color);
    root.style.setProperty('--text-color', defaultConfig.text_color);
    root.style.setProperty('--primary-action-color', defaultConfig.primary_action_color);
    root.style.setProperty('--secondary-action-color', defaultConfig.secondary_action_color);
}

// Pemanggilan fungsi filter saat DOMContentLoaded //
document.addEventListener('DOMContentLoaded', () => {
    applyDefaultConfig();
    renderSeriFilters(); 
    fetchProducts(); // Fetch products from API
    setupCartModal();
    setupCheckoutModal();
    setupContactForm();
});

// Add CSS animation for notification
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
