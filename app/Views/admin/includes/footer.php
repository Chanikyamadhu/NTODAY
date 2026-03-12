<footer class="mt-5 py-4 border-top">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-0 small">&copy; <?= date('Y') ?> <strong>NToday News</strong>. నిరంతరం మీ వెంటే.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-muted mb-0 small">Handcrafted by Concito Mind Solutions</p>
                </div>
            </div>
        </div>
    </footer>
</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('show');
        if(sidebar.classList.contains('show')) {
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        } else {
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Auto-active submenu if child is active
    document.addEventListener("DOMContentLoaded", function() {
        const activeLink = document.querySelector('.submenu .nav-link.active');
        if (activeLink) {
            const collapseEl = activeLink.closest('.collapse');
            if (collapseEl) {
                const bsCollapse = new bootstrap.Collapse(collapseEl, { show: true });
                const parentLink = document.querySelector(`[href="#${collapseEl.id}"]`);
                if (parentLink) parentLink.classList.add('active');
            }
        }
    });
</script>
</body>
</html>