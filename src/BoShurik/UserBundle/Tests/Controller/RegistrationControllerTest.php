<?php
/**
 * Created by JetBrains PhpStorm.
 * User: BoShurik
 * Date: 05.02.12
 * Time: 14:56
 */

namespace BoShurik\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistration()
    {
        $client = static::createClient();

        for ($i = 1; $i <= 128; $i++)
        {
            $crawler = $client->request('GET', '/register/');

            $buttonCrawlerNode = $crawler->selectButton('Регистрация');

            $form = $buttonCrawlerNode->form(array(
                'fos_user_registration_form[username]'                => 'Player'. $i,
                'fos_user_registration_form[email]'                   => 'player'. $i .'@example.com',
                'fos_user_registration_form[plainPassword][first]'    => 'Player',
                'fos_user_registration_form[plainPassword][second]'   => 'Player'
            ));

            $client->submit($form);
            $crawler = $client->followRedirect();

            $this->assertTrue($crawler->filter('html:contains("Player'. $i .'")')->count() > 0);

            $client->restart();
        }
    }
}
