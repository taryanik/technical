<?php

namespace App\Http\Controllers;

use App\Http\Responses\CompanyResource;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $companies = $this->companyService->getCompaniesByUser($user);

        return response()->json(CompanyResource::collection($companies));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|min:2|max:150',
            'description' => 'required|string|min:2|max:150',
            'phone' => 'required|numeric',
        ]);

        // We could consider using DTO here
        $data = $request->only(['title', 'phone', 'description']);
        $company = $this->companyService->createCompanyForUser($request->user(), $data);

        return response()->json(new CompanyResource($company), Response::HTTP_CREATED);
    }
}
