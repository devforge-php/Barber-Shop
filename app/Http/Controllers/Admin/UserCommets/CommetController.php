<?php

namespace App\Http\Controllers\Admin\UserCommets;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommetResource;
use App\Service\AdminReviewService;
use Illuminate\Http\Request;

class CommetController extends Controller
{
    public function __construct(protected AdminReviewService $service) {}

    public function index()
    {
        $reviews = $this->service->get();
        return CommetResource::collection($reviews);
    }

    public function show(string $id)
    {
        $review = $this->service->find($id);
        return new CommetResource($review);
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
