<?php
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Api\Project;

use Eurotext\RestApiClient\Request\Project\ItemDataRequest;
use Eurotext\RestApiClient\Response\Project\ItemPostResponse;

interface ItemV1ApiInterface
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(ItemDataRequest $request): ItemPostResponse;
}