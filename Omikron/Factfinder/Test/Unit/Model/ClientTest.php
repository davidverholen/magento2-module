<?php

declare(strict_types = 1);

namespace Omikron\Factfinder\Test\Unit\Model;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\HTTP\ClientInterface;
use Omikron\Factfinder\Api\Config\AuthConfigInterface;
use Omikron\Factfinder\Exception\ResponseException;
use Omikron\Factfinder\Model\Client;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ClientTest extends TestCase
{
    /** @var MockObject|ClientFactory\ */
    protected $clientFactoryMock;

    /** @var MockObject|SerializerInterface\ */
    protected $serializerMock;

    /** @var  MockObject|AuthConfigInterface*/
    protected $authConfigMock;

    /** @var MockObject|ClientInterface\ */
    protected $curlClientMock;

    /** @var Client */
    protected $client;

    protected function setUp()
    {
        $this->clientFactoryMock = $this->createMock(ClientFactory::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);
        $this->authConfigMock = $this->createMock(AuthConfigInterface::class);
        $this->curlClientMock = $this->createMock(ClientInterface::class);
        $this->clientFactoryMock->method('create')->willReturn($this->curlClientMock);

        $this->client = new Client(
            $this->clientFactoryMock,
            $this->serializerMock,
            $this->authConfigMock
        );
    }

    public function testSendRequestShouldThrowExceptionWhenResponseIsNotSerializable()
    {
        $this->serializerMock->expects($this->once())->method('unserialize')->willThrowException(new ResponseException());
        $this->curlClientMock->method('getStatus')->willReturn(200);
        $this->curlClientMock->method('getBody')->willReturn('unserializable string');

        $this->expectException('Omikron\Factfinder\Exception\ResponseException');

        $this->client->sendRequest('http://fake-ff-server.com/Search.ff', []);
    }

    public function testSendRequestShouldThrowExceptionIfStatusIsNot200()
    {
        $this->curlClientMock->method('getStatus')->willReturn(500);
        $this->curlClientMock->method('getBody')->willReturn('unserializable string');

        $this->expectException('Omikron\Factfinder\Exception\ResponseException');

        $this->client->sendRequest('http://fake-ff-server.com/Search.ff', []);
    }

    public function testSendCorrectRequest()
    {
        $response = '{"searchResult":{"breadCrumbTrailItems":[],"campaigns":[],"channel":"channel","fieldRoles":{"brand":"Manufacturer","campaignProductNumber":"ProductNumber","deeplink":"ProductUrl","description":"Description","displayProductNumber":"ProductNumber","ean":"EAN","imageUrl":"ImageUrl","masterArticleNumber":"MasterProductNumber","price":"Price","productName":"Name","trackingProductNumber":"ProductNumber"},"filters":[],"groups":[],"paging":{"currentPage":1,"firstLink":{"caption":"1","currentPage":true,"number":1,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026verbose=true\u0026format=JSON"},"lastLink":{"caption":"723","currentPage":false,"number":723,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=723\u0026verbose=true\u0026format=JSON"},"nextLink":{"caption":"\u003e\u003e","currentPage":false,"number":2,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=2\u0026verbose=true\u0026format=JSON"},"pageCount":723,"pageLinks":[{"caption":"1","currentPage":true,"number":1,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026verbose=true\u0026format=JSON"},{"caption":"2","currentPage":false,"number":2,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=2\u0026verbose=true\u0026format=JSON"},{"caption":"3","currentPage":false,"number":3,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=3\u0026verbose=true\u0026format=JSON"},{"caption":"4","currentPage":false,"number":4,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=4\u0026verbose=true\u0026format=JSON"},{"caption":"5","currentPage":false,"number":5,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=5\u0026verbose=true\u0026format=JSON"},{"caption":"6","currentPage":false,"number":6,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=6\u0026verbose=true\u0026format=JSON"},{"caption":"7","currentPage":false,"number":7,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=7\u0026verbose=true\u0026format=JSON"},{"caption":"8","currentPage":false,"number":8,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=8\u0026verbose=true\u0026format=JSON"},{"caption":"9","currentPage":false,"number":9,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026page=9\u0026verbose=true\u0026format=JSON"}],"previousLink":null,"resultsPerPage":10},"records":[{"foundWords":[],"id":"24-MB01","keywords":[],"position":1,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":2,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":3,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":4,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":5,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":6,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":7,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":8,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":9,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0},{"foundWords":[],"id":"24-MB01","keywords":[],"position":10,"record":{"Email":"","Description":"The sporty Joust Duffle Bag can\u0027t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\u0027s ideal for athletes with places to go.Dual top handles.Adjustable shoulder strap.Full-length zipper.L 29\u0022 x W 13\u0022 x H 11\u0022.","MagentoEntityId":"1","MetaKeywords":"","FeaturesBags":"","PageImage":"","Name":"Joust Duffle Bag","CategoryPath":"|CategoryPathROOT=Gear|CategoryPathROOT/Gear=Bags|","Identifier":"","PageUrl":"","FirstFailure":"","Dob":"","ErinRecommends":"","Manufacturer":"..Black..","MetaDescription":"","Short":"","Availability":"1","PageId":"","MasterProductNumber":"24-MB01","ImageUrl":"http://magento2.local/media/catalog/product/cache/6dd18fb85a59916e944c7f1f42e58a4c/m/b/mb01-blue-0.jpg","Title":"","ProductNumber":"24-MB01","ProductUrl":"http://magento2.local/joust-duffle-bag.html","Firstname":"","ContentHeading":"","EAN":"24-MB01","Price":"34.00","FilterPriceRange":"","Content":""},"searchSimilarity":100.0,"simiMalusAdd":0}],"resultArticleNumberStatus":"noArticleNumberSearch","resultCount":7227,"resultStatus":"resultsFound","resultsPerPageList":[{"default":false,"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026verbose=true\u0026format=JSON","selected":true,"value":10}],"searchControlParams":{"disableCache":false,"generateAdvisorTree":true,"idsOnly":false,"useAsn":true,"useAso":true,"useCampaigns":true,"useFoundWords":false,"useKeywords":false,"usePersonalization":true,"useSemanticEnhancer":true},"searchParams":"/FACT-Finder-7.3/Search.ff?query=FACT-Finder+version\u0026channel=channel\u0026verbose=true\u0026format=JSON","searchTime":13,"simiFirstRecord":10000,"simiLastRecord":10000,"singleWordResults":null,"sortsList":[],"timedOut":false}}';
        $this->curlClientMock->method('getStatus')->willReturn(200);
        $this->curlClientMock->method('getBody')->willReturn($response);
        $this->serializerMock->expects($this->once())->method('unserialize')->willReturn(json_decode($response, true));

        $response = $this->client->sendRequest('http://fake-ff-server.com/Search.ff', []);

        $this->assertArrayHasKey('searchResult', $response, 'Correct response should contains searchResult key');
        $this->assertArrayNotHasKey('error', $response, 'Response should not contains error key');
    }
}