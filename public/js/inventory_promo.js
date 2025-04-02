document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("addPromoModal");
    const addPromoForm = document.getElementById("addPromoForm");
    const promoTable = document.getElementById("promoTable");

    window.openModal = function() {
        console.log("Opening modal");
        modal.style.display = "block";
    };

    window.closeModal = function() {
        modal.style.display = "none";
        addPromoForm.reset();
    };

    // Add or Edit Promo
    addPromoForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const url = "/promotions"; // Always POST to this URL for adding promotions
        const method = "POST"; // Always use POST for adding promotions

        console.log("Form submission triggered");
        console.log("Sending data:", {
            code_name: document.getElementById("promoName").value,
            discount: document.getElementById("promoDiscount").value,
            expiration_date: document.getElementById("promoDate").value,
            status: document.getElementById("promoStatus").value
        });

        
        fetch(url, {
            method: method,
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content || '' },
            body: JSON.stringify({
                code_name: document.getElementById("promoName").value,
                discount: document.getElementById("promoDiscount").value,
                expiration_date: document.getElementById("promoDate").value,
                status: document.getElementById("promoStatus").value
            })
        }).then(response => {
            console.log("Response status:", response.status);
            if (!response.ok) {
                console.error("Server error:", response.statusText);
            }
            if (!response.ok) {
                console.error("Server error:", response.statusText);
            }
            return response.json();
        }).then(data => {
            console.log("Response from server:", data);
            if (data.message) {
                alert(data.message); // Alert the user with the success message
            }
            location.reload();
        }).catch(error => {
            console.error("Error:", error);
        });
    });

    // Delete Promo
    window.deletePromo = function(id) {
        fetch(`/promotions/${id}`, { method: "DELETE", headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content } })
            .then(() => location.reload());
    };

    // Edit Promo
    window.editPromo = function(id) {
        fetch(`/promotions/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("promoId").value = data.id;
                document.getElementById("promoName").value = data.code_name;
                document.getElementById("promoDiscount").value = data.discount;
                document.getElementById("promoDate").value = data.expiration_date;
                document.getElementById("promoStatus").value = data.status;
                openModal();
            });
    };
});
