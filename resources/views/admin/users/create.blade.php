<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; margin: 0; }
        .navbar { height: 70px; background: #fff; border-bottom: 1px solid #eaeaea; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; object-fit: cover; }
        .form-card { border: 1px solid #e0e0e0; border-radius: 12px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .form-label { font-weight: 600; color: #333; margin-bottom: 0.5rem; }
        .form-control, .form-select { border-radius: 8px; padding: 0.6rem 1rem; border: 1px solid #ced4da; transition: all 0.2s; }
        .form-control:focus, .form-select:focus { border-color: var(--mdmc-light-blue); box-shadow: 0 0 0 0.25rem rgba(26, 164, 246, 0.25); }
        .input-group-text { border-radius: 8px; cursor: pointer; background-color: #fff; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm px-4 d-flex justify-content-between align-items-center">
        <a href="/dashboard" class="text-decoration-none">
            <img src="{{ asset('images/logo-mdmc.png') }}" height="40" alt="Logo MDMC">
        </a>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/logo-mdmc.png') }}" class="profile-img me-2 shadow-sm" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=0047ba&color=fff'">
                <i class="fas fa-chevron-down text-muted small"></i>
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <h4 class="fw-bold mb-4 text-dark"><i class="fas fa-user-plus text-primary me-2"></i>Tambah Pengguna Baru</h4>
                
                <div class="form-card p-4 p-md-5">
                    <form action="/users" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label">Pilih Daerah <span class="text-danger">*</span></label>
                                <select name="regency" id="regencySelect" class="form-select @error('regency') is-invalid @enderror" required onchange="setCoordinates()">
                                    <option value="" selected disabled> Pilih Kabupaten/Kota </option>
                                    @foreach($regencies as $name => $coords)
                                        <option value="{{ $name }}" data-lat="{{ $coords['lat'] }}" data-lng="{{ $coords['lng'] }}" {{ old('regency') == $name ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('regency')
                                    <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Nama akun otomatis akan digenerate berdasarkan daerah (Contoh: MDMC Sintang).</small>
                            </div>
                            
                            <input type="hidden" name="coordinates" id="coordinates" value="{{ old('coordinates') }}">

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@gmail.com" required>
                                @error('email')
                                    <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="no_whatsapp" class="form-control @error('no_whatsapp') is-invalid @enderror" value="{{ old('no_whatsapp') }}" placeholder="Contoh: 08123456789" required>
                                @error('no_whatsapp')
                                    <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group has-validation">
                                    <input type="password" name="password" id="password" class="form-control border-end-0 @error('password') is-invalid @enderror" required>
                                    <span class="input-group-text border-start-0 @error('password') border-danger @enderror" onclick="togglePassword('password', this)">
                                        <i class="fas fa-eye text-muted"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted mt-1 d-block">*Gunakan minimal 8 karakter.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-end-0" required>
                                    <span class="input-group-text border-start-0" onclick="togglePassword('password_confirmation', this)">
                                        <i class="fas fa-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>
                                <div class="col-12 mt-4 pt-4 border-top d-flex justify-content-center gap-3">
                                    <a href="/users" class="btn btn-light px-5 py-2 fw-bold rounded-pill border shadow-sm text-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">Daftarkan Akun</button>
                                </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setCoordinates() {
        const select = document.getElementById('regencySelect');
        const selectedOption = select.options[select.selectedIndex];
        if(selectedOption.value) {
            const lat = selectedOption.getAttribute('data-lat');
            const lng = selectedOption.getAttribute('data-lng');
            document.getElementById('coordinates').value = lat + ',' + lng;
        }
    }

    function togglePassword(inputId, iconSpan) {
        const input = document.getElementById(inputId);
        const icon = iconSpan.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('regencySelect').value) {
            setCoordinates();
        }
    });
</script>
</body>
</html>