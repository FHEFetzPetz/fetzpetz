document.addEventListener('DOMContentLoaded', function() {
    const scriptData = document.getElementById('cart-data');
    var data = {};
    if (scriptData) data = JSON.parse(scriptData.getAttribute('data-value'));

    document.querySelectorAll("#content table .quantity-actions .action").forEach(function(item) {
        item.addEventListener("click", function() {
            const itemRow = this.closest("tr");
            const id = itemRow.getAttribute("data-id");
            const action = this.getAttribute("data-action");
            var quantity = parseInt(this.closest(".quantity").querySelector(".number").textContent);
            if (action === "add") quantity++;
            else if (quantity > 0) quantity--;

            var request = new XMLHttpRequest();
            request.open("GET", data.quantity + id + "/" + quantity);
            request.addEventListener('load', function() {
                if (request.status === 200) {
                    const data = JSON.parse(request.responseText);
                    if (data.changed_product == null) {
                        itemRow.remove();

                        if (data.item_count === 0) location.reload();
                    } else {
                        itemRow.querySelector(".quantity .number").textContent = data.changed_product.quantity;
                        itemRow.querySelector(".item-price").textContent = parseFloat(data.changed_product.total).toFixed(2) + " €";
                        document.querySelector("#content tfoot .total-text").textContent = "Total (" + data.item_count + " Item(s)): " + parseFloat(data.total).toFixed(2) + " €";
                    }
                } else
                    pushNotification('Update failed', 'Quantity could not be updated.', 'error');
            });

            request.send();
        });
    });
});