@extends('layouts.pengguna') {{-- Memanggil kerangka di atas --}}

@section('content')
<div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center overflow-hidden" style="border-radius: 12px;">
                <div class="bg-primary p-2 text-white fw-bold" style="font-size: 14px;">LAPORAN BENCANA</div>
                <div class="card-body bg-dark text-white p-4">
                    <i class="fas fa-clipboard-list fa-3x mb-2 text-secondary"></i>
                    <h1 class="display-3 fw-bold m-0">1</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="fw-bold text-muted mb-3"><i class="fas fa-cloud-sun-rain me-2"></i>PETA PANTAUAN CUACA</h6>
                <div class="ratio ratio-16x9">
                    <iframe 
                        src="https://embed.windy.com/embed2.html?lat=-0.027&lon=109.342&detailLat=-0.027&detailLon=109.342&width=650&height=450&zoom=8&level=surface&overlay=rain&product=ecmwf&menu=&message=&marker=&calendar=now&pressure=&type=map&location=coordinates&detail=&metricWind=default&metricTemp=default&radarRange=1" 
                        frameborder="0" style="border-radius: 8px;">
                    </iframe>
                </div>
                <div class="mt-3 text-end">
                    <a href="https://www.windy.com" target="_blank" class="btn btn-primary btn-sm px-4 shadow-sm">
                        Analisa Lebih Lanjut <i class="fas fa-external-link-alt ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection