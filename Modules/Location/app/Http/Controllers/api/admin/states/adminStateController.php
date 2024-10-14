<?php

namespace Modules\Location\Http\Controllers\api\admin\states;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use Modules\Location\Models\State;
use Modules\Location\Transformers\StateResource;
use Illuminate\Validation\ValidationException;

class adminStateController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }
    public function index(Request $request)
    {
        $State = $this->LocationRepository->allState($request);
        return $this->apiResponse(StateResource::collection($State), 200, "ok");
    }
    public function search(Request $request)
    {
        $State = $this->LocationRepository->searchAllState($request);
        return $this->apiResponse(StateResource::collection($State), 200, "ok");
    }
    public function store(Request $request)
    {
        try {
            $State =  $this->LocationRepository->storeState($request);
            return $this->apiResponse($State, 201, "State created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function update(Request $request)
    {
        try {
            $State =  $this->LocationRepository->updateState($request);

            return $this->apiResponse($State, 200, "State updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
    public function destroy(Request $request)
    {
        try {
            $State =  $this->LocationRepository->deleteState($request);
            return $this->apiResponse($State, 204, "State deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
