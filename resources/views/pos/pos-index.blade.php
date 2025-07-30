<x-pos-layout>

    @section('title', 'POS')

    <div id="pos" class="flex flex-col h-[90vh] bg-gray-100 font-sans">

        <header id="order-main-nav"
            class="flex items-center justify-between w-full bg-gray-800 text-white shadow-md z-10 px-4 py-2 shrink-0">
            <div id="items-search-options" class="flex-grow flex items-center gap-x-4 w-2/3">
                <input id="search-input"
                    class="w-1/2 p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 placeholder-gray-400"
                    type="text" placeholder="Search by Name..">
                <input id="shortcode-input"
                    class="w-1/2 p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 placeholder-gray-400"
                    type="text" placeholder="By ShortCode..">
            </div>
            <div id="order-type-options" class="flex items-center justify-end w-1/3 ml-4">
                @php $orderType = $orderType->value; @endphp
                <div id="dine_in"
                    class="h-full px-4 py-2 text-center cursor-pointer rounded-l-lg {{ $orderType == 'dine_in' ? 'bg-green-700 font-bold' : 'bg-gray-600 hover:bg-gray-700' }}">
                    <p>Dine In</p>
                </div>
                <div id="takeaway"
                    class="h-full px-4 py-2 text-center cursor-pointer rounded-r-lg {{ $orderType == 'takeaway' ? 'bg-green-700 font-bold' : 'bg-gray-600 hover:bg-gray-700' }}">
                    <p>Take Away</p>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">

            <div class="w-3/5 flex">
                <aside id="category" class="category w-1/4 bg-gray-200 overflow-y-auto border-r border-gray-300 p-2">
                    <div class="flex flex-col gap-y-2">
                        @foreach ($categoriesWithMenus as $category)
                            <button
                                class="btn w-full text-left font-semibold p-3 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 bg-white text-gray-700 hover:bg-green-50 hover:text-green-800"
                                id="c{{ $category->id }}-btn" onclick="showMenu('c{{ $category->id }}')">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </aside>

                <main class="w-3/4 bg-white overflow-y-auto p-4">
                    @foreach ($categoriesWithMenus as $category)
                        <div id="c{{ $category->id }}" class="menu-items hidden">
                            <div
                                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                @foreach ($category->menus as $menu)
                                    <button
                                        class="flex flex-col justify-center items-center text-center p-2 h-24 rounded-lg shadow-md bg-green-600 text-white font-semibold transition-transform transform hover:-translate-y-1 hover:shadow-lg"
                                        id="{{ $menu->id }}" onclick="addItemToOrder({{ $menu->id }})"
                                        data-price="{{ $menu->price }}" data-shortcode="{{ $menu->shortcode }}">
                                        {{-- JS will auto-format this with <br> tags --}}
                                        {{ $menu->name }}
                                    </button>
                                    @php $menuShortCuts[$menu->shortcode] = $menu->id; @endphp
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </main>
            </div>

            <aside id="order-panel" class="w-2/5 bg-gray-100 flex flex-col border-l border-gray-300">
                <div id="order-options-parent"
                    class="flex items-center justify-around p-2 border-b border-gray-200 bg-white shrink-0">
                    <button
                        class="btn order-options relative flex flex-col items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-800"
                        id="count" title="Number of Items">
                        <i class="fa fa-list text-xl"></i>
                        <span id="item-count" class="text-sm font-bold">0</span>
                    </button>
                    <button
                        class="btn order-options flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-800"
                        id="add-notes-btn" title="Add Notes">
                        <i class="fa fa-sticky-note text-xl"></i>
                    </button>
                    @if ($table)
                        <button data-tableid="{{ $table->id }}"
                            class="btn order-options flex flex-col items-center justify-center w-16 h-16 rounded-full bg-purple-100 text-purple-800"
                            id="table" title="Assign to Table">
                            <i class="fa fa-table text-xl"></i>
                            <span class="text-xs font-bold">{{ $table->name }}</span>
                        </button>
                    @endif
                </div>

                <div class="flex-grow overflow-hidden flex flex-col bg-white">
                    <table class="w-full shrink-0">
                        <thead class="bg-gray-700 text-white">
                            <tr id="order-items-heading">
                                <th class="p-2 text-left font-semibold w-3/6">Item</th>
                                <th class="p-2 font-semibold w-2/6">Qty</th>
                                <th class="p-2 font-semibold w-1/6">Amount</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="flex-grow overflow-y-auto">
                        <table class="w-full">
                            <tbody id="order-items-body" class="divide-y divide-gray-200">
                                <tr id="noitems">
                                    <td colspan="3" class="text-center text-gray-400 p-8">
                                        <i class="fa fa-shopping-cart text-4xl mb-2"></i>
                                        <p>No Items Selected</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <table class="w-full shrink-0">
                        <tfoot class="bg-gray-200 font-bold">
                            <tr class="text-lg text-gray-800">
                                <td class="p-3 text-left" colspan="2">Total</td>
                                <td id="total" class="p-3 text-right">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="p-2 bg-gray-100 border-t border-gray-200 shrink-0">
                    <div id="payment-types" class="flex items-center justify-around gap-2 mb-2">
                        @foreach ($paymentTypes as $paymentType)
                            <label
                                class="flex-1 flex items-center justify-center p-2 rounded-lg border bg-white cursor-pointer has-[:checked]:bg-green-50 has-[:checked]:border-green-400 has-[:checked]:ring-2 has-[:checked]:ring-green-200">
                                <input id="{{ $paymentType }}" type="radio" value="{{ $paymentType }}"
                                    name="payment-type" class="h-4 w-4 text-green-600 focus:ring-green-500"
                                    {{ $loop->first ? 'checked' : '' }}>
                                <span class="ml-2 font-medium text-gray-700">{{ Str::upper($paymentType) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="save-and-bill-options" class="grid grid-cols-3 gap-2">
                        <button
                            class="btn flex items-center justify-center gap-2 col-span-1 py-3 rounded-lg bg-green-600 text-white font-bold hover:bg-green-700 transition-colors"
                            id="bill-order"><i class="fa fa-print"></i> Bill</button>
                        <button
                            class="btn flex items-center justify-center gap-2 col-span-1 py-3 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 transition-colors"
                            id="kot-order"><i class="fa fa-receipt"></i> KOT</button>
                        <button
                            class="btn flex items-center justify-center gap-2 col-span-1 py-3 rounded-lg bg-red-600 text-white font-bold hover:bg-red-700 transition-colors"
                            id="cancel-order"><i class="fa fa-times"></i> Cancel</button>
                    </div>
                </div>
            </aside>
        </div>

        <div id="addNotesModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60"
            style="display: none;">
            <div class="modal-overlay absolute inset-0" tabindex="-1" data-close="addNotesModal"></div>
            <div class="modal-container bg-white rounded-xl shadow-2xl w-full max-w-md m-4 relative">
                <div class="modal-header flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-800">Add Special Instructions</h3>
                    <button class="modal-close text-gray-400 hover:text-gray-600 font-bold py-1 px-3"
                        data-close="addNotesModal">&times;</button>
                </div>
                <div class="modal-body p-6">
                    <div class="form-group mb-6">
                        <label class="block text-md font-medium text-gray-700 mb-3">Select from predefined
                            notes:</label>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                            @foreach ($predefinedNotes as $note)
                                <label for="{{ $note }}"
                                    class="flex items-center text-gray-600 cursor-pointer">
                                    <input type="checkbox" name="notes" id="{{ $note }}"
                                        class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-3 capitalize">{{ $note }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="customNotes" class="block text-md font-medium text-gray-700 mb-2">Or type a custom
                            note:</label>
                        <textarea name="extra-notes" id="customNotes"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer flex justify-end p-4 bg-gray-50 border-t rounded-b-xl">
                    <button type="button"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold mr-2 transition-colors"
                        data-close="addNotesModal">Close</button>
                    <button type="button"
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors"
                        id="saveNotesBtn">Save Notes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- The original script block is preserved to ensure functionality remains unchanged --}}
    <script>
        var orderItems = [];
        const menuShortCuts = @json($menuShortCuts);
        const selectedNotes = [];
        const SOURCE = 'pos';
        let customerData = null;
        let audioUrl = "{{ asset('audio/select.wav') }}";
        let hasPrevOrders = false;
        let hasNewOrders = false;
        const orderSubmitUrl = "{{ route('order.submit', [], false) }}";
        const billTableUrl = "{{ route('pos.table.bill', [], false) }}";
        const indexUrl = "{{ route('pos.tables', [], false) }}";
        const settleTableUrl = "{{ route('pos.table.settle', [], false) }}";
    </script>
    <script src="{{ asset('js/pos.js') }}"></script>
    <style>
        /* Custom styles for dynamically generated elements from pos.js */
        #order-items-body tr {
            display: flex;
            width: 100%;
            align-items: center;
            padding: 0.5rem 0.25rem;
        }

        #order-items-body tr td {
            padding: 0.25rem;
        }

        #order-items-body tr td:nth-child(1) {
            width: 50%;
            display: flex;
            align-items: center;
        }

        /* 3/6 */
        #order-items-body tr td:nth-child(2) {
            width: 33.33%;
            text-align: center;
        }

        /* 2/6 */
        #order-items-body tr td:nth-child(3) {
            width: 16.66%;
            text-align: right;
            padding-right: 0.5rem;
        }

        /* 1/6 */

        .del-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #ef4444;
            /* red-500 */
            color: white;
            border-radius: 50%;
            width: 24px !important;
            height: 24px !important;
            margin-right: 10px;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .del-item:hover {
            background-color: #dc2626;
        }

        /* red-600 */

        .qty-options {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            font-size: 1.2rem;
            line-height: 1;
            transition: background-color 0.2s;
        }

        .addQty {
            background-color: #22c55e;
            margin-left: 1rem;
        }

        /* green-500 */
        .addQty:hover {
            background-color: #16a34a;
        }

        /* green-600 */
        .remQty {
            background-color: #f97316;
            margin-right: 1rem;
        }

        /* orange-500 */
        .remQty:hover {
            background-color: #ea580c;
        }

        /* orange-600 */

        /* Custom scrollbar for a better look */
        #category::-webkit-scrollbar,
        main::-webkit-scrollbar,
        #order-items-table+div::-webkit-scrollbar {
            width: 8px;
        }

        #category::-webkit-scrollbar-thumb,
        main::-webkit-scrollbar-thumb,
        #order-items-table+div::-webkit-scrollbar-thumb {
            background-color: #a7a7a7;
            border-radius: 10px;
        }
    </style>
</x-pos-layout>
