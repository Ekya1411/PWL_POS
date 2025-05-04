<form action="{{ url('/transaksi/store') }}" method="POST" id="form-tambah">
    @csrf
    <input type="hidden" name="barangData" id="barang-data">
    <div id="modal-master" class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="filter" class="form-horizontal filter-date p-2 pb-5 border-bottom mb-6">
                        <div class="row">
                            <div class="col-md-5 mb-2">
                                <div class="form-group form-group-sm row text-sm mb-0">
                                    <label for="filter_kategori" class="col-md-4 col-form-label">Kategori</label>
                                    <div class="col-md-8">
                                        <select id="filter_kategori" class="form-control form-control-sm">
                                            <option value="">- Semua Kategori -</option>
                                            @foreach ($kategori as $l)
                                                <option value="{{ $l->kategori_id }}">
                                                    {{ $l->kategori_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 mb-2">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="search-nama" class="form-control form-control-sm"
                                        placeholder="Cari barang...">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-bordered table-sm table-striped table-hover"
                                id="table-barang-pilih">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->barang_kode }}</td>
                                            <td>{{ $item->barang_nama }}</td>
                                            <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center" style="gap: 5px;">
                                                    <button class="btn btn-dark btn-sm btn-minus"
                                                        data-id="{{ $item->barang_id }}">-</button>
                                                    <input type="text" inputmode="numeric"
                                                        class="form-control text-center jumlah-barang"
                                                        data-id="{{ $item->barang_id }}" value="0" readonly
                                                        style="width: 50px; " />
                                                    <button class="btn btn-dark btn-sm btn-plus"
                                                        data-id="{{ $item->barang_id }}">+</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row align-items-center mb-3">
                            <label class="col-md-3 col-form-label">Kode Transaksi</label>
                            <div class="col-md-9">
                                <input value="{{ $newKode }}" type="text" name="penjualan_kode"
                                    id="penjualan_kode" class="form-control" required readonly>
                                <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                            </div>
                        </div>
                        <div class="form-group row align-items-center mb-3">
                            <label class="col-md-3 col-form-label">Pembeli</label>
                            <div class="col-md-9">
                                <input value="" type="text" name="pembeli" id="pembeli" class="form-control"
                                    required>
                                <small id="error-pembeli" class="error-text form-text text-danger"></small>
                            </div>
                        </div>
                        <div class="" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-bordered table-sm table-striped table-hover"
                                id="table-barang-terpilih">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total Harga:</strong></td>
                                        <td id="total-harga">Rp 0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        // Setup AJAX headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Form validation and submission
        $("#form-tambah").on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            console.log("Form submitted");

            // Validate the form
            if ($("#pembeli").val().trim() === '') {
                $('#error-pembeli').text('Pembeli harus diisi');
                return false;
            }

            // Check if any products are selected
            if (Object.keys(barangDipilih).length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak ada barang yang dipilih!'
                });
                return false;
            }

            // Log the data being sent
            console.log("Data being sent:", $(this).serialize());

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST', // Force POST method
                data: $(this).serialize(),
                success: function(response) {
                    console.log("Response received:", response);
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        });
                        // Reload table if needed
                        $('#table_transaksi').DataTable().ajax.reload();
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error details:", xhr.status, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan dalam permintaan. Coba lagi.'
                    });
                }
            });

            return false;
        });

        // Handling the item selection
        let barangDipilih = {};

        $('.btn-plus').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const input = $('.jumlah-barang[data-id="' + id + '"]');
            let val = parseInt(input.val()) || 0;
            input.val(++val);
            updateBarangTerpilih(id, val);
        });

        $('.btn-minus').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const input = $('.jumlah-barang[data-id="' + id + '"]');
            let val = parseInt(input.val()) || 0;
            if (val > 0) {
                input.val(--val);
                updateBarangTerpilih(id, val);
            }
        });

        function updateBarangTerpilih(id, jumlah) {
            const row = $('button[data-id="' + id + '"]').closest('tr');
            const kode = row.find('td:eq(1)').text();
            const nama = row.find('td:eq(2)').text();
            const hargaText = row.find('td:eq(3)').text().replace(/\./g, '').replace(',', '.');
            const harga = parseFloat(hargaText);

            if (jumlah > 0) {
                barangDipilih[id] = {
                    kode,
                    nama,
                    harga,
                    jumlah
                };
            } else {
                delete barangDipilih[id];
            }

            renderBarangTerpilih();
            updateBarangData(); // Memperbarui input tersembunyi dengan data barang
        }

        function renderBarangTerpilih() {
            const tbody = $('#table-barang-terpilih tbody');
            tbody.empty();
            let no = 1;
            let totalHarga = 0; // Variabel untuk menghitung total harga

            Object.values(barangDipilih).forEach(item => {
                const total = item.harga * item.jumlah;
                totalHarga += total; // Menambahkan total per barang ke totalHarga
                const row = `<tr>
                <td>${no++}</td>
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>${formatRupiah(item.harga)}</td>
                <td>${formatRupiah(total)}</td>
            </tr>`;
                tbody.append(row);
            });
            $('#total-harga').text(`Rp ${formatRupiah(totalHarga)}`);
        }

        function formatRupiah(angka) {
            return angka.toLocaleString('id-ID', {
                minimumFractionDigits: 0
            });
        }

        // Update data barang pada input tersembunyi
        function updateBarangData() {
            $('#barang-data').val(JSON.stringify(barangDipilih)); // Mengirim data barang dalam format JSON
        }

        // Filter functionality
        $('#filter_kategori').on('change', function() {
            filterBarang();
        });

        $('#search-nama').on('keyup', function() {
            filterBarang();
        });

        function filterBarang() {
            const kategoriId = $('#filter_kategori').val();
            const searchText = $('#search-nama').val().toLowerCase();

            $('#table-barang-pilih tbody tr').each(function() {
                const row = $(this);
                const kodeBarang = row.find('td:eq(1)').text().toLowerCase();
                const namaBarang = row.find('td:eq(2)').text().toLowerCase();
                const rowKategoriId = row.data('kategori-id');

                const matchesKategori = !kategoriId || kategoriId === '' || rowKategoriId == kategoriId;
                const matchesSearch = !searchText || kodeBarang.includes(searchText) || namaBarang
                    .includes(searchText);

                if (matchesKategori && matchesSearch) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }
    });
</script>