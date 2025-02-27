document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".status-select").forEach(function (select) {
        select.addEventListener("change", function () {
            let taskId = this.dataset.id;
            let newStatus = this.value;

            fetch("status_update.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${taskId}&status=${encodeURIComponent(newStatus)}`
            })
        });
    });
});
