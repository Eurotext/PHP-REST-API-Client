<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Api;

use Eurotext\RestApiClient\Request\ProjectDataRequest;
use Eurotext\RestApiClient\Response\ProjectGetResponse;
use Eurotext\RestApiClient\Response\ProjectPostResponse;

class ProjectV1Api extends AbstractV1Api implements ProjectV1ApiInterface
{
    public function post(ProjectDataRequest $request): ProjectPostResponse
    {
        $httpPath    = '/api/v1/project.json';
        $httpHeaders = $request->getHeaders();
        $httpBody    = $this->serializer->serialize($request->getData(), 'json');

        $response = $this->sendRequestAndHandleResponse(
            $this->createHttpPostRequest($httpPath, $httpHeaders, $httpBody),
            $this->createHttpClientOptions(),
            ProjectPostResponse::class
        );

        /** @var ProjectPostResponse $response */
        return $response;
    }

    /**
     * @param int $projectId
     *
     * @return ProjectGetResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(int $projectId): ProjectGetResponse
    {
        $httpPath = "/api/v1/project/$projectId.json";

        $response = $this->sendRequestAndHandleResponse(
            $this->createHttpGetRequest($httpPath),
            $this->createHttpClientOptions(),
            ProjectGetResponse::class
        );

        /** @var ProjectGetResponse $response */
        return $response;
    }

}
