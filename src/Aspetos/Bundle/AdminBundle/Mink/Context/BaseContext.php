<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Mink\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

/**
 * Behat context class, using Mink.
 *
 * @author david
 */
class BaseContext extends MinkContext implements SnippetAcceptingContext
{
    /**
     * @Then /^I will dump the content$/
     *
     * This is the last resort when everything else fails ;)
     */
    public function iWillDumpTheContent()
    {
        $driver = $this->getBrowserKitDriver();
        $container = $driver->getClient()->getContainer();
        $path = $container->getParameter('kernel.root_dir').'/../var/debug';
        if (!is_dir($path)) {
            mkdir($path);
        }

        $filename = preg_replace('~[^\\pL\d]+~u', '-', $this->getSession()->getCurrentUrl());
        file_put_contents($path.'/'.$filename.'.html', $driver->getContent());
    }

    /**
     * Check for BrowserKit driver
     *
     * @throws UnsupportedDriverActionException
     *
     * @return BrowserKitDriver
     */
    protected function getBrowserKitDriver()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver', $driver);
        }

        return $driver;
    }

    /**
     * @Given I am loading the default test fixtures
     */
    public function iAmLoadingTheDefaultTestFixtures()
    {
        $driver = $this->getBrowserKitDriver();
        $container = $driver->getClient()->getContainer();
        $path = $container->getParameter('kernel.root_dir').'/../src/Aspetos/Tests/Service/DataFixtures';

        $loader = new ContainerAwareLoader($container);
        $loader->loadFromDirectory($path);

        $purger = new ORMPurger();
        $executor = new ORMExecutor($container->get('entity_manager'), $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * @Given /^I am authenticated with Role "([^"]*)"$/
     *
     * Use this to authenticate the default test admin with the specified role
     *
     * @param string $role
     */
    public function iAmAuthenticatedWithRole($role)
    {
        $driver = $this->getBrowserKitDriver();
        $client = $driver->getClient();

        $user = $this->fetchUser('max.mustermann@dummy.local', $client);
        $user->setRoles(array(
            $role
        ));
        $this->forceSession($user, $client);
    }

    /**
     * @Given /^I am authenticated as User "([^"]*)"$/
     *
     * Use this to authenticate a user with the specified username
     *
     * @param string $username
     */
    public function iAmAuthenticatedAsUser($username)
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver', $driver);
        }

        $client = $driver->getClient();

        $user = $this->fetchUser($username, $client);
        $this->forceSession($user, $client);
    }

    /**
     * Fetch a user from the database using the given username.
     *
     * @throws ExpectationException
     *
     * @param string $username
     * @param Client $client
     *
     * @return UserInterface
     */
    protected function fetchUser($username, Client $client)
    {
        $user = $client->getContainer()->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
        if (null === $user) {
            $message = 'User with username "'.$username.'" does not exist';

            throw new ExpectationException($message, $this->getSession());
        }

        return $user;
    }

    /**
     * Force a valid session in the virtual browser.
     *
     * @param UserInterface $user
     * @param Client $client
     */
    protected function forceSession(UserInterface $user, Client $client)
    {
        $client->getCookieJar()->set(new Cookie(session_name(), true));
        $session = $client->getContainer()->get('session');

        $providerKey = $client->getContainer()->getParameter('fos_user.firewall_name');
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $session->set('_security_'.$providerKey, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
