
document.querySelector("#checkout #billingAddress").addEventListener('change', function() {
    document.querySelector('#checkout .billing-address').classList.toggle('reveal', this.checked);
    if(this.checked) {
        document.querySelectorAll('#checkout .billing-address input:not(.optional), #checkout .billing-address select:not(.optional)').forEach(function(item) {
            item.setAttribute("required", "1");
        });
    } else {
        document.querySelectorAll('#checkout .billing-address input:not(.optional), #checkout .billing-address select:not(.optional)').forEach(function(item) {
            item.removeAttribute("required");
        });
    }
});