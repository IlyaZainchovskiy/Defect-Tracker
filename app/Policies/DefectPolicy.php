<?php

namespace App\Policies;

use App\Models\Defect;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefectPolicy
{

    use HandlesAuthorization;
      public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Defect $defect)
    {
        return $user->id === $defect->user_id;
    }

    public function create(User $user)
    {
        return true; 
    }

    public function update(User $user, Defect $defect)
    {
        return $user->id === $defect->user_id;
    }

    public function delete(User $user, Defect $defect)
    {
        return $user->id === $defect->user_id;
    }
}
