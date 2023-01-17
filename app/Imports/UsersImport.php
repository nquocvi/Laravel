<?php

namespace App\Imports;

use App\Models\User;
use Exception;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UsersImport implements 
    ToModel, 
    WithHeadingRow, 
    ShouldQueue,
    WithChunkReading
{
    use Importable;
        /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $errors;
    private $row = 1;

    public function __construct($errors = [])
    {
        $this->errors = $errors;
    }

     public function model(array $row)
    {
        try {
            User::create([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'phone'     => $row['phone'],
            'address'    => $row['address'],
            'password'    => $row['password'],   
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e);
        }
    }



    public function startRow(): int
    {
        return 2;
    }


    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }





}
