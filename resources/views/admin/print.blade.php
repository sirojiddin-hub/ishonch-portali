<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Ariza #{{ $appeal->track_code }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background: #eee;
            padding: 40px;
            margin: 0;
        }
        .page {
            background: #fff;
            width: 210mm; /* A4 */
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .page { box-shadow: none; margin: 0; width: 100%; height: auto; }
            .no-print { display: none; }
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12pt;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .meta-table th, .meta-table td {
            border: 1px solid #000;
            padding: 8px 12px;
            font-size: 12pt;
        }
        .meta-table th {
            background: #f0f0f0;
            text-align: left;
            width: 30%;
        }
        .content-box {
            border: 1px solid #000;
            padding: 15px;
            min-height: 300px;
            font-size: 12pt;
            line-height: 1.5;
            text-align: justify;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 12pt;
        }
        .stamp {
            border: 1px dashed #ccc;
            padding: 10px;
            text-align: center;
            width: 200px;
            color: #ccc;
            font-size: 10pt;
        }
        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            font-family: sans-serif;
            font-weight: bold;
        }
        .btn-print:hover { background: #0056b3; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">🖨️ HUJJATNI CHOP ETISH</button>

    <div class="page">
        <!-- HEADER -->
        <div class="header">
            <h1>O'zbekiston Respublikasi</h1>
            <p>Elektron Murojaatlar Portali</p>
            <p style="font-size: 10pt; margin-top: 10px;">ID Raqam: <b>{{ $appeal->track_code }}</b> | Sana: {{ $appeal->created_at->format('d.m.Y H:i') }}</p>
        </div>

        <h2 style="text-align: center; font-size: 16pt; margin-bottom: 20px;">ARIZA VARAQASI</h2>

        <!-- INFO TABLE -->
        <table class="meta-table">
            <tr>
                <th>Murojaat Turi</th>
                <td>{{ $appeal->type }}</td>
            </tr>
            <tr>
                <th>Hudud</th>
                <td>{{ $appeal->region }}, {{ $appeal->district }}</td>
            </tr>
            <tr>
                <th>Ariza Beruvchi</th>
                <td>
                    @if($appeal->is_anonymous)
                        <i>(Shaxsi sir tutilgan - Anonim)</i>
                    @else
                        {{ $appeal->full_name }} <br>
                        Tel: {{ $appeal->phone }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Kimga (Tashkilot)</th>
                <td>{{ $appeal->organization ?? 'Ko\'rsatilmagan' }}</td>
            </tr>
            <tr>
                <th>Hozirgi Holati</th>
                <td>
                    @if($appeal->status == 'new') Yangi
                    @elseif($appeal->status == 'processing') Jarayonda
                    @elseif($appeal->status == 'closed') Yopilgan (Hal etilgan)
                    @else Rad etilgan @endif
                </td>
            </tr>
        </table>

        <!-- CONTENT -->
        <div style="font-weight: bold; margin-bottom: 5px;">Murojaat Mazmuni:</div>
        <div class="content-box">
            {{ $appeal->description }}
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div>
                <b>Mas'ul Xodim:</b><br>
                @if($assignedUser)
                    <u>{{ $assignedUser->name }}</u><br>
                    <span style="font-size: 10pt;">({{ $assignedUser->department }})</span>
                @else
                    _______________________
                @endif
            </div>
            
            <div style="text-align: center;">
                <b>Imzo:</b><br><br><br>
                _______________________
            </div>
        </div>

        <div style="margin-top: 40px; font-size: 9pt; color: #555; border-top: 1px solid #ccc; padding-top: 5px;">
            <i>Ushbu hujjat elektron tizim orqali shakllantirildi. Asl nusxa bilan teng kuchga ega.</i><br>
            <i>Generatsiya vaqti: {{ now()->format('d.m.Y H:i:s') }}</i>
        </div>
    </div>

</body>
</html>