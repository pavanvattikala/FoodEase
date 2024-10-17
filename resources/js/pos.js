// Author: Pavan Vattikala

const noitemsContainer =
    '<tr id="noitems"><td colspan="3">No Items Selected <br> Select from Left Menu</td></tr>';

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

    // if no items are present in the order
    if (orderItems.length === 0) {
        orderItemsBody.append(noitemsContainer);
    } else {
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
    }

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
function toggleOrderType() {
    $("#takeaway").toggleClass("active");

    $("#kot-order").toggle();

    $("#dine_in").toggleClass("active");

    $("#table").toggleClass("hidden");
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

    // Filter all menu names
    filterAllMenuNames();

    // Add event listener to Shortcode input
    $("#shortcode-input").keypress(function (event) {
        if (event.which === 13) {
            event.preventDefault();
            searchByShortcode();
        }
    });

    // on load focus on shortcode input
    $("#shortcode-input").focus();

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

    // Add event listener to Toggle Takeaway & Dine In button
    $("#takeaway").click(toggleOrderType);
    $("#dine_in").click(toggleOrderType);
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
$("#bill-order").click(function (e) {
    e.preventDefault(); // Prevent default action

    // Get the button element
    let button = this;

    // Disable the button to prevent multiple clicks
    disableButton(button);

    if (orderItems.length === 0) {
        alert("No Items Selected");

        // Re-enable the button if no items are selected
        enableButton(button);
        return;
    }

    let printBill = true;
    let hasNewOrders = orderItems.length > 0;
    let isTableToBePaid = $("#settle-order").length > 0;

    let operation;

    if (hasNewOrders) {
        operation = saveOrder(printBill, false);
    } else if (isTableToBePaid) {
        operation = printDuplicateBill();
    } else {
        operation = billTable();
    }

    // Re-enable the button after the operation completes
    operation.finally(() => {
        enableButton(button);
    });
});

$("#kot-order").click(function () {
    let button = this;

    disableButton(button);

    if (orderItems.length === 0) {
        alert("No Items Selected");

        // Re-enable the button if no items are selected
        enableButton(button);
        return;
    }
    const operation = saveOrder();
    operation.finally(() => {
        enableButton(button);
    });
});

// Save order
function saveOrder(printBill = false) {
    //validate order
    if (!hasPrevOrders && orderItems.length === 0) {
        alert("No Items Selected");
        return;
    }
    var tableId = null;
    var isPickUpOrder = false;
    const billTable = printBill;

    if ($("#takeaway").hasClass("active")) {
        tableId = null;
        isPickUpOrder = true;
    } else {
        tableId = $("#table").data("tableid");
    }

    const paymentMethod = $("input[name='payment-type']:checked").next().text();

    const order = {
        orderItems: orderItems,
        total: $("#total").text(),
        discount: $("#discount").text(),
        grandtotal: $("#grandtotal").text(),
    };

    var csrf_token = $('meta[name="csrf-token"]').attr("content");
    showLoader();
    $.ajax({
        url: orderSubmitUrl,
        type: "POST",
        data: {
            source: SOURCE,
            tableId: tableId,
            specialInstructions: selectedNotes,
            isPickUpOrder: isPickUpOrder,
            paymentMethod: paymentMethod,
            billTable: billTable,
            order: order,
        },
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        contentType: "application/x-www-form-urlencoded",
        success: function (response) {
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
        complete: function () {
            hideLoader();
        },
    });
}

function billTable() {
    let tableId = $("#table").data("tableid");
    let csrf_token = $('meta[name="csrf-token"]').attr("content");

    showLoader();

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
        complete: function () {
            hideLoader();
        },
    });
}

// close table
$("#settle-order").click(function () {
    var tableId = $("#table").data("tableid");
    var csrf_token = $('meta[name="csrf-token"]').attr("content");
    showLoader();
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
        complete: function () {
            hideLoader();
        },
    });
});

// Save Order Functions End

// Print Duplicate Bill
function printDuplicateBill() {
    var tableId = $("#table").data("tableid");
    var csrf_token = $('meta[name="csrf-token"]').attr("content");
    showLoader();
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
        complete: function () {
            hideLoader();
        },
    });
}
//------------------------------------------------------------------------------------------------------------------------------

//  Button Helpers
// Makes sure that users can't click the same button multiple times

function disableButton(button) {
    button.disabled = true;
    button.classList.add("disabled");
}

function enableButton(button) {
    button.disabled = false;
    button.classList.remove("disabled");
}

// function to add <br> to menu names where there is a space after every two words
// This is done to make sure that the menu names are displayed correctly in the POS
function filterAllMenuNames() {
    $(".menu-items button").each(function () {
        var menuName = $(this).text();
        var filteredMenuName = menuName.replace(/(\w+)\s(\w+)\s/g, "$1 $2<br>");
        $(this).html(filteredMenuName);
    });
}
