<x-waiter-layout>
    <div class="container max-w-max px-2 processing-orders">
        <h1 class="text-2xl font-semibold w-full align-top text-center">Orders {{ ucfirst(basename(Request::url()) )}} </h1>
        <div class="flex flex-wrap">
            @if($orders->isEmpty())
                <p class="text-gray-500 w-full">No order history available.</p>
            @else
                @foreach($orders as $order)
                    <x-order-component-for-waiter :order="$order"/>
                @endforeach
            @endif
        </div>
    </div>

    <script>

        function markAsServed(orderId){
            $.ajax({
                type: "POST",
                url: "{{ route('waiter.order.mark.as.served',[],false) }}",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                data: {
                    orderId: orderId
                },
                success: function (response) {
                    console.log(response);
                    if(response.status === 'success'){
                        location.reload();
                    }else{
                        alert('Something went wrong');
                    }
                }
            });
        }

    </script>
</x-waiter-layout>
