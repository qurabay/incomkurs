<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0] != 'email'){
          $user = User::whereEmail($row[0])->exists();
          if (!$user){
              $user = new User();
              $user->email = $row[0];
              $user->password = $row[1];
              $user->phone = $row[2];
              $user->name = $row[3];
              $user->token = Str::random(30);
              $user->save();
          }
        }
    }
}
