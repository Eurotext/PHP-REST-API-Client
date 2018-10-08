<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Request;

class ProjectTranslateRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $projectId;

    /**
     * @var int
     */
    private $translate;

    public function __construct(int $projectId, int $translate = 1)
    {
        $this->projectId = $projectId;
        $this->translate = $translate;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getTranslate(): int
    {
        return $this->translate;
    }

    public function getHeaders(): array
    {
        return [
            'X-Translate' => $this->getTranslate(),
        ];
    }
}