<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);
        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'phone'     => $row['phone'],
            'address'    => $row['address'],
            'password'    => $row['password'],   
            // 'password' => Hash::make($row['password']),

        ]);
    }
}
