<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Response;

use Eurotext\RestApiClient\Request\Data\Project\ItemData;

class ProjectGetResponse extends AbstractResponse
{
    /** @var string */
    private $description;

    /** @var ItemData[] */
    private $items;

    /** @var mixed[] */
    private $files;

    public function __construct(string $description, array $items, array $files)
    {
        $this->description = $description;
        $this->items       = $items;
        $this->files       = $files;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return ItemData[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return mixed[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
