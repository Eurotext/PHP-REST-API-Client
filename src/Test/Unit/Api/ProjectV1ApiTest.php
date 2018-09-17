<?php
declare(strict_types=1);
/**
 * @copyright see PROJECT_LICENSE.txt
 *
 * @see       PROJECT_LICENSE.txt
 */

namespace Eurotext\RestApiClient\Api;

use Eurotext\RestApiClient\Enum\ProjectTypeEnum;
use Eurotext\RestApiClient\Exception\DeserializationFailedException;
use Eurotext\RestApiClient\Request\Data\ProjectData;
use Eurotext\RestApiClient\Request\ProjectDataRequest;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectV1ApiTest extends TestCase
{
    /** @var ProjectV1Api */
    private $api;

    /** @var \GuzzleHttp\ClientInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = $this->getMockBuilder(\GuzzleHttp\ClientInterface::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['send'])
                             ->getMockForAbstractClass();

        $this->api = new ProjectV1Api(null, $this->client);
    }

    /**
     * @throws \Eurotext\RestApiClient\Exception\DeserializationFailedException
     * @throws \Eurotext\RestApiClient\Exception\ApiClientException
     */
    public function testItShouldSendProjectPost()
    {
        $projectData = new ProjectData('Unit Test');

        $request = new ProjectDataRequest('', $projectData, ProjectTypeEnum::QUOTE());

        $responseStatus  = 200;
        $responseHeaders = [];
        $responseBody    = file_get_contents(__DIR__ . '/_files/project-post-response.json');

        $httpResponse = new \GuzzleHttp\Psr7\Response($responseStatus, $responseHeaders, $responseBody);

        $this->client->expects($this->once())->method('send')->willReturn($httpResponse);

        $response = $this->api->post($request);

        $this->assertEquals(78, $response->getId());
    }

    /**
     * @throws ApiClientException
     */
    public function testItShouldCaptureExceptionsDuringResponseDeserialization()
    {
        $request = new ProjectDataRequest('', new ProjectData(''));

        $responseStatus  = 200;
        $responseHeaders = [];
        $responseBody    = file_get_contents(__DIR__ . '/_files/project-post-response.json');

        $httpResponse = new \GuzzleHttp\Psr7\Response($responseStatus, $responseHeaders, $responseBody);

        $this->client->expects($this->once())->method('send')->willReturn($httpResponse);

        // SERIALIZER
        /** @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMockBuilder(SerializerInterface::class)
                           ->setMethods(['deserialize'])
                           ->getMockForAbstractClass();
        $serializer->expects($this->once())->method('deserialize')->willThrowException(new \Exception());

        $api = new ProjectV1Api(null, $this->client, $serializer);

        $response = null;
        try {
            $response = $api->post($request);
        } catch (DeserializationFailedException $e) {
            // we are catching the exception and asserting various parameters
            $this->assertInstanceOf(RequestInterface::class, $e->getHttpRequest());
            $this->assertInstanceOf(ResponseInterface::class, $e->getHttpResponse());
        }

        $this->assertNull($response);
    }

    /**
     * @throws ApiClientException
     * @throws DeserializationFailedException
     */
    public function testItShouldInitializeEmptyResponseObject()
    {
        $request = new ProjectDataRequest('', new ProjectData(''));

        $responseStatus  = 200;
        $responseHeaders = [];
        $responseBody    = file_get_contents(__DIR__ . '/_files/project-post-response.json');

        $httpResponse = new \GuzzleHttp\Psr7\Response($responseStatus, $responseHeaders, $responseBody);

        $this->client->expects($this->once())->method('send')->willReturn($httpResponse);

        // SERIALIZER
        /** @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMockBuilder(SerializerInterface::class)
                           ->setMethods(['deserialize'])
                           ->getMockForAbstractClass();
        $serializer->expects($this->once())->method('deserialize')->willReturn(new \StdClass());

        $api = new ProjectV1Api(null, $this->client, $serializer);

        $api->post($request);
    }

    public function testItShouldGetProjectDetails()
    {
        $projectId = 27;

        $responseStatus  = 200;
        $responseHeaders = [];
        $responseBody    = file_get_contents(__DIR__ . '/_files/project-get-response.json');

        $httpResponse = new \GuzzleHttp\Psr7\Response($responseStatus, $responseHeaders, $responseBody);

        $this->client->expects($this->once())->method('send')->willReturn($httpResponse);

        $response = $this->api->get($projectId);

        $this->assertSame('the project description', $response->getDescription());
        $this->assertArrayHasKey(1, $response->getItems());
        $this->assertArrayHasKey(2, $response->getItems());
        $this->assertArrayHasKey(3, $response->getItems());
        $this->assertSame([], $response->getFiles());
    }

    public function testItShouldThrowExceptionOnDeserializationError()
    {
        $this->expectException(DeserializationFailedException::class);
        $this->expectExceptionMessage('Error during deserialization');

        $brokenResponse = new \GuzzleHttp\Psr7\Response(200, [], '[]');

        $this->client->expects($this->once())->method('send')->willReturn($brokenResponse);

        $this->api->get(27);
    }

    public function testItShouldThrowAnExceptionOnRequestError()
    {
        $this->expectException(GuzzleException::class);

        $this->client->expects($this->once())->method('send')->willThrowException(new HttpTestException());

        $this->api->get(27);
    }

}

/** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

class HttpTestException extends \Exception implements GuzzleException
{

}