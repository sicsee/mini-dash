setTimeout(() => {
    const toast = document.getElementById("toast");

    if (toast) {
        toast.style.transition = "all 0.4s ease";
        toast.style.opacity = "0";
        toast.style.transform = "translateX(120%)";

        setTimeout(() => toast.remove(), 400);
    }
}, 4000);
