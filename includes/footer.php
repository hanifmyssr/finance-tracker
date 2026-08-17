</div>
<footer class="text-center text-muted py-4">
    <small>&copy; <?php echo date("Y"); ?> Pencatat Keuangan Pribadi</small>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const themeToggleLabel = document.getElementById('themeToggleLabel');
    const themeIcon = themeToggleBtn.querySelector('i');

    function updateToggleUI(theme) {
        if (theme === 'dark') {
            themeToggleLabel.textContent = 'Mode Terang';
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else {
            themeToggleLabel.textContent = 'Mode Gelap';
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }
    }

    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    updateToggleUI(currentTheme);

    themeToggleBtn.addEventListener('click', function() {
        const current = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateToggleUI(newTheme);
    });
</script>
</body>
</html>
