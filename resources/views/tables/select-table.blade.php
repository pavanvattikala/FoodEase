<x-waiter-layout>
    @section('title', 'Waiter - Choose Table')


    <div class="p-4" x-data="waiterTables()">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Choose a Table</h1>
            <button @click="refreshPage"
                class="flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </button>
        </div>

        <div class="flex flex-wrap justify-end space-x-4 mb-6 text-sm text-gray-600">
            @foreach ($table_colors as $status => $color)
                <div class="flex items-center">
                    <span class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $color }};"></span>
                    <span>{{ ucfirst($status) }}</span>
                </div>
            @endforeach
        </div>

        <div class="space-y-6">
            @foreach ($tablesWithLocations as $location => $tables)
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 border-b-2 border-gray-200 pb-2 mb-4">
                        {{ ucfirst($location) }}
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($tables as $table)
                            <div @click="handleTableClick({{ $table->id }}, '{{ $table->status->value }}')"
                                id="{{ $table->id }}" data-table-status="{{ $table->status->value }}"
                                data-taken-at="{{ $table->taken_at }}"
                                class="relative p-4 rounded-lg shadow-lg text-white cursor-pointer transition-transform transform hover:scale-105"
                                :style="{ 'background-color': getTableColor('{{ $table->status->value }}') }">
                                <div class="text-center">
                                    <h3 class="text-2xl font-bold">{{ $table->name }}</h3>
                                    <p class="text-xs font-medium text-white/80">{{ $table->guest_number }} Guests</p>
                                </div>
                                <div class="absolute top-2 right-2 text-xs font-mono bg-black/20 px-1.5 py-0.5 rounded"
                                    x-show="isTimerVisible('{{ $table->status->value }}')">
                                    <span class="elapsed-time"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Alpine.js component for managing state and logic
        function waiterTables() {
            return {
                // Storing colors and URLs from Blade for easy access in JS
                tableColors: @json($table_colors),
                selectTableURL: "{{ route('waiter.order', ['table' => ':id'], false) }}",

                // Initial setup for timers
                init() {
                    this.updateAllTimers();
                    setInterval(() => this.updateAllTimers(), 60000); // Update every minute
                },

                /**
                 * Gets the appropriate background color for a table status.
                 * @param {string} status - The status of the table (e.g., 'available').
                 * @returns {string} The hex color code.
                 */
                getTableColor(status) {
                    return this.tableColors[status] || '#E5E7EB'; // Default to gray
                },

                /**
                 * Determines if the timer should be visible for a given status.
                 * @param {string} status - The table status.
                 * @returns {boolean}
                 */
                isTimerVisible(status) {
                    return ['running', 'printed'].includes(status);
                },

                /**
                 * Handles clicks on a table.
                 * Redirects to the order page for any table.
                 * @param {number} tableId - The ID of the table.
                 */
                handleTableClick(tableId) {
                    // Per your request, any click on a table redirects to the order screen.
                    // The backend will handle the logic for new vs. existing orders.
                    const url = this.selectTableURL.replace(':id', tableId);
                    window.location.href = url;
                },

                /**
                 * Updates the elapsed time display for all applicable tables.
                 */
                updateAllTimers() {
                    document.querySelectorAll('[data-table-status]').forEach(tableEl => {
                        const status = tableEl.dataset.tableStatus;
                        if (this.isTimerVisible(status)) {
                            const takenAt = tableEl.dataset.takenAt;
                            if (takenAt) {
                                const timerSpan = tableEl.querySelector('.elapsed-time');
                                timerSpan.textContent = this.getElapsedString(takenAt);
                            }
                        }
                    });
                },

                /**
                 * Calculates the elapsed time string (e.g., "1H:15M").
                 * @param {string} startTimeISO - The ISO 8601 timestamp when the table was taken.
                 * @returns {string} The formatted elapsed time.
                 */
                getElapsedString(startTimeISO) {
                    if (!startTimeISO) return '';
                    const startTime = new Date(startTimeISO);
                    const now = new Date();
                    const diffMs = now - startTime;

                    const hours = Math.floor(diffMs / 3600000);
                    const minutes = Math.floor((diffMs % 3600000) / 60000);

                    return `${hours}H:${String(minutes).padStart(2, '0')}M`;
                },

                /**
                 * Simple page reload function.
                 */
                refreshPage() {
                    location.reload();
                }
            }
        }
    </script>
</x-waiter-layout>
