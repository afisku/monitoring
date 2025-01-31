<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Croscek SMP</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Rekapitulasi Croscek SMP</h2>

    <table>
        <tr>
            <th>Total Siswa</th>
            <td>{{ $totalSiswa }}</td>
        </tr>
        <tr>
            <th>Total Siswa Sudah Lunas</th>
            <td>{{ $totalSiswaLunas }}</td>
        </tr>
        <tr>
            <th>Total Anak GTK</th>
            <td>{{ $totalAnakGtk }}</td>
        </tr>
    </table>

</body>
</html>
