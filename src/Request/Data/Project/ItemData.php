<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Request\Data\Project;

class ItemData implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data;

    /** @var string */
    private $__meta;

    public function __construct(array $data, array $__meta)
    {
        $this->data   = $data;
        $this->__meta = $__meta;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMeta(): array
    {
        return $this->__meta;
    }

    public function jsonSerialize()
    {
        $data = $this->getData();

        $data['__meta'] = $this->getMeta();

        return $data;
    }
}
