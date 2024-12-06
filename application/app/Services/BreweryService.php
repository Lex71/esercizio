<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BreweryService
{
  protected $baseUri;
  protected $path;
  protected $requestUrl;

  public function __construct()
  {
    $this->baseUri = config('brewery-service.BREWERY_API_BASE_URL');
    $this->path = config('brewery-service.BREWERY_API_PATH');
    $this->requestUrl = join('/', [$this->baseUri, $this->path]);
  }

  public function getData()
  {
    $client = new Client();
    $page = request()->get('page') ?? 1;
    $perPage = request()->get('per_page') ?? config('brewery-service.BREWERY_API_PAGINATION');
    $params = "?page={$page}&per_page={$perPage}";

    $response = $client->request('GET', $this->requestUrl . $params);

    try {
      return $response->getBody();
    } catch (\Exception $e) {
      Log::error('Error retrieving  brewery data: ' . $e->getMessage());
      throw new \Exception('Error retrieving brewery data: ' . $e->getMessage());
    }
  }
}
