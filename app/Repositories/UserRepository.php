<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('roles')->orderBy('created_at', 'desc')->get();
    }

    public function find($id)
    {
        return $this->model->with('roles')->find($id);
    }

    public function create(array $data)
    {
        $user = $this->model->create($data);
        
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }
        
        return $user;
    }

    public function update($id, array $data)
    {
        $user = $this->model->find($id);
        
        if (!$user) {
            return null;
        }
        
        // Handle password update
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        $user->update($data);
        
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }
        
        return $user;
    }

    public function delete($id)
    {
        $user = $this->model->find($id);
        
        if (!$user) {
            return false;
        }
        
        return $user->delete();
    }

    public function getActiveUsers()
    {
        return $this->model->active()->with('roles')->orderBy('created_at', 'desc')->get();
    }

    public function getInactiveUsers()
    {
        return $this->model->inactive()->with('roles')->orderBy('created_at', 'desc')->get();
    }

    public function search($query)
    {
        return $this->model->with('roles')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('username', 'like', "%{$query}%")
                  ->orWhere('phone_number', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function count()
    {
        return $this->model->count();
    }

    public function countActive()
    {
        return $this->model->active()->count();
    }

    public function countInactive()
    {
        return $this->model->inactive()->count();
    }

    public function getUsersWithRoles()
    {
        return $this->model->with('roles')->get();
    }

    public function findWithRoles($id)
    {
        return $this->model->with('roles')->find($id);
    }

    public function findByUsername($username)
    {
        return $this->model->where('username', $username)->first();
    }
}