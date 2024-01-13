document.addEventListener("DOMContentLoaded", function () {
    // Check the last segment of the URL
    var lastSegment = window.location.pathname.split("/").pop();

    // Call the appropriate function based on the last segment
    if (lastSegment === "running") {
        //setInterval(checkOrderUpdates, 5000);
    } else if (lastSegment === "ready-for-pickup") {
        setInterval(checkPickUpOrderUpdates, waiterSyncTime);
    }
});

function markAsServed(orderId) {
    $.ajax({
        type: "POST",
        url: markAsServedRoute,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        data: {
            orderId: orderId,
        },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                $("#order" + orderId).remove();
            } else {
                alert("Something went wrong");
            }
        },
    });
}

// function checkOrderUpdates() {
//     var lastOrderId = getLastOrderId();
//     console.log('Last Order Id:', lastOrderId);
//     $.ajax({
//         url: checkOrderUpdatesRoute,
//         method: 'GET',
//         dataType: 'json',
//         data:{
//             lastOrderId: lastOrderId
//         },
//         success: function (data) {
//             $("#orders-list").prepend(data.html);
//         },
//         error: function (error) {
//             console.error('Error checking for updates:', error);
//         }
//     });
// }

function checkPickUpOrderUpdates() {
    var lastOrderId = getLastOrderId();
    $.ajax({
        url: checkPickUpOrderUpdatesRoute,
        method: "GET",
        dataType: "json",
        data: {
            lastOrderId: lastOrderId,
        },
        success: function (data) {
            $("#orders-list").prepend(data.html);
        },
        error: function (error) {
            console.error("Error checking for updates:", error);
        },
    });
}

function getLastOrderId() {
    if ($("#noOrders").length === 1) {
        return -1;
    }

    var id = $("#orders-list").children().first().attr("id");
    return id.replace("order", "");
}
