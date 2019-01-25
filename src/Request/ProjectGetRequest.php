<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Request\Project;

use Eurotext\RestApiClient\Request\RequestInterface;

class ProjectGetRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $projectId;

    public function __construct(
        int $projectId
    ) {
        $this->projectId  = $projectId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getHeaders(): array
    {
        return [];
    }

}
