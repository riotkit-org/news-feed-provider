<?php declare(strict_types=1);

namespace Tests\AppBundle\Factory\Specification;

use AppBundle\Factory\Specification\RssSpecificationFactory;
use Tests\TestCase;

/**
 * @see RssSpecificationFactory
 */
class RssSpecificationFactoryTest extends TestCase
{
    /**
     * @test
     * @see RssSpecificationFactory::isAbleToHandle()
     */
    public function should_be_able_to_handle()
    {
        $this->assertTrue((new RssSpecificationFactory())->isAbleToHandle('rss'));
    }

    /**
     * @test
     * @see RssSpecificationFactory::create()
     */
    public function expects_the_url_matches()
    {
        $factory = new RssSpecificationFactory();
        $specification = $factory->create([
            'url' => 'http://federacja-anarchistyczna.pl/feed',
        ]);

        $this->assertSame('http://federacja-anarchistyczna.pl/feed', $specification->getUrl());
    }
}
