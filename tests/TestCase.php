<?php declare(strict_types=1);

namespace Tests;

use PicoFeed\Client\Client;
use PicoFeed\Reader\Reader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestCase extends WebTestCase
{
    protected function getTestFeedPath() : string
    {
        return __DIR__ . '/test_feed_rss_2_0.xml';
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Reader
     */
    protected function createReader()
    {
        $client = Client::getInstance();
        $ref = new \ReflectionObject($client);
        $prop = $ref->getProperty('content');
        $prop->setAccessible(true);
        $prop->setValue($client, file_get_contents($this->getTestFeedPath()));

        $builder = $this->getMockBuilder(Reader::class);
        $builder->setMethods(['download']);
        $reader = $builder->getMock();

        $reader->method('download')
            ->willReturn($client);

        return $reader;
    }
}
