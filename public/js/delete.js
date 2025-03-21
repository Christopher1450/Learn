document.addEventListener("DOMContentLoaded", function () {
    // Attach click event to all delete buttons
    document.querySelectorAll("[data-bs-target='#confirmDeleteModal']").forEach((button) => {
        button.addEventListener("click", function () {
            const deleteUrl = this.getAttribute("data-url");
            document.getElementById("deleteForm").setAttribute("action", deleteUrl);
        });
    });

    // Fetch and update statistics dynamically
    fetch("/dashboard/stats")
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("total_buku").textContent = data.total_buku;
            document.getElementById("total_categories").textContent = data.total_categories;
            document.getElementById("total_borrowed").textContent = data.total_borrowed;
        });
});
