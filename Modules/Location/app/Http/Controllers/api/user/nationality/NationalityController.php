<?php

namespace Modules\Location\Http\Controllers\api\user\nationality;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use Modules\Location\Transformers\NationalityResource;
use Exception;

class NationalityController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }

    public function index(Request $request)
    {
        try {

            $Nationalities = $this->LocationRepository->allNationality($request);
            return $this->apiResponse(NationalityResource::collection($Nationalities), 200, "ok");
        } catch (Exception $e) {
            return $this->apiResponse(null, 500, "An error occurred while fetching nationalities: " . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $Nationalities = $this->LocationRepository->searchAllNationality($request);
            return $this->apiResponse(NationalityResource::collection($Nationalities), 200, "ok");
        } catch (Exception $e) {
            return $this->apiResponse(null, 500, "An error occurred while searching nationalities: " . $e->getMessage());
        }
    }

    public function getCountries(Request $request)
    {
        try {
            $countries = $this->LocationRepository->getCountryFromNationality($request);
            return $this->apiResponse(NationalityResource::collection($countries), 200, "ok");
        } catch (Exception $e) {
            return $this->apiResponse(null, 500, "An error occurred while fetching countries: " . $e->getMessage());
        }
    }
}
