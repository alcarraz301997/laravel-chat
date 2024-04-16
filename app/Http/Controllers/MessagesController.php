<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessagesRequest;
use App\Services\MessagesService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    use HasResponse;

    /** @var MessagesService */
    private $messagesService;

    public function __construct(MessagesService $messagesService)
    {
        $this->messagesService = $messagesService;
    }

    public function list($id, Request $request)
    {
        $withPagination = $this->validatePagination($request->only('perPage', 'page'));
        return $this->messagesService->list($id, $withPagination);
    }

    public function store(MessagesRequest $request)
    {
        return $this->messagesService->store($request->validated());
    }

    public function delete($id)
    {
        return $this->messagesService->delete($id);
    }
}
