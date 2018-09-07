<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Api\Project;

use Eurotext\RestApiClient\Api\AbstractV1Api;
use Eurotext\RestApiClient\Request\Project\ItemDataRequest;
use Eurotext\RestApiClient\Response\Project\ItemPostResponse;

class ItemV1Api extends AbstractV1Api implements ItemV1ApiInterface
{
    const API_URL = '/api/v1/project/{project_id}/item.json';

    const REQUEST_CONTENT_TYPE = 'application/json';

    /**
     * @param ItemDataRequest $request
     *
     * @return ItemPostResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(ItemDataRequest $request): ItemPostResponse
    {
        $projectId = $request->getProjectId();

        $httpPath    = "/api/v1/project/$projectId/item.json";
        $httpBody    = $this->serializer->serialize($request->getData(), 'json');
        $httpHeaders = $request->getHeaders();

        $response = $this->sendRequestAndHandleResponse(
            $this->createHttpPostRequest($httpPath, $httpHeaders, $httpBody),
            $this->createHttpClientOptions(),
            ItemPostResponse::class
        );

        /** @var ItemPostResponse $response */
        return $response;
    }

}
