<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zxing\QrReader;

class QrCodeController extends Controller
{
    public function index()
    {
      return view('qrcode');
    }

    public function read()
    {
        $QRCodeReader = new \Libern\QRCodeReader\QRCodeReader();
        $qrcode_text = $QRCodeReader->decode("images/id.png");
        echo $qrcode_text;
    }
}
