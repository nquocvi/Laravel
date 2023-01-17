<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;


class ValidateCsvFile implements ToCollection,  WithStartRow
{
    /**
    * @param Collection $collection
    */

    public $errors = [];
    public $isValidFile = false; 

    public function __construct()
    {
        //
    }

    public function collection(Collection $rows)
    {
        
        $errors = [];
       
        if (count($rows) > 1) {
            
            $rows = $rows->slice(1);
            
            
            foreach ($rows as $key => $row) {
               
                $validator = Validator::make($row->toArray(), [
                    '0' => [
                        'required',
                    ],
                    '1' => [
                        'required',                        
                        'email',
                        'unique:users,email'
                    ],
                    '2' => [
                        'required',
                        'min:3',
                    ],
                ]);
                
                if ($validator->fails()) {
                    $errors[$key] = $validator;
                }
            }
            $this->errors = $errors;
            $this->isValidFile = true;
        }
    }

    public function startRow(): int
    {
        return 1;
    }
}
