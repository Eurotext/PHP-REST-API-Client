<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Test\Integration\Api;

use Eurotext\RestApiClient\Api\ProjectV1Api;
use Eurotext\RestApiClient\Configuration;
use Eurotext\RestApiClient\Enum\ProjectTypeEnum;
use Eurotext\RestApiClient\Request\Data\ProjectData;
use Eurotext\RestApiClient\Request\ProjectDataRequest;
use PHPUnit\Framework\TestCase;

class ProjectV1ApiIntegrationTest extends TestCase
{
    /** @var ProjectV1Api */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $config = new Configuration();
        $config->setApiKey(\constant('EUROTEXT_API_KEY'));

        $this->sut = new ProjectV1Api($config);
    }

    /**
     * @throws \Eurotext\RestApiClient\Exception\DeserializationFailedException
     */
    public function testItShouldSendProjectPost()
    {
        $projectData = new ProjectData('Integration Test');

        $request = new ProjectDataRequest('', $projectData, ProjectTypeEnum::QUOTE());

        $response = $this->sut->post($request);

        $this->assertGreaterThan(0, $response->getId());
    }

}
