<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Croscek - {{ strtoupper($unit) }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 15px; }
        .header { text-align: center; margin-bottom: 20px; }
        .kop-surat1 { text-align: center; font-size: 30px; font-weight: bold; color: rgba(255, 0, 0, 0.853); }
        .kop-surat2 { text-align: center; font-size: 21px; font-weight: bold; color: rgb(0, 102, 255) }
        .sub-kop { font-size: 12px; }
        .logo { position: absolute; top: 10px; width: 80px; }
        /* .logo-left { left: 20px; }
        .logo-right { right: 20px; } */
        .line { border-bottom: 3px solid black; margin-top: 5px; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table, .table th, .table td { border: 1px solid black; }
        .table th, .table td { padding: 5px; text-align: left; }
        .title { text-align: center; font-size: 16px; font-weight: bold; }
        .footer { margin-top: 40px; text-align: right; }
        .footer .signature { margin-top: 50px; font-weight: bold; text-decoration: underline; }
        .footer .position { margin-top: -10px; }
    </style>
</head>
<body>

    <!-- LOGO -->
    {{-- <img src="{{ public_path('assets/logo_alfityan.png') }}" class="logo logo-left">
    <img src="{{ public_path('assets/logo_ppdb.png') }}" class="logo logo-right"> --}}

    <!-- KOP SURAT -->
    <div class="header">
        <div class="kop-surat1">YAYASAN AL-FITYAN KUBU RAYA</div>
        <div class="kop-surat2">{{ strtoupper($unit) }} AL-FITYAN KUBU RAYA</div>
        <div class="kop-surat2">"AKREDITASI A"</div>
        <div class="sub-kop">
            Alamat: Jl. Raya Sungai Kakap Km. 7 RT.03/ RW.01, Desa Pal Sembilan, Kec. Sungai Kakap,  
            Kab. Kubu Raya, Prov. Kalimantan Barat  
            Telp. 62 896-0407-0304, Email: smpit_afisku@yahoo.com  
            Website: www.alfityankuburaya.sch.id  
        </div>
    </div>

    <!-- GARIS PEMISAH -->
    <div class="line"></div>

    <!-- JUDUL DOKUMEN -->
    <div class="title">LAPORAN REKAP MONITORING DATA SISWA - {{ strtoupper($unit) }}</div>

    <p><strong>Total Siswa:</strong> {{ $totalSiswa }}</p>
    <p><strong>Total Siswa Lunas:</strong> {{ $totalLunas }}</p>
    <p><strong>Total Siswa Mengundurkan Diri:</strong> <span style="color: red">{{ $totalMengundurkanDiri }}</span></p>
    <p><strong>Total Anak GTK:</strong> {{ $totalAnakGtk }}</p>
    <p><strong>Total Siswa Status Yatim di {{ strtoupper($unit) }}:</strong> {{ $totalYatim }}</p>
    <p><strong>Total Siswa Pindahan di {{ strtoupper($unit) }}:</strong> {{ $totalPindahan }}</p>
    <p><strong>Total Siswa Alumni di {{ strtoupper($unit) }}:</strong> {{ $totalAlumni }}</p>
    <p><strong>Total Siswa Perempuan di {{ strtoupper($unit) }}:</strong> {{ $totalPerempuan }}</p>
    <p><strong>Total Siswa Laki-Laki di {{ strtoupper($unit) }}:</strong> {{ $totalLakiLaki }}</p>



    <!-- TABEL DATA -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Status Casis</th>
                <th>Anak GTK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $siswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $siswa->siswa->nm_siswa }}</td>
                    <td>{{ $siswa->statusCasis->nm_status_casis }}</td>
                    <td>{{ $siswa->nama_GTK }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <p>Kubu Raya, {{ now()->format('d F Y') }}</p>
        <p class="position">Ketua Panitia PPDB</p>
        <p class="signature">Nama Ketua Panitia</p>
    </div>

</body>
</html>
