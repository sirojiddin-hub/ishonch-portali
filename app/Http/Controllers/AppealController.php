<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // HTTP so'rovlar uchun

class AppealController extends Controller
{
    // 1. ARIZA YUBORISH
    public function store(Request $request)
    {
        // Validatsiya
        $request->validate([
            'type' => 'required',
            'region' => 'required',
            'description' => 'required|min:5',
            'files.*' => 'mimes:jpg,jpeg,png,pdf,mp4,mov,avi|max:20480',
        ]);

        if ($request->is_anonymous == '0') {
            $request->validate([
                'full_name' => 'required',
                'phone' => 'required',
            ]);
        }

        try {
            // Fayllarni yuklash
            $filePaths = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('uploads', 'public');
                    $filePaths[] = $path;
                }
            }

            // ID generatsiya
            $trackCode = '#ADL-' . strtoupper(Str::random(4)) . '-' . rand(100, 999);

            // Bazaga yozish
            DB::table('appeals')->insert([
                'track_code' => $trackCode,
                'is_anonymous' => $request->is_anonymous == '1' ? true : false,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'type' => $request->type,
                'region' => $request->region,
                'district' => $request->district,
                'organization' => $request->organization,
                'description' => $request->description,
                'files' => json_encode($filePaths),
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- TELEGRAMGA XABAR YUBORISH ---
            try {
                $token = env('TELEGRAM_BOT_TOKEN');
                $chat_id = env('TELEGRAM_CHAT_ID');

                if ($token && $chat_id) {
                    $message = "🔔 <b>Yangi Ariza!</b>\n\n";
                    $message .= "🆔 <b>ID:</b> {$trackCode}\n";
                    $message .= "📂 <b>Tur:</b> {$request->type}\n";
                    $message .= "📍 <b>Hudud:</b> {$request->region}, {$request->district}\n";
                    $message .= "🏢 <b>Tashkilot:</b> " . ($request->organization ?? 'Ko\'rsatilmagan') . "\n\n";
                    $message .= "📝 <b>Mazmuni:</b> " . Str::limit($request->description, 100) . "\n\n";
                    $message .= "📅 " . now()->format('d.m.Y H:i');

                    // Telegram API ga so'rov yuborish
                    $url = "https://api.telegram.org/bot{$token}/sendMessage";
                    
                    // Oddiy PHP funksiyasi orqali yuboramiz (kutubxonasiz)
                    $data = [
                        'chat_id' => $chat_id,
                        'text' => $message,
                        'parse_mode' => 'HTML'
                    ];
                    
                    $options = [
                        'http' => [
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method'  => 'POST',
                            'content' => http_build_query($data),
                            'timeout' => 5 // 5 sekund kutadi
                        ]
                    ];
                    $context  = stream_context_create($options);
                    file_get_contents($url, false, $context);
                }
            } catch (\Exception $e) {
                // Agar Telegram ishlamasa, sayt to'xtab qolmasligi kerak
                \Log::error('Telegram Error: ' . $e->getMessage());
            }
            // ---------------------------------

            return redirect()->back()->with('success', $trackCode);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    // 2. STATUSNI TEKSHIRISH
    public function checkStatus(Request $request)
    {
        $request->validate(['track_code' => 'required']);
        $code = trim($request->track_code);
        $appeal = DB::table('appeals')->where('track_code', $code)->first();

        if ($appeal) {
            $statusText = match ($appeal->status) {
                'new' => 'Yangi (Ko\'rib chiqilmoqda)',
                'processing' => 'Jarayonda (O\'rganilmoqda)',
                'closed' => 'Yopilgan (Hal etildi)',
                'rejected' => 'Rad etilgan',
                default => 'Noma\'lum'
            };
            $color = match ($appeal->status) {
                'new' => 'blue',
                'processing' => 'yellow',
                'closed' => 'green',
                'rejected' => 'red',
                default => 'gray'
            };

            return back()->with('status_result', [
                'found' => true,
                'code' => $appeal->track_code,
                'status' => $statusText,
                'color' => $color,
                'note' => $appeal->admin_note ?? 'Izoh yo\'q',
                'time' => \Carbon\Carbon::parse($appeal->created_at)->format('d.m.Y H:i')
            ]);
        } else {
            return back()->with('status_result', ['found' => false, 'message' => 'Bunday ID raqamli ariza topilmadi.']);
        }
    }
}