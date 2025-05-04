<form action="{{ url('/stok/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Dropdown Barang -->
                <div class="form-group">
                    <label for="barang">Pilih Barang</label>
                    <select name="barang_id" id="barang" class="form-control" required>
                        <option value="" disabled selected>Pilih Barang</option>
                        @foreach ($barang as $item)
                            <option value="{{ $item->barang_id }}">{{ $item->barang_nama }} - {{ $item->barang_kode }}</option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="error-text form-text text-danger"></small>
                </div>

                <!-- Dropdown Supplier -->
                <div class="form-group">
                    <label for="supplier">Pilih Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        <option value="" disabled selected>Pilih Supplier</option>
                        @foreach ($supplier as $item)
                            <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }} - {{ $item->supplier_kode }}</option>
                        @endforeach
                    </select>
                    <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input value="" type="number" name="jumlah" id="jumlah" class="form-control" required>
                    <small id="error-jumlah" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                barang_id: {
                    required: true,
                    number: true
                },
                supplier_id: {
                    required: true,
                    number: true
                },
                jumlah: {
                    required: true,
                    number: true,
                    min: 0
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide'); // Tutup modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                if (response.redirect) {
                                    window.location.href = response
                                    .redirect; // Jika ada redirect
                                }
                            });

                            // Reload DataTable
                            $('#table_stok').DataTable().ajax.reload();
                        } else {
                            $('.error-text').text(''); // Hapus error sebelumnya
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan dalam permintaan. Coba lagi.'
                        });
                        console.error(xhr.responseText);
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
