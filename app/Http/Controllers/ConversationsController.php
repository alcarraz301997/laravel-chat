<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConversationsRequest;
use App\Services\ConversationsService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class ConversationsController extends Controller
{
    use HasResponse;

    /** @var ConversationsService */
    private $conversationsService;

    public function __construct(ConversationsService $conversationsService)
    {
        $this->conversationsService = $conversationsService;
    }

    public function list(Request $request)
    {
        $withPagination = $this->validatePagination($request->only('perPage', 'page'));
        return $this->conversationsService->list($withPagination);
    }

    public function listConversation($id)
    {
        return $this->conversationsService->listConversation($id);
    }

    public function store(ConversationsRequest $request)
    {
        return $this->conversationsService->store($request->validated());
    }

    public function updateName($id, ConversationsRequest $request)
    {
        return $this->conversationsService->updateName($id, $request->validated());
    }

    public function delete($id)
    {
        return $this->conversationsService->delete($id);
    }

    public function messageView($id)
    {
        return $this->conversationsService->messageView($id);
    }
}
