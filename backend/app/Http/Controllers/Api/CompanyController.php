<?php

namespace App\Http\Controllers\Api;

use App\Actions\Companies\CreateCompanyAction;
use App\Actions\Companies\DeleteCompanyAction;
use App\Actions\Companies\ListCompaniesAction;
use App\Actions\Companies\UpdateCompanyAction;
use App\DataTransferObjects\Companies\CompanyData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Companies\StoreCompanyRequest;
use App\Http\Requests\Companies\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    public function index(Request $request, ListCompaniesAction $action): AnonymousResourceCollection
    {
        $companies = $action->execute(
            filters: $request->only(['search', 'status']),
            perPage: (int) $request->get('per_page', 15),
        );

        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request, CreateCompanyAction $action): JsonResponse
    {
        $result = $action->execute(CompanyData::fromRequest($request->validated()));

        return (new CompanyResource($result['company']))
            ->withAdmin($result['admin'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Company $company): CompanyResource
    {
        $this->authorize('view', $company);

        return new CompanyResource($company->load('municipality.department'));
    }

    public function update(UpdateCompanyRequest $request, Company $company, UpdateCompanyAction $action): CompanyResource
    {
        $updated = $action->execute($company, CompanyData::fromRequest($request->validated()));

        return new CompanyResource($updated);
    }

    public function destroy(Company $company, DeleteCompanyAction $action): JsonResponse
    {
        $action->execute($company);

        return response()->json(null, 204);
    }
}
