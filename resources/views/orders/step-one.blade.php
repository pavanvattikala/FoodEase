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
                                        <span id="count_{{ $menu->id }}" class="text-lg font-semibold">{{ $menu->initialCount ?? 0 }}</span>
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
            <h2 class="text-2xl font-semibold">Total Amount: <span id="totalAmount">Rs 0</span></h2>
        </div>

        <a href="{{ route("waiter.order.step.two") }}">
            <button class="bg-green-500 text-white px-4 py-2 rounded relative m-2">
                Shopping Cart
                <span id="cartBadge" class="bg-red-500 text-black px-2 py-1 rounded-full absolute top-0 right-0 -mt-1 -mr-1 hidden">9</span>
            </button>
        </a>
        
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
    let itemCounts = {};

    function addToTotal(menuId, amount) {
        itemCounts[menuId] = (itemCounts[menuId] || 0) + 1;
        totalAmount += amount;
        addToCart(menuId);
        updateTotalAmount();
        updateItemCount(menuId);
        
    }

    function subtractFromTotal(menuId, amount) {
        if (itemCounts[menuId] && itemCounts[menuId] > 0) {
            itemCounts[menuId] -= 1;
            totalAmount -= amount;
            updateTotalAmount();
            updateItemCount(menuId);
        }
    }

    function updateTotalAmount() {
        document.getElementById('totalAmount').innerText = `Rs ${Math.max(totalAmount, 0)}`;
    }

    function updateItemCount(menuId) {
        const countElement = document.getElementById(`count_${menuId}`);
        if (countElement) {
            countElement.innerText = itemCounts[menuId] || 0;
        }
    }

    function addToCart(menuId) {
    const url = "addtocart";  // Correct the URL to the correct endpoint

    var csrf_token = "{{ csrf_token()  }}";

    $.ajax({
        type: "POST",
        url: url,
        data: { menuId: menuId },  
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

</script>
  