<footer class="footer has-background-primary has-text-white">
        <div class="content has-text-centered">
            <nav class="navbar is-primary" role="navigation" aria-label="footer navigation">
                <div class="navbar-menu">
                    <div class="navbar-start">
                        <a class="navbar-item has-text-white" href="index.php">Accueil</a>
                        <a class="navbar-item has-text-white" href="list.php">Gestion des camions</a>
                        <a class="navbar-item has-text-white" href="create_camion.php">Créer un camion</a>
                        <a class="navbar-item has-text-white" href="edit_marque.php">Gestion des marques</a>
                        <a class="navbar-item has-text-white" href="edit_modeles.php">Gestion des modèles</a>
                        <a class="navbar-item has-text-white" href="profil.php">Profil</a>
                        <a class="navbar-item has-text-white" href="deconnexion.php">Déconnexion</a>
                    </div>
                </div>
            </nav>
            <p>© 2024 Parc Auto. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Bulma JS (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bulma/js/bulma.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach(el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);

                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }
        });
    </script>
</body>
</html>
