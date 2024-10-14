<?php

namespace Modules\Location\Http\Controllers\api\admin\city;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ApiResponseTrait;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Modules\Location\Transformers\CityResource;

class adminCityController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }
    public function index(Request $request)
    {
        $City = $this->LocationRepository->allCity($request);
        return $this->apiResponse(CityResource::collection($City), 200, "ok");
    }
    public function search(Request $request)
    {
        $City = $this->LocationRepository->searchAllCity($request);
        return $this->apiResponse(CityResource::collection($City), 200, "ok");
    }
    public function store(Request $request)
    {
        try {
            $City =  $this->LocationRepository->storeCity($request);
            return $this->apiResponse($City, 201, "City created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function update(Request $request)
    {
        try {
            $City =  $this->LocationRepository->updateCity($request);

            return $this->apiResponse($City, 200, "City updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function destroy(Request $request)
    {
        try {
            $City =  $this->LocationRepository->deleteCity($request);
            return $this->apiResponse($City, 204, "City deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
