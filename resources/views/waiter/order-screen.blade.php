<x-waiter-layout>
    @section('title', 'Create Order')

    <div class="bg-gray-100 min-h-screen font-sans text-gray-700 " x-data="waiterOrderScreen({
        table: {{ Js::from($table) }},
        menus: {{ Js::from($categoriesWithMenus) }},
        notes: {{ Js::from($predefinedNotes) }},
        submitUrl: '{{ route('order.submit') }}',
        redirectUrl: '{{ route('waiter.tables.index') }}'
    })" x-init="init">
        <header class="sticky top-0 z-30 bg-white shadow-md p-3 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">
                Table: <span class="text-blue-600">{{ $table->name }}</span>
            </h1>
            <div class="text-right">
                <p class="text-sm text-gray-600">Subtotal</p>
                <p class="text-2xl font-bold text-green-600" x-text="formatCurrency(subtotal)"></p>
            </div>
        </header>

        <main class="p-4 pb-32">
            <div class="space-y-3">
                <template x-for="category in categories" :key="category.id">
                    <div class="bg-white rounded-lg shadow">
                        <div @click="toggleCategory(category.id)"
                            class="p-4 flex justify-between items-center cursor-pointer">
                            <h2 class="text-lg font-semibold text-gray-700" x-text="category.name"></h2>
                            <i class="fas text-gray-500"
                                :class="activeCategory === category.id ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        <div x-show="activeCategory === category.id" x-collapse>
                            <div class="p-2 space-y-2 border-t border-gray-200">
                                <template x-for="menu in category.menus" :key="menu.id">
                                    <div class="flex items-center justify-between p-2 rounded-md hover:bg-gray-50">
                                        <span class="text-gray-800" x-text="menu.name"></span>
                                        <div class="flex items-center space-x-3">
                                            <button @click="updateQuantity(menu.id, -1)"
                                                class="w-8 h-8 bg-red-500 text-white rounded-full font-bold text-lg leading-tight shadow-sm hover:bg-red-600">-</button>
                                            <span class="w-8 text-gray-500 text-center text-lg font-semibold"
                                                x-text="getCartItemQuantity(menu.id)"></span>
                                            <button @click="updateQuantity(menu.id, 1, menu.name, menu.price)"
                                                class="w-8 h-8 bg-green-500 text-white rounded-full font-bold text-lg leading-tight shadow-sm hover:bg-green-600">+</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        <footer
            class="fixed bottom-0 left-0 w-full bg-white border-t-2 border-blue-600 p-3 z-30 flex items-center justify-between space-x-2">
            <button @click="showNotesModal = true"
                class="flex-1 py-3 px-4 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600">
                <i class="fas fa-sticky-note mr-2"></i>Notes
            </button>
            <button @click="showCartModal = true"
                class="flex-1 py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 relative">
                <i class="fas fa-shopping-cart mr-2"></i>View Cart
                <span x-show="cart.length > 0"
                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center"
                    x-text="cart.length"></span>
            </button>
            <button @click="submitOrder"
                class="flex-1 py-3 px-4 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700">
                <i class="fas fa-paper-plane mr-2"></i>Submit KOT
            </button>
        </footer>

        <div x-show="showCartModal" class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="showCartModal = false">
            <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-lg mx-auto">
                <div class="p-4 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Review Order</h3>
                </div>
                <div class="p-4 max-h-96 overflow-y-auto">
                    <template x-if="cart.length === 0">
                        <p class="text-center text-gray-500 py-8">Your cart is empty.</p>
                    </template>
                    <div class="space-y-3">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold" x-text="item.name"></span>
                                <div class="flex items-center space-x-3">
                                    <button @click="updateQuantity(item.id, -1)"
                                        class="w-8 h-8 bg-red-500 text-white rounded-full font-bold text-lg leading-tight shadow-sm hover:bg-red-600">-</button>
                                    <span class="wc-8 text-center text-lg font-semibold" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(item.id, 1)"
                                        class="w-8 h-8 bg-green-500 text-white rounded-full font-bold text-lg leading-tight shadow-sm hover:bg-green-600">+</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 border-t flex justify-between items-center">
                    <button @click="cancelOrder"
                        class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">Cancel</button>
                    <button @click="showCartModal = false"
                        class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700">Close</button>
                </div>
            </div>
        </div>

        <div x-show="showNotesModal" class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="showNotesModal = false">
            <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-lg mx-auto">
                <div class="p-4 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Add Special Instructions</h3>
                </div>
                <div class="p-4 max-h-96 overflow-y-auto">
                    <div class="space-y-2">
                        <template x-for="note in predefinedNotes" :key="note">
                            <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-100">
                                <input type="checkbox" :value="note" x-model="selectedNotes"
                                    class="h-5 w-5 rounded text-blue-600 focus:ring-blue-500">
                                <span x-text="note"></span>
                            </label>
                        </template>
                    </div>
                    <textarea x-model="customNotes" class="mt-4 w-full p-2 border rounded-md" placeholder="Or type custom notes..."></textarea>
                </div>
                <div class="p-4 bg-gray-50 border-t flex justify-end">
                    <button @click="saveNotes"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Save
                        Notes</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function waiterOrderScreen(data) {
            return {
                table: data.table,
                categories: data.menus,
                predefinedNotes: data.notes,
                submitUrl: data.submitUrl,
                redirectUrl: data.redirectUrl,
                cart: [],
                activeCategory: null,
                showCartModal: false,
                showNotesModal: false,
                selectedNotes: [],
                customNotes: '',
                finalNotes: [],

                // Initialize the component
                init() {
                    if (this.categories.length > 0) {
                        this.activeCategory = this.categories[0].id;
                    }
                },

                // Computed property for subtotal
                get subtotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                toggleCategory(categoryId) {
                    this.activeCategory = this.activeCategory === categoryId ? null : categoryId;
                },

                getCartItemQuantity(menuId) {
                    const item = this.cart.find(i => i.id === menuId);
                    return item ? item.quantity : 0;
                },

                updateQuantity(menuId, change, name = null, price = null) {
                    let item = this.cart.find(i => i.id === menuId);
                    if (item) {
                        item.quantity += change;
                        if (item.quantity <= 0) {
                            this.cart = this.cart.filter(i => i.id !== menuId);
                        }
                    } else if (change > 0) {
                        this.cart.push({
                            id: menuId,
                            name: name,
                            price: price,
                            quantity: 1
                        });
                    }
                },

                saveNotes() {
                    let notes = [...this.selectedNotes];
                    if (this.customNotes.trim()) {
                        notes.push(...this.customNotes.trim().split(',').map(n => n.trim()));
                    }
                    this.finalNotes = notes;
                    this.showNotesModal = false;
                },

                // **MODIFIED: Submit order using jQuery $.ajax to match pos.js**
                submitOrder() {
                    if (this.cart.length === 0) {
                        alert('Cannot submit an empty order.');
                        return;
                    }

                    const orderData = {
                        orderItems: this.cart.map(item => ({
                            ...item,
                            total: item.price * item.quantity
                        })),
                        total: this.subtotal,
                        discount: 0,
                        grandtotal: this.subtotal
                    };

                    const payload = {
                        source: 'waiter',
                        tableId: this.table.id,
                        specialInstructions: this.finalNotes,
                        paymentMethod: 'cash', // Defaulting as waiters don't handle payments.
                        isPickUpOrder: false,
                        billTable: false,
                        order: orderData
                    };

                    const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    showLoader();

                    $.ajax({
                        url: this.submitUrl,
                        type: "POST",
                        data: payload, // jQuery handles the URL encoding
                        headers: {
                            "X-CSRF-TOKEN": csrf_token,
                        },
                        contentType: "application/x-www-form-urlencoded",
                        success: (response) => {
                            if (response.status === "success") {
                                window.location.href = this.redirectUrl;
                            } else {
                                alert("Order submission failed: " + (response.message || 'Unknown error'));
                            }
                        },
                        error: (error) => {
                            console.error("Submission Error:", error);
                            alert("An error occurred while submitting the order.");
                        },
                        complete: () => {
                            hideLoader();
                        }
                    });
                },

                cancelOrder() {
                    if (confirm('Are you sure you want to cancel this order? All items will be removed.')) {
                        window.location.href = this.redirectUrl;
                    }
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('en-IN', {
                        style: 'currency',
                        currency: 'INR'
                    }).format(amount);
                }
            }
        }
    </script>
</x-waiter-layout>
