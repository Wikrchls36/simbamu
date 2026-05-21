<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fcfcfc; }
        .form-card { border: 1px solid #ccc; border-radius: 8px; background: #fff; max-width: 600px; margin: 0 auto; }
        .form-label { font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #000; }
        .form-control { border: 1px solid #ddd; border-radius: 4px; padding: 10px; }
        .input-readonly { background-color: #e9ecef; color: #495057; cursor: not-allowed; }
        .password-container { position: relative; }
        .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #000; }
        .btn-kembali { background-color: #aaa; color: white; font-weight: bold; padding: 10px 30px; border-radius: 5px; text-decoration: none; }
        .btn-kembali:hover { background-color: #999; color: white; }
        .btn-simpan { background-color: #0000ff; color: white; font-weight: bold; padding: 10px 30px; border-radius: 5px; }
        .btn-simpan:hover { background-color: #0000cc; color: white; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 p-5">

<div class="form-card p-5 flex-grow-1">
    <h5 class="text-center fw-bold mb-4">Edit Data Pengguna</h5>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0 rounded-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/users/{{ $user->id }}" method="POST">
        @csrf
        @method('PUT') 
        
        <div class="mb-4">
            <label class="form-label">Daerah</label>
            <input type="text" class="form-control input-readonly" value="{{ $user->regency }}" readonly>
        </div>

        <div class="mb-4">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control input-readonly" value="{{ $user->name }}" readonly>
        </div>

        <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" name="no_whatsapp" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('no_whatsapp', $user->no_whatsapp) }}" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="password-container">
                <input type="password" name="password" id="password" class="form-control" >
                <i class="fas fa-eye toggle-password" onclick="toggleVisibility('password', this)"></i>
            </div>
            <small class="text-muted" style="font-size: 11px;">*Gunakan minimal 8 karakter jika ingin mengganti password.</small>
        </div>

        <div class="mb-5">
            <label class="form-label">Konfirmasi Password</label>
            <div class="password-container">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                <i class="fas fa-eye toggle-password" onclick="toggleVisibility('password_confirmation', this)"></i>
            </div>
            <small class="text-muted" style="font-size: 11px;">*Gunakan minimal 8 karakter jika ingin mengganti password.</small>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="/users" class="btn btn-kembali">Kembali</a>
            <button type="submit" class="btn btn-simpan">Simpan</button>
        </div>
    </form>
</div>

<footer class="text-center py-4 mt-5">
    <small class="text-muted">
        <i class="far fa-copyright"></i> MDMC KALIMANTAN BARAT 2026 - SOLID BERGERAK MONITOR | DIKELOLA OLEH BIDANG TANGGAP DARURAT
    </small>
</footer>

<script>
   
    function toggleVisibility(inputId, iconElement) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            iconElement.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            input.type = "password";
            iconElement.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2500,
            showConfirmButton: false,
            timerProgressBar: true,
            showClass: { popup: 'animate__animated animate__fadeInUp' },
            hideClass: { popup: 'animate__animated animate__fadeOutDown' }
        });
    @endif
</script>

</body>
</html>