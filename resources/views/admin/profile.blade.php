<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; }
        .profile-card { border-radius: 8px; border: 1px solid #ccc; background: #fff; max-width: 550px; margin: 0 auto; }
        .profile-photo-wrapper { position: relative; width: 120px; height: 120px; margin: 0 auto 30px; }
        .profile-photo { width: 100%; height: 100%; border-radius: 50%; border: 1px solid #ccc; object-fit: cover; padding: 5px; background: #fff; }
        .edit-photo-badge { position: absolute; bottom: 0; right: 5px; background: #dcdcdc; color: #555; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; cursor: pointer; transition: 0.2s; }
        .edit-photo-badge:hover { background: #bbb; }
        .form-label { font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #000; }
        
       
        .input-readonly { background-color: #9e9e9e !important; border-color: #888; color: #222; font-weight: 500; cursor: not-allowed; }
        
        .password-container { position: relative; }
        .password-container input { padding-right: 40px; }
        .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #000; }
        
        .btn-kembali { background-color: #aaa; color: white; font-weight: bold; padding: 10px 30px; border-radius: 5px; text-decoration: none; }
        .btn-kembali:hover { background-color: #999; color: white; }
        .btn-simpan { background-color: #0000ff; color: white; font-weight: bold; padding: 10px 30px; border-radius: 5px; }
        .btn-simpan:hover { background-color: #0000cc; color: white; }
        .img-container img { max-width: 100%; display: block; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<div class="container mt-5 mb-4 flex-grow-1">
    <div class="profile-card p-5 shadow-sm">
        <h5 class="text-center fw-bold mb-4">Edit Profil</h5>

        @if($errors->any())
            <div class="alert alert-danger shadow-sm border-0 rounded-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/profile" method="POST" id="profileForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="cropped_photo" id="cropped_photo">

            <div class="profile-photo-wrapper text-center">
                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/logo-mdmc.png') }}" 
                     class="profile-photo" id="photoPreview" alt="Foto Profil">
                
                <div class="edit-photo-badge" onclick="document.getElementById('profile_photo').click()" title="Ubah Foto Profil">
                    <i class="fas fa-pencil-alt fa-sm"></i>
                </div>
                
                <input type="file" id="profile_photo" class="d-none" accept="image/png, image/jpeg, image/jpg">
            </div>

            <div class="mb-4">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control input-readonly" value="{{ $user->name }}" readonly>
            </div>

            <div class="mb-4">
                <label class="form-label">Email</label>
                <input type="email" class="form-control input-readonly" value="{{ $user->email }}" readonly>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" class="form-control" placeholder="kosongkan jika tidak ingin mengubah password" minlength="8">
                    <i class="fas fa-eye toggle-password" onclick="toggleVisibility('password', this)"></i>
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label">Konfirmasi Password</label>
                <div class="password-container">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="kosongkan jika tidak ingin mengubah password" minlength="8">
                    <i class="fas fa-eye toggle-password" onclick="toggleVisibility('password_confirmation', this)"></i>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="/dashboard" class="btn btn-kembali text-decoration-none">Kembali</a>
                <button type="submit" class="btn btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="cropModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="cropModalLabel">Sesuaikan Ukuran Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="imageToCrop" src="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnCrop">Potong & Terapkan</button>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-4 mt-5">
    <small class="text-muted">
        <i class="far fa-copyright"></i> MDMC KALIMANTAN BARAT 2026 - SOLID BERGERAK MONITOR | DIKELOLA OLEH BIDANG TANGGAP DARURAT
    </small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    // Animasi SweetAlert2 Sukses
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

    // LOGIKA CROPPER JS
    let cropper;
    const profilePhotoInput = document.getElementById('profile_photo');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    profilePhotoInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const url = URL.createObjectURL(file);
            imageToCrop.src = url; 
            cropModal.show();      
        }
    });

    document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, 
            viewMode: 1,
            autoCropArea: 1,
        });
    });

    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) { cropper.destroy(); cropper = null; }
        profilePhotoInput.value = null; 
    });

    document.getElementById('btnCrop').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({
            width: 400, 
            height: 400
        });

        const base64Image = canvas.toDataURL('image/png');
        document.getElementById('photoPreview').src = base64Image;
        document.getElementById('cropped_photo').value = base64Image;
        cropModal.hide();
    });
</script>
</body>
</html>