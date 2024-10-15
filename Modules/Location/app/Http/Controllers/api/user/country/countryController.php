<?php

namespace Modules\Location\Http\Controllers\api\user\country;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use App\Http\Traits\ApiResponseTrait;
use Modules\Location\Http\Requests\SearchCountryRequest;
use Modules\Location\Transformers\CountryResource;
use Exception;

class countryController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }

    /**
     * Get all active countries.
     */
    public function index(Request $request)
    {
        try {
            $Countries = $this->LocationRepository->allActiveCountry($request);
            return $this->apiResponse(CountryResource::collection($Countries), 200, "ok");
        } catch (Exception $e) {
            return $this->apiResponse(null, 500, "An error occurred while fetching countries: " . $e->getMessage());
        }
    }

    /**
     * Search for active countries based on request.
     */
    public function search(SearchCountryRequest $request)
    {
        try {
            $Countries = $this->LocationRepository->searchAllActiveCountry($request);
            return $this->apiResponse(CountryResource::collection($Countries), 200, "ok");
        } catch (Exception $e) {
            return $this->apiResponse(null, 500, "An error occurred while searching for countries: " . $e->getMessage());
        }
    }

    /**
     * Get states of a country.
     */
    public function getStates(Request $request)
    {
        try {
            $states = $this->LocationRepository->getStateFromCountry($request);
            return $this->apiResponse(CountryResource::collection($states), 200, "ok");
        } catch (Exception $e) {
            return $this->apiResponse(null, 500, "An error occurred while fetching states: " . $e->getMessage());
        }
    }
}
