document.addEventListener("DOMContentLoaded", function () {
    setInterval(checkOrderUpdates, orderSyncTime);
});

function markAsServed(orderId) {
    showLoader();
    $.ajax({
        type: "POST",
        url: markAsServedRoute,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
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
        error: function (error) {
            console.error("Error marking order as served:", error);
        },
        complete: function () {
            hideLoader();
        },
    });
}
function markAsPrepared(orderId) {
    showLoader();
    $.ajax({
        type: "POST",
        url: markAsPreparedRoute,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            orderId: orderId,
        },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                window.location.reload();
            } else {
                alert("Something went wrong");
            }
        },
        error: function (error) {
            console.error("Error marking order as prepared:", error);
        },
        complete: function () {
            hideLoader();
        },
    });
}
function markAsClosed(orderId) {
    showLoader();
    $.ajax({
        type: "POST",
        url: markAsClosedRoute,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            orderId: orderId,
        },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                window.location.reload();
            } else {
                alert("Something went wrong");
            }
        },
        error: function (error) {
            console.error("Error marking order as closed:", error);
        },
        complete: function () {
            hideLoader();
        },
    });
}

function checkOrderUpdates() {
    var lastOrderId = getLastOrderId();
    console.log("Last Order Id:", lastOrderId);
    $.ajax({
        url: checkOrderUpdatesRoute,
        method: "GET",
        dataType: "json",
        data: {
            lastOrderId: lastOrderId,
        },
        success: function (response) {
            console.log(response);
            if (
                response.status === "success" &&
                response.hasNewOrders === true
            ) {
                window.location.reload();
            } else {
                console.log("No new orders found");
            }
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

    var ids = $(".order-item")
        .map(function () {
            return parseInt(this.id.replace("order", ""), 10);
        })
        .get();

    var lastOrderId = Math.max(...ids);
    return lastOrderId;
}
