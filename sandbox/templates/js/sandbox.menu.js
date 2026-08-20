document.addEventListener('DOMContentLoaded', function() {
    // get all links from submenu
    const menu_links = document.querySelectorAll('#component-submenu a[href^="#"], #component-submenu a[href*="#"]');

    menu_links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Vérifie si le lien pointe vers une ancre de la même page
            const href = this.getAttribute('href');
            const currentPath = window.location.pathname;
            const targetPath = href.split('#')[0];

            // Checks if the link points to an anchor on the same page
            if (targetPath === '' || targetPath === currentPath) {
                e.preventDefault(); // Prevents default behavior
                const targetId = href.split('#')[1];
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    // Scroll to the anchor smoothly
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });

                    // Updates the URL without reloading the page
                    history.pushState(null, null, `#${targetId}`);
                }
            }
        });
    });
});