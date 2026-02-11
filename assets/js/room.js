setTimeout(() => {
    const toastBox = document.getElementById("toastBox");
    
    if(toastBox) {
        toastBox.style.display = "none";
    }
}, 3000);

/* replaceState tells the browser: "Update the address bar with this new URL, but do not refresh the page */
if (window.history.replaceState) {
    const url = new URL(window.location.href);
    url.searchParams.delete('msg'); // Remove the msg parameter
    window.history.replaceState({ path: url.href }, '', url.href);
}


document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('roomSearch');
    const tableRows = document.querySelectorAll('table tbody tr');

    searchInput.addEventListener('keyup', function(e) {
        const query = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            const roomName = row.querySelector('.room-name-cell').textContent.toLowerCase();
            const branch = row.querySelector('.branch-cell').textContent.toLowerCase();

            if (roomName.includes(query) || branch.includes(query)) {
                row.style.display = ""; // Show the row
            } else {
                row.style.display = "none"; // Hide the row
            }
        });
    });
});