<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Peta - SIMBAMU</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .badge-potensi { min-width: 85px; }
        
        
        .modal-content { border-radius: 15px; border: none; }
        .modal-header { border-bottom: 2px solid #f1f1f1; border-top-left-radius: 15px; border-top-right-radius: 15px; }
        .modal-footer { border-top: 2px solid #f1f1f1; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; }
    </style>
</head>
<body>

    <div class="container-fluid max-w-100">
       <div class="d-flex align-items-start align-items-md-center mb-4">
                
                <a href="{{ route('peta.index') }}" class="btn btn-light border text-secondary shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; border-radius: 8px;" title="Kembali">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="flex-grow-1 text-center" style="padding-right: 42px;"> 
                    <h4 class="fw-bold text-dark m-0">Kelola Data Peta</h4>
    
                </div>
                
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle border rounded-3 overflow-hidden">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-3">Kabupaten / Kota</th>
                            <th class="text-center py-3">Banjir (Ha)</th>
                            <th class="text-center py-3">Status Banjir</th>
                            <th class="text-center py-3">Karhutla (Titik)</th>
                            <th class="text-center py-3">Status Karhutla</th>
                            <th class="text-center py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPeta as $item)
                        <tr>
                            <td class="fw-bold px-3">{{ $item->kabupaten_kota }}</td>
                            <td class="text-center">{{ $item->luas_genangan }}</td>
                            <td class="text-center">
                                <span class="badge badge-potensi {{ $item->potensi_banjir == 'Tinggi' ? 'bg-danger' : ($item->potensi_banjir == 'Sedang' ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $item->potensi_banjir }}
                                </span>
                            </td>
                            <td class="text-center">{{ $item->jumlah_hotspot }}</td>
                            <td class="text-center">
                                <span class="badge badge-potensi {{ $item->potensi_karhutla == 'Tinggi' ? 'bg-danger' : ($item->potensi_karhutla == 'Sedang' ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $item->potensi_karhutla }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow">
                                    <div class="modal-header bg-white">
                                        <h5 class="modal-title fw-bold text-dark">
                                            Update Data: <span class="text-primary">{{ $item->kabupaten_kota }}</span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <form action="{{ route('peta.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') 
                                        
                                        <div class="modal-body p-4">
                                            
                                            <div class="p-3 bg-light rounded-3 mb-3 border border-primary border-opacity-25">
                                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-water me-2"></i>Data Profil Banjir</h6>
                                                
                                                <div class="row g-3 mb-3">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Luas Genangan (Ha)</label>
                                                        <input type="number" step="0.01" name="luas_genangan" class="form-control form-control-sm" value="{{ $item->luas_genangan }}" required>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Tingkat Potensi Banjir</label>
                                                        <select name="potensi_banjir" class="form-select form-select-sm">
                                                            <option value="Rendah" {{ $item->potensi_banjir == 'Rendah' ? 'selected' : '' }}>Rendah (Hijau)</option>
                                                            <option value="Sedang" {{ $item->potensi_banjir == 'Sedang' ? 'selected' : '' }}>Sedang (Kuning)</option>
                                                            <option value="Tinggi" {{ $item->potensi_banjir == 'Tinggi' ? 'selected' : '' }}>Tinggi (Merah)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Rentang Tahun</label>
                                                        <input type="text" name="tahun_banjir" class="form-control form-control-sm" value="{{ $item->tahun_banjir }}" placeholder="Cth: 2024-2025">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Jumlah Jiwa Terdampak</label>
                                                        <input type="number" name="jiwa_terdampak_banjir" class="form-control form-control-sm" value="{{ $item->jiwa_terdampak_banjir }}">
                                                    </div>
                                                </div>

                                                <label class="form-label small fw-bold text-dark border-bottom pb-1 d-block mb-2">Jumlah Material Terdampak (Unit)</label>
                                                <div class="row g-2">
                                                    <div class="col-6 col-md-3">
                                                        <label class="form-label" style="font-size: 11px;">Rumah Warga</label>
                                                        <input type="number" name="rumah_warga_banjir" class="form-control form-control-sm" value="{{ $item->rumah_warga_banjir }}">
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <label class="form-label" style="font-size: 11px;">Rumah Ibadah</label>
                                                        <input type="number" name="rumah_ibadah_banjir" class="form-control form-control-sm" value="{{ $item->rumah_ibadah_banjir }}">
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <label class="form-label" style="font-size: 11px;">Fasilitas Kesehatan</label>
                                                        <input type="number" name="faskes_banjir" class="form-control form-control-sm" value="{{ $item->faskes_banjir }}">
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <label class="form-label" style="font-size: 11px;">Fasilitas Pendidikan</label>
                                                        <input type="number" name="fasdik_banjir" class="form-control form-control-sm" value="{{ $item->fasdik_banjir }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 bg-light rounded-3 mb-3 border border-danger border-opacity-25">
                                                <h6 class="fw-bold text-danger mb-3"><i class="fas fa-fire me-2"></i>Data Profil Karhutla</h6>
                                                
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Jumlah Hotspot (Titik)</label>
                                                        <input type="number" name="jumlah_hotspot" class="form-control form-control-sm" value="{{ $item->jumlah_hotspot }}" required>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Tingkat Potensi Karhutla</label>
                                                        <select name="potensi_karhutla" class="form-select form-select-sm">
                                                            <option value="Rendah" {{ $item->potensi_karhutla == 'Rendah' ? 'selected' : '' }}>Rendah (Hijau)</option>
                                                            <option value="Sedang" {{ $item->potensi_karhutla == 'Sedang' ? 'selected' : '' }}>Sedang (Kuning)</option>
                                                            <option value="Tinggi" {{ $item->potensi_karhutla == 'Tinggi' ? 'selected' : '' }}>Tinggi (Merah)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Rentang Tahun</label>
                                                        <input type="text" name="tahun_karhutla" class="form-control form-control-sm" value="{{ $item->tahun_karhutla }}" placeholder="Cth: 2024-2025">
                                                        </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-bold text-muted">Jumlah Jiwa Terdampak</label>
                                                        <input type="number" name="jiwa_terdampak_karhutla" class="form-control form-control-sm" value="{{ $item->jiwa_terdampak_karhutla }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-bold text-muted">Total Luas Hektare Terbakar (Ha)</label>
                                                        <input type="number" step="0.01" name="luas_terbakar_karhutla" class="form-control form-control-sm" value="{{ $item->luas_terbakar_karhutla }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 bg-white rounded-3 border">
                                                <label class="form-label small fw-bold text-dark"><i class="fas fa-info-circle text-info me-2"></i>Sumber Referensi / Informasi</label>
                                                <input type="text" name="sumber_data" class="form-control form-control-sm" value="{{ $item->sumber_data }}" placeholder="Contoh: BPBD Provinsi Kalbar / BMKG Supadio">
                                            </div>

                                        </div>
                                        <div class="modal-footer bg-white">
                                            <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary fw-bold px-4"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-database fa-3x mb-3 text-light"></i><br>
                                Data kabupaten/kota belum tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false, 
                timer: 2500, 
                timerProgressBar: true
            });
        @endif
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
     
        const kotakHijau = document.querySelector('.alert-success');
        
        if (kotakHijau) {
            
            setTimeout(function() {
                
                kotakHijau.style.transition = "opacity 0.5s ease";
                kotakHijau.style.opacity = "0";
                
                setTimeout(function() {
                    kotakHijau.remove();
                }, 500); 
                
            }, 2500); 
        }
    });
</script>
</body>
</html>