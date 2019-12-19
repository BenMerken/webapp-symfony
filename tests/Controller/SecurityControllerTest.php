<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;

    public function registeredUserAndPasswordProvider()
    {
        return [
            [
                [
                    'user' => 'admin1@pxl.be',
                    'password' => 'secret',
                ]
            ],
            [
                [
                    'user' => 'mod1@pxl.be',
                    'password' => 'secret',
                ]
            ],
            [
                [
                    'user' => 'custodian1@pxl.be',
                    'password' => 'secret',
                ]
            ],
        ];
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLogin_AnonymousUser_Statuscode200()
    {
        $crawler = $this->client->request('GET', '/login');
        $h1 = $crawler->filter('h1')->text();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Please, sign in.', $h1);
    }

    /**
     * @dataProvider registeredUserAndPasswordProvider
     */
    public function testLogin_AnonymousUserLogsInWithForm_Statuscode302AndRedirectToHomePage($userAndPassword)
    {
        $crawler = $this->client->request('GET', '/login');
        $loginForm = $crawler->filter('form')->form();

        $loginForm['_username'] = $userAndPassword['user'];
        $loginForm['_password'] = $userAndPassword['password'];

        $this->client->submit($loginForm);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
