document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#sort_button").addEventListener("click", sortTable);
});

function sortTable() {
    let table = document.querySelector(".products-table");
    let rows = Array.from(table.rows).slice(1);

    let sortColumn = document.querySelector("#sort_column").value;
    let sortOrder = document.querySelector("#sort_order").value;

    let columnIndex = sortColumn === "start_date" ? 0 : 1;

    rows.sort((a, b) => {
        let valA = new Date(a.cells[columnIndex].innerText.replace(/年|月/g, "-").replace(/日/g, ""));
        let valB = new Date(b.cells[columnIndex].innerText.replace(/年|月/g, "-").replace(/日/g, ""));

        return sortOrder === "asc" ? valA - valB : valB - valA;
    });

    rows.forEach(row => table.appendChild(row));
}
