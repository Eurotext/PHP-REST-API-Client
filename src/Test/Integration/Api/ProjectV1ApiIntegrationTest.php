<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Test\Integration\Api;

use Eurotext\RestApiClient\Api\Project\ItemV1Api;
use Eurotext\RestApiClient\Api\ProjectV1Api;
use Eurotext\RestApiClient\Configuration;
use Eurotext\RestApiClient\Enum\ProjectStatusEnum;
use Eurotext\RestApiClient\Enum\ProjectTypeEnum;
use Eurotext\RestApiClient\Request\Data\Project\ItemData;
use Eurotext\RestApiClient\Request\Data\ProjectData;
use Eurotext\RestApiClient\Request\Project\ItemGetRequest;
use Eurotext\RestApiClient\Request\Project\ItemPostRequest;
use Eurotext\RestApiClient\Request\ProjectPostRequest;
use Eurotext\RestApiClient\Request\ProjectTransitionRequest;
use Eurotext\RestApiClient\Request\ProjectTranslateRequest;
use Eurotext\RestApiClient\Response\ProjectTransitionResponse;
use PHPUnit\Framework\TestCase;

class ProjectV1ApiIntegrationTest extends TestCase
{
    const PROJECT_DESCRIPTION    = 'Integration Test';
    const DESCRIPTION_TRANSLATED = '!em etalsnart esaelP';
    const DESCRIPTION            = 'Please translate me!';

    /** @var ProjectV1Api */
    private $projectV1Api;

    /** @var ItemV1Api */
    private $projectItemV1Api;

    private $metaData = [
        'item_id'   => 27,
        'more_meta' => 'eurotext are the best',
    ];

    protected function setUp()
    {
        parent::setUp();

        $config = new Configuration();
        $config->setApiKey(\constant('EUROTEXT_API_KEY'));

        $this->projectV1Api     = new ProjectV1Api($config);
        $this->projectItemV1Api = new ItemV1Api($config);
    }

    /**
     * @throws \Eurotext\RestApiClient\Exception\DeserializationFailedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testItShouldCreateProject()
    {
        $projectData = new ProjectData(self::PROJECT_DESCRIPTION);

        $request = new ProjectPostRequest('', $projectData, ProjectTypeEnum::QUOTE());

        $response = $this->projectV1Api->post($request);

        $this->assertGreaterThan(0, $response->getId());

        return $response->getId();
    }

    /**
     * @depends testItShouldCreateProject
     */
    public function testItShouldCreateItem(int $projectId)
    {
        $itemRequest = new ItemPostRequest(
            $projectId,
            'en-us',
            'de-de',
            'product',
            'Magento',
            new ItemData(
                ['description' => self::DESCRIPTION],
                $this->metaData
            )
        );

        $response = $this->projectItemV1Api->post($itemRequest);

        $itemId = $response->getId();
        $this->assertGreaterThan(0, $itemId);

        return ['project_id' => $projectId, 'item_id' => $itemId];
    }

    /**
     * @depends testItShouldCreateProject
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testItShouldTransitionProjectStatus(int $projectId)
    {
        $request = new ProjectTransitionRequest($projectId, ProjectStatusEnum::NEW());

        $response = $this->projectV1Api->transition($request);

        $this->assertInstanceOf(ProjectTransitionResponse::class, $response);

        $httpResponse = $response->getHttpResponse();
        $this->assertEquals(204, $httpResponse->getStatusCode());

        return $projectId;
    }

    /**
     * @depends testItShouldCreateProject
     */
    public function testItShouldTriggerTranslateInSandbox(int $projectId)
    {
        $request = new ProjectTranslateRequest($projectId);

        $result = $this->projectV1Api->translate($request);

        $this->assertGreaterThan(0, $result->getId());
        $this->assertEquals($projectId, $result->getId());
    }

    /**
     * @depends testItShouldCreateProject
     */
    public function testItShouldGetProjectData(int $projectId)
    {
        $response = $this->projectV1Api->get($projectId);

        $actualItem   = $response->getItems()[1];

        $this->assertSame($response->getDescription(), self::PROJECT_DESCRIPTION);
        $this->assertEquals(self::DESCRIPTION_TRANSLATED, $actualItem->getDataValue('description'));
        $this->assertEquals('new', $actualItem->getDataValue('status'));
        $this->assertSame($this->metaData, $actualItem->getMeta());
    }

    /**
     * @depends testItShouldCreateItem
     */
    public function testItShouldGetItemData(array $data)
    {
        $projectId = $data['project_id'];
        $itemId    = $data['item_id'];
        $request   = new ItemGetRequest($projectId, $itemId);
        $response  = $this->projectItemV1Api->get($request);

        $itemData = $response->getItemData();

        $this->assertEquals(self::DESCRIPTION_TRANSLATED, $itemData->getDataValue('description'));

        $this->assertEquals($this->metaData, $itemData->getMeta());
    }
}
