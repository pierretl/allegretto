function afficheLaSuite(e) {
    if (e.getAttribute("aria-expanded") === "false") {
        e.setAttribute("aria-expanded", "true");
    } else {
        e.setAttribute("aria-expanded", "false");
    }
}