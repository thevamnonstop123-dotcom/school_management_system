const hamburgerBtn = document.querySelector(".hamburger");
const sidebar = document.querySelector(".sidebar");
const closeBtn = document.getElementById("closeSidebar");

 
hamburgerBtn.addEventListener('click', () => {
    sidebar.classList.add("active");
    hamburgerBtn.classList.add("hidden"); 
});


closeBtn.addEventListener('click', () => {
    sidebar.classList.remove("active");
    hamburgerBtn.classList.remove("hidden");
});