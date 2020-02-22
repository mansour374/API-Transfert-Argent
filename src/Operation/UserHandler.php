<?php
namespace App\Operation;

use App\Entity\User;
use symfony\component\HttpFoundation\Response;
use symfony\component\HttpFoundation\Request;
class UserHandler{

    public function handle(User $data){

        return true;

    }
}