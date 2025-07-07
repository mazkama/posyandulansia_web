<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="create_tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_waktu" class="form-label">Waktu</label>
                        <input type="time" class="form-control" id="create_waktu" name="waktu" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="create_lokasi" name="lokasi"
                            placeholder="Masukkan lokasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_desa_id" class="form-label">Desa</label>
                        <select class="form-control" id="create_desa_id" name="desa_id" required>
                            <option value="">Pilih Desa</option>
                            @foreach(\App\Models\Desa::all() as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_waktu" class="form-label">Waktu</label>
                        <input type="time" class="form-control" id="edit_waktu" name="waktu" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="edit_lokasi" name="lokasi"
                            placeholder="Masukkan lokasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_desa_id" class="form-label">Desa</label>
                        <select class="form-control" id="edit_desa_id" name="desa_id" required>
                            <option value="">Pilih Desa</option>
                            @foreach(\App\Models\Desa::all() as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let today = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD
        document.getElementById("create_tanggal").setAttribute("min", today);
        document.getElementById("edit_tanggal").setAttribute("min", today);
    });

    $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '/jadwal/' + id + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#edit_tanggal').val(data.tanggal);
                $('#edit_waktu').val(data.waktu);
                $('#edit_lokasi').val(data.lokasi);
                $('#edit_keterangan').val(data.keterangan);
                $('#edit_desa_id').val(data.desa_id); // set desa
                $('#editForm').attr('action', '/jadwal/' + id);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat mengambil data jadwal.');
            }
        });
    });
</script>
