document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById("addTrainerModal");
    const btn = document.querySelector(".btn-add"); 
    const closeBtn = document.querySelector(".close-btn");
    const cancelBtn = document.querySelector(".btn-cancel");


    if (btn) {
        btn.onclick = function() {
            modal.style.display = "block";
        }
    }

    const closeModal = () => {
        modal.style.display = "none";
    }

    if (closeBtn) closeBtn.onclick = closeModal;
    if (cancelBtn) cancelBtn.onclick = closeModal;

    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
});

const searchInput = document.querySelector('input[name="search"]');
searchInput.addEventListener('input', (e) => {
    if (e.target.value == "") {
        window.location.href = "index.php?view=trainers";
    }
})


 function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('img-preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
}