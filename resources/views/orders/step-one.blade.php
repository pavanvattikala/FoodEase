<x-waiter-layout>
    <div class="container w-full px-2 py-2 mx-2">
        <div class=" flex flex-col">
            @foreach ($categoriesWithMenus as $category)
                <div class="category mb-12">
                    <h2 class="flex items-center justify-between text-xl font-semibold mb-2 cursor-pointer border border-gray-300 rounded p-2 bg-gray-100" onclick="toggleDropdown('{{ $category->name }}')">
                        {{ $category->name }}
                        <span id="{{ $category->name }}Arrow">&#9662;</span>
                    </h2>
                    <div id="{{ $category->name }}Dropdown" class=" flex flex-wrap">
                        @foreach ($category->menus as $menu)
                        
                            <div class="max-w-screen-sm md:max-w-1/2 mx-1 mb-1 p-1 rounded-lg shadow-lg">
                                <img class="w-full h-20 object-cover" src="{{ Storage::url($menu->image) }}" alt="Image" />
                                <div class="flex flex-auto text-center px-6 py-4 items-center">
                                    <h4 class="text-xl font-semibold tracking-tight text-green-600 uppercase">
                                        {{ $menu->name }}
                                    </h4>
                                    <h4 class="text-sm font-semibold tracking-tight text-blue-600 lowercase ml-2">
                                        Rs {{ $menu->price }}
                                    </h4>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-100">
                                    <div class="flex items-center">
                                        <button class="bg-green-500 text-ipwhite px-4 py-2 mr-2 rounded" onclick="addToTotal({{ $menu->id }}, {{ $menu->price }})">+</button>
                                        <span id="count_{{ $menu->id }}" class="text-lg font-semibold">                    {{ session('cart.' . $menu->id . '.quantity', 0) }}
                                        </span>
                                        <button class="bg-red-500 text-white px-4 py-2 ml-2 rounded" onclick="subtractFromTotal({{ $menu->id }}, {{ $menu->price }})">-</button>
                                    </div>
                                </div>
                            </div>
                            
                        @endforeach
                    </div>
                </div>
            @endforeach   
        </div>
        
        <div class="mt-8">
            <h2 class="text-2xl font-semibold">Total Amount: <span id="totalAmount">Rs {{  session('cart.total' , 0)  }}</span></h2>
        </div>

        <a href="{{ route("waiter.order.cart") }}">
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

function toggleDropdown(categoryName) {
        const dropdown = document.getElementById(`${categoryName}Dropdown`);
        const arrow = document.getElementById(`${categoryName}Arrow`);
        dropdown.classList.toggle('hidden');
        arrow.innerHTML = dropdown.classList.contains('hidden') ? '&#9662;' : '&#9652;';
    }
    
    let totalAmount = 0;

    function addToTotal(menuId, amount) {
        let currentItemCount =  document.getElementById(`count_${menuId}`).innerText;
        totalAmount += amount;
        addToCart(menuId,amount);
        updateTotalAmount();
        updateItemCount(menuId,++currentItemCount);
        
    }

    function subtractFromTotal(menuId, amount) {

        let currentItemCount =  document.getElementById(`count_${menuId}`).innerText;
        if (currentItemCount > 0) {
            totalAmount -= amount;
            removeFromCart(menuId,amount)
            updateTotalAmount();
            updateItemCount(menuId,--currentItemCount);
        }
    }

    function updateTotalAmount() {
        document.getElementById('totalAmount').innerText = `Rs ${Math.max(totalAmount, 0)}`;
    }

    function updateItemCount(menuId,count) {
        const countElement = document.getElementById(`count_${menuId}`);
        countElement.innerText = count;
    }

    function addToCart(menuId,amount) {
    const url = "addtocart";  // Correct the URL to the correct endpoint

    var csrf_token = "{{ csrf_token()  }}";

    $.ajax({
        type: "POST",
        url: url,
        data: { menuId: menuId,price:amount},  
        headers: { 'X-CSRF-TOKEN': csrf_token },
        contentType:'application/x-www-form-urlencoded',
        success: function (response) {
            console.log('Item added to cart:', response);
        },
        error: function (error) {
            console.error('Error adding item to cart:', error);
        }
    });
}

function removeFromCart(menuId,amount) {
    const url = "removefromcart";
    var csrf_token = "{{ csrf_token() }}";

    $.ajax({
        type: "POST",
        url: url,
        data: { menuId: menuId,price:amount},  
        headers: { 'X-CSRF-TOKEN': csrf_token },
        success: function (response) {
            console.log('Item removed from cart:', response);
            // Handle the success response if needed
        },
        error: function (error) {
            console.error('Error removing item from cart:', error);
            // Handle the error if needed
        }
    });
}

function clearCart() {
    const url = "clearcart";  

    var csrf_token = "{{ csrf_token()  }}";

    $.ajax({
        type: "POST",
        url: url,
        headers: { 'X-CSRF-TOKEN': csrf_token },
        contentType:'application/x-www-form-urlencoded',
        success: function (response) {
            console.log(response);
            location.reload(); 
        },
        error: function (error) {
            console.error('Error ', error);
        }
    });
}


</script>
  