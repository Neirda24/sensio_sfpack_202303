<?php

namespace App\Tests\Controller;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\HelloController
 *
 * @group smoke
 */
class HelloControllerTest extends WebTestCase
{
    public static function getValidNames(): Generator
    {
        yield 'default' => [
            'uri'          => '/hello',
            'expectedName' => 'Adrien',
        ];

        yield 'name "Adrien"' => [
            'uri'          => '/hello/Adrien',
            'expectedName' => 'Adrien',
        ];

        yield 'name "louise"' => [
            'uri'          => '/hello/louise',
            'expectedName' => 'louise',
        ];
    }

    /**
     * @dataProvider getValidNames
     */
    public function testNameIsDisplayed(string $uri, string $expectedName): void
    {
        $client = static::createClient();
        $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString("Hello {$expectedName} !", $client->getResponse()->getContent());
    }
}
