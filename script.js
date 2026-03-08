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

// --- DATA PRODDUK --- //
const products = [
    // --- SERI C --- //
    {
        id: 1,
        name: "POCO C71",
        price: "Rp 1.049.000",
        specs: "4GB/8GB RAM, 128GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI C/C71/C71-BLACK.png" },
            { name: "Blue", imageUrl: "assets/images/POCO SERI C/C71/C71-BLUE.png" },
            { name: "Gold", imageUrl: "assets/images/POCO SERI C/C71/C71-GOLD.png" }
        ],
        detailSpecs: ["Layar:  6,88 inch 120Hz", "Processor: UNISOC T7250", "RAM: 4GB/8GB ", "Storage: 128GB", "Kamera: 32MP + 8MP", "Baterai: 5200 mAh, 15W Fast Charging"],
        seri: "C" 
    },
    {
        id: 2,
        name: "POCO C75",
        price: "Rp 1.349.000",
        specs: "6GB/8GB RAM, 128GB/256GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI C/C75/C75-BLACK.png" },
            { name: "Green", imageUrl: "assets/images/POCO SERI C/C75/C75-GREEN.png" },
        ],
        detailSpecs: ["Layar: 6,88 inch 120 Hz", "Processor: MediaTek Helio G81 Ultra", "RAM: 6GB/8GB", "Storage: 128GB/256GB", "Kamera: 50MP + 13MP", "Baterai: 5160 mAh, 18W Fast Charging"],
        seri: "C" 
    },
    {
        id: 3,
        name: "POCO C85",
        price: "Rp 1.499.000",
        specs: "6GB/8GB RAM, 128GB/256GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI C/C85/C85-BLACK.png" },
            { name: "Green", imageUrl: "assets/images/POCO SERI C/C85/C85-GREEN.png" },
            { name: "Purple", imageUrl: "assets/images/POCO SERI C/C85/C85-PURPLE.png" }
        ],
        detailSpecs: ["Layar: 6,9 inch 120Hz", "Processor: MediaTek Helio G81-Ultra.", "RAM: 6GB/8GB", "Storage: 128GB/256GB", "Kamera: 50MP + 8MP", "Baterai: 6000 mAh, 33W Fast Charging"],
        seri: "C" 
    },
    // --- SERI F --- //
    {
        id: 4,
        name: "POCO F6",
        price: "Rp 4.549.000",
        specs: " 12GB RAM, 512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI F/F6/F6-BLACK.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI F/F6/F6-SILVER.png" },
            { name: "Titanium", imageUrl: "assets/images/POCO SERI F/F6/F6-TITANIUM.png" }
        ],
        detailSpecs: ["Layar: 6,67 inci AMOLED 120Hz", "Processor: Snapdragon 8s Gen 3", "RAM:  12GB", "Storage: 512GB", "Kamera: 50MP + 8MP + 20MP", "Baterai: baterai 5000 mAh, 90W Fast Charging"],
        seri: "F" 
    },
    {
        id: 5,
        name: "POCO F6 PRO",
        price: "Rp 9.499.000",
        specs: "12GB/16GB RAM, 256GB/512GB/1TB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI F/F6 PRO/F6 PRO-BLACK.png" },
            { name: "White", imageUrl: "assets/images/POCO SERI F/F6 PRO/F6 PRO-WHITE.png" },
        ],
        detailSpecs: ["Layar: 6,67 inch AMOLED 120Hz", "Processor: Snapdragon 8s Gen 3", "RAM: 12GB/16GB", "Storage: 256GB/512GB/1TB", "Kamera: 50MP + 8MP + 2MP", "Baterai: 5000mAh, 120W Fast Charging"],
        seri: "F" 
    },
    {
        id: 6,
        name: "POCO F7",
        price: "Rp 5.999.000",
        specs: "12GB RAM, 256GB/512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI F/F7/F7-BLACK.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI F/F7/F7-SILVER.png" },
            { name: "White", imageUrl: "assets/images/POCO SERI F/F7/F7-WHITE.png" }
        ],
        detailSpecs: ["Layar: 6,83 inch AMOLED 120Hz 1,5K", "Processor: Snapdragon 8s Gen 4", "RAM: 12GB", "Storage: 256GB/512GB", "Kamera: 50MP + 8MP + 20MP", "Baterai: 6.500 mAh, 90W Fast Charging"],
        seri: "F" 
    },
    {
        id: 7,
        name: "POCO F7 PRO",
        price: "Rp 7.499.000",
        specs: "12GB RAM, 256GB/512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI F/F7 PRO/F7 PRO-BLACK.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI F/F7 PRO/F7 PRO-SILVER.png" },
        ],
        detailSpecs: ["Layar: 6,67 inch AMOLED 2K 120Hz", "Processor: Snapdragon 8 Gen 3", "RAM: 12GB", "Storage: 256GB/512GB", "Kamera: 50MP + 8MP + 20MP", "Baterai: 6000mAh, 90W Fast Charging"],
        seri: "F" 
    },
    {
        id: 8,
        name: "POCO F7 ULTRA",
        price: "Rp 9.799.000",
        specs: "12GB/16GB RAM, 256GB/512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI F/F7 ULTRA/F7 ULTRA-BLACK.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI F/F7 ULTRA/F7 ULTRA-SILVER.png" },
            { name: "Yellow", imageUrl: "assets/images/POCO SERI F/F7 ULTRA/F7 ULTRA-YELLOW.png" }
        ],
        detailSpecs: ["Layar: 6.67 inch AMOLED 2K 120Hz", "Processor: Snapdragon 8 Elite", "RAM: 12GB/16GB", "Storage: 256GB/512GB", "Kamera: 50MP + 50MP + 32MP", "Baterai: 5300mAh, 120W Fast Charging"],
        seri: "F" 
    },
    // --- SERI M --- //
    {
        id: 9,
        name: "POCO M6",
        price: "Rp 1.899.000",
        specs: "8GB RAM, 256GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI M/M6/M6-BLACK.png" },
            { name: "Purple", imageUrl: "assets/images/POCO SERI M/M6/M6-PURPLE.png" },
            { name: "White", imageUrl: "assets/images/POCO SERI M/M6/M6-WHITE.png" }
        ],
        detailSpecs: ["Layar:  6,79 inch FHD+ 90 Hz", "Processor: Helio G91-Ultra", "RAM: 8GB", "Storage: 256GB", "Kamera: 108MP + 2MP + 13MP", "Baterai: 5030 mAh, 33W Fast Charging"],
        seri: "M" 
    },
    {
        id: 10,
        name: "POCO M6 PRO 5G",
        price: "Rp 2.330.000",
        specs: "12GB RAM, 256GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI M/M6 PRO/M6 PRO-BLACK.png" },
            { name: "Blue", imageUrl: "assets/images/POCO SERI M/M6 PRO/M6 PRO-BLUE.png" },
            { name: "Purple", imageUrl: "assets/images/POCO SERI M/M6 PRO/M6 PRO-PURPLE.png" }
        ],
        detailSpecs: ["Layar: 6.5 inch AMOLED 120Hz", "Processor: MediaTek Helio G99 Ultra", "RAM: 12GB", "Storage: 256GB", "Kamera: 64MP + 8MP + 2MP + 16MP", "Baterai: 5000 mAh, 67W Fast Charging"],
        seri: "M" 
    },
    {
        id: 11,
        name: "POCO M7",
        price: "Rp 2.199.000",
        specs: "8GB RAM, 256GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI M/M7/M7-BLACK.png" },
            { name: "Blue", imageUrl: "assets/images/POCO SERI M/M7/M7-BLUE.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI M/M7/M7-SILVER.png" }
        ],
        detailSpecs: ["Layar: 6,9 inch 144Hz AdaptiveSync", "Processor: Snapdragon 685 (6 nm)", "RAM: 8GB", "Storage: 256GB", "Kamera: 50MP + 0,08MP + 8MP", "Baterai: 7000mAh, 33W Fast Charging"],
        seri: "M" 
    },
    {
        id: 12,
        name: "POCO M7 PRO 5G",
        price: "Rp 2.599.000",
        specs: "12GB RAM, 512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI M/M7 PRO/M7 PRO-BLACK.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI M/M7 PRO/M7 PRO-SILVER.png" },
        ],
        detailSpecs: ["Layar:  6,67 inch AMOLED FHD+ 120Hz ", "Processor: Dimensity 7025-Ultra", "RAM: 12GB", "Storage: 512GB", "Kamera: 50MP + 2MP + 20MP", "Baterai: 5110mAh, 45W Fast Charging"],
        seri: "M" 
    },
    // --- SERI X --- //
    {
        id: 13,
        name: "POCO X6 5G",
        price: "Rp 2.499.000",
        specs: "12GB RAM, 256GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI X/X6 5g/X6 5G-BLACK.png" },
            { name: "White", imageUrl: "assets/images/POCO SERI X/X6 5g/X6 5G-WHITE.png" },
        ],
        detailSpecs: ["Layar: 6,67 inch Flow AMOLED CrystalRes 1.5K 120 Hz", "Processor: Snapdragon 7s Gen 2 ", "RAM: 12GB", "Storage: 256GB.", "Kamera: 64MP + 2MP + 8MP + 16MP", "Baterai: 5000mAh, 67W Fast Charging"],
        seri: "X" 
    },
    {
        id: 14,
        name: "POCO X6 PRO",
        price: "Rp 6.999.000",
        specs: "8GB/12GB RAM, 256GB/512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI X/X6 5g PRO/X6 PRO-BLACK.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI X/X6 5g PRO/X6 PRO-SILVER.png" },
            { name: "Yellow", imageUrl: "assets/images/POCO SERI X/X6 5g PRO/X6 PRO-YELLOW.png" }
        ],
        detailSpecs: ["Layar: 6,67 inch Flow AMOLED CrystalRes 1.5K 120Hz", "MediaTek Dimensity 8300-Ultra", "RAM: 8GB/12GB", "Storage: 256GB/512GB", "Kamera: 64MP + 8MP + 4MP", "Baterai: 5000mAh, 67W Fast Charging"],
        seri: "X" 
    },
    {
        id: 15,
        name: "POCO X7 5G",
        price: "Rp 3.349.000",
        specs: "8GB/12GB RAM, 256GB/512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI X/X7/X7-BLACK.png" },
            { name: "Green", imageUrl: "assets/images/POCO SERI X/X7/X7-GREEN.png" },
            { name: "Silver", imageUrl: "assets/images/POCO SERI X/X7/X7-SILVER.png" }
        ],
        detailSpecs: ["Layar: 6,67 inch Flow AMOLED CrystalRes 1.5K 120 Hz", "Processor: MediaTek Dimensity 7300-Ultra (4 nm)", "RAM: 8GB/12GB", "Storage: 256GB/512GB", "Kamera: 50MP + 8MP + 2MP", "Baterai: 5110 mAh, 45W Fast Charging"],
        seri: "X" 
    },
    {
        id: 16,
        name: "POCO X7 PRO 5G",
        price: "Rp 4.599.000",
        specs: "12GB RAM, 512GB Storage",
        colors: [
            { name: "Black", imageUrl: "assets/images/POCO SERI X/X7 PRO/X7 PRO-BLACK.png" },
            { name: "Green", imageUrl: "assets/images/POCO SERI X/X7 PRO/X7 PRO-GREEN.png" },
            { name: "Yellow", imageUrl: "assets/images/POCO SERI X/X7 PRO/X7 PRO-YELLOW.png" }
        ],
        detailSpecs: ["Layar: 6,67 inci Flow AMOLED CrystalRes 1.5K", "Processor: MediaTek Dimensity 8400-Ultra (4 nm)", "RAM: 12GB", "Storage: 512GB", "Kamera: 50MP + 8MP", "Baterai: 6000 mAh, 90W Fast Charging"],
        seri: "X" 
    }
];

