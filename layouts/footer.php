        </div> <!-- Closing Content Body -->
        <footer class="py-3 px-4 bg-white mt-auto border-top no-print text-center" style="font-size: 0.85rem; color: var(--text-muted);">
            <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span>&copy; <?= date('Y') ?> <strong>Buku Kerja Digital</strong>. All Rights Reserved.</span>
                <span>Powered by PHP Native & Bootstrap 5</span>
            </div>
        </footer>
    </div> <!-- Closing Main Wrapper -->

    <!-- Core Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables JS & dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- FontAwesome Icon Set -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

    <!-- App JS -->
    <script src="assets/js/main.js"></script>

    <!-- SweetAlert Session Notifications -->
    <script>
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $_SESSION['success'] ?>',
                confirmButtonColor: '#3B82F6',
                customClass: {
                    popup: 'rounded-4'
                }
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $_SESSION['error'] ?>',
                confirmButtonColor: '#3B82F6',
                customClass: {
                    popup: 'rounded-4'
                }
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
