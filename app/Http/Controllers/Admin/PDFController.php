<?php
  
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use PDF;
  
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {
        $ids = (json_decode(base64_decode(urldecode($request->uid))));
        $users = User::get()->whereIn('id', $ids);
  
        $data = [
            'title' => 'List User',
            'date' => date('m/d/Y'),
            'users' => $users
        ]; 
            
        $pdf = PDF::loadView('admin.users.exportPdf', $data);
     
        return $pdf->download('users.pdf');
    }
}