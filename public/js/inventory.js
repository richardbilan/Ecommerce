
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addProductModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const addProductForm = document.getElementById("addProductForm");
    const inventoryTable = document.getElementById("inventoryTable");
    const toggleShop = document.getElementById("toggleShop");
    const statusLabel = document.querySelector(".status-label");

    // Open Modal
    openModalBtn.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // Close Modal
    closeModalBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close Modal When Clicking Outside
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Handle Form Submission to Add New Product
    addProductForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevents form from reloading the page

        // Get form values
        const productName = document.getElementById("productName").value;
        const productCategory = document.getElementById("productCategory").value;
        const productPrice = document.getElementById("productPrice").value;
        const productAvailability = document.getElementById("productAvailability").value;

        // Create new table row
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
            <td>${productName}</td>
            <td>${productCategory}</td>
            <td>P ${productPrice}</td>
            <td>${productAvailability}</td>
            <td>
                <i class="fas fa-edit edit-icon"></i>
                <i class="fas fa-trash delete-icon"></i>
            </td>
        `;

        // Append new row to table
        inventoryTable.appendChild(newRow);

        // Add delete functionality to new row
        newRow.querySelector(".delete-icon").addEventListener("click", function () {
            this.closest("tr").remove();
        });

        // Add edit functionality (placeholder for now)
        newRow.querySelector(".edit-icon").addEventListener("click", function () {
            alert("Edit functionality coming soon!");
        });

        // Close the modal after adding product
        modal.style.display = "none";

        // Clear form inputs
        addProductForm.reset();
    });

    // Delete Item
    document.querySelectorAll(".delete-icon").forEach(icon => {
        icon.addEventListener("click", function () {
            this.closest("tr").remove();
        });
    });

    // Edit Item (For Now, Just an Alert)
    document.querySelectorAll(".edit-icon").forEach(icon => {
        icon.addEventListener("click", function () {
            alert("Edit functionality coming soon!");
        });
    });

    // Toggle Shop Status
    toggleShop.addEventListener("change", function () {
        statusLabel.textContent = this.checked ? "OPEN" : "CLOSED";
    });
});
