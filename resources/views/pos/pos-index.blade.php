<x-pos-layout>
    <style>
        main {
            margin: 0% !important;
            padding: 0% !important;
        }


        button {
            background-color: #4CAF50;
        }

        .mw-60 {

            width: 60%;
        }

        .mw-40 {
            width: 40%;
        }

        .order-options {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 10px;

        }


        #order-options-parent {
            overflow-x: scroll;
            overflow-y: hidden;
            white-space: nowrap;
            text-overflow: wrap;
            justify-content: space-evenly
        }

        #order-items-table {
            overflow-y: scroll;
            height: 500px;
        }

        .mw-10 {
            width: 10%;
        }

        #order-items-heading {
            background-color: #666b66;
            color: white;
        }

        td {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
        }

        th {
            text-align: center;
            padding: 5px
        }

        .qty-options {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin: 10px;
        }

        #addQty {
            background-color: #4CAF50;
        }

        #remQty {
            background-color: #f44336;
        }
    </style>
    <div class="flex flex-row mb-2 mt-2" id="items-search-bar">
        <div class="flex flex-row mw-60">
            <div>
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
            <div>
                <input type="text" placeholder="ShortCode.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <div class="mw-40 flex flex-row align-middle" style="justify-content: space-evenly">
            <button class="btn h-full">Dine In</button>
            <button class="btn h-full">Pick Up</button>
            <button class="btn h-full">Delivery</button>
        </div>
    </div>
    <div class="flex flex-row">
        <div class="mw-60 flex flex-row">
            <div id="category" class="category flex flex-col">
                @foreach ($categoriesWithMenus as $category)
                    <button class="btn p-4 m-4"
                        onclick="showMenu('c{{ $category->id }}')">{{ $category->name }}</button>
                @endforeach
            </div>

            @foreach ($categoriesWithMenus as $category)
                <div id="c{{ $category->id }}" class="menu-items flex flex-row hidden">
                    @foreach ($category->menus as $menu)
                        <button class="w-40 h-20 m-2 p-2 rounded-lg shadow-lg" id="{{ $menu->id }}"
                            onclick="addItemToOrder({{ $menu->id }})"
                            data-price="{{ $menu->price }}">{{ $menu->name }}</button>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div id="order-panel" class="items flex flex-col  mw-40">
            <div id="order-options-parent" class="flex flex-row justify-evenly">
                <button class="btn order-options" id="count">Count</button>
                <button class="btn order-options" id="notes">Notes</button>
                <button class="btn order-options" id="customer">Customer</button>
                <button class="btn order-options" is="table">Table</button>
            </div>
            <div id="order-items-table" class="items ">
                <table class="table-auto">
                    <thead>
                        <tr id="order-items-heading">
                            <th class="mw-10">Item</th>
                            <th class="mw-10">Qty</th>
                            <th class="mw-10">Price</th>
                            <th class="mw-10">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-body">
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td id="total">0</td>
                        </tr>
                        <tr>
                            <td colspan="3">Discount</td>
                            <td id="discount">0</td>
                        </tr>
                        <tr>
                            <td colspan="3">Grand Total</td>
                            <td id="grandtotal">0</td>
                        </tr>
                </table>
            </div>

        </div>
    </div>

    <script>
        const orderItems = [];

        function showMenu(categoryId) {
            $(".menu-items").addClass('hidden');
            const menuItems = document.getElementById(categoryId);
            menuItems.classList.toggle('hidden');

        }

        function renderOrderTable() {
            const orderItemsBody = $('#order-items-body');
            orderItemsBody.empty(); // Clear existing content

            orderItems.forEach(item => {
                const tr = $(`
                    <tr>
                        <td>${item.name}</td>
                        <td>
                            <button id="remQty" class="qty-options" onclick="remQty(${item.id})">-</button>
                            <span id="qty">${item.quantity}</span>
                            <button id="addQty" class="qty-options" onclick="addQty(${item.id})">+</button>
                        </td>
                        <td>${item.price}</td>
                        <td>${item.total}</td>
                    </tr>
                `);
                orderItemsBody.append(tr);
            });

            calculateTotal();
        }

        function addItemToOrder(menuId) {
            const menu = $('#' + menuId);
            const existingItem = orderItems.find(item => item.id === menuId);

            if (existingItem) {
                existingItem.quantity++;
                existingItem.total = existingItem.quantity * existingItem.price;
            } else {
                const newItem = {
                    id: menuId,
                    name: menu.text(),
                    quantity: 1,
                    price: menu.data('price'),
                    total: menu.data('price'),
                };
                orderItems.push(newItem);
            }

            renderOrderTable();
        }

        function addQty(menuId) {
            const item = orderItems.find(item => item.id === menuId);
            if (item) {
                item.quantity++;
                item.total = item.quantity * item.price;
                renderOrderTable();
            }
        }

        function remQty(menuId) {
            const item = orderItems.find(item => item.id === menuId);
            if (item) {
                if (item.quantity > 1) {
                    item.quantity--;
                    item.total = item.quantity * item.price;
                } else {
                    // Remove the item if quantity becomes 0
                    orderItems.splice(orderItems.indexOf(item), 1);
                }
                renderOrderTable();
            }
        }

        function calculateTotal() {
            let total = 0;
            orderItems.forEach(item => {
                total += item.total;
            });

            const discount = $("#discount").text();
            const grandtotal = total - discount;

            $("#grandtotal").text(grandtotal);
            $("#total").text(total);
        }

        // DOM loaded
        $(document).ready(function() {
            $(".category button:first-child").click();
        });
    </script>
</x-pos-layout>
