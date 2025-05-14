<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="create_judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_konten" class="form-label">Konten</label>
                        <textarea class="form-control" id="create_konten" name="konten" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="create_tanggal_publish" class="form-label">Tanggal Publish</label>
                        <input type="date" class="form-control" id="create_tanggal_publish" name="tanggal_publish" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="create_foto" name="foto" accept="image/*">
                        <img id="create_foto_preview" class="img-fluid mt-2" style="max-height: 150px;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_konten" class="form-label">Konten</label>
                        <textarea class="form-control" id="edit_konten" name="konten" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal_publish" class="form-label">Tanggal Publish</label>
                        <input type="date" class="form-control" id="edit_tanggal_publish" name="tanggal_publish" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="edit_foto" name="foto" accept="image/*">
                        <img id="edit_foto_preview" class="img-fluid mt-2" style="max-height: 150px;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Berita</button>
                </div>
            </form>            
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split('T')[0];

    // Preview foto di create
    document.getElementById('create_foto').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('create_foto_preview').setAttribute('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });

    // Preview foto di edit
    document.getElementById('edit_foto').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('edit_foto_preview').setAttribute('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });

    // Menangani klik tombol Edit dan mengisi formulir Edit dengan data yang sesuai
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const judul = this.dataset.judul;
            const konten = this.dataset.konten;
            const tanggal = this.dataset.tanggal;
            const foto = this.dataset.foto;

            // Update URL action dengan ID berita
            const form = document.getElementById('editForm');
            form.setAttribute('action', '/berita/' + id);

            // Isi formulir dengan data yang sudah ada
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_konten').value = konten;
            document.getElementById('edit_tanggal_publish').value = tanggal;

            // Update preview foto
            const preview = document.getElementById('edit_foto_preview');
            if (foto) {
                preview.src = foto;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });
    });
});

</script>

