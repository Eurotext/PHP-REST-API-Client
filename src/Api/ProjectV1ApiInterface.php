<?php
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Api;

use Eurotext\RestApiClient\Exception\DeserializationFailedException;
use Eurotext\RestApiClient\Exception\ApiClientException;
use Eurotext\RestApiClient\Request\ProjectDataRequest;
use Eurotext\RestApiClient\Response\ProjectGetResponse;
use Eurotext\RestApiClient\Response\ProjectPostResponse;

interface ProjectV1ApiInterface
{
    /**
     * @param ProjectDataRequest $request
     *
     * @return ProjectPostResponse
     * @throws DeserializationFailedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(ProjectDataRequest $request): ProjectPostResponse;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(int $projectId): ProjectGetResponse;
}