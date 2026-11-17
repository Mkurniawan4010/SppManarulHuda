<!-- Modal Konfirmasi Delete -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <div class="mb-4">
            <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Konfirmasi</h3>
            <p id="modalText" class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus data ini?</p>
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" id="cancelButton" class="btn-modal btn-cancel">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <form id="deleteForm" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal btn-delete">
                    <i class="fas fa-trash-alt mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('confirmationModal');
        const cancelButton = document.getElementById('cancelButton');
        const modalBackdrop = modal;
        
        // Fungsi untuk menutup modal
        function closeModal() {
            modal.classList.add('hidden');
        }
        
        // Event listener untuk tombol batal
        cancelButton.addEventListener('click', closeModal);
        
        // Event listener untuk backdrop
        modalBackdrop.addEventListener('click', function(e) {
            if (e.target === modalBackdrop) {
                closeModal();
            }
        });
        
        // Fungsi untuk menampilkan modal konfirmasi
        window.showDeleteConfirmation = function(formId, title, text) {
            // Set judul dan teks modal
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalText').textContent = text;
            
            // Set action form
            const originalForm = document.getElementById(formId);
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = originalForm.action;
            
            // Tampilkan modal
            modal.classList.remove('hidden');
        }
    });
</script>

<style>
.btn-modal {
    min-width: 80px;
    text-align: center;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-width: 1px;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
.btn-cancel {
    border-color: #d1d5db;
    color: #374151;
    background-color: white;
}
.btn-cancel:hover {
    background-color: #f9fafb;
}
.btn-delete {
    border-color: #fca5a5;
    color: #b91c1c;
    background-color: white;
}
.btn-delete:hover {
    background-color: #fee2e2;
}
</style>

















