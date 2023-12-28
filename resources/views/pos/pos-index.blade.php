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
    </style>
    <div class="flex flex-row" id="items-search-bar">
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
        <div class="mw-40">
            <button class="btn">Chinneses</button>
            <button class="btn">Chinneses</button>
            <button class="btn">Chinneses</button>
        </div>
    </div>
    <div class="flex flex-row">
        <div class="mw-60 flex flex-row">
            <div id="category" class="category flex flex-col">
                @foreach ($categoriesWithMenus as $category)
                    <button class="btn p-4 m-4" onclick="showMenu({{ $category->id }})">{{ $category->name }}</button>
                @endforeach
            </div>

            @foreach ($categoriesWithMenus as $category)
                <div id="{{ $category->id }}" class="menu-items flex flex-row hidden">
                    @foreach ($category->menus as $menu)
                        <button class="w-40 h-20 m-2 p-2 rounded-lg shadow-lg" id="{{ $menu->id }}"
                            onclick="addItem({{ $menu->id }})"
                            data-price="{{ $menu->price }}">{{ $menu->name }}</button>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div id="order-panel" class="items flex flex-col mw-40">
            <div id="order-type" class="flex flex-row">
                <button class="btn p-1 m-1">Chinneses</button>
                <button class="btn p-1 m-1">Chinneses</button>
                <button class="btn p-1 m-1">Chinneses</button>
            </div>
            <div id="order-items-table" class="items mw-40">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-body">
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        function showMenu(categoryId) {
            $(".menu-items").addClass('hidden');
            const menuItems = document.getElementById(categoryId);
            menuItems.classList.toggle('hidden');

        }

        function addItem($menuId) {
            const menu = $('#' + $menuId);
            var price = menu.data('price');
            var total = Number(price) * 1;
            const orderItems = document.getElementById('order-items-body');
            const orderItem = document.createElement('tr');
            orderItem.innerHTML = `
                <td>${menu.text()}</td>
                <td>1</td>
                <td>${price}</td>
                <td>${total}</td>
            `;
            orderItems.appendChild(orderItem);
        }

        //dom loaded
        document.addEventListener('DOMContentLoaded', () => {
            $(".category button:first-child").click();
        });
    </script>
</x-pos-layout>
