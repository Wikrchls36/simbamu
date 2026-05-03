<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Bencana - SIMBAMU</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; overflow-x: hidden; }
        
        /* SIDEBAR FLEXBOX */
        #sidebar {
            width: 280px; min-height: 100vh; background: var(--mdmc-light-blue);
            transition: all 0.3s; position: fixed; z-index: 1000;
            display: flex; flex-direction: column;
        }
        #sidebar.active { margin-left: -280px; }

        .sidebar-header { 
            height: 70px; display: flex; align-items: center; justify-content: center;
            background: #fff; border-bottom: 1px solid #eee; position: relative;
        }

        .nav {
            flex-grow: 1; display: flex; flex-direction: column;
            justify-content: space-evenly; padding: 20px 0 40px 0;
        }

        .nav-item { padding: 0 15px; }

        .nav-link { 
            color: white; padding: 15px 20px; font-weight: 500; transition: 0.3s; 
            border-radius: 8px; display: flex; align-items: center; text-decoration: none;
        }

        .nav-link:hover, .nav-link.active { 
            color: var(--mdmc-blue) !important; background: white; 
        }
        
        /* OVERLAY UNTUK MOBILE */
        #sidebarOverlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 999; display: none;
        }
        #sidebarOverlay.show { display: block; }

        /* MAIN CONTENT & NAVBAR */
        #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
        #content.active { width: 100%; margin-left: 0; }
        .navbar { height: 70px; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; }

        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }

        /* CUSTOM CSS TABEL PERINGATAN */
        .card-top-border { border-top: 4px solid var(--mdmc-light-blue) !important; }
        .table-custom th { color: #333; font-weight: 600; padding: 15px; border-bottom: 2px solid #ddd; }
        .table-custom td { vertical-align: middle; padding: 15px; border-bottom: 1px solid #eee; }
        
        /* Indikator Status Potensi */
        .dot-indikator { height: 12px; width: 12px; border-radius: 50%; display: inline-block; margin-right: 8px; }
        .dot-monitoring { background-color: #00d26a; } /* Hijau */
        .dot-siaga { background-color: #ffc107; } /* Kuning */
        .dot-waspada { background-color: #f82649; } /* Merah */

        /* Hover Tombol Aksi */
        .btn-warning:hover {
            background-color: #ffca2c !important; /* Warna kuning menjadi sedikit lebih gelap */
            color: #000 !important; /* Memastikan teks tetap hitam/gelap */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important; /* Bayangan hitam tipis jatuh ke bawah */
        }

        /* Responsive Mobile */
        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }

        /*menghilangkan efek */
        #searchInput:focus {
            box-shadow: none !important;
            border-color: #dee2e6 !important;
        }   

    </style>
</head>
<body>

<div id="sidebarOverlay" class="d-lg-none"></div>

<div class="d-flex">
    
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo-mdmc.png') }}" height="40" alt="Logo" onerror="this.style.display='none'">
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
        
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
            <div class="d-flex align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-primary d-lg-none me-3">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="fw-bold m-0 d-none d-md-block text-dark">Peringatan Bencana</h4>
            </div>
            
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

            <h4 class="fw-bold text-dark d-md-none mb-4">Peringatan Bencana</h4>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm border-0" id="success-alert">
                    <i class="fas fa-check-circle me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center shadow-sm border-0" id="warning-alert">
                    <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- 1. TABEL PENGGUNA (Tampilan Bersih & Sticky Header) --}}
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-0">
                    {{-- Batas tinggi 300px agar tabel pengguna tidak memakan layar jika data kabupaten banyak --}}
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            
                            {{-- Header menempel di atas (Sticky) --}}
                            <thead class="bg-white" style="position: sticky; top: 0; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 bg-white text-dark" style="width: 5%">No</th>
                                    <th class="py-3 bg-white text-dark">Pengguna</th>
                                    <th class="py-3 bg-white text-dark">Lokasi</th>
                                    <th class="py-3 bg-white text-dark text-center" style="width: 20%">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($users as $index => $user)
                                <tr class="border-bottom-0">
                                    <td class="px-4">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $user->name }}</span>
                                    </td>
                                    <td>
                                        {{ $user->regency ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Aksi --}}
                                        <button type="button" class="btn btn-warning btn-sm fw-bold px-3 shadow-sm border-0" 
                                            style="border-radius: 8px; transition: all 0.3s;"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalPeringatan"
                                            data-nama="{{ $user->name }}"
                                            data-id="{{ $user->id }}">
                                            <i class="fas fa-exclamation-triangle me-2"></i> Kirim Peringatan
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 2. TABEL LOG PERINGATAN (Sesuai kodemu) --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="fw-bold text-dark m-0">Log Instruksi & Peringatan</h6>
                    <div class="input-group" style="max-width: 250px;">
                        <input type="text" id="searchInput" class="form-control form-control-sm rounded-start-pill border-end-0" placeholder="Tuliskan pencarian...">
                        <span class="input-group-text bg-white rounded-end-pill border-start-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    {{-- Batas tinggi 400px agar bisa di-scroll --}}
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-custom m-0 table-hover">
                            {{-- Header menempel di atas (Sticky) --}}
                            <thead class="bg-white" style="position: sticky; top: 0; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Tujuan</th>
                                    <th>Potensi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white" id="logTableBody">
                                @forelse($peringatans as $log)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $log->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                                    <td class="fw-medium text-dark">{{ $log->user->asal_daerah ?? $log->user->name }}</td>
                                    <td>
                                        @if($log->tingkat_potensi == 'Monitoring')
                                            <span class="fw-bold"><span class="dot-indikator dot-monitoring"></span> Monitoring</span>
                                        @elseif($log->tingkat_potensi == 'Siaga')
                                            <span class="fw-bold"><span class="dot-indikator dot-siaga"></span> Siaga</span>
                                        @else
                                            <span class="fw-bold"><span class="dot-indikator dot-waspada"></span> Waspada</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($log->status_konfirmasi == 'Telah Direspon' || $log->status_konfirmasi == 'Dikonfirmasi')
                                            <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">Telah Direspon</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">Belum Direspon</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm rounded-3 shadow-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDetailPeringatan{{ $log->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat peringatan yang dikirim.</td>
                                </tr>
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

{{-- MODAL (Satu Modal Saja, di-handle oleh Javascript) --}}
<div class="modal fade" id="modalPeringatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <form action="{{ route('peringatan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="inputIdTujuan">

                <div class="modal-header border-bottom px-4 pt-4 pb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Kirim Peringatan Bencana</h5>
                        <h6 class="fw-bold text-dark m-0">Tujuan : <span id="labelTujuan" class="text-primary">...</span></h6>
                    </div>
                </div>

                <div class="modal-body p-4">
                    <p class="fw-bold text-dark mb-3">Pilih Tingkat Potensi Peringatan</p>
                    
                    <div class="d-flex justify-content-between flex-wrap gap-2 mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tingkat_potensi" id="levelMonitoring" value="Monitoring" checked>
                            <label class="form-check-label fw-medium text-dark" for="levelMonitoring">
                                Monitoring
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tingkat_potensi" id="levelSiaga" value="Siaga">
                            <label class="form-check-label fw-medium text-dark" for="levelSiaga">
                                Siaga
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tingkat_potensi" id="levelWaspada" value="Waspada">
                            <label class="form-check-label fw-medium text-dark" for="levelWaspada">
                                Waspada
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-dark mb-2">Instruksi</label>
                        <textarea class="form-control rounded-3" name="instruksi" rows="4" placeholder="Tuliskan instruksi untuk daerah terkait..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top-0 px-4 pb-4 pt-0 justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary px-4 fw-bold border-0 rounded-3 shadow-sm" style="background-color: #a5a5a5;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold border-0 rounded-3 shadow-sm" style="background-color: #1a1aff;">Kirim</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL DETAIL RIWAYAT PERINGATAN --}}

@foreach($peringatans as $log)
<div class="modal fade" id="modalDetailPeringatan{{ $log->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            {{-- Header --}}
            <div class="modal-header border-bottom flex-column align-items-start px-4 pt-4 pb-3">
                <h6 class="fw-bold text-dark mb-2">Detail Peringatan Bencana</h6>
                <h6 class="fw-bold text-dark m-0">Tujuan : {{ $log->user->asal_daerah ?? $log->user->name }}</h6>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4 text-start">
                <p class="fw-bold text-dark mb-2">Potensi Peringatan :</p>
                <div class="d-flex align-items-center mb-4">
                    @if($log->tingkat_potensi == 'Monitoring')
                        <span class="dot-indikator dot-monitoring me-2"></span> <span class="fw-bold text-dark">Monitoring</span>
                    @elseif($log->tingkat_potensi == 'Siaga')
                        <span class="dot-indikator dot-siaga me-2"></span> <span class="fw-bold text-dark">Siaga</span>
                    @else
                        <span class="dot-indikator dot-waspada me-2"></span> <span class="fw-bold text-dark">Waspada</span>
                    @endif
                </div>

                <p class="fw-bold text-dark mb-2">Detail Informasi</p>
                <div class="ms-3 mb-3">
                    {{-- PERBAIKAN 1: Menyesuaikan Zona Waktu ke WIB (Asia/Jakarta) --}}
                    <p class="fw-bold text-dark mb-1">Tanggal : {{ $log->created_at->timezone('Asia/Jakarta')->format('d F Y') }}</p>
                    <p class="fw-bold text-dark mb-3">Waktu : {{ $log->created_at->timezone('Asia/Jakarta')->format('H.i') }} WIB</p>

                    <p class="fw-bold text-dark mb-2">Instruksi</p>
                    <div class="border rounded-3 p-3 bg-white text-dark mb-4" style="min-height: 100px; border-color: #ccc !important;">
                        {{ $log->instruksi }}
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <p class="fw-bold text-dark mb-0 me-2">Status Respon :</p>
                    {{-- PERBAIKAN 2: Sinkronisasi Status dengan Tabel Log (Merah/Hijau) --}}
                    @if($log->status_konfirmasi == 'Telah Direspon' || $log->status_konfirmasi == 'Dikonfirmasi')
                        <span class="badge bg-success px-3 py-2 rounded-3 text-white fw-bold">Telah Direspon</span>
                    @else
                        <span class="badge bg-danger px-3 py-2 rounded-3 text-white fw-bold">Belum Direspon</span>
                    @endif
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-top-0 px-4 pb-4">
                <button type="button" class="btn text-white fw-bold px-4 rounded-3" style="background-color: #a5a5a5;" data-bs-dismiss="modal">Tutup</button>
            </div>
            
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 1. Logika Sidebar Mobile
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    sidebarCollapse.addEventListener('click', () => { sidebar.classList.add('show'); sidebarOverlay.classList.add('show'); });
    const hideSidebar = () => { sidebar.classList.remove('show'); sidebarOverlay.classList.remove('show'); };
    closeSidebar.addEventListener('click', hideSidebar); sidebarOverlay.addEventListener('click', hideSidebar);

    // 2. Logika Modal Peringatan (Mengisi ID dan Nama tujuan otomatis)
    const modalPeringatan = document.getElementById('modalPeringatan');
    modalPeringatan.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const namaDaerah = button.getAttribute('data-nama');
        const idDaerah = button.getAttribute('data-id'); // Menangkap ID
        
        document.getElementById('labelTujuan').textContent = namaDaerah;
        document.getElementById('inputIdTujuan').value = idDaerah; // Memasukkan ID ke input hidden
    });

    

    // 3. Auto-hide Alert Sukses
    document.addEventListener("DOMContentLoaded", function() {
        const alertElement = document.getElementById("success-alert");
        if (alertElement) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, 3000);
        }
    });


    // 4. Logika Pencarian Tabel Log (Real-time)
    const searchInput = document.getElementById('searchInput');
    const logTableBody = document.getElementById('logTableBody');

    if (searchInput && logTableBody) {
        searchInput.addEventListener('keyup', function() {
            let filterValue = this.value.toLowerCase();
            let rows = logTableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                // Abaikan baris kosong ("Belum ada riwayat...")
                if (rows[i].getElementsByTagName('td')[0].colSpan > 1) {
                    continue; 
                }

                let rowText = rows[i].textContent.toLowerCase();
                
                // Jika teks baris cocok dengan pencarian, tampilkan. Jika tidak, sembunyikan.
                if (rowText.includes(filterValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    }
</script>
</body>
</html>