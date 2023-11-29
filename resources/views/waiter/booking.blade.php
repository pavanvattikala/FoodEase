<x-guest-layout>
    <div class="container mx-auto p-8 text-center">

        <!-- Countdown Timer -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-4 text-gray-800">Countdown Timer</h2>
            <div class="grid grid-flow-col gap-5 auto-cols-max">
                <div class="countdown-item">
                    <span id="minutes" class="countdown-number"></span>
                    <span class="countdown-label">min</span>
                </div>
                <div class="countdown-item">
                    <span id="seconds" class="countdown-number"></span>
                    <span class="countdown-label">sec</span>
                </div>
            </div>
        </div>

        <!-- Message from Controller -->
        <p class="text-gray-600" id="message">Loading...</p>
    </div>

    <script>
        // Fetch countdown values from Blade template compact
        var countdownValues = {
            minutes: {{ $minutes }},
            seconds: {{ $seconds }}
        };

        function updateCountdown() {
            document.getElementById('minutes').textContent = countdownValues.minutes;
            document.getElementById('seconds').textContent = countdownValues.seconds;

            countdownValues.seconds--;

            if (countdownValues.seconds < 0) {
                countdownValues.seconds = 59;
                countdownValues.minutes--;

                if (countdownValues.minutes < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('minutes').textContent = '0';
                    document.getElementById('seconds').textContent = '0';
                }
            }
        }

        function updateMessage(message) {
            document.getElementById('message').textContent = message;
        }

        // Update countdown every second
        var countdownInterval = setInterval(updateCountdown, 1000);

        // Store countdown values in localStorage
        localStorage.setItem('countdownValues', JSON.stringify(countdownValues));
    </script>
</x-guest-layout>
