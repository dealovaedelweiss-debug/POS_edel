<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Transaction POS' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .product-item {
            cursor: pointer;
        }

        .product-card {
            border: none;
            border-radius: 15px;
            transition: 0.2s;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(85, 47, 47, 0.1);
        }

        .product-image {
            height: 130px;
            display: flex;
            justify-content: center;
        }

        .product-image img {
            object-fit: contain;
            width: 100%;
        }

        .price {
            color: #6f4e37;
            font-weight: bold;
        }

        .cart-box {
            position: sticky;
            top: 20px;
        }

        .cart-item {
            border-bottom: 1px solid #ffd5d5;
            padding: 12px 0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            padding: 0;
            border-radius: 50%;
        }

        .total-price {
            font-size: 25px;
            font-weight: bold;
            color: #8f6a51;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Styling khusus untuk cetak struk (Hanya tampil saat nge-print) */
        #print-receipt {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #print-receipt,
            #print-receipt * {
                visibility: visible;
            }

            #print-receipt {
                display: block;
                position: absolute;
                left: 0;
                top: 0;
                width: 58mm;
                /* Ukuran standar printer thermal kasir */
                font-family: monospace;
                color: #000;
            }
        }
    </style>
</head>

<body>
    <!-- Hidden element untuk menampung format struk kasir -->
    <div id="print-receipt"></div>

    <!-- Modal Pembayaran (Cash Only) -->
    <div class="modal fade" id="paymentMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="paymentMethodLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="paymentMethodLabel">Konfirmasi Pembayaran Cash</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-semibold">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-1">
                            <strong class="bg-success p-2 text-white rounded d-block text-center" id="total-paid">Harga:
                                Rp.0</strong>
                        </div>
                    </div>
                    <div class="row align-items-center my-3">
                        <div class="col-md-6">
                            <label for="cash_paid" class="form-label fw-bold">Pembayaran Cash :</label>
                            <input type="number" id="cash_paid" step="any" min="0" class="form-control mb-2"
                                oninput="calculateChange()">
                        </div>
                        <div class="col-md-6">
                            <strong class="bg-primary p-2 text-white rounded d-block text-center"
                                id="change-paid">Kembalian :
                                Rp.0</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                        onclick="processPayment()">Pay Now!</button>
                </div>
            </div>
        </div>
    </div>

    {{-- halaman utama POS --}}
    <div class="container-fluid">
        <main class="col-lg-12 p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Point Of Sales</h3>
                </div>
                <button class="btn btn-dark" onclick="cart=[]; displayCart();">Empty Cart</button>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-7">
                                    <h5 class="fw-bold">Select Product</h5>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="searchProduct" class="form-control"
                                        placeholder="Search Product..." onkeyup="searchProduct()">
                                </div>
                            </div>
                            <div class="mb-4">
                                <button class="btn btn-dark btn-sm me-1 category-btn"
                                    onclick="filterCategory('all', this)">Semua</button>
                                @foreach ($categories as $category)
                                    <button class="btn btn-outline-dark btn-sm me-1 category-btn"
                                        onclick="filterCategory({{ $category->id }}, this)">{{ $category->name ?? '' }}</button>
                                @endforeach
                            </div>
                            <div class="row g-3" id="productList">
                                @foreach ($products as $product)
                                    <div class="col-md-4 col-sm-6 product-item"
                                        data-category="{{ $product->category_id }}"
                                        onclick="addToCart({{ $product->id }}, this)" data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                        <div class="card product-card shadow h-100">
                                            <div class="product-image"><img
                                                    src="{{ asset('storage/' . $product->photo) }}" alt="">
                                            </div>
                                            <div class="card-body">
                                                <span class="badge bg-light text-dark mb-2">
                                                    {{ $product->description ?? '' }}
                                                </span>
                                                <h6 class="fw-bold">{{ $product->name ?? '' }}</h6>
                                                <span
                                                    class="price">{{ number_format($product->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow cart-box p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-cart"></i> Cart
                            </h5>
                            <span class="badge bg-dark" id="cartCount">
                                0
                            </span>
                        </div>
                        <div class="mb-3" id="cartItems" style="max-height: 400px; overflow-y: auto;">
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-cart4"></i>
                                <p>Cart Still Empty</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sub Total</span>
                            <strong id="subtotal">Rp. 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak (11%)</span>
                            <strong id="tax" data-percent="11">Rp. 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Total</span>
                            <span class="total-price" id="total">Rp. 0</span>
                        </div>
                        <button class="btn btn-success w-100 py-3" onclick="openModalPayment()">Payment</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let cart = [];

        function calculateChange() {
            let subtotal = 0;
            cart.forEach(function(item) {
                subtotal += Number(item.price) * Number(item.qty);
            });
            const tax = subtotal * 0.11;
            const totalAmount = subtotal + tax;

            const cashPaidInput = parseFloat(document.getElementById('cash_paid').value) || 0;
            const changeMoney = cashPaidInput - totalAmount;

            const changeElement = document.getElementById('change-paid');
            if (changeMoney < 0) {
                changeElement.innerText = `Kurang Rp. ${rupiahFormat(Math.abs(changeMoney))}`;
                changeElement.classList.add('bg-danger');
                changeElement.classList.remove('bg-success', 'bg-primary');
            } else {
                changeElement.innerText = `Kembali Rp. ${rupiahFormat(changeMoney)}`;
                changeElement.classList.add('bg-success');
                changeElement.classList.remove('bg-danger', 'bg-primary');
            }
            return {
                changeMoney
            };
        }

        function openModalPayment() {
            if (cart.length === 0) {
                alert('Cart is Empty');
                return;
            }
            document.getElementById('cash_paid').value = '';
            document.getElementById('change-paid').innerText = 'Kembalian : Rp.0';
            document.getElementById('change-paid').className = 'bg-primary p-2 text-white rounded d-block text-center';

            const modal = new bootstrap.Modal(document.getElementById('paymentMethod'));
            modal.show();
            setTimeout(() => document.getElementById('cash_paid').focus(), 500);
        }

        async function processPayment() {
            if (cart.length === 0) {
                alert('Cart is Empty');
                return;
            }
            const customerName = document.getElementById('customer_name').value;
            const {
                changeMoney
            } = calculateChange();
            const cashPaid = document.getElementById('cash_paid');
            const cashPaidValue = parseFloat(cashPaid?.value) || 0;

            if (!cashPaidValue) {
                alert("INPUT PEMBAYARAN TERLEBIH DAHULU!");
                cashPaid.focus();
                return;
            }

            if (changeMoney < 0) {
                alert("Uang pembayaran kurang!");
                cashPaid.focus();
                return;
            }

            try {
                const response = await fetch("{{ route('order.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        items: cart.map((item) => {
                            return {
                                id: item.id,
                                qty: item.qty
                            }
                        }),
                        payment_method: 'cash',
                        customer_name: customerName,
                        order_change: changeMoney
                    })
                });

                const result = await response.json();
                console.log(result)
                if (!response.ok) {
                    alert('Error: ' + (result.message || 'Unknown error'));
                    console.error(result);
                    return;
                }

                alert("Pembayaran Cash Berhasil!");

                // Panggil fungsi print struk otomatis
                printReceipt(customerName, cashPaidValue, changeMoney);

                cart = [];
                displayCart();

                document.getElementById('cash_paid').value = '';
                document.getElementById('customer_name').value = '';
                document.getElementById('change-paid').innerText = 'Kembalian : Rp.0';
                document.getElementById('change-paid').className =
                    'bg-primary p-2 text-white rounded d-block text-center';

            } catch (error) {
                console.log(error);
                alert('Gagal memproses transaksi: ' + error.message);
            }
        }

        // FUNGSI UNTUK MERENDER DAN MENCETAK STRUK GAYA KASIR/MINIMARKET
        function printReceipt(customerName, cashPaid, changeMoney) {
            const receiptDiv = document.getElementById('print-receipt');

            let itemsHtml = '';
            let subtotal = 0;

            cart.forEach(item => {
                let itemTotal = item.qty * item.price;
                subtotal += itemTotal;

                itemsHtml += `
                    <tr>
                        <td colspan="3" style="padding-top: 3px;"><strong>${item.name.toUpperCase()}</strong></td>
                    </tr>
                    <tr>
                        <td style="width: 15%; padding-left: 10px;">${item.qty}x</td>
                        <td style="width: 40%;">${rupiahFormat(item.price)}</td>
                        <td style="width: 45%; text-align: right;">${rupiahFormat(itemTotal)}</td>
                    </tr>
                `;
            });

            let tax = subtotal * 0.11;
            let grandTotal = subtotal + tax;

            let now = new Date();
            let dateStr = now.toLocaleDateString('id-ID').replace(/\//g, '.');
            let timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            receiptDiv.innerHTML = `
                <div style="text-align: center; margin-bottom: 5px;">
                    <h3 style="margin: 0; font-size: 14px; font-weight: bold;">KOPI PPKD JAKARTA PUSAT</h3>
                    <p style="margin: 0; font-size: 10px;">JL. KARET PASAR BARU BARAT</p>
                </div>

                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>

                <table style="width: 100%; font-size: 10px;">
                    <tr>
                        <td>Kasir : Admin</td>
                        <td style="text-align: right;">${dateStr} ${timeStr}</td>
                    </tr>
                    <tr>
                        <td>Plg   : ${(customerName ? customerName.toUpperCase() : 'UMUM')}</td>
                        <td style="text-align: right;">CASH</td>
                    </tr>
                </table>

                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>

                <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
                    ${itemsHtml}
                </table>

                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>

                <table style="width: 100%; font-size: 11px;">
                    <tr><td colspan="2">SUBTOTAL</td><td style="text-align: right">${rupiahFormat(subtotal)}</td></tr>
                    <tr><td colspan="2">PPN (11%)</td><td style="text-align: right">${rupiahFormat(tax)}</td></tr>
                    <tr><td colspan="2"><strong>TOTAL</strong></td><td style="text-align: right"><strong>${rupiahFormat(grandTotal)}</strong></td></tr>
                    <tr><td colspan="3" style="padding: 2px 0;"></td></tr>
                    <tr><td colspan="2">TUNAI</td><td style="text-align: right">${rupiahFormat(cashPaid)}</td></tr>
                    <tr><td colspan="2">KEMBALIAN</td><td style="text-align: right">${rupiahFormat(changeMoney)}</td></tr>
                </table>

                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>

                <div style="text-align: center; font-size: 10px; margin-top: 5px;">
                    <p style="margin: 2px 0;">TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
                </div>
            `;

            window.print();
        }

        function filterCategory(categoryId, button) {
            const products = document.querySelectorAll('.product-item');
            products.forEach((product) => {
                const categoryName = product.dataset.category;
                if (categoryId === "all" || categoryName === String(categoryId)) {
                    product.style.display = "";
                } else {
                    product.style.display = "none";
                }
            });

            document.querySelectorAll('.category-btn').forEach((btn) => {
                btn.classList.remove('btn-dark', 'active');
                btn.classList.add('btn-outline-dark');
            });

            button.classList.remove('btn-outline-dark');
            button.classList.add('btn-dark', 'active');
        }

        function addToCart(productId, element) {
            const products = element;
            const productName = products.dataset.name;
            const productPrice = Number(products.dataset.price);

            const existingItem = cart.find((item) => {
                return Number(item.id) === Number(productId);
            });
            if (existingItem) {
                existingItem.qty++;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    qty: 1
                });
            }
            displayCart();
        }

        function displayCart() {
            const cartItems = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartItems.innerHTML = `<div class="text-center text-muted py-5">
                                    <i class="bi bi-cart4"></i>
                                    <p>Cart Still Empty</p>
                                </div>`;
                updateCart();
                return;
            }
            cartItems.innerHTML = '';

            cart.forEach((item, index) => {
                cartItems.innerHTML += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>${item.name}</strong>
                            <div class="small text-muted">Rp. ${rupiahFormat(item.price)}</div>
                        </div>
                        <strong>Rp. ${rupiahFormat(item.qty * item.price)}</strong>
                    </div>
                    <div class="d-flex align-items-center mt-3 gap-2">
                        <button class="btn btn-outline-danger quantity-btn rounded-2" onclick="changeItem(${index}, -1)">-</button>
                        <input type="number" min="1" class="form-control text-center px-1" style="width: 70px; height: 32px;" value="${item.qty}" onchange="updateItemQty(${index}, this.value)">
                        <button class="btn btn-outline-success quantity-btn rounded-2" onclick="changeItem(${index}, 1)">+</button>
                        <button class="btn btn-outline-dark ms-auto" onclick="dumpItem(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                `;
            });

            updateCart();
        }

        const cartCount = document.getElementById('cartCount');
        const subTotal = document.getElementById('subtotal');
        const tax = document.getElementById('tax');
        const total = document.getElementById('total');
        const harga = document.getElementById('total-paid');

        function updateCart() {
            cartCount.innerText = `${cart.length}`;

            let subTotalCount = 0;
            const taxes = tax.dataset.percent / 100;

            cart.forEach((item) => {
                subTotalCount += item.price * item.qty;
            });

            tax.innerText = `Rp. ${rupiahFormat(subTotalCount * taxes)}`;
            subTotal.innerText = `Rp. ${rupiahFormat(subTotalCount)}`;
            total.textContent = `Rp. ${rupiahFormat(subTotalCount * taxes + subTotalCount)}`;
            harga.textContent = total.textContent;
        }

        function changeItem(index, change) {
            let newQty = cart[index].qty + change;
            if (newQty <= 0) {
                dumpItem(index);
                return;
            }
            cart[index].qty = newQty;
            displayCart();
        }

        function updateItemQty(index, value) {
            let qty = parseInt(value);
            if (isNaN(qty) || qty <= 0) {
                dumpItem(index);
                return;
            }
            cart[index].qty = qty;
            displayCart();
        }

        function dumpItem(index) {
            cart.splice(index, 1);
            displayCart();
        }

        function rupiahFormat(number) {
            return number.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            })
        }

        function searchProduct() {
            const searchValue = document.getElementById('searchProduct').value.toLowerCase().trim();
            const products = document.querySelectorAll('.product-item');

            products.forEach((product) => {
                const productName = product.dataset.name.toLowerCase();
                if (productName.includes(searchValue)) {
                    product.style.display = "";
                } else {
                    product.style.display = "none";
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
