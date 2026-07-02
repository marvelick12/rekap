// main.js - Core UI Interactions

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarWrapper = document.getElementById('sidebarWrapper');
    const bodyOverlay = document.createElement('div');
    
    bodyOverlay.className = 'sidebar-overlay';
    bodyOverlay.style.position = 'fixed';
    bodyOverlay.style.top = '0';
    bodyOverlay.style.left = '0';
    bodyOverlay.style.width = '100vw';
    bodyOverlay.style.height = '100vh';
    bodyOverlay.style.backgroundColor = 'rgba(0,0,0,0.4)';
    bodyOverlay.style.zIndex = '998';
    bodyOverlay.style.display = 'none';
    bodyOverlay.style.transition = 'all 0.3s ease';
    document.body.appendChild(bodyOverlay);

    if (sidebarToggle && sidebarWrapper) {
        sidebarToggle.addEventListener('click', function () {
            sidebarWrapper.classList.add('show');
            bodyOverlay.style.display = 'block';
        });

        bodyOverlay.addEventListener('click', function () {
            sidebarWrapper.classList.remove('show');
            bodyOverlay.style.display = 'none';
        });
    }

    // 2. SweetAlert Delete Confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'btn btn-primary px-4',
                    cancelButton: 'btn btn-danger px-4 ms-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // 3. AJAX Status Toggle for Rencana Pekerjaan
    const rencanaCheckboxes = document.querySelectorAll('.rencana-status-checkbox');
    rencanaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const id = this.getAttribute('data-id');
            const isChecked = this.checked ? 1 : 0;
            const badge = document.getElementById(`rencana-badge-${id}`);
            
            // Temporary loader style
            this.disabled = true;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', isChecked);

            fetch('index.php?route=rencana/toggle', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                this.disabled = false;
                if (data.status === 'success') {
                    // Update Badge UI
                    if (isChecked === 1) {
                        badge.className = 'badge-modern badge-modern-success';
                        badge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Selesai';
                    } else {
                        badge.className = 'badge-modern badge-modern-danger';
                        badge.innerHTML = '<i class="fas fa-times-circle me-1"></i> Belum Selesai';
                    }

                    // Update Progress bar widgets if they exist on the page
                    updateProgressBarWidgets(data.progress);
                    
                    // Show small toast notification
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Status berhasil diperbarui'
                    });
                } else {
                    // Revert UI on failure
                    this.checked = !this.checked;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan sistem.'
                    });
                }
            })
            .catch(error => {
                this.disabled = false;
                this.checked = !this.checked;
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Koneksi ke server terputus.'
                });
            });
        });
    });

    function updateProgressBarWidgets(progress) {
        if (!progress) return;
        
        // Target list/page widget
        const percentText = document.getElementById('progress-percent-text');
        const countText = document.getElementById('progress-count-text');
        const progressBar = document.getElementById('progress-bar-fill');

        if (percentText) percentText.innerText = `${progress.persen}%`;
        if (countText) countText.innerText = `${progress.selesai} dari ${progress.total} pekerjaan selesai`;
        if (progressBar) progressBar.style.width = `${progress.persen}%`;

        // Target Dashboard Widget Card stats
        const dbCompletedCard = document.getElementById('db-stat-selesai');
        const dbUncompletedCard = document.getElementById('db-stat-belum-selesai');
        
        if (dbCompletedCard) {
            dbCompletedCard.innerText = progress.selesai;
        }
        if (dbUncompletedCard) {
            dbUncompletedCard.innerText = progress.total - progress.selesai;
        }
    }
});
