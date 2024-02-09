<x-waiter-layout>
    <style>
        .menu-item {
            width: 8rem;
            margin: 1rem;
            padding: 1rem;
            border: 1px solid #221d1d;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        main {
            padding: 0px !important;
            margin: 0px !important;
        }

        .selected-count {
            background-color: #f6ad55;
            color: #fff;
            padding: 0.2rem 0.5rem;
            border-radius: 0.2rem;
        }
    </style>
    <div class="container w-full px-2 py-2 mx-2">
        <div class=" flex flex-col">
            @foreach ($categoriesWithMenus as $category)
                {{-- show each category  --}}
                <div class="category w-full">
                    <div class="category-heading flex justify-between text-xl font-semibold mb-2 border border-gray-300 rounded p-2 bg-gray-100"
                        data-category="{{ $category->name }}" onclick="toggleDropdown('{{ $category->name }}')">
                        <div class="flex flex-row">
                            <h2>{{ $category->name }}</h2>
                            &nbsp
                            &nbsp
                            <span class="selected-count"></span> <!-- Placeholder for selected count -->
                        </div>
                        <span id="{{ $category->name }}Arrow">&#9662;</span>

                    </div>

                    {{-- //dropdown for menus by category --}}
                    <div id="{{ $category->name }}Dropdown" class="flex flex-wrap hidden">
                        @foreach ($category->menus as $menu)
                            <div class=" rounded-lg shadow-lg menu-item">
                                {{-- <img class="w-full h-20 object-cover" src="{{ Storage::url($menu->image) }}"
                                    alt="Image" /> --}}
                                <div class="flex flex-col text-center pt-2 mb-2 items-center ">
                                    <p
                                        class="text-lg font-semibold tracking-tight text-orange-500 uppercase break-words">
                                        {{ $menu->name }}
                                    </p>
                                    <p class="text-sm font-semibold tracking-tight text-blue-600 lowercase ml-2">
                                        {{ $currency }}{{ $menu->price }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  bg-gray-100">
                                    <div class="flex items-center">
                                        <button
                                            class="increase-count-btn bg-green-500 text-ipwhite px-4 py-2 mr-2 rounded"
                                            onclick="addToTotal({{ $menu->id }}, {{ $menu->price }})">+</button>
                                        <span id="count_{{ $menu->id }}" class="itemCount text-lg font-semibold">
                                            {{ session('cart.' . $menu->id . '.quantity', 0) }}
                                        </span>
                                        <button class="decrease-count-btn bg-red-500 text-white px-4 py-2 ml-2 rounded"
                                            onclick="subtractFromTotal({{ $menu->id }}, {{ $menu->price }})">-</button>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-semibold">Total Amount: <span id="totalAmount">Rs
                    {{ session('cart.total', 0) }}</span></h2>
            <span class="total-items-count"></span>
            <h2 class="text-2xl font-semibold">Total Items: <span id="total-items-count"> </h2>
        </div>

        <a href="{{ route('order.cart') }}">
            <button class="bg-green-500 text-white px-4 py-2 rounded relative m-2">
                View Cart
            </button>
        </a>

        <button onclick="clearCart()" class="bg-red-500 text-white px-4 py-2 rounded relative m-2">
            Clear Cart
        </button>

    </div>


</x-waiter-layout>

<script>
    //tooggle menu
    function toggleDropdown(categoryName) {
        const dropdown = document.getElementById(`${categoryName}Dropdown`);
        const arrow = document.getElementById(`${categoryName}Arrow`);
        dropdown.classList.toggle('hidden');
        arrow.innerHTML = dropdown.classList.contains('hidden') ? '&#9662;' : '&#9652;';
    }

    let totalAmount = 0;

    function addToTotal(menuId, amount) {
        let currentItemCount = document.getElementById(`count_${menuId}`).innerText;
        totalAmount += amount;
        addToCart(menuId, amount);
        updateTotalAmount();
        updateItemCount(menuId, ++currentItemCount);

    }

    function subtractFromTotal(menuId, amount) {

        let currentItemCount = document.getElementById(`count_${menuId}`).innerText;
        if (currentItemCount > 0) {
            totalAmount -= amount;
            removeFromCart(menuId, amount)
            updateTotalAmount();
            updateItemCount(menuId, --currentItemCount);
        }
    }

    // to update total amount
    function updateTotalAmount() {

        document.getElementById('totalAmount').innerText = `Rs ${Math.max(totalAmount, 0)}`;
    }

    // to update individual count 
    function updateItemCount(menuId, count) {
        const countElement = document.getElementById(`count_${menuId}`);
        countElement.innerText = count;
    }

    //ajax call to add item in cart
    function addToCart(menuId, amount) {
        const url = "addtocart";

        var csrf_token = "{{ csrf_token() }}";

        $.ajax({
            type: "POST",
            url: url,
            data: {
                menuId: menuId,
                price: amount
            },
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            contentType: 'application/x-www-form-urlencoded',
            success: function(response) {
                console.log('Item added to cart:', response);
                updateAllCategoryHeadings();
            },
            error: function(error) {
                console.error('Error adding item to cart:', error);
            }
        });
    }

    //ajax call to remove item in cart
    function removeFromCart(menuId, amount) {
        const url = "removefromcart";
        var csrf_token = "{{ csrf_token() }}";

        $.ajax({
            type: "POST",
            url: url,
            data: {
                menuId: menuId,
                price: amount
            },
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function(response) {
                console.log('Item removed from cart:', response);
                // Handle the success response if needed
                updateAllCategoryHeadings();
            },
            error: function(error) {
                console.error('Error removing item from cart:', error);
                // Handle the error if needed
            }
        });
    }

    //ajax call to clear cart

    function clearCart() {
        const url = "clearcart";

        var csrf_token = "{{ csrf_token() }}";

        $.ajax({
            type: "POST",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            contentType: 'application/x-www-form-urlencoded',
            success: function(response) {
                console.log(response);
                location.reload();
            },
            error: function(error) {
                console.error('Error ', error);
            }
        });
    }

    // Function to count selected items for each category
    function countSelectedItems(categoryName) {
        const categoryDropdown = document.getElementById(`${categoryName}Dropdown`);
        const menuItems = categoryDropdown.querySelectorAll('.menu-item');
        let selectedCount = 0;
        menuItems.forEach(item => {
            const itemCount = parseInt(item.querySelector('.itemCount').innerText);
            selectedCount += isNaN(itemCount) ? 0 : itemCount;
        });

        return selectedCount;
    }

    // Function to update category heading with selected items count
    function updateCategoryHeading(categoryName) {
        const categoryCount = countSelectedItems(categoryName);
        const categoryHeading = document.querySelector(`.category-heading[data-category="${categoryName}"]`);
        if (categoryHeading) {
            if (categoryCount > 0) {
                //show
                categoryHeading.querySelector('.selected-count').style.display = 'inline-block';
                categoryHeading.querySelector('.selected-count').innerText = categoryCount;
            } else {
                categoryHeading.querySelector('.selected-count').innerText = '0';
                //hide
                categoryHeading.querySelector('.selected-count').style.display = 'none';
            }
        }
    }

    // Function to update all category headings with selected items count
    function updateAllCategoryHeadings() {

        const categories = document.querySelectorAll('.category-heading');
        categories.forEach(category => {
            const categoryName = category.dataset.category;
            updateCategoryHeading(categoryName);
        });
        updateTotalItems();
    }

    // dom loaded
    document.addEventListener('DOMContentLoaded', () => {
        // Call updateAllCategoryHeadings initially to update all headings when the page loads
        updateAllCategoryHeadings();

    });

    function updateTotalItems() {
        const totalItemsCount = document.getElementById('total-items-count');
        const totalItems = document.querySelectorAll('.selected-count');
        let totalCount = 0;
        totalItems.forEach(item => {
            console.log(item.innerText);
            totalCount += parseInt(item.innerText);
        });
        totalItemsCount.innerText = totalCount;
    }
</script>
