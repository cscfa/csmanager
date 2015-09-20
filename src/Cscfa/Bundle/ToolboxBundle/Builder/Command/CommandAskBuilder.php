<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Builder
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Builder\Command;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * CommandAskBuilder class.
 *
 * The CommandAskBuilder class is used
 * to build ask helper for the command
 * facade.
 *
 * @category Facade
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CommandAskBuilder
{

    /**
     * Ask confirmation type.
     *
     * This is the ask confirmation
     * helper type.
     *
     * @var string
     */
    const TYPE_ASK_CONFIRMATION = "out.ask_confirmation";

    /**
     * Ask type.
     * 
     * This is the ask
     * helper type.
     * 
     * @var string
     */
    const TYPE_ASK = "out.ask";

    /**
     * Select type.
     * 
     * This is the select
     * helper type.
     * 
     * @var string
     */
    const TYPE_ASK_SELECT = "out.select";

    /**
     * Autocompletion option.
     *
     * This is the auto-completion
     * option of the helper.
     *
     * @var integer
     */
    const OPTION_ASK_AUTOCOMPLETED = 1;

    /**
     * Hidden response option.
     * 
     * This is the hidden response
     * option of the helper.
     * 
     * @var integer
     */
    const OPTION_ASK_HIDDEN_RESPONSE = 2;

    /**
     * Multi select option.
     * 
     * This is the multi-select
     * option of the helper.
     * 
     * @var integer
     */
    const OPTION_ASK_MULTI_SELECT = 4;

    /**
     * The type.
     * 
     * This is the type
     * of the helper.
     * 
     * @var string
     */
    protected $askType;

    /**
     * The options.
     * 
     * This is the options
     * of the helper.
     * 
     * @var integer
     */
    protected $askOptions;

    /**
     * The question.
     * 
     * This is the question string
     * that will be displayed by
     * the helper.
     * 
     * @var string
     */
    protected $question;

    /**
     * The default value.
     * 
     * This is the default
     * value returned if the
     * user doesn't answer
     * anyone.
     * 
     * @var mixed
     */
    protected $default;

    /**
     * The completion array.
     * 
     * This array register the
     * autocompletions values
     * of an ask helper.
     * 
     * @var array
     */
    protected $completion;

    /**
     * The limit array.
     * 
     * This array register the
     * granted values for a select
     * helper.
     * 
     * @var array
     */
    protected $limited;

    /**
     * CommandAskBuilder constructor.
     * 
     * This method is the default builder
     * constructor. It register all of the
     * internals parameters.
     * 
     * @param string  $type       The type of helper
     * @param string  $question   The question to display
     * @param string  $default    The default response
     * @param integer $options    The options of the helper
     * @param array   $completion The autocompletion array of an ask type
     * @param array   $limited    The limited choices of a select type
     */
    public function __construct($type = self::TYPE_ASK_CONFIRMATION, $question = null, $default = null, $options = null, array $completion = null, array $limited = null)
    {
        $this->askType = $type;
        $this->question = $question;
        $this->default = $default;
        $this->askOptions = $options;
        $this->completion = $completion;
        $this->limited = $limited;
    }

    /**
     * Set type.
     * 
     * This method allow to set
     * the type of the ask helper.
     * 
     * @return string
     */
    public function getType()
    {
        return $this->askType;
    }

    /**
     * Set type.
     * 
     * This method allow to set
     * the type of the ask helper.
     * 
     * @param string $askType The type of helper
     * 
     * @return \Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder
     */
    public function setType($askType)
    {
        $this->askType = $askType;
        return $this;
    }

    /**
     * Set option.
     * 
     * This method allow to set
     * the options of the ask
     * helper.
     * 
     * @return integer
     */
    public function getOptions()
    {
        return $this->askOptions;
    }

    /**
     * Set option.
     * 
     * This method allow to set
     * the options of the ask
     * helper.
     * 
     * @param integer $askOptions The options of the helper
     * 
     * @return \Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder
     */
    public function setOptions($askOptions)
    {
        $this->askOptions = $askOptions;
        return $this;
    }

    /**
     * Get question.
     * 
     * This method allow to get
     * the question string that
     * will be displayed.
     * 
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question.
     * 
     * This method allow to set
     * the question string that
     * will be displayed.
     * 
     * @param string $question The question to display
     * 
     * @return \Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder
     */
    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * Get default.
     * 
     * This method allow to get
     * the default response of 
     * the question when the user
     * doesn't answer any one.
     * 
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default.
     * 
     * This method allow to set
     * the default response of 
     * the question when the user
     * doesn't answer any one.
     * 
     * @param mixed $default The default response
     * 
     * @return \Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Get completion.
     * 
     * This method allow to get
     * the array of autocompletion
     * to assist the user answer.
     * 
     * @return array
     */
    public function getCompletion()
    {
        return $this->completion;
    }

    /**
     * Set completion.
     * 
     * This method allow to set
     * the array of autocompletion
     * to assist the user answer.
     * 
     * @param array $completion The autocompletion array of an ask type
     * 
     * @return \Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder
     */
    public function setCompletion(array $completion = null)
    {
        $this->completion = $completion;
        return $this;
    }

    /**
     * Get limite.
     * 
     * This method allow to get
     * the array of select question
     * to reform the response of
     * the user.
     * 
     * @return array
     */
    public function getLimite()
    {
        return $this->limited;
    }

    /**
     * Set limite.
     * 
     * This method allow to set
     * the array of select question
     * to reform the response of
     * the user.
     * 
     * @param array $limite The limited choices of a select type
     * 
     * @return \Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder
     */
    public function setLimite(array $limite = null)
    {
        $this->limited = $limite;
        return $this;
    }
}