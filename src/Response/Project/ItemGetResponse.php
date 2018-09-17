<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Response\Project;

use Eurotext\RestApiClient\Request\Data\Project\ItemData;
use Eurotext\RestApiClient\Response\AbstractResponse;

class ItemGetResponse extends AbstractResponse
{
    /** @var ItemData */
    private $itemData;

    public function getItemData(): ItemData
    {
        return $this->itemData;
    }

    public function setData(array $data)
    {
        $meta = $data['__meta'];
        unset($data['__meta']);

        $this->itemData = new ItemData($data, $meta);
    }

}
