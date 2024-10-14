<?php

namespace Modules\Location\Http\Controllers\api\admin\Nationality;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use Illuminate\Validation\ValidationException;
use App\Exceptions\CustomException;
use Modules\Location\Transformers\NationalityResource;
use Modules\Location\Http\Requests\StoreNationalityRequest;
use  Modules\Location\Http\Requests\UpdateNationalityRequest;
class AdminNationalityController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }
    public function index(Request $request)
    {
        $Nationalities = $this->LocationRepository->allNationality($request);
        return $this->apiResponse(NationalityResource::collection($Nationalities), 200, "ok");
    }
    public function search(Request $request)
    {
        $Nationalities = $this->LocationRepository->searchAllNationality($request);
        return $this->apiResponse(NationalityResource::collection($Nationalities), 200, "ok");
    }
    public function store(StoreNationalityRequest $request)
    {
        try {
            $Nationality =  $this->LocationRepository->storeNationality($request);
            return $this->apiResponse($Nationality, 201, "Nationality created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function update(UpdateNationalityRequest $request)
    {
        try {
            $Nationality =  $this->LocationRepository->updateNationality($request);

            return $this->apiResponse($Nationality, 200, "Nationality updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function destroy(Request $request)
    {
        try {
            $Nationality =  $this->LocationRepository->deleteNationality($request);
            return $this->apiResponse($Nationality, 204, "Nationality deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
