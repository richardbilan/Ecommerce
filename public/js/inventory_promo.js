document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addPromoModal");
    const addPromoForm = document.getElementById("addPromoForm");
    const promoTable = document.getElementById("promoTable"); // FIXED

    // Open Modal
    window.openModal = function () {
        modal.style.display = "block";
    };

    // Close Modal
    window.closeModal = function () {
        modal.style.display = "none";
    };

    // Add Promo Function
    addPromoForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // Get form values
        const promoName = document.getElementById("promoName").value;
        const promoDiscount = document.getElementById("promoDiscount").value;
        const promoDate = document.getElementById("promoDate").value;
        const promoStatus = document.getElementById("Status").value; // FIXED

        // Create new row
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
            <td>${promoName}</td>
            <td>${promoDiscount}</td>
            <td>${promoDate}</td>
            <td>${promoStatus}</td>
            <td>
                <i class="fas fa-edit edit-icon"></i>
                <i class="fas fa-trash delete-icon"></i>
            </td>
        `;

        // Append to table
        promoTable.appendChild(newRow);

        // Delete Function for New Rows
        newRow.querySelector(".delete-icon").addEventListener("click", function () {
            this.closest("tr").remove();
        });

        // Placeholder Edit Function
        newRow.querySelector(".edit-icon").addEventListener("click", function () {
            alert("Edit functionality coming soon!");
        });

        // Close Modal and Reset Form
        closeModal();
        addPromoForm.reset();
    });

    // Delete Existing Promos
    document.querySelectorAll(".delete-icon").forEach(icon => {
        icon.addEventListener("click", function () {
            this.closest("tr").remove();
        });
    });

    // Edit Existing Promos
    document.querySelectorAll(".edit-icon").forEach(icon => {
        icon.addEventListener("click", function () {
            alert("Edit functionality coming soon!");
        });
    });
});
