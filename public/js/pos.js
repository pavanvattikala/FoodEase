function showMenu(categoryId) {
    $(".menu-items").addClass("hidden");
    $(".category button").removeClass("active");
    const menuItems = document.getElementById(categoryId);

    menuItems.classList.toggle("hidden");

    $("#" + categoryId + "-btn").addClass("active");
}

function renderOrderTable() {
    const orderItemsBody = $("#order-items-body");
    orderItemsBody.empty(); // Clear existing content
    var count = 0;

    orderItems.forEach((item) => {
        const tr = $(`
                        <tr>
                            <td>
                                <button class="del-item" onclick="delItem(${item.id})">X</button>
                                <span>${item.name}</span>
                            </td>
                            <td>
                                <button class="qty-options remQty" onclick="remQty(${item.id})">-</button>
                                <span id="qty">${item.quantity}</span>
                                <button class="qty-options addQty" onclick="addQty(${item.id})">+</button>
                            </td>
                            <td>${item.total}</td>
                        </tr>
                    `);
        orderItemsBody.append(tr);
        count++;
    });
    $("#item-count").text(count);

    calculateTotal();
    $("input[type='text']").val("");
    $(".menu-items button").show();
}

function scrollToTop() {
    const lastChild = $("#order-items-body")[0].lastElementChild;

    lastChild.scrollIntoView({
        behavior: "smooth",
        block: "start",
    });
}

function addItemToOrder(menuId) {
    playAudio();
    if ($("#noitems").length > 0) {
        $("#noitems").remove();
    }
    const menu = $("#" + menuId);
    const existingItem = orderItems.find((item) => item.id === menuId);

    if (existingItem) {
        existingItem.quantity++;
        existingItem.total = existingItem.quantity * existingItem.price;
    } else {
        const newItem = {
            id: menuId,
            name: menu.text(),
            quantity: 1,
            price: Number(menu.data("price")),
            total: Number(menu.data("price")),
        };
        orderItems.push(newItem);
    }
    renderOrderTable();
    scrollToTop();
}

function playAudio() {
    var audio = new Audio(audioUrl);
    audio.volume = 0.1;
    audio.play();
}

function addQty(menuId) {
    const item = orderItems.find((item) => item.id === menuId);
    if (item) {
        item.quantity++;
        item.total = item.quantity * item.price;
        renderOrderTable();
    }
}

function remQty(menuId) {
    const item = orderItems.find((item) => item.id === menuId);
    if (item) {
        if (item.quantity > 1) {
            item.quantity--;
            item.total = item.quantity * item.price;
        } else {
            // Remove the item if quantity becomes 0
            orderItems.splice(orderItems.indexOf(item), 1);
        }
        renderOrderTable();
    }
}

function calculateTotal() {
    let total = 0;
    orderItems.forEach((item) => {
        total += Number(item.total);
    });

    const discount = Number($("#discount").text());
    const grandtotal = Number(total - discount);

    $("#grandtotal").text(grandtotal);
    $("#total").text(total);
}

// DOM loaded
$(document).ready(function () {
    $(".category button:first-child").click();

    $("#shortcode-input").keypress(function (event) {
        console.log(event);
        if (event.which === 13) {
            event.preventDefault();
            searchByShortcode();
        }
    });

    $("#customerName, #mobileNumber").on("keyup", function (event) {
        // Check if Enter key is pressed (key code 13)
        if (event.which === 13) {
            // Trigger the click event on the save button
            $("#saveCustomerDataBtn").click();
        }
    });

    //use time out to prevent multiple calls
    var timer = null;
    $("#search-input").keyup(function () {
        clearTimeout(timer);
        timer = setTimeout(searchByName, 500);
    });
});

function searchByShortcode() {
    var shortcodeInput = $("#shortcode-input").val().toLowerCase();
    const menuId = menuShortCuts[shortcodeInput];
    if (menuId) {
        addItemToOrder(menuId);
    } else {
        alert("Invalid Shortcode");
    }
    $("#shortcode-input").val("");
    $("#shortcode-input").focus();
}

function searchByName() {
    const searchInput = $("#search-input").val().toLowerCase().trim();
    console.log("called");
    if (searchInput === "") {
        $(".menu-items button").show();
        return;
    }

    $(".menu-items button").each(function () {
        const menuItem = $(this);
        const menuItemName = menuItem.text().toLowerCase();

        if (
            menuItemName.startsWith(searchInput) ||
            menuItemName.includes(searchInput)
        ) {
            var menudivId = menuItem.parent().attr("id");
            showMenu(menudivId);
            menuItem.show();
        } else {
            menuItem.hide();
        }
    });
}

$("#cancel-order").click(function () {
    orderItems.splice(0, orderItems.length);
    renderOrderTable();
    $("#order-items-body").append(`
            <tr id="noitems">
                <td colspan="3">No Items Selected</td>
            </tr>
        `);
    $("input[type='radio']").prop("checked", false);
    $("textarea").val("");
    $("input[type='checkbox']").prop("checked", false);
});

function delItem(menuId) {
    const item = orderItems.find((item) => item.id === menuId);
    if (item) {
        orderItems.splice(orderItems.indexOf(item), 1);
        renderOrderTable();
    }
}

function setOrderType(orderType) {
    $("#order-type-options button").removeClass("active");
    $("#" + orderType).addClass("active");
    console.log("Selected Order Type:", orderType);
}

document.getElementById("add-notes-btn").addEventListener("click", function () {
    document.getElementById("addNotesModal").style.display = "block";
});

document
    .querySelectorAll('[data-close="addNotesModal"]')
    .forEach(function (element) {
        element.addEventListener("click", function () {
            document.getElementById("addNotesModal").style.display = "none";
        });
    });

document.getElementById("saveNotesBtn").addEventListener("click", function () {
    $('input[name="notes"]:checked').each(function () {
        selectedNotes.push($(this).next().text());
    });

    const customNotesValue = $("#customNotes").val().trim();
    if (customNotesValue !== "") {
        const customNotesArray = customNotesValue.split(",");
        selectedNotes.push(...customNotesArray);
    }

    document.getElementById("addNotesModal").style.display = "none";
});

$("#save-order").click(function () {
    //validate order
    if (orderItems.length === 0) {
        alert("No Items Selected");
        return;
    }
    var tableId = null;

    if ($("#takeaway").hasClass("active")) {
        tableId = null;
    } else {
        tableId = $("#table").data("tableid");
    }

    const order = {
        orderItems: orderItems,
        paymentType: $("input[name='payment-type']:checked").next().text(),
        specialInstructions: selectedNotes,
        customer: customerData,
        tableId: tableId,
        total: $("#total").text(),
        discount: $("#discount").text(),
        grandtotal: $("#grandtotal").text(),
    };
    const printBill = $("#print-options input[name='bill']").prop("checked");
    const printKOT = $("#print-options input[name='kot']").prop("checked");

    console.log(order);
    var csrf_token = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('order.submit', [], false) }}",
        type: "POST",
        data: {
            order: order,
            source: "pos",
            printKOT: printKOT,
            printBill: printBill,
        },
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        contentType: "application/x-www-form-urlencoded",
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                alert("Order Saved Successfully");
                $("#cancel-order").click();
                window.location.replace(" {{ route('pos.index') }}");
            } else {
                alert("Order Save Failed");
            }
        },
        error: function (error) {
            console.log(error);
            alert("Order Save Failed");
        },
    });
});

document
    .getElementById("add-customer-btn")
    .addEventListener("click", function () {
        document.getElementById("customerDataModal").style.display = "block";
        $("#customerName").focus();
    });

document
    .querySelectorAll('[data-close="customerDataModal"]')
    .forEach(function (element) {
        element.addEventListener("click", function () {
            document.getElementById("customerDataModal").style.display = "none";
        });
    });

document
    .getElementById("saveCustomerDataBtn")
    .addEventListener("click", function () {
        const customerName = $("#customerName").val();
        const mobileNumber = $("#mobileNumber").val();

        customerData = {
            customerName,
            mobileNumber,
        };
        document.getElementById("customerDataModal").style.display = "none";
    });

// old KOT functions

$("#prev-kots").click(function () {
    $("#prev-kots table tbody").toggleClass("hidden");
});
