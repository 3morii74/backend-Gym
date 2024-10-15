<?php

namespace Modules\Location\Http\Controllers\api\admin\country;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use App\Http\Traits\ApiResponseTrait;
use Modules\Location\Http\Requests\DeleteCountryRequest;
use Modules\Location\Http\Requests\StoreCountryRequest;
use Modules\Location\Http\Requests\UpdateCountryRequest;
use Modules\Location\Transformers\CountryResource;

class AdminCountryController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }
    public function index(Request $request)
    {
        $Nationalities = $this->LocationRepository->allCountry($request);
        return $this->apiResponse(CountryResource::collection($Nationalities), 200, "ok");
    }
    public function search(Request $request)
    {
        $Nationalities = $this->LocationRepository->searchAllCountry($request);
        return $this->apiResponse(CountryResource::collection($Nationalities), 200, "ok");
    }
    public function store(StoreCountryRequest $request)
    {
        try {
            $Nationality =  $this->LocationRepository->storeCountry($request);
            return $this->apiResponse($Nationality, 201, "Country created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function update(Request $request)
    {

        try {
            $Nationality =  $this->LocationRepository->updateCountry($request);

            return $this->apiResponse($Nationality, 200, "Country updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function destroy(DeleteCountryRequest $request)
    {
        try {
            $Nationality =  $this->LocationRepository->deleteCountry($request);
            return $this->apiResponse($Nationality, 204, "Country deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
