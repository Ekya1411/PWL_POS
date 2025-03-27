<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Akun</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card border-primary w-50">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Tambah Akun</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/account/store') }}" method="POST" id="form-tambah">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Level Pengguna</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">- Pilih Level -</option>
                        @foreach ($level as $l)
                            <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="text-danger"></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="text-danger"></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="text-danger"></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="text-danger"></small>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#form-tambah").validate({
                rules: {
                    level_id: { required: true },
                    username: { required: true, minlength: 3, maxlength: 20 },
                    nama: { required: true, minlength: 3, maxlength: 100 },
                    password: { required: true, minlength: 6, maxlength: 20 }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then(() => {
                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    }
                                });
                            } else {
                                $('.text-danger').text('');
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
                        error: function(xhr) {
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
                    element.closest('.mb-3').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