let cart = [];
let selectedProduct = null;
let selectedColorIndex = 0;

// Variabel untuk seri dan filter aktif //
const series = ['Semua', 'C', 'F', 'M', 'X']; 
let activeFilter = 'Semua'; 

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
            renderProducts(seri); 
        });
        filterContainer.appendChild(button);
    });
}

// RenderProducts untuk menerima filter dan menampilkan kategori //
function renderProducts(filterSeri = activeFilter) {
    const grid = document.getElementById('productsGrid');
    
    // Filter produk berdasarkan kategori //
    const filteredProducts = products.filter(product => 
        filterSeri === 'Semua' || product.seri === filterSeri
    );

    // Render produk yang telah difilter //
    grid.innerHTML = filteredProducts.map(product => `
        <div class="product-card" data-product-id="${product.id}">
            <div class="product-image">
                <img src="${product.colors[0].imageUrl}" alt="Gambar ${product.name} ${product.colors[0].name}">
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
        const modalImageUrl = currentColor.imageUrl;

        document.getElementById('modalImage').innerHTML = `<img src="${modalImageUrl}" alt="Detail ${selectedProduct.name} - ${currentColor.name}">`;
        document.getElementById('modalTitle').textContent = `${selectedProduct.name} - ${currentColor.name}`;
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

    const selectedColor = product.colors[colorIndex];
    const cartItem = {
        id: Date.now(), 
        productId: product.id,
        name: product.name,
        price: product.price,
        colorName: selectedColor.name,
        colorImageUrl: selectedColor.imageUrl,
        quantity: 1
    };

    // Tambahkan item ke keranjang //
    cart.push(cartItem);
    updateCartDisplay();
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

    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const customerName = document.getElementById('customerName').value;

        // Reset keranjang //
        cart = [];
        updateCartDisplay();
        closeCheckoutModal();

        // Tampilkan Pop-up Sukses //
        showSuccessPopup(customerName);
        this.reset(); // Reset form 
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
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Sembunyikan pesan sukses sebelumnya //
        successMessage.style.display = 'none';
        
        // Logika sederhana simulasi pengiriman formulir //
        console.log("Formulir kontak dikirim!");
        
        // Tampilkan pesan sukses //
        successMessage.style.display = 'block';
        
        // Kosongkan formulir //
        contactForm.reset();
        
        // Sembunyikan pesan sukses setelah beberapa detik //
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 5000);
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
    renderProducts();        
    setupCartModal();
    setupCheckoutModal();
    setupContactForm();
    setupEditableContent(); //
});

if (window.elementSdk) {
    window.elementSdk.setup({
        element_id: '1',
        name: 'SIJA SHOP',
        type: 'commerce',
        sections: [
            {
                id: 'hero',
                title: 'Beranda',
                content: [
                    {
                        get: () => document.getElementById('heroTitle').textContent,
                        set: (value) => document.getElementById('heroTitle').textContent = value,
                        name: 'Judul Utama',
                        type: 'text',
                        default: 'Smartphone Terbaik untuk Anda'
                    },
                    {
                        get: () => defaultConfig.site_slogan,
                        set: (value) => {
                            defaultConfig.site_slogan = value;
                            document.getElementById('heroSlogan').textContent = value;
                        },
                        name: 'Slogan',
                        type: 'text',
                        default: defaultConfig.site_slogan
                    }
                ]
            },
            {
                id: 'about',
                title: 'Tentang',
                content: [
                    {
                        get: () => defaultConfig.about_title,
                        set: (value) => {
                            defaultConfig.about_title = value;
                            document.getElementById('aboutTitle').textContent = value;
                        },
                        name: 'Judul Tentang',
                        type: 'text',
                        default: defaultConfig.about_title
                    },
                    {
                        get: () => defaultConfig.about_description,
                        set: (value) => {
                            defaultConfig.about_description = value;
                            document.getElementById('aboutDescription').textContent = value;
                        },
                        name: 'Deskripsi',
                        type: 'textarea',
                        default: defaultConfig.about_description
                    }
                ]
            },
            {
                id: 'contact',
                title: 'Kontak',
                content: [
                    {
                        get: () => defaultConfig.contact_title,
                        set: (value) => {
                            defaultConfig.contact_title = value;
                            document.getElementById('contactTitle').textContent = value;
                        },
                        name: 'Judul Kontak',
                        type: 'text',
                        default: defaultConfig.contact_title
                    }
                ]
            }
        ],
        styles: window.elementSdk.mapConfigToStyles(defaultConfig, {
            colors: [
                {
                    get: () => config.background_color || defaultConfig.background_color,
                    set: (value) => {
                        config.background_color = value;
                        window.elementSdk.setConfig({ background_color: value });
                    }
                },
                {
                    get: () => config.surface_color || defaultConfig.surface_color,
                    set: (value) => {
                        config.surface_color = value;
                        window.elementSdk.setConfig({ surface_color: value });
                    }
                },
                {
                    get: () => config.text_color || defaultConfig.text_color,
                    set: (value) => {
                        config.text_color = value;
                        window.elementSdk.setConfig({ text_color: value });
                    }
                },
                {
                    get: () => config.primary_action_color || defaultConfig.primary_action_color,
                    set: (value) => {
                        config.primary_action_color = value;
                        window.elementSdk.setConfig({ primary_action_color: value });
                    }
                },
                {
                    get: () => config.secondary_action_color || defaultConfig.secondary_action_color,
                    set: (value) => {
                        config.secondary_action_color = value;
                        window.elementSdk.setConfig({ secondary_action_color: value });
                    }
                }
            ],
            borderables: [],
            fontEditable: undefined,
            fontSizeable: undefined
        }),
        mapToEditPanelValues: (config) => new Map([
            ["site_slogan", config.site_slogan || defaultConfig.site_slogan],
            ["about_title", config.about_title || defaultConfig.about_title],
            ["about_description", config.about_description || defaultConfig.about_description],
            ["contact_title", config.contact_title || defaultConfig.contact_title]
        ])
    });
}
 
