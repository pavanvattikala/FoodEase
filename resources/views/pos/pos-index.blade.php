<x-pos-layout>
    <style>
        main {
            margin: 0% !important;
            padding: 0% !important;
        }


        button {
            background-color: #59c45d;
            border-radius: 8px;
        }

        .active {
            background-color: #276a29 !important;
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
            white-space: nowrap;
            text-overflow: wrap;
            justify-content: space-evenly
        }

        .mw-20 {
            width: 20%;
        }


        .mw-10 {
            width: 10%;
        }

        #order-items-heading {
            background-color: #666b66;
            color: white;
        }

        #order-items-body tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #order-items-body tr:nth-child(odd) {
            background-color: #ffffff;
        }

        #order-items-body tr:hover {
            background-color: #ddd;
        }

        #order-items-body tr td:first-child {
            text-align: left;
            width: 20%;
        }

        .del-item {
            background-color: #f44336;
            border-radius: 45%;
            color: white;
            width: 30px !important;
            height: 30px !important;
            margin: 10px;
        }

        #order-items-body {
            display: flex;
            flex-direction: column-reverse;
            max-height: 360px;
            overflow-y: scroll;
            flex-grow: 1;
            min-height: 350px;
        }

        #order-items-body::-webkit-scrollbar {
            width: 12px;
        }

        #order-items-body::-webkit-scrollbar-thumb {
            background-color: #a78585;
            border-radius: 10px;
        }

        #order-items-body {
            scrollbar-width: auto;
            scrollbar-color: #888 transparent;
        }

        #order-items-body tr {
            width: 100%;
        }

        td {
            width: 10%;
            padding: 5px;
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

        .addQty {
            background-color: #4CAF50;
        }

        .remQty {
            background-color: #f44336;
        }

        #payment-types {
            justify-content: space-evenly;
            background-color: #8ea08e
        }

        #payment-types div {
            text-align: center;
            padding: 10px;
        }

        #payment-types div input {
            padding: 10px;
        }

        #save-and-bill-options {
            justify-content: space-between;
            background-color: #6a6a6a
        }

        #save-and-bill-options div {
            text-align: center;
            padding: 10px;
        }

        #print-options {
            width: 50%;
            justify-content: space-evenly;
        }

        #print-options div input {
            padding: 10px;
        }

        #save-options {
            width: 50%;
            justify-content: space-evenly;
        }

        #save-options button {
            width: 80px;
        }

        #items-search-options {
            justify-content: space-evenly;
        }

        #items-search-options input {
            padding: 10px;
            width: 100%;
        }

        #noitems td {
            text-align: center !important;
        }
    </style>
    <div class="flex flex-row mb-2 mt-2" id="order-main-nav">
        <div class="flex flex-row mw-60" id=items-search-options>
            <input id="search-input" type="text" placeholder="Search by Name.." name="search">
            <input id="shortcode-input" type="text" placeholder="By ShortCode.." name="search-by-shortcode">
        </div>
        <div id="order-type-options" class="mw-40 flex flex-row align-middle" style="justify-content: space-evenly">
            <button class="btn h-full" id="dinein" onclick="setOrderType('dinein')">Dine In</button>
            <button class="btn h-full" id="pickup" onclick="setOrderType('pickup')">Pick Up</button>
            <button class="btn h-full" id="delivery" onclick="setOrderType('delivery')">Delivery</button>
        </div>

    </div>
    <div class="flex flex-row">
        <div class="mw-60 flex flex-row">
            <div id="category" class="category flex flex-col">
                @foreach ($categoriesWithMenus as $category)
                    <button class="btn p-4 m-4" id="c{{ $category->id }}-btn"
                        onclick="showMenu('c{{ $category->id }}')">{{ $category->name }}</button>
                @endforeach
            </div>

            @foreach ($categoriesWithMenus as $category)
                <div id="c{{ $category->id }}" class="menu-items flex flex-row hidden">
                    @foreach ($category->menus as $menu)
                        <button class="w-40 h-20 m-2 p-2 rounded-lg shadow-lg" id="{{ $menu->id }}"
                            onclick="addItemToOrder({{ $menu->id }})" data-price="{{ $menu->price }}"
                            data-shortcode="{{ $menu->shortcode }}">{{ $menu->name }}</button>
                        @php
                            $menuShortCuts[$menu->shortcode] = $menu->id;
                        @endphp
                    @endforeach
                </div>
            @endforeach
        </div>

        <div id="order-panel" class="items flex flex-col  mw-40">
            <div id="order-options-parent" class="flex flex-row justify-evenly">
                <button class="btn order-options" id="count" title="Number of Items">
                    <i class="fa fa-list"></i><br> <span id="item-count">0</span>
                </button>
                <button class="btn order-options" id="notes" title="Add Notes">
                    <i class="fa fa-sticky-note"></i>
                </button>
                <button class="btn order-options" id="customer" title="Assign to Customer">
                    <i class="fa fa-user"></i>
                </button>
                <button class="btn order-options" id="table" title="Assign to Table">
                    <i class="fa fa-table"></i>
                </button>
            </div>
            <div id="order-items-table" class="items ">
                <table class="table-auto flex flex-col">
                    <thead>
                        <tr id="order-items-heading">
                            <th class="mw-20">Item</th>
                            <th class="mw-10">Qty</th>
                            <th class="mw-10">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-body">
                        <tr id="noitems">
                            <td colspan="3">No Items Selected <br> Select from Left Menu</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td id="total">0</td>
                        </tr>
                        <tr>
                            {{-- <td colspan="3">Discount</td>
                            <td id="discount">0</td>
                        </tr>
                        <tr>
                            <td colspan="3">Grand Total</td>
                            <td id="grandtotal">0</td>
                        </tr> --}}
                </table>
            </div>
            <div id="payment-types" class="flex flex-row">
                <div>
                    <input type="radio" name="payment-type" selected="true">
                    <label>Cash</label>
                </div>
                <div>
                    <input type="radio" name="payment-type">
                    <label>Card</label>
                </div>
                <div>
                    <input type="radio" name="payment-type">
                    <label>UPI</label>
                </div>
                <div>
                    <input type="radio" name="payment-type" id="">
                    <label>Due</label>
                </div>
            </div>
            <div id="save-and-bill-options" class="flex flex-row">
                <div class="flex flex-row" id="print-options">
                    <div>
                        <input type="checkbox" name="" id="">
                        <label>Print Bill</label>
                    </div>
                    <div>
                        <input type="checkbox" name="" id="">
                        <label>Print KOT</label>
                    </div>
                </div>
                <div id="save-options" class="flex flex-row">
                    <button class="btn" id="save-order">Save</button>
                    <button class="btn" id="cancel-order" style="background-color: #f44336">Cancel</button>
                    <button class="btn" id="hold-order" style="background-color: rgb(42, 88, 161)">Hold</button>
                </div>
            </div>

        </div>
    </div>

    <script>
        //
        //     Order Items Structure
        //     id : int (menu id)
        //     name : string
        //     quantity : int
        //     price : int
        //     total : int
        //

        const orderItems = [];

        const menuShortCuts = @json($menuShortCuts);

        function showMenu(categoryId) {
            $(".menu-items").addClass("hidden");
            $(".category button").removeClass("active");
            const menuItems = document.getElementById(categoryId);

            menuItems.classList.toggle("hidden");

            $("#" + categoryId + "-btn").addClass("active");

        }

        function renderOrderTable() {
            const orderItemsBody = $("#order-items-body");
            orderItemsBody.empty(); // Clear existing content
            var count = 0;

            orderItems.forEach((item) => {
                const tr = $(`
                                <tr>
                                    <td>
                                        <button class="del-item" onclick="delItem(${item.id})">X</button>
                                        <span>${item.name}</span>
                                    </td>
                                    <td>
                                        <button class="qty-options remQty" onclick="remQty(${item.id})">-</button>
                                        <span id="qty">${item.quantity}</span>
                                        <button class="qty-options addQty" onclick="addQty(${item.id})">+</button>
                                    </td>
                                    <td>${item.total}</td>
                                </tr>
                            `);
                orderItemsBody.append(tr);
                count++;
            });
            $("#item-count").text(count);

            calculateTotal();
            $("input[type='text']").val('');
            $('.menu-items button').show();

        }

        function scrollToTop() {
            const lastChild = $('#order-items-body')[0].lastElementChild;

            lastChild.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        }

        function addItemToOrder(menuId) {
            if ($("#noitems").length > 0) {
                $("#noitems").remove();
            }
            const menu = $("#" + menuId);
            const existingItem = orderItems.find((item) => item.id === menuId);

            if (existingItem) {
                existingItem.quantity++;
                existingItem.total = existingItem.quantity * existingItem.price;
            } else {
                const newItem = {
                    id: menuId,
                    name: menu.text(),
                    quantity: 1,
                    price: Number(menu.data("price")),
                    total: Number(menu.data("price")),
                };
                orderItems.push(newItem);
            }

            renderOrderTable();
            scrollToTop();
        }

        function addQty(menuId) {
            const item = orderItems.find((item) => item.id === menuId);
            if (item) {
                item.quantity++;
                item.total = item.quantity * item.price;
                renderOrderTable();
            }
        }

        function remQty(menuId) {
            const item = orderItems.find((item) => item.id === menuId);
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
            orderItems.forEach((item) => {
                total += Number(item.total);
            });

            const discount = Number($("#discount").text());
            const grandtotal = Number(total - discount);

            $("#grandtotal").text(grandtotal);
            $("#total").text(total);
        }

        // DOM loaded
        $(document).ready(function() {
            $(".category button:first-child").click();

            $('#shortcode-input').keypress(function(event) {
                console.log(event);
                if (event.which === 13) {
                    event.preventDefault();
                    searchByShortcode();
                }
            });

            //use time out to prevent multiple calls
            var timer = null;
            $('#search-input').keyup(function() {
                clearTimeout(timer);
                timer = setTimeout(searchByName, 500)
            });

            $("#dinein").click();

        });

        function searchByShortcode() {
            var shortcodeInput = $('#shortcode-input').val().toLowerCase();
            const menuId = menuShortCuts[shortcodeInput];
            if (menuId) {
                addItemToOrder(menuId);

            } else {
                alert('Invalid Shortcode');
            }
            $('#shortcode-input').val('');
            $('#shortcode-input').focus();

        }

        function searchByName() {
            const searchInput = $('#search-input').val().toLowerCase().trim();
            console.log("called");
            if (searchInput === '') {
                $('.menu-items button').show();
                return;
            }

            $('.menu-items button').each(function() {
                const menuItem = $(this);
                const menuItemName = menuItem.text().toLowerCase();

                if (menuItemName.startsWith(searchInput) || menuItemName.includes(searchInput)) {
                    var menudivId = menuItem.parent().attr('id');
                    showMenu(menudivId);
                    menuItem.show();
                } else {
                    menuItem.hide();
                }
            });
        }

        $("#cancel-order").click(function() {
            orderItems.splice(0, orderItems.length);
            renderOrderTable();
            $("#order-items-body").append(`
                    <tr id="noitems">
                        <td colspan="3">No Items Selected</td>
                    </tr>
                `);
            $("input[type='radio']").prop('checked', false);
            $("input[type='checkbox']").prop('checked', false);
        });

        function delItem(menuId) {
            const item = orderItems.find((item) => item.id === menuId);
            if (item) {
                orderItems.splice(orderItems.indexOf(item), 1);
                renderOrderTable();
            }
        }

        function setOrderType(orderType) {
            $('#order-type-options button').removeClass('active');
            $('#' + orderType).addClass('active');
            console.log('Selected Order Type:', orderType);
        }
    </script>
</x-pos-layout>
