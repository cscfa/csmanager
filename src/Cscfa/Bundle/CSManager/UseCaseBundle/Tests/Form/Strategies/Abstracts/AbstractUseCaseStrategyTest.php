<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Form\Strategies\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Abstracts\AbstractUseCaseStrategy;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * AbstractUseCaseStrategyTest.
 *
 * The AbstractUseCaseStrategyTest
 * process the AbstractUseCaseStrategy
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Abstracts\AbstractUseCaseStrategy
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Abstracts\AbstractUseCaseStrategy
 */
class AbstractUseCaseStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Strategy.
     *
     * This property register a mocked AbstractUseCaseStrategy
     * object to be tested.
     *
     * @var AbstractUseCaseStrategy|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $strategy;

    /**
     * Translator.
     *
     * This property register a mocked TranslatorInterface
     * object to be injected during tests.
     *
     * @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $translator;

    /**
     * Set up.
     *
     * This method set up the test class before
     * tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->strategy = $this->getMockForAbstractClass(AbstractUseCaseStrategy::class);

        $this->translator = $this->getMockForAbstractClass(TranslatorInterface::class);
    }

    /**
     * Trans domain provider.
     *
     * This method return a test range
     * for the trans domain AbstractUseCaseStrategy
     * usage.
     */
    public function transDomainProvider()
    {
        $base = array();

        for ($i = 0; $i < 20; ++$i) {
            $domain = openssl_random_pseudo_bytes(mt_rand(4, 12));
            array_push($base, array($domain, $domain));
        }

        return $base;
    }

    /**
     * Test trans domain.
     *
     * This method test the setter|getter
     * method of AbstractUseCaseStrategy.
     *
     * @param string $expected The expected transDomain
     * @param string $setted   The setted transDomain
     *
     * @dataProvider transDomainProvider
     */
    public function testTransDomain($expected, $setted)
    {
        $this->strategy->setTransDomain($setted);
        $this->assertEquals(
            $expected,
            $this->strategy->getTransDomain(),
            'The AbstractUseCaseStrategy::getTransDomain must return the setted transDomain'
        );
    }

    /**
     * Test trans domain.
     *
     * This method test the setter|getter
     * method of AbstractUseCaseStrategy.
     */
    public function testTranslator()
    {
        $this->strategy->setTranslator($this->translator);
        $this->assertSame(
            $this->translator,
            $this->strategy->getTranslator(),
            'The AbstractUseCaseStrategy::getTranslator must return the setted translator'
        );
    }
}
