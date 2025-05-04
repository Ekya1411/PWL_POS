@empty($transaksi)
    <div id="modal-detail" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-detail" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Data Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Kode Penjualan</th>
                        <td>{{ $transaksi->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Petugas</th>
                        <td>{{ $transaksi->user->nama }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $transaksi->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $transaksi->penjualan_tanggal }}</td>
                    </tr>
                </table>
                <div class="" style="max-height: 300px; overflow-y: auto;">
                    <table id="transaksiTable" class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi->detail_transaksi as $index => $detail)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $detail->barang->barang_nama }}</td>
                                    <td>Rp {{number_format($detail->barang->harga_jual)}}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->harga) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="4" class="text-right"></th>
                                <td>Rp {{ number_format($transaksi->total) }}</td>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
            </div>
        </div>
    </div>
@endempty
