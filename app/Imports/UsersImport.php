<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;

class UsersImport implements 
    ToModel, 
    WithHeadingRow, 
    SkipsOnError, 
    WithValidation,
    SkipsOnFailure,
    WithEvents
{
    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;
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

    public function rules(): array
    {
        return [
            '*.email' => ['email', 'required','unique:users,email'],
            '*.password' => ['required', 'min:3']
        ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         BeforeImport::class => function(BeforeImport $event) {
    //             $totalRows = $event->getReader()->getTotalRows();
    //             if (!empty($totalRows)) {
    //               // dd ($totalRows['Worksheet']);
    //                //dd     ($totalRows);
    //             }
    //         },
    //     ];
    // }

    // public function beforeImport(BeforeImport $event)
    // {
    //     return ( $event->getReader()->getTotalRows());
    // }

}
