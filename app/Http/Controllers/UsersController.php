<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersImageRequest;
use App\Http\Requests\UsersRequest;
use App\Services\UsersService;
use App\Traits\HasResponse;

class UsersController extends Controller
{
    use HasResponse;

    /** @var UsersService */
    private $usersService;

    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    public function store(UsersRequest $request)
    {
        return $this->usersService->store($request->validated());
    }

    public function update(UsersRequest $request)
    {
        return $this->usersService->update($request->validated());
    }

    public function delete()
    {
        return $this->usersService->delete();
    }

    public function updateImage(UsersImageRequest $request)
    {
        return $this->usersService->updateImage($request->validated());
    }
}
