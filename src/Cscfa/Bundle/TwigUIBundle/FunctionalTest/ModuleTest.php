<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface;
use Cscfa\Bundle\TwigUIBundle\Factories\EnvironmentFactory;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;
use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Twig\Extension\TwigModuleExtension;
use Symfony\Component\DomCrawler\Crawler;

/**
 * ModuleTest.
 *
 * The ModuleTest is used to test
 * the Module services.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @SuppressWarnings(PHPMD)
 */
class ModuleTest extends WebTestCase
{
    /**
     * Module.
     *
     * This property store the
     * module instance
     * to test.
     *
     * @var ModuleSetInterface
     */
    protected $module;

    /**
     * Factory.
     *
     * This property store the
     * EnvironmentFactory instance
     * to be used in tests.
     *
     * @var EnvironmentFactory
     */
    protected $factory;

    /**
     * Set up.
     *
     * This method set up the test
     * class before tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        self::bootKernel();

        $this->module = static::$kernel->getContainer()->get('cscfa_twig_ui.test.main');
        $this->factory = static::$kernel->getContainer()->get('EnvironmentContainerFactory');
    }

    /**
     * Test process.
     *
     * This method test the process of
     * service modules.
     */
    public function testProcess()
    {
        $environment = $this->factory->getInstance();
        $this->module->processAll($environment);

        $excpectedIterator = new TwigRequestIterator();
        $firstRequest = new TwigRequest();
        $secondRequest = new TwigRequest();
        $medior = new TwigRequest();

        $firstRequest->setTwigPath('CscfaTwigUIBundle:test:firstTop.html.twig')
            ->addArgument('arg', 'argument');
        $secondRequest->setTwigPath('CscfaTwigUIBundle:test:secondTop.html.twig')
            ->addArgument('args', 'arguments');
        $medior->setTwigPath('CscfaTwigUIBundle:test:firstMedior.html.twig')
            ->addArgument('med', 'medior');

        $firstRequest->addChildRequest($medior, 'firstMediorLevel');
        $excpectedIterator->addTwigRequest($firstRequest, 'firstTopLevel');
        $excpectedIterator->addTwigRequest($secondRequest, 'secondTopLevel');

        $this->assertEquals(
            $excpectedIterator,
            $environment->getTwigRequests(),
            'The modules must be called as the service definition and register twig request as defined'
        );

        return $environment;
    }

    /**
     * Test rendering.
     *
     * This method test the rendering
     * service.
     *
     * @param EnvironmentContainer $environment The environment
     * @depends testProcess
     */
    public function testRendering(EnvironmentContainer $environment)
    {
        $twigService = self::$kernel->getContainer()->get('twig');

        $extension = new TwigModuleExtension();

        $rendering = $extension->processEnvironment($twigService, $environment);

        $crawler = new Crawler($rendering);

        $this->assertEquals(
            3,
            $crawler->filter('div')->count(),
            'Expected div count is 3'
        );

        $this->assertEquals(
            3,
            $crawler->filter('p')->count(),
            'Expected p count is 3'
        );

        $this->assertEquals(
            'argument',
            $crawler->filter('p')->eq(0)->text(),
            "Expected first p container 'argument'"
        );

        $this->assertEquals(
            'medior',
            $crawler->filter('p')->eq(1)->text(),
            "Expected second p container 'medior'"
        );

        $this->assertEquals(
            'arguments',
            $crawler->filter('p')->eq(2)->text(),
            "Expected third p container 'arguments'"
        );
    }
}
