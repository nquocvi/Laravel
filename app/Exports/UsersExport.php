<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings
{
    use Exportable;

    private $id;

    public function __construct(array  $id = null)
    {
        $this->id = $id;
    }

    public function query()
    {
        $select =  User::select("id", "name", "email","phone","address");

        if($this->id){
            $select = $select->whereIn('id', $this->id);
        }
        return $select;
    }

    public function headings(): array
    {
        return ["ID", "Name", "Email", "Phone", "Address"];
    }
}
