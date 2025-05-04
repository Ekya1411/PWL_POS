@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-body row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chart Penjualan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" height="100"></canvas>
                    </div>
                </div>
                <div class="row d-flex justify-content-between align-items-center mt-3 pl-2 pr-2">
                    <button class="btn btn-outline-secondary btn-toggle active" id="btnTransaksi">Transaksi Terbaru</button>
                    <button class="btn btn-outline-secondary btn-toggle" id="btnStokHabis">Stok yang Hampir Habis</button>
                    <button class="btn btn-outline-secondary btn-toggle" id="btnBarangTerlaris">Barang Terlaris</button>
                    <button class="btn btn-outline-secondary btn-toggle" id="btnStokBanyak">Produk dengan Stok
                        Banyak</button>
                </div>
                <div class="table-responsive mt-3">
                    <table id="transaksi_terbaru" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Petugas</th>
                                <th>Pembeli</th>
                                <th>Kode Penjualan</th>
                                <th>Tanggal Penjualan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center p-2 pr-3 pl-3">
                        <h3 class="card-title m-0">Barang Yang Dijual</h3>
                        <select id="filterOption" class="form-control form-control-sm" style="width: 100px;">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly" selected>Bulanan</option>
                            <option value="yearly">Tahunan</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <canvas id="kategoriDoughnutChart" height="25"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        let chartInstance;

        function renderChart(data, labels) {
            const ctx = document.getElementById('myChart').getContext('2d');

            if (chartInstance) {
                chartInstance.destroy(); // hancurin chart lama
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: data,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function fetchData(filter) {
            $.get(`/chart/data`, {
                filter: filter
            }, function(response) {
                // Misalnya response = { labels: [...], data: [...] }
                renderChart(response.data, response.labels);
            });
        }

        $('#filterOption').on('change', function() {
            const selected = $(this).val();
            fetchData(selected);
        });

        // First render
        $(document).ready(function() {
            fetchData($('#filterOption').val());

            var dataUser = $('#transaksi_terbaru').DataTable({
                "paging": false, // Menonaktifkan pagination (halaman)
                "searching": false, // Menonaktifkan pencarian
                "info": false,
                // serverSide: true, jika ingin menggunakan server side processing

                serverSide: true,
                ajax: {
                    "url": "{{ url('welcome/transaksi_terbaru') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.level_id = $('#level_id').val()
                    }
                },
                columns: [{
                    // nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "user.nama",
                    className: "",
                    // orderable: true, jika ingin kolom ini bisa diurutkan
                    orderable: true,
                    // searchable: true, jika ingin kolom ini bisa dicari
                    searchable: true
                }, {
                    data: "pembeli",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    // mengambil data level hasil dari ORM berelasi
                    data: "penjualan_kode",
                    className: "",
                    orderable: false,
                    searchable: true
                }, {
                    data: "penjualan_tanggal",
                    className: "",
                    orderable: false,
                    searchable: false
                }, {
                    data: "total",
                    className: "",
                    orderable: false,
                    searchable: false
                }]
            });

            // Toggle active class for buttons
            $('.btn-toggle').on('click', function() {
                // Remove active class and btn-primary from all buttons
                $('.btn-toggle').removeClass('active btn-primary').addClass('btn-outline-primary');

                // Add active class and btn-primary to the clicked button
                $(this).removeClass('btn-outline-primary').addClass('btn-primary active');
            });
        });
    </script>
    <script>
        const ctx = document.getElementById('kategoriDoughnutChart').getContext('2d');

        const labels = {!! json_encode($kategoriPie->pluck('kategori_nama')) !!};
        const dataValues = {!! json_encode($kategoriPie->pluck('total')) !!};
        const total = {!! json_encode($totalJumlahJual->total) !!};

        const data = {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: [
                    '#f87171', '#facc15', '#4ade80', '#60a5fa', '#a78bfa', '#f472b6'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                cutout: '70%', // buat lingkaran bolong
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.formattedValue}`;
                            }
                        }
                    }
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const {
                        width
                    } = chart;
                    const {
                        height
                    } = chart;
                    const ctx = chart.ctx;
                    ctx.restore();
                    const fontSize = (height / 150).toFixed(2);
                    ctx.font = `${fontSize}em sans-serif`;
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#333";

                    const text = `${total}`;
                    const textX = Math.round((width - ctx.measureText(text).width) / 2);
                    const textY = height / 2;

                    ctx.fillText(text, textX, textY);
                    ctx.save();
                }
            }]
        };

        new Chart(ctx, config);
    </script>
@endpush
