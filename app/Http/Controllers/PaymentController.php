<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function handleLinkQuCallback(Request $request) {
        $data = $request->all();

        // You might want to add validation and security checks here

        if ($data['status'] == 'success') {
            $this->sendWhatsAppMessage($data);
        }

        return response()->json(['status' => 'success']);
    }

    private function sendWhatsAppMessage($data)
    {
        $whatsappApiUrl = 'https://api.easywa.id/v1/send-group';
        $email = env('EASYWA_EMAIL');
        $secretKey = env('EASYWA_SECRET_KEY');
        $groupNameOrId = 'test api';
        $message = "Notifikasi Pembayaran
Nama Pembeli : {$data['accountname']}
Email Pembeli : {$data['customer_email']}
No Hp : {$data['customer_phone']}
Nama Pembayaran : QRIS by ShopeePay
Kode Referensi Tripay : T1957015769067TEBUK
Link Tagihan : https://tripay.co.id/checkout/T1957015769067TEBUK
Kode Transaksi Website : TRX258490864364128
Total Tagihan : Rp 509.498
Kode Pembayaran : -
Status Pembayaran : PAID

C6hYONhFQBJsqPz";

        $response = Http::withHeaders([
            'email' => $email,
            'secret-key' => $secretKey,
        ])->post($whatsappApiUrl, [
            'group_name' => $groupNameOrId,
            'message' => json_encode($data),
        ]);

        if ($response->successful()) {
            return response()->json([
                'message' => "WhatsApp message sent successfully to group $groupNameOrId"
            ]);
        } else {
            return response()->json([
                'message' => "Failed to send WhatsApp message to group $groupNameOrId"
            ]);
        }
    }
}
