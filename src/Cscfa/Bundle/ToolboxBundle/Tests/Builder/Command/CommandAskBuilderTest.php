<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Test
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Tests\Builder\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;

/**
 * CommandAskBuilderTest class.
 *
 * The CommandAskBuilderTest class provide
 * test to valid CommandAskBuilder methods
 * process.
 *
 * @category Test
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class CommandAskBuilderTest extends WebTestCase
{
    /**
     * The setUp.
     * 
     * This method is used to configure
     * and process the initialisation of
     * the test class.
     * 
     * @return void
     */
    public function setUp()
    {
    }

    /**
     * The testConstructor test.
     *
     * This test is used to confirm
     * the CommandAskBuilder constructor.
     *
     * @return void
     */
    public function testConstructor()
    {
        $type = CommandAskBuilder::TYPE_ASK_CONFIRMATION;
        $question = null;
        $default = null;
        $options = null;
        $completion = null;
        $limited = null;
        
        $commandBuilder = new CommandAskBuilder($type, $question, $default, $options, $completion, $limited);
        
        $this->assertTrue($commandBuilder->getType() == $type);
        $this->assertTrue($commandBuilder->getQuestion() == $question);
        $this->assertTrue($commandBuilder->getDefault() == $default);
        $this->assertTrue($commandBuilder->getOptions() == $options);
        $this->assertTrue($commandBuilder->getCompletion() == $completion);
        $this->assertTrue($commandBuilder->getLimite() == $limited);

        $type = CommandAskBuilder::TYPE_ASK;
        $question = "This is a question";
        $default = true;
        $options = CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED;
        $completion = array("12", "23", "34");
        $limited = array("13", "24", "35");
        
        $commandBuilder = new CommandAskBuilder($type, $question, $default, $options, $completion, $limited);
        
        $this->assertTrue($commandBuilder->getType() == $type);
        $this->assertTrue($commandBuilder->getQuestion() == $question);
        $this->assertTrue($commandBuilder->getDefault() == $default);
        $this->assertTrue($commandBuilder->getOptions() == $options);
        $this->assertTrue($commandBuilder->getCompletion() == $completion);
        $this->assertTrue($commandBuilder->getLimite() == $limited);
    }

    /**
     * The testSetter test.
     *
     * This test is used to confirm
     * the CommandAskBuilder setters.
     *
     * @return void
     */
    public function testSetter()
    {
        $type = CommandAskBuilder::TYPE_ASK;
        $question = "This is a question";
        $default = true;
        $options = CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED;
        $completion = array("12", "23", "34");
        $limited = array("13", "24", "35");
        
        $commandBuilder = new CommandAskBuilder();
        
        $commandBuilder->setType($type);
        $commandBuilder->setQuestion($question);
        $commandBuilder->setDefault($default);
        $commandBuilder->setOptions($options);
        $commandBuilder->setCompletion($completion);
        $commandBuilder->setLimite($limited);
        
        $this->assertTrue($commandBuilder->getType() == $type);
        $this->assertTrue($commandBuilder->getQuestion() == $question);
        $this->assertTrue($commandBuilder->getDefault() == $default);
        $this->assertTrue($commandBuilder->getOptions() == $options);
        $this->assertTrue($commandBuilder->getCompletion() == $completion);
        $this->assertTrue($commandBuilder->getLimite() == $limited);
    }
}