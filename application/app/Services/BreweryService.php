<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
// use App\Http\Resources\BrewCollection;
// use Illuminate\Pagination\LengthAwarePaginator;

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

  private function getAccessToken()
  {
    $client = new Client();
    try {
      $response = $client->post('https://login.microsoftonline.com/' . config('sharepoint.AZURE_TENANT_ID') . '/oauth2/v2.0/token', [
        'form_params' => [
          'client_id' => config('sharepoint.AZURE_CLIENT_ID'),
          'client_secret' => config('sharepoint.AZURE_CLIENT_SECRET'),
          'scope' => 'https://graph.microsoft.com/.default',
          'grant_type' => 'client_credentials',
        ],
      ]);

      $body = json_decode((string)$response->getBody(), true);
      return $body['access_token'];
    } catch (\Exception $e) {
      Log::error('Error obtaining access token: ' . $e->getMessage());
      throw new \Exception('Error obtaining access token: ' . $e->getMessage());
    }
  }

  public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $hasFile = false)
  {
    $client = new Client([
      'base_uri' => $this->baseUri,
    ]);

    $bodyType = 'form_params';

    if ($hasFile) {
      $bodyType = 'multipart';
      $multipart = [];

      foreach ($formParams as $name => $contents) {
        $multipart[] = [
          'name' => $name,
          'contents' => $contents
        ];
      }
    }

    $response = $client->request($method, $requestUrl, [
      'query' => $queryParams,
      $bodyType => $hasFile ? $multipart : $formParams,
      'headers' => $headers,
    ]);

    return $response->getBody()->getContents();
  }

  public function getData()
  {
    $client = new Client();
    $page = request()->get('page') ?? 1;
    $perPage = request()->get('per_page') ?? config('brewery-service.BREWERY_API_PAGINATION');
    $params = "?page={$page}&per_page={$perPage}";

    $response = $client->request('GET', $this->requestUrl . $params);

    try {
      return $response->getBody(); //->getContents();

      // $data = $response->getBody();
      // $res = collect(json_decode($data));
      // $paginator = new LengthAwarePaginator($res, $res->count(), $perPage, $page);

      // return BrewCollection::make($paginator);
    } catch (\Exception $e) {
      Log::error('Error retrieving  brewery data: ' . $e->getMessage());
      throw new \Exception('Error retrieving brewery data: ' . $e->getMessage());
    }
  }
}
