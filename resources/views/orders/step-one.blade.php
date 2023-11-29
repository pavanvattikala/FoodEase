<x-guest-layout>
  <div class="container w-full px-5 py-6 mx-auto">
      <div class="categories-wrapper flex flex-col">
          @foreach ($categories as $category)
              <div class="category mb-6">
                  <h2 class="text-xl font-semibold mb-2">{{ $category->name }}</h2>
                  <div class="menu-items flex flex-nowrap overflow-x-auto">
                      @foreach ($menus->where('category_id', $category->id) as $menu)
                          <div class="max-w-xs mx-4 mb-4 rounded-lg overflow-hidden shadow-lg">
                              <img class="w-full h-48 object-cover" src="{{ Storage::url($menu->image) }}" alt="Image" />
                              <div class="px-6 py-4">
                                  <h4 class="mb-2 text-xl font-semibold tracking-tight text-green-600 uppercase">
                                      {{ $menu->name }}
                                  </h4>
                                  <p class="text-gray-700">{{ $menu->description }}.</p>
                              </div>
                              <div class="flex items-center justify-between p-4 bg-gray-100">
                                  <span class="text-xl text-green-600">Rs {{ $menu->price }}</span>
                                  <div class="flex items-center">
                                      <button class="bg-green-500 text-white px-4 py-2 mr-2 rounded" onclick="addToTotal({{ $menu->id }}, {{ $menu->price }})">+</button>
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
  </div>

  <script>
      // ... your existing JavaScript code ...
  </script>
</x-guest-layout>

<style>
  .categories-wrapper {
      overflow-y: hidden;
      overflow-x: auto;
  }

  .category {
      margin-right: 16px; /* Adjust spacing between categories */
  }

  .menu-items {
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
  }

  .max-w-xs {
      width: 100%; /* Ensure menu items take full width */
  }
</style>
