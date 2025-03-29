document.addEventListener("DOMContentLoaded", function() {
    const addPromoForm = document.getElementById("addPromoForm");
    const modalTitle = document.getElementById("modalTitle");
    let editingPromoId = null; // Store the promo ID being edited

    // Open modal for new promo
    function openModal() {
        editingPromoId = null; // Reset to indicate a new entry
        modalTitle.textContent = "Add New Discounts/Promo";
        addPromoForm.reset();
        document.getElementById("addPromoModal").style.display = "block";
    }

    // Open modal for editing promo
    window.editPromo = function(id, name, discount, date, status) {
        editingPromoId = id;
        modalTitle.textContent = "Edit Discounts/Promo";
        document.getElementById("promoId").value = id;
        document.getElementById("promoName").value = name;
        document.getElementById("promoDiscount").value = discount;
        document.getElementById("promoDate").value = date;
        document.getElementById("promoStatus").value = status;
        document.getElementById("addPromoModal").style.display = "block";
    };

    // Close modal
    function closeModal() {
        document.getElementById("addPromoModal").style.display = "none";
    }

    // Handle form submission
    addPromoForm.addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent default form submission

        const promoId = editingPromoId;
        const url = promoId ? `/promotions/${promoId}` : "/promotions";
        const method = promoId ? "PUT" : "POST";

        fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content || ''
                },
                body: JSON.stringify({
                    code_name: document.getElementById("promoName").value,
                    discount: document.getElementById("promoDiscount").value,
                    expiration_date: document.getElementById("promoDate").value,
                    status: document.getElementById("promoStatus").value
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server response:", data);
                location.reload(); // Refresh page after saving
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });

    // Expose functions globally
    window.openModal = openModal;
    window.closeModal = closeModal;
});
