<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; overflow-x: hidden; margin: 0; }
        
        /* SIDEBAR (Ukuran 280px) */
        #sidebar {
            width: 280px; min-height: 100vh; background: var(--mdmc-light-blue);
            transition: all 0.3s; position: fixed; z-index: 1000;
            display: flex; flex-direction: column;
        }
        .sidebar-header { 
            height: 70px; display: flex; align-items: center; justify-content: center;
            background: #fff; border-bottom: 1px solid #eee; position: relative;
        }

        /* Navigasi & Jarak Menu */
        .nav {
            flex-grow: 1; /* Mengisi sisa ruang kosong */
            display: flex; flex-direction: column;
            justify-content: space-evenly; /* Menyebar merata atas ke bawah */
            padding: 20px 0 40px 0; /* Ruang atas dan bawah */
        }

        .nav-item { padding: 0 15px; }

        .nav-link { 
            color: white; padding: 15px 20px; font-weight: 500; transition: 0.3s; 
            border-radius: 8px; display: flex; align-items: center;
        }

        .nav-link:hover { color: var(--mdmc-blue) !important; background: white; }

        /* MAIN CONTENT */
        #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
        
        /* NAVBAR & PROFILE SPACING */
        .navbar { height: 70px; background: #fff; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; object-fit: cover; }
        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }

        /* TABEL & CARD */
        .main-card { border: 1px solid #e0e0e0; border-radius: 12px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .btn-tambah { background-color: #0d6efd; color: #fff; font-weight: 600; border-radius: 8px; transition: 0.2s; border: none; }
        .btn-tambah:hover { background-color: #0b5ed7; color: #fff !important; }
        
        /*TOMBOL AKSI  */
        .btn-edit { 
            background-color: #00e600; 
            color: white; 
            width: 34px; 
            height: 34px; 
            border-radius: 8px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            text-decoration: none; 
            transition: all 0.3s ease-in-out; 
        }

        .btn-edit:hover {
            background-color: #00b300; /* Warna menjadi lebih gelap */
            color: white;
            /* Bayangan hitam tipis ke bawah, BUKAN cahaya menyebar */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        }

        .btn-delete { 
            background-color: #ff0000; 
            color: white; 
            width: 34px; 
            height: 34px; 
            border-radius: 8px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            border: none; 
            transition: all 0.3s ease-in-out; 
        }

        .btn-delete:hover {
            background-color: #d90000; /* Warna menjadi lebih gelap */
            /* Bayangan hitam tipis ke bawah, BUKAN cahaya menyebar */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        }
       

        /* MOBILE RESPONSIVE */
        #sidebarOverlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 999; display: none; }
        #sidebarOverlay.show { display: block; }
        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }
    </style>
</head>
<body>

<div id="sidebarOverlay" class="d-lg-none"></div>

<div class="d-flex">
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo-mdmc.png') }}" height="40" alt="Logo">
            <button class="btn btn-sm text-dark d-lg-none position-absolute start-0 ms-3" id="closeSidebar">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
        
        <ul class="nav">
            <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="fas fa-th-large me-3"></i> Beranda</a></li>
            <li class="nav-item"><a href="/users" class="nav-link"><i class="fas fa-users-cog me-3"></i> Manajemen Pengguna</a></li>
            <li class="nav-item"><a href="/peta" class="nav-link"><i class="fas fa-map-marked-alt me-3"></i> Peta Potensi Bencana</a></li>
            <li class="nav-item"><a href="/peringatan" class="nav-link"><i class="fas fa-exclamation-triangle me-3"></i> Peringatan Bencana</a></li>
            <li class="nav-item"><a href="/laporan" class="nav-link"><i class="fas fa-file-alt me-3"></i> Laporan Bencana</a></li>
        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm px-4">
            <button type="button" id="sidebarCollapse" class="btn btn-primary d-lg-none"><i class="fas fa-bars"></i></button>
             <div class="ms-auto dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark profile-toggle-btn" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/logo-mdmc.png') }}" class="profile-img me-2 shadow-sm" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=0047ba&color=fff'">
                    <i class="fas fa-chevron-down profile-arrow text-muted"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3" style="min-width: 250px; overflow: hidden;">
                    <li class="px-4 py-3 bg-light border-bottom">
                        <span class="d-block text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Login sebagai:</span>
                        <span class="d-block fw-bold text-dark" style="font-size: 14px; line-height: 1.2;">{{ Auth::user()->name }}</span>
                        <span class="d-block text-primary fw-medium" style="font-size: 12px;">(Admin)</span>
                    </li>

                    <li>
                        <a class="dropdown-item py-2 px-4 d-flex align-items-center mt-2" href="/profile">
                            <i class="fas fa-user-edit text-muted me-3" style="width: 20px;"></i> 
                            <span>Edit Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form action="{{ url('/logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 px-4 d-flex align-items-center text-danger mb-1">
                                <i class="fas fa-power-off me-3" style="width: 20px;"></i> 
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-4 flex-grow-1">
            <h4 class="fw-bold mb-4" style="color: #111; text-transform: uppercase;">Manajemen Pengguna</h4>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert" id="autoCloseAlert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <a href="/users/create" class="btn btn-tambah px-4 py-2 mb-4 d-inline-block shadow-sm">Tambah Pengguna <i class="fas fa-user-plus ms-2"></i></a>

            <div class="main-card p-4">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Pengguna</th>
                                <th width="30%">Email</th>
                                <th width="25%">No WhatsApp</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $u)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-medium">{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->no_whatsapp ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="/users/{{ $u->id }}/edit" class="btn-edit shadow-sm" title="Edit"><i class="fas fa-pen fa-sm"></i></a>
                                  <form action="{{ url('/users/' . $u->id) }}" method="POST" class="d-inline form-delete">
                                      @csrf
                                      @method('DELETE') <button type="button" class="btn-delete btn-hapus-custom" data-nama="{{ $u->name }}">
                                     <i class="fas fa-trash"></i>
                                     </button>
                                  </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada akun daerah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="text-center py-4 mt-auto">
            <small class="text-muted">
                <i class="far fa-copyright"></i> MDMC KALIMANTAN BARAT 2026 - SOLID BERGERAK MONITOR | DIKELOLA OLEH BIDANG DATA DAN INFORMASI
            </small>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. LOGIKA SIDEBAR (KODE LAMA KAMU)
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    sidebarCollapse.addEventListener('click', () => { 
        sidebar.classList.add('show'); 
        sidebarOverlay.classList.add('show'); 
    });
    
    const hideSidebar = () => { 
        sidebar.classList.remove('show'); 
        sidebarOverlay.classList.remove('show'); 
    };
    
    closeSidebar.addEventListener('click', hideSidebar);
    sidebarOverlay.addEventListener('click', hideSidebar);

    // 2. LOGIKA SWEETALERT UNTUK KONFIRMASI HAPUS
    document.querySelectorAll('.btn-hapus-custom').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('.form-delete');
            const namaUser = this.getAttribute('data-nama');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Akun " + namaUser + " akan dihapus dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0000ff', // Biru sesuai tema tombol Simpan kamu
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'rounded-3',
                    cancelButton: 'rounded-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim form jika klik OK
                }
            });
        });
    });

    // 3. LOGIKA SWEETALERT UNTUK NOTIFIKASI SUKSES (PENGGANTI ALERT HIJAU)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true
        });
    @endif

    // 4. (OPSIONAL) TETAP JAGA LOGIKA AUTOCLOSE ALERT BOOTSTRAP 
    // Jika kamu masih pakai alert hijau bawaan di atas tabel
    setTimeout(function() {
        let alertNode = document.getElementById('autoCloseAlert');
        if(alertNode) { 
            let alert = new bootstrap.Alert(alertNode); 
            alert.close(); 
        }
    }, 3000); 
</script>
</body>
</html>