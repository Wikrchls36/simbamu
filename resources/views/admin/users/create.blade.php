<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fcfcfc; }
        .form-card { border: 1px solid #ccc; border-radius: 8px; background: #fff; max-width: 600px; margin: 0 auto; }
        .form-label { font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #000; }
        .form-control, .form-select { border: 1px solid #ddd; border-radius: 4px; padding: 10px; }
        .input-readonly { background-color: #f8f9fa; color: #555; }
        .password-container { position: relative; }
        .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #000; }
        .btn-kembali { background-color: #aaa; color: white; font-weight: bold; padding: 10px 30px; border-radius: 5px; text-decoration: none; }
        .btn-kembali:hover { background-color: #999; color: white; }
        .btn-simpan { background-color: #0000ff; color: white; font-weight: bold; padding: 10px 30px; border-radius: 5px; }
        .btn-simpan:hover { background-color: #0000cc; color: white; }
   }
    </style>
</head>
<body class="p-5">

<div class="form-card p-5">
    <h5 class="text-center fw-bold mb-4">Tambah Pengguna</h5>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="/users" method="POST">
        @csrf
        <input type="hidden" name="coordinates" id="coordinates">

        <div class="mb-4">
            <label class="form-label">Daerah</label>
            <select name="regency" id="regency_select" class="form-select" required>
                <option value="">Pilih Daerah</option>
                @foreach($regencies as $name => $coords)
                    <option value="{{ $name }}" data-coords="{{ $coords['lat'] }},{{ $coords['lng'] }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label">Nama</label>
            <input type="text" name="name" id="auto_name" class="form-control input-readonly" placeholder="Otomatis (MDMC + Daerah)" readonly required>
        </div>

        <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Isikan alamat email" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Nomor WhatsApp</label>
           <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="no_whatsapp" class="form-control" placeholder="Isikan nomor WhatsApp (Contoh: 62812...)" value="{{ old('no_whatsapp') }}" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="password-container">
                <input type="password" name="password" id="password" class="form-control" placeholder="Isikan minimal 8 karakter" required minlength="8">
                <i class="fas fa-eye toggle-password" onclick="toggleVisibility('password', this)"></i>
            </div>
            <small class="text-muted" style="font-size: 11px;">*Gunakan minimal 8 karakter kombinasi huruf dan angka.</small>
        </div>

        <div class="mb-5">
            <label class="form-label">Konfirmasi Password</label>
            <div class="password-container">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Isikan minimal 8 karakter" required>
                <i class="fas fa-eye toggle-password" onclick="toggleVisibility('password_confirmation', this)"></i>
            </div>
            <small class="text-muted" style="font-size: 11px;">*Gunakan minimal 8 karakter kombinasi huruf dan angka.</small>
        </div>

        <div class="d-flex justify-content-between">
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
    
    document.getElementById('regency_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const regencyName = selectedOption.value;
        const coords = selectedOption.getAttribute('data-coords');
        
        const nameInput = document.getElementById('auto_name');
        const coordsInput = document.getElementById('coordinates');

        if(regencyName) {
            nameInput.value = 'MDMC ' + regencyName;
            coordsInput.value = coords; 
        } else {
            nameInput.value = '';
            coordsInput.value = '';
        }
    });

    
    function toggleVisibility(inputId, iconElement) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            iconElement.classList.remove("fa-eye");
            iconElement.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            iconElement.classList.remove("fa-eye-slash");
            iconElement.classList.add("fa-eye");
        }
    }
</script>
</body>
</html>