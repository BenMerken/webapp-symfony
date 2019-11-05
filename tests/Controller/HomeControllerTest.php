<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function userAndPasswordProvider()
    {
        return [
            [
                ['PHP_AUTH_USER' => 'admin1@pxl.be',
                    'PHP_AUTH_PW' => 'secret']
            ],
            [
                ['PHP_AUTH_USER' => 'mod1@pxl.be',
                    'PHP_AUTH_PW' => 'secret']
            ],
            [
                ['PHP_AUTH_USER' => 'custodian1@pxl.be',
                    'PHP_AUTH_PW' => 'secret']
            ],
        ];
    }

    public function testIndex_noUserLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Welcome to the PXL Asset Management tool!');
    }

    /**
     * @dataProvider userAndPasswordProvider
     */
    public function testIndex_UserLoggedIn($userAndPassword)
    {
        $client = static::createClient([], $userAndPassword);
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', $userAndPassword['PHP_AUTH_USER']);
    }
}