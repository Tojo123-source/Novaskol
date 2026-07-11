/* ===============================
   THEME SYSTEM - NOVASKOL
================================= */
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("themeToggle");
    if (!toggleBtn) return; // sécurité

    const root = document.documentElement;
    const icon = toggleBtn.querySelector("i");

    // Charger thème sauvegardé
    const savedTheme = localStorage.getItem("novaskol-theme") || localStorage.getItem("theme");
    if (savedTheme === "light") {
        root.classList.add("light");
        if (icon) icon.classList.replace("fa-moon", "fa-sun");
    }

    toggleBtn.addEventListener("click", function () {
        if (root.classList.contains("light")) {
            root.classList.remove("light");
            root.dataset.theme = "dark";
            localStorage.setItem("novaskol-theme", "dark");
            localStorage.setItem("theme", "dark");
            if (icon) icon.classList.replace("fa-sun", "fa-moon");
        } else {
            root.classList.add("light");
            root.dataset.theme = "light";
            localStorage.setItem("novaskol-theme", "light");
            localStorage.setItem("theme", "light");
            if (icon) icon.classList.replace("fa-moon", "fa-sun");
        }
    });
});
