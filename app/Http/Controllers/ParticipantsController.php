<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantsRequest;
use App\Services\ParticipantsService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class ParticipantsController extends Controller
{
    use HasResponse;
    /** @var ParticipantsService */
    private $participantsService;

    public function __construct(ParticipantsService $participantsService)
    {
        $this->participantsService = $participantsService;
    }

    // public function list(Request $request)
    // {
    //     $withPagination = $this->validatePagination($request->only('perPage', 'page'));
    //     return $this->participantsService->list($withPagination);
    // }

    public function store(ParticipantsRequest $request)
    {
        return $this->participantsService->store($request->validated());
    }

    public function delete($id)
    {
        return $this->participantsService->delete($id);
    }
}
