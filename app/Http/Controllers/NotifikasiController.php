<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;  // Untuk logging

class NotifikasiController extends Controller
{
    protected $messaging;

    public function __construct()
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');

        // Debugging path untuk memastikan file ada
        if (!file_exists($credentialsPath)) {
            Log::error('Firebase credentials file not found: ' . $credentialsPath);
            throw new \Exception('Firebase credentials file not found.');
        }

        try {
            $firebase = (new Factory)
                ->withServiceAccount($credentialsPath);
            $this->messaging = $firebase->createMessaging();
        } catch (\Exception $e) {
            Log::error('Error initializing Firebase: ' . $e->getMessage());
            throw new \Exception('Error initializing Firebase: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'pesan' => 'required|string',
        ]);

        // Simpan ke database dengan tanggal otomatis
        $notifikasi = Notifikasi::create([
            'pesan' => $request->pesan,
            'tanggal_kirim' => Carbon::now(),
        ]);

        $this->sendNotificationToTopic('Jadwal Posyandu Lansia', $notifikasi['pesan']);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil disimpan!',
            'data' => $notifikasi
        ], 201);
    }

    /**
     * Method untuk mengirim notifikasi ke topic Firebase
     *
     * @param string $title
     * @param string $body
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */

    public function sendNotificationToTopic($title, $body, $data = [], $attempt = 1)
    {
        $maxAttempts = 3; // Maksimal percobaan
        try {
            $notification = [
                'title' => $title,
                'body' => $body,
            ];

            $message = CloudMessage::withTarget('topic', 'PosyanduLansia')
                ->withNotification($notification)
                ->withData($data);

            return $this->messaging->send($message);

        } catch (\Throwable $e) {
            if ($attempt < $maxAttempts) {
                sleep(2); // Tunggu 2 detik sebelum mencoba ulang
                return $this->sendNotificationToTopic($title, $body, $data, $attempt + 1);
            }
            return false;
        }
    }


    public function sendToTopic(Request $request)
    {
        Log::info('Incoming request to send notification to topic.', [
            'request_data' => $request->all(),
        ]);

        // Validasi data request
        $request->validate([
            'topic' => 'required',
            'title' => 'required',
            'body' => 'required',
        ]);

        $topic = $request->topic;
        $title = $request->title;
        $body = $request->body;

        Log::info('Validation successful. Preparing to send notification.', [
            'topic' => $topic,
            'title' => $title,
            'body' => $body,
        ]);

        try {
            $response = $this->sendNotificationToTopic($title, $body);

            Log::info('Notification sent successfully.', [
                'response' => $response,
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAllFirebaseUsers()
    {
        try {

            $credentialsPath = storage_path('app/firebase_credentials.json');

            // Inisialisasi Firebase Auth
            $firebaseAuth = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->createAuth();

            // Ambil daftar semua pengguna di Firebase Auth
            $users = $firebaseAuth->listUsers();

            $deletedCount = 0;
            foreach ($users as $user) {
                $firebaseAuth->deleteUser($user->uid);
                $deletedCount++;
            }

            return response()->json([
                'message' => "Berhasil menghapus $deletedCount pengguna dari Firebase Authentication."
            ], 200);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pengguna dari Firebase: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal menghapus semua pengguna: ' . $e->getMessage()
            ], 500);
        }
    }
}
