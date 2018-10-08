<?php
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Api;

use Eurotext\RestApiClient\Exception\DeserializationFailedException;
use Eurotext\RestApiClient\Request\ProjectPostRequest;
use Eurotext\RestApiClient\Request\ProjectTransitionRequest;
use Eurotext\RestApiClient\Response\ProjectGetResponse;
use Eurotext\RestApiClient\Response\ProjectPostResponse;
use Eurotext\RestApiClient\Response\ProjectTransitionResponse;

interface ProjectV1ApiInterface
{
    /**
     * @param ProjectPostRequest $request
     *
     * @return ProjectPostResponse
     * @throws DeserializationFailedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(ProjectPostRequest $request): ProjectPostResponse;

    /**
     * @param ProjectTransitionRequest $request
     *
     * @return ProjectTransitionResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transition(ProjectTransitionRequest $request): ProjectTransitionResponse;

    /**
     * @param int $projectId
     *
     * @return ProjectGetResponse
     */
    public function get(int $projectId): ProjectGetResponse;

    /**
     * @deprecated ONLY AVAILABLE IN SANDBOX, to simulate translated project
     *
     * @param int $projectId
     *
     * @return \stdClass
     */
    public function translate(int $projectId): \stdClass;
}