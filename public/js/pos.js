// Author: Pavan Vattikala

// Menu Function Start
function showMenu(categoryId) {
    $(".menu-items").addClass("hidden");
    $(".category button").removeClass("active");
    const menuItems = document.getElementById(categoryId);

    menuItems.classList.toggle("hidden");

    $("#" + categoryId + "-btn").addClass("active");
}

// Menu Function End

//------------------------------------------------------------------------------------------------------------------------------

// Order Table Functions Start

// render the order table
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

// add item to order
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

// increase quantity of an item
function addQty(menuId) {
    const item = orderItems.find((item) => item.id === menuId);
    if (item) {
        item.quantity++;
        item.total = item.quantity * item.price;
        renderOrderTable();
    }
}
// decrease quantity of an item
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

// calculate total
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

// delete item from order
function delItem(menuId) {
    const item = orderItems.find((item) => item.id === menuId);
    if (item) {
        orderItems.splice(orderItems.indexOf(item), 1);
        renderOrderTable();
    }
}

// Order Table Functions End

//------------------------------------------------------------------------------------------------------------------------------

// Order Table Helper Functions Start

// scroll to top of the order table
function scrollToTop() {
    const lastChild = $("#order-items-body")[0].lastElementChild;

    lastChild.scrollIntoView({
        behavior: "smooth",
        block: "start",
    });
}

// play audio
function playAudio() {
    var audio = new Audio(audioUrl);
    audio.volume = 0.1;
    audio.play();
}
// Order Table Helper Functions End

//------------------------------------------------------------------------------------------------------------------------------

// DOM Ready Functions Start
$(document).ready(function () {
    // Show the first category by default
    $(".category button:first-child").click();

    // Add event listener to Shortcode input
    $("#shortcode-input").keypress(function (event) {
        console.log(event);
        if (event.which === 13) {
            event.preventDefault();
            searchByShortcode();
        }
    });

    // Add event listener to Customer Data input
    $("#customerName, #mobileNumber").on("keyup", function (event) {
        // Check if Enter key is pressed (key code 13)
        if (event.which === 13) {
            // Trigger the click event on the save button
            $("#saveCustomerDataBtn").click();
        }
    });

    // Add event listener to Search input with debounce
    var timer = null;
    $("#search-input").keyup(function () {
        clearTimeout(timer);
        timer = setTimeout(searchByName, 500);
    });

    // set default payment type
    $("#cash").prop("checked", true);

    // Hide KOT if Takeaway is selected
    if ($("#takeaway").hasClass("active")) {
        $("#kot-order").hide();
    }

    //check if any previous KOTs exist
    hasPrevOrders = $("#prev-kots").length > 0;
});

// DOM Ready Functions End

//------------------------------------------------------------------------------------------------------------------------------

// Search Functions Start

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

// Search by name
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

// Search Functions End

//------------------------------------------------------------------------------------------------------------------------------

// Order Functions Start

// Cancel Order
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

// Order Functions End

//------------------------------------------------------------------------------------------------------------------------------

// Modal Functions Start

// Notes Modal Start

// Add Notes Modal Open
document.getElementById("add-notes-btn").addEventListener("click", function () {
    document.getElementById("addNotesModal").style.display = "block";
});

// Add Notes Modal Close
document
    .querySelectorAll('[data-close="addNotesModal"]')
    .forEach(function (element) {
        element.addEventListener("click", function () {
            document.getElementById("addNotesModal").style.display = "none";
        });
    });

// Notes Modal Save Button
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

// Notes Modal End

// Customer Modal Start

// Add Customer Modal Open
document
    .getElementById("add-customer-btn")
    .addEventListener("click", function () {
        document.getElementById("customerDataModal").style.display = "block";
        $("#customerName").focus();
    });

// Add Customer Modal Close
document
    .querySelectorAll('[data-close="customerDataModal"]')
    .forEach(function (element) {
        element.addEventListener("click", function () {
            document.getElementById("customerDataModal").style.display = "none";
        });
    });

// Customer Modal Save Button
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

// Customer Modal End

// Modal Functions End

//------------------------------------------------------------------------------------------------------------------------------

// old KOT functions

// Hide KOT by default
$("#showPrevKots").click(function () {
    $("#prev-kots table tbody").toggleClass("hidden");
    $("#order-items-table").toggleClass("hidden");
});

// old KOT functions end

//------------------------------------------------------------------------------------------------------------------------------

// Save Order Functions Start

// Bill Order function
$("#bill-order").click(function () {
    let printBill = true;

    hasNewOrders = orderItems.length > 0;
    let isTableToBePaid = $("#settle-order").length > 0;
    if (hasNewOrders) {
        saveOrder(printBill, false);
    } else if (isTableToBePaid) {
        printDuplicateBill();
    } else {
        billTable();
    }
});

// KOt Order function
$("#kot-order").click(function () {
    const printKOT = true;

    if (orderItems.length === 0) {
        alert("No Items Selected");
        return;
    }

    saveOrder(false, printKOT);
});

// Save order
function saveOrder(printBill = false, printKOT = false) {
    //validate order
    if (!hasPrevOrders && orderItems.length === 0) {
        alert("No Items Selected");
        return;
    }
    var tableId = null;
    var reOrder = false;

    if ($("#takeaway").hasClass("active")) {
        tableId = null;
    } else {
        tableId = $("#table").data("tableid");
    }

    if (hasPrevOrders) {
        reOrder = true;
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

    console.log(order);
    var csrf_token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: orderSubmitUrl,
        type: "POST",
        data: {
            order: order,
            source: "pos",
            printKOT: printKOT,
            printBill: printBill,
            reOrder: reOrder,
        },
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        contentType: "application/x-www-form-urlencoded",
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                $("#cancel-order").click();
                window.location.replace(indexUrl);
            } else {
                alert("Order Save Failed");
            }
        },
        error: function (error) {
            console.log(error);
            alert("Order Save Failed");
        },
    });
}

function billTable() {
    let tableId = $("#table").data("tableid");
    let csrf_token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: billTableUrl,
        type: "POST",
        data: {
            tableId: tableId,
            paymentType: $("input[name='payment-type']:checked").next().text(),
        },
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        contentType: "application/x-www-form-urlencoded",
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                $("#cancel-order").click();
                window.location.replace(indexUrl);
            } else {
                alert("Table Billing Failed");
            }
        },
        error: function (error) {
            console.log(error);
            alert("Table Billing Failed");
        },
    });
}

// close table
$("#settle-order").click(function () {
    var tableId = $("#table").data("tableid");
    var csrf_token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: settleTableUrl,
        type: "POST",
        data: {
            tableId: tableId,
            paymentType: $("input[name='payment-type']:checked").next().text(),
        },
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        contentType: "application/x-www-form-urlencoded",
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                $("#cancel-order").click();
                window.location.replace(indexUrl);
            } else {
                alert("Table Settlement Failed");
            }
        },
        error: function (error) {
            console.log(error);
            alert("Table Settlement Failed");
        },
    });
});

// Save Order Functions End

// Print Duplicate Bill
function printDuplicateBill() {
    var tableId = $("#table").data("tableid");
    var csrf_token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: billTableUrl,
        type: "POST",
        data: {
            tableId: tableId,
            printDuplicateBill: true,
        },
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        contentType: "application/x-www-form-urlencoded",
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                $("#cancel-order").click();
                window.location.replace(indexUrl);
            } else {
                alert("Bill Print Failed");
            }
        },
        error: function (error) {
            console.log(error);
            alert("Bill Print Failed");
        },
    });
}
//------------------------------------------------------------------------------------------------------------------------------
