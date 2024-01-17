<x-pos-layout>
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    <div class="flex flex-row mb-2 mt-2" id="order-main-nav">
        <div class="flex flex-row mw-60" id=items-search-options>
            <input id="search-input" type="text" placeholder="Search by Name.." name="search">
            <input id="shortcode-input" type="text" placeholder="By ShortCode.." name="search-by-shortcode">
        </div>

        <div id="order-type-options" class="mw-40 flex flex-row align-middle text-center">
            @php
                $orderType = session()->get('orderType');
            @endphp
            <div id="dine_in" class="h-full mw-40 @if ($orderType == 'dine_in') active @endif">
                <p>Dine In</p>
            </div>
            <div id="takeaway" class="h-full mw-40 @if ($orderType == 'takeaway') active @endif">
                <p>Take Away</p>
            </div>

        </div>

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
                <button class="btn order-options" id="add-notes-btn" title="Add Notes">
                    <i class="fa fa-sticky-note"></i>
                </button>
                <button class="btn order-options" id="add-customer-btn" title="Assign to Customer">
                    <i class="fa fa-user"></i>
                </button>

                @if (session()->has('tableData'))
                    @php
                        $tableData = session()->get('tableData');
                    @endphp
                    <button data-tableid={{ $tableData['tableId'] }} class="btn order-options" id="table"
                        title="Assign to Table">
                        <i class="fa fa-table"></i>
                        <br>
                        {{ session()->get('tableData')['tableName'] }}
                    </button>
                @endif
            </div>
            {{-- Display Previous KOTs --}}
            @if ($prevOrders != null && $prevOrders->count() > 0)
                <div id="prev-kots">
                    <table class="table-auto flex flex-col">
                        <thead id="showPrevKots">
                            <tr>
                                <th colspan="3" class="underline">Previous KOTs..</th>
                            </tr>
                        </thead>
                        <tbody class="hidden">
                            @foreach ($prevOrders as $order)
                                <tr>
                                    <td colspan="3">{{ $order->KOT }} &ThickSpace; Time
                                        - {{ $order->created_at->format('h:i:a') }}
                                    </td>
                                </tr>
                                @foreach ($order->orderDetails as $item)
                                    <tr>
                                        <td>
                                            <button class="del-item" onclick="delItem({{ $item->menu_id }})">X</button>
                                            <span>{{ $item->menu->name }}</span>
                                        </td>
                                        <td>
                                            <button class="qty-options remQty"
                                                onclick="remQty({{ $item->menu_id }})">-</button>
                                            <span id="qty">{{ $item->quantity }}</span>
                                            <button class="qty-options addQty"
                                                onclick="addQty({{ $item->menu_id }})">+</button>
                                        </td>
                                        <td>{{ $item->quantity * $item->menu->price }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td colspan="3"> Bill Total {{ $prevOrders->sum('total') }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            @endif
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
                    <input id="cash" type="radio" name="payment-type" selected="true">
                    <label>Cash</label>
                </div>
                <div>
                    <input id="card" type="radio" name="payment-type">
                    <label>Card</label>
                </div>
                <div>
                    <input id="upi" type="radio" name="payment-type">
                    <label>UPI</label>
                </div>
                <div>
                    <input type="radio" name="payment-type" id="due">
                    <label>Due</label>
                </div>
            </div>
            @if ($isTableToBePaid == true)
                <div>
                    <button class="btn" id="settle-order">Settle And Save</button>
                </div>
            @endif
            <div id="save-and-bill-options" class="flex flex-row">
                <div id="save-options" class="flex flex-row">
                    <button class="btn" id="bill-order">Bill & Print</button>
                    <button class="btn" id="kot-order">KOT & Print</button>
                    <button class="btn" id="cancel-order" style="background-color: #f44336">Cancel</button>
                    <button class="btn" id="hold-order" style="background-color: rgb(42, 88, 161)">Hold</button>
                </div>
            </div>

        </div>
        <div id="addNotesModal" class="modal">
            <div class="modal-overlay" tabindex="-1" data-close="addNotesModal"></div>
            <div class="modal-container bg-white mx-auto mt-10 p-6 rounded-lg shadow-lg w-1/2">
                <div class="modal-header flex justify-between items-center border-b pb-4">
                    <span class="text-2xl font-bold">Add Notes</span>
                    <span class="modal-close cursor-pointer" data-close="addNotesModal">&times;</span>
                </div>
                <div class="modal-body mt-4">
                    <div id="notes-data">
                        <div class="form-group mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Select a
                                predefined note:</label>
                            <div class="flex flex-wrap">
                                @foreach ($predefinedNotes as $note)
                                    <div class="w-1/3 pr-2 mb-2 mr-2">
                                        <input type="checkbox" name="notes" id="{{ $note }}"
                                            class="mr-1">
                                        <label for="{{ $note }}"
                                            class="capitalize">{{ $note }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customNotes" class="block text-sm font-medium text-gray-700 mb-2">Or type a
                                new note:</label>
                            <textarea name="extra-notes" id="customNotes" class="w-full p-2 border rounded-md"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-4 flex justify-end">
                    <button type="button" class="btn  mr-2" data-close="addNotesModal">Close</button>
                    <button type="button" class="btn bg-green-500" id="saveNotesBtn">Save Notes</button>
                </div>
            </div>
        </div>
        <div id="customerDataModal" class="modal">
            <div class="modal-overlay" tabindex="-1" data-close="customerDataModal"></div>
            <div class="modal-container bg-white mx-auto mt-10 p-6 rounded-lg shadow-lg w-1/2">
                <div class="modal-header flex justify-between items-center border-b pb-4">
                    <span class="text-2xl font-bold">Customer Data</span>
                    <span class="modal-close cursor-pointer" data-close="customerDataModal">&times;</span>
                </div>
                <div class="modal-body mt-4">
                    <div class="form-group mb-4">
                        <label for="customerName" class="block text-sm font-medium text-gray-700 mb-2">Customer
                            Name:</label>
                        <input type="text" id="customerName" name="customerName"
                            class="w-full p-2 border rounded-md">
                    </div>
                    <div class="form-group mb-4">
                        <label for="mobileNumber" class="block text-sm font-medium text-gray-700 mb-2">Mobile
                            Number:</label>
                        <input type="text" id="mobileNumber" name="mobileNumber"
                            class="w-full p-2 border rounded-md">
                    </div>
                </div>
                <div class="modal-footer mt-4 flex justify-end">
                    <button type="button" class="btn  mr-2" data-close="customerDataModal">Close</button>
                    <button type="button" class="btn bg-green-500" id="saveCustomerDataBtn">Save Customer
                        Data</button>
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

        const selectedNotes = [];

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
</x-pos-layout>
