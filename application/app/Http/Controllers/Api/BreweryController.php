<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Services\BreweryService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Resources\BrewCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class BreweryController extends BaseController
{

  public function __construct(protected BreweryService $breweryService) {}
  /**
   * Display a paginated table of the resource.
   *
   * @return Illuminate\View\View;
   */
  public function web_index(): View
  {
    try {
      // $response = json_decode($this->breweryService->getData(), false);
      $page = request()->get('page') ?? 1;
      $perPage = request()->get('per_page') ?? config('brewery-service.BREWERY_API_PAGINATION');
      $response = $this->breweryService->getData();

      $data = $response->getContents();
      $res = collect(json_decode($data));
      $paginator = new LengthAwarePaginator($res, $res->count(), $perPage, $page);

      return view('brewery', ['data' => BrewCollection::make($paginator)]);
    } catch (\Exception $e) {
      // Handle any errors that occur during the API request
      // return view('brewery_error', ['error' => $e->getMessage()]);
      return view('brewery_error', ['error' => $e->getMessage()]);
    }
  }

  // API call for SPA
  public function index(): JsonResponse
  {
    try {
      $data = json_decode($this->breweryService->getData(), false);
      return $this->sendResponse($data, 'Breweries retrieved successfully.');
    } catch (\Exception $e) {
      // Handle any errors that occur during the API request
      // return view('brewery_error', ['error' => $e->getMessage()]);
      return $this->sendError($e->getMessage(), $e->getCode());
    }
  }
}
