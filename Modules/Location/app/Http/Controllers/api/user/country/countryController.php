<?php

namespace Modules\Location\Http\Controllers\api\user\country;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use App\Http\Traits\ApiResponseTrait;
use Modules\Location\Transformers\CountryResource;

class countryController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }
    public function index(Request $request)
    {
        $Countries = $this->LocationRepository->allActiveCountry($request);
        return $this->apiResponse(CountryResource::collection($Countries), 200, "ok");
    }
    public function search(Request $request)
    {
        $Countries = $this->LocationRepository->searchAllActiveCountry($request);
        return $this->apiResponse(CountryResource::collection($Countries), 200, "ok");
    }
    public function getStates(Request $request)
    {

        $states = $this->LocationRepository->getStateFromCountry($request);
        return $this->apiResponse(CountryResource::collection($states), 200, "ok");
    }
}
