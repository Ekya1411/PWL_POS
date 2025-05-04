<!-- File: resources/views/transaksi/modal_edit.blade.php -->
<form action="{{ url('/transaksi/'. $transaksi->penjualan_id .'/update/') }}" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="barangData" id="barang-data">
    <div id="modal-master" class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Side - Product Selection -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Pilih Barang</h6>
                            </div>
                            <div class="card-body p-2">
                                <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-3">
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <div class="form-group form-group-sm row text-sm mb-0">
                                                <label for="filter_kategori"
                                                    class="col-md-4 col-form-label">Kategori</label>
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
                                                <input type="text" id="search-nama"
                                                    class="form-control form-control-sm" placeholder="Cari barang...">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                                    <table class="table table-bordered table-sm table-striped table-hover"
                                        id="table-barang-pilih">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Kode</th>
                                                <th width="40%">Nama Barang</th>
                                                <th width="20%">Harga</th>
                                                <th width="20%">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barang as $key => $item)
                                                <tr data-kategori-id="{{ $item->kategori_id }}">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->barang_kode }}</td>
                                                    <td>{{ $item->barang_nama }}</td>
                                                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <button class="btn btn-dark btn-sm btn-minus"
                                                                    data-id="{{ $item->barang_id }}">-</button>
                                                            </div>
                                                            <input type="text" inputmode="numeric"
                                                                class="form-control text-center jumlah-barang"
                                                                data-id="{{ $item->barang_id }}" value="0"
                                                                readonly />
                                                            <div class="input-group-append">
                                                                <button class="btn btn-dark btn-sm btn-plus"
                                                                    data-id="{{ $item->barang_id }}">+</button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Transaction Details -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Detail Transaksi</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group row align-items-center mb-3">
                                    <label class="col-md-4 col-form-label">Kode Transaksi</label>
                                    <div class="col-md-8">
                                        <input value="{{ $newKode }}" type="text" name="penjualan_kode"
                                            id="penjualan_kode" class="form-control" required readonly>
                                        <small id="error-penjualan_kode"
                                            class="error-text form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3">
                                    <label class="col-md-4 col-form-label">Tanggal</label>
                                    <div class="col-md-8">
                                        <input value="{{ $tanggal }}" type="date" name="tanggal" id="tanggal"
                                            class="form-control" required>
                                        <small id="error-tanggal" class="error-text form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3">
                                    <label class="col-md-4 col-form-label">Nama Pembeli</label>
                                    <div class="col-md-8">
                                        <input value="{{ $transaksi->pembeli }}" type="text" name="pembeli"
                                            id="pembeli" class="form-control" required
                                            placeholder="Masukkan nama pembeli">
                                        <small id="error-pembeli" class="error-text form-text text-danger"></small>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0">Barang Dipilih</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                            <table class="table table-bordered table-sm table-striped"
                                                id="table-barang-terpilih">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th width="35%">Nama Barang</th>
                                                        <th width="20%">Harga</th>
                                                        <th width="15%">Qty</th>
                                                        <th width="25%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Items will be populated here -->
                                                </tbody>

                                            </table>
                                        </div>

                                        <div class="card-footer bg-white">
                                            <div class="row">
                                                <div class="col-md-6 text-right">
                                                    <h6 class="font-weight-bold">Total Items:</h6>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <h6 id="total-items" class="font-weight-bold">0</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 text-right">
                                                    <h5 class="font-weight-bold">Total Harga:</h5>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <h5 id="total-harga" class="font-weight-bold text-primary">Rp 0
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
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

        // Initialize barangDipilih with existing transaction data
        let barangDipilih = {};

        // Pre-fill selected items from existing transaction
        @foreach ($transaksi->detail_transaksi as $detail)
            barangDipilih['{{ $detail->barang_id }}'] = {
                kode: '{{ $detail->barang->barang_kode }}',
                nama: '{{ $detail->barang->barang_nama }}',
                harga: {{ $detail->harga }},
                jumlah: {{ $detail->jumlah }}
            };

            // Update the quantity input for this item
            $('.jumlah-barang[data-id="{{ $detail->barang_id }}"]').val({{ $detail->jumlah }});
        @endforeach

        // Initialize the selected items table
        renderBarangTerpilih();
        updateBarangData();

        // Form validation and submission
        $("#form-edit").on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Clear previous error messages
            $('.error-text').text('');

            // Validate the form
            if ($("#pembeli").val().trim() === '') {
                $('#error-pembeli').text('Nama pembeli harus diisi');
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

            // Set barang data to hidden input
            updateBarangData();

            // Submit form via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    // Show loading indicator if needed
                    $('button[type="submit"]').prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                    );
                },
                success: function(response) {
                    if (response.status) {
                        $('#editModal').modal('hide');
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
                        if (typeof $('#table_penjualan').DataTable === 'function') {
                            $('#table_penjualan').DataTable().ajax.reload();
                        }
                        $('#myModal').modal('hide');
                        $('#table_transaksi').DataTable().ajax.reload();
                    } else {
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
                },
                complete: function() {
                    // Re-enable submit button
                    $('button[type="submit"]').prop('disabled', false).html(
                        '<i class="fas fa-save mr-1"></i> Simpan Perubahan'
                    );
                }
            });

            return false;
        });

        // Handling the item selection
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

        // Allow manual input for quantity
        $('.jumlah-barang').on('change', function() {
            const id = $(this).data('id');
            let val = parseInt($(this).val()) || 0;
            if (val < 0) val = 0;
            $(this).val(val);
            updateBarangTerpilih(id, val);
        });

        // Update selected items
        function updateBarangTerpilih(id, jumlah) {
            const row = $('button[data-id="' + id + '"]').closest('tr');
            const kode = row.find('td:eq(1)').text();
            const nama = row.find('td:eq(2)').text();
            const hargaText = row.find('td:eq(3)').text().replace(/Rp /g, '').replace(/\./g, '').replace(',',
                '.');
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
            updateBarangData(); // Update hidden input with item data
        }

        // Render selected items in the table
        function renderBarangTerpilih() {
            const tbody = $('#table-barang-terpilih tbody');
            tbody.empty();
            let no = 1;
            let totalHarga = 0;
            let totalItems = 0;

            Object.values(barangDipilih).forEach(item => {
                const total = item.harga * item.jumlah;
                totalHarga += total;
                totalItems += item.jumlah;

                const row = `<tr>
                    <td>${no++}</td>
                    <td>${item.nama}</td>
                    <td>Rp ${formatRupiah(item.harga)}</td>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-right">Rp ${formatRupiah(total)}</td>
                </tr>`;
                tbody.append(row);
            });

            $('#total-harga').text(`Rp ${formatRupiah(totalHarga)}`);
            $('#total-items').text(totalItems);
        }

        // Format currency to Indonesian Rupiah
        function formatRupiah(angka) {
            return angka.toLocaleString('id-ID', {
                minimumFractionDigits: 0
            });
        }

        // Update data barang pada input tersembunyi
        function updateBarangData() {
            $('#barang-data').val(JSON.stringify(barangDipilih));
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
