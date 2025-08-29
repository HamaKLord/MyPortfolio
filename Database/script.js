document.addEventListener("DOMContentLoaded", function () {
    const addToSaleButtons = document.querySelectorAll("button");

    addToSaleButtons.forEach(button => {
        button.addEventListener("click", function () {
            const partName = this.closest("tr").querySelector("td:first-child").innerText;
            const price = this.closest("tr").querySelector("td:nth-child(2)").innerText;

            alert(`Added ${partName} to the sale for $${price}`);
            
            // Optional: Add item to a shopping cart (can be managed via PHP sessions later)
        });
    });
});
