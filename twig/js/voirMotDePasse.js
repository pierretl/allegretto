function voirMotDePasse(e) {
    const input = e.parentNode.querySelector('input');
    if (e.getAttribute("password-visible") === "false") {
        input.setAttribute("type", "text");
        e.setAttribute("password-visible", "true");
    } else {
        input.setAttribute("type", "password");
        e.setAttribute("password-visible", "false");
    }
}