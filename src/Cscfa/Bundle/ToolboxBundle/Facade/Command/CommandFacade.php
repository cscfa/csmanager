<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Facade
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Facade\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\Exception\Type\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints\Null;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandColorBuilder;
use Cscfa\Bundle\ToolboxBundle\Converter\Command\CommandTypeConverter;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;

/**
 * CommandFacade class.
 *
 * The CommandFacade class is an utility
 * tool to use command.
 *
 * @category Facade
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CommandFacade
{

    /**
     * The input interface.
     * 
     * This allow access to input
     * stream of the command.
     * 
     * @var InputInterface
     */
    protected $input;

    /**
     * The output interface.
     * 
     * This allow to access to output
     * stream of the command.
     * 
     * @var OutputInterface
     */
    protected $output;

    /**
     * The command.
     * 
     * This allow to access to the
     * main command.
     * 
     * @var ContainerAwareCommand
     */
    protected $command;

    /**
     * The constructor.
     * 
     * This default constructor register
     * an input and output interface and
     * a container aware command to be 
     * used by the methods.
     * 
     * @param InputInterface        $input   The input interface that be used to get arguments and options
     * @param OutputInterface       $output  The output interface that be used to display
     * @param ContainerAwareCommand $command The command that is the parent of the process
     */
    public function __construct(InputInterface $input, OutputInterface $output, ContainerAwareCommand $command)
    {
        $this->input = $input;
        $this->output = $output;
        $this->command = $command;
    }

    /**
     * Get ask builder.
     * 
     * This method allow to get a
     * new instance of CommandAskBuilder
     * to be used by ask method.
     * 
     * @return \Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder
     */
    public function getAskBuilder()
    {
        return new CommandAskBuilder();
    }

    /**
     * The ask method.
     * 
     * This method allow to get
     * a response from the user 
     * into a command line context.
     * It use an ask builder to
     * perform this action.
     * 
     * @param CommandAskBuilder $builder The CommandAskBuilder that explain ask usage
     * 
     * @return mixed
     * @throws UnexpectedTypeException
     */
    public function ask(CommandAskBuilder $builder)
    {
        $builder->setQuestion($builder->getQuestion());
        
        switch ($builder->getType()) {
        case CommandAskBuilder::TYPE_ASK_CONFIRMATION:
            return $this->askConfirmation($builder);
        case CommandAskBuilder::TYPE_ASK:
            return $this->askRun($builder);
        case CommandAskBuilder::TYPE_ASK_SELECT:
            return $this->askSelect($builder);
        default:
            throw new UnexpectedTypeException("The selected type is unknow.", 404, null, array("out.ask_confirmation", "out.ask", "out.select"));
        }
    }

    /**
     * The ask confirmation launcher.
     * 
     * This method allow the 
     * ask confirmation method 
     * to perform a ask helper 
     * usage.
     * 
     * @param CommandAskBuilder $builder The ask builder
     * 
     * @return boolean
     */
    protected function askConfirmation(CommandAskBuilder $builder)
    {
        $dialog = $this->getDialog();
        
        return $dialog->askConfirmation($this->output, $builder->getQuestion(), $builder->getDefault());
    }

    /**
     * The ask launcher.
     * 
     * This method allow the 
     * ask method to perform 
     * a ask helper usage.
     * 
     * @param CommandAskBuilder $builder The ask builder
     * 
     * @return string
     */
    protected function askRun(CommandAskBuilder $builder)
    {
        $dialog = $this->getDialog();
        
        if ($builder->getOptions() & CommandAskBuilder::OPTION_ASK_HIDDEN_RESPONSE) {
            return $dialog->askHiddenResponse($this->output, $builder->getQuestion());
        } else {
            return $dialog->ask($this->output, $builder->getQuestion(), $builder->getDefault(), $builder->getCompletion());
        }
    }

    /**
     * The select launcher.
     * 
     * This method allow the 
     * ask method to perform 
     * a select helper usage.
     * 
     * @param CommandAskBuilder $builder The ask builder
     * 
     * @return mixed
     */
    protected function askSelect(CommandAskBuilder $builder)
    {
        $dialog = $this->getDialog();
        
        return $dialog->select($this->output, $builder->getQuestion(), $builder->getLimite(), $builder->getDefault(), false, 'Value "%s" is invalid', ($builder->getOptions() & CommandAskBuilder::OPTION_ASK_MULTI_SELECT));
    }

    /**
     * Get the dialog helper.
     * 
     * This method allow to
     * get the dialog helper
     * from the command.
     * 
     * @return \Symfony\Component\Console\Helper\DialogHelper
     */
    protected function getDialog()
    {
        return $this->command->getHelperSet()->get("dialog");
    }

    /**
     * Get or ask an argument.
     * 
     * This method allow to get
     * an argument from the input
     * or ask a value to the
     * user.
     * 
     * @param string            $name    The input argument name to get
     * @param CommandAskBuilder $builder The ask builder to reclaim the value
     * 
     * @return \Symfony\Component\Console\Input\mixed|\Cscfa\Bundle\ToolboxBundle\Facade\Command\mixed
     */
    public function getOrAsk($name, CommandAskBuilder $builder)
    {
        try {
            $result = $this->input->getArgument($name);
        } catch (\InvalidArgumentException $e) {
            $result = $this->input->getOption($name);
        }
        
        if ($result) {
            return $result;
        } else {
            return $this->ask($builder);
        }
    }

    /**
     * Get or ask multiple parameters.
     * 
     * This method allow to get
     * or ask for user a multiple
     * set of parameters.
     * 
     * The parameter to ask must
     * be given as an array of
     * associative array or json 
     * array of objects. The format
     * of objects must contain a 'var',
     * and can contain 'type', 
     * 'question', 'default', 'option',
     * 'completion', 'limit' and 'extra'.
     * 
     * The extra must be set as associative 
     * array or null. It can contain :
     * <ul>
     *      <li>'empty' : set to TRUE or FALSE. Allow to answer with an empty value (TRUE as default).</li>
     *      <li>'default' : set to TRUE or FALSE. Allow to display the default value (TRUE as default).</li>
     *      <li>'transform' : a transformation function that get a parameter to transform and return.</li>
     *      <li>'active' : inform that the var must be get or not.</li>
     *      <li>'unactive' : inform under the value to return if unactive.</li>
     * </ul>
     * 
     * @param array|json $params The parameter to use
     * 
     * @return NULL|mixed
     */
    public function getOrAskMulti($params)
    {
        if (is_string($params)) {
            $params = json_decode($params, true);
        }
        
        if (! is_array($params)) {
            return null;
        }
        
        $defaultArray = array(
            "var" => "",
            "type" => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
            "question" => null,
            "default" => null,
            "option" => null,
            "completion" => null,
            "limit" => null,
            "extra" => array(
                "empty" => true,
                "default" => true,
                "active" => true,
                "unactive" => null,
                "transform" => function ($param) {
                    return $param;
                }
            )
        );
        
        $results = array();
        foreach ($params as $value) {
            $parameters = array_merge($defaultArray, $value);
            
            if (isset($value["extra"])) {
                $parameters["extra"] = array_merge($defaultArray["extra"], $value["extra"]);
            } else {
                $parameters["extra"] = $defaultArray["extra"];
            }
            
            if (! $parameters["extra"]["active"]) {
                $results[] = $parameters["extra"]["unactive"];
                continue;
            }
            
            $question = new CommandColorFacade($this->output);
            $question->addColor("def", CommandColorBuilder::GREEN, null, null);
            $question->addText($parameters["question"]);
            
            if ($parameters["extra"]["default"]) {
                $question->addText(" [");
                $question->addText(CommandTypeConverter::convertToString($parameters["default"]), "def");
                $question->addText("] ");
            }
            
            $question->addText(": ");
            $parameters["question"] = $question->getString();
            
            $askBuilder = new CommandAskBuilder();
            $askBuilder->setType($parameters["type"])
                ->setQuestion($parameters["question"])
                ->setDefault($parameters["default"])
                ->setOptions($parameters["option"])
                ->setCompletion($parameters["completion"])
                ->setLimite($parameters["limit"]);
            
            $skip = false;
            $result = null;
            
            while (! $skip) {
                $result = $this->getOrAsk($parameters["var"], $askBuilder);
                
                if (! $parameters["extra"]["empty"] && (empty($result) || is_bool($result))) {
                    $skip = false;
                } else {
                    $skip = true;
                }
            }
            
            $results[] = $parameters["extra"]["transform"]($result);
        }
        
        return $results;
    }

    /**
     * Get confirmation
     * 
     * This method allow to get
     * the user confirmation for 
     * a set of values. This 
     * values are given into an
     * associative array. This 
     * array keys are used as
     * value label.
     * 
     * @param array $values The associative array of values to confirm
     * 
     * @return boolean
     */
    public function getConfirmation($values)
    {
        $colorOutput = new CommandColorFacade($this->output);
        
        $colorOutput->addColor('default', 'green', null, 'bold')
            ->addColor('value', 'black', 'cyan', null)
            ->addColor('ask', null, null, null);
        
        foreach ($values as $text => $value) {
            
            $value = CommandTypeConverter::convertToString($value);
            
            $colorOutput->addText("\n" . $text . " : ");
            $colorOutput->addText($value, 'value');
        }
        
        $colorOutput->write();
        
        $askBuilder = new CommandAskBuilder();
        
        $askBuilder->setDefault(true)->setQuestion("Did you confirm [<fg=green>yes</fg=green>] : ");
        
        return $this->ask($askBuilder);
    }

    /**
     * Apply to builder and validate.
     * 
     * This method allow to apply and
     * validate all of the given parameters.
     * Assert that the used builders methods
     * return a boolean that be equal to true
     * if the method success or false if
     * not. Also assert that the builder
     * method take only one parameter.
     * 
     * The "params" parameter represent an
     * associative array where each element
     * contain an array. In this case, each
     * key represent the builder method to
     * use. The inside elements are :
     * <ul>
     *      <li>The value to insert</li>
     *      <li>The error label as string</li>
     *      <li>an associative array with the possible last error of the builder and the label to display</li>
     * </ul>
     * 
     * This method take as argument the error
     * message to display, and the success
     * message. As last argument, this method
     * get a "verbose" optional argument that
     * enable or desable the display state of
     * the messages. If this parameter is set
     * to true, and it's the case by default,
     * the errors or success messages are
     * automaticaly displayed. If it's set to
     * false, none of this messages are displayed.
     * 
     * @param ErrorRegisteryInterface &$builder The builder to use for insert the values
     * @param array                   $params   An array that contain all of the method configuration
     * @param string                  $error    A string to be displayed in case of error
     * @param string                  $success  A string to be displayed in case of success
     * @param boolean                 $verbose  A boolean that enable or desable the automatic message display
     * 
     * @return boolean
     */
    public function applyAndValidate(ErrorRegisteryInterface &$builder, $params, $error, $success, $verbose = true)
    {
        if (is_string($params)) {
            $params = json_decode($params, true);
        }
        
        if (! is_array($params)) {
            return false;
        }
        
        $errorOccured = false;
        $cf = new CommandColorFacade($this->output);
        $cf->addColor("error", CommandColorInterface::BLACK, CommandColorInterface::RED, null);
        $cf->addColor("errorLabel", CommandColorInterface::BLUE, null, CommandColorBuilder::BOLD);
        $cf->addColor("success", CommandColorInterface::BLACK, CommandColorInterface::GREEN, null);
        $cf->addColor("default", null, null, null);
        
        $cf->addText("\n");
        $cf->addText("\n$error.\n", "error");
        $cf->addText("\n");
        
        foreach ($params as $method => $param) {
            list ($value, $errorLabel, $errorCase) = $param;
            
            if (is_array($value)) {
                foreach ($value as $valueElement) {
                    try {
                        if (! $this->apply2Builder($builder, $method, $valueElement, $errorLabel, $errorCase, $cf)) {
                            $errorOccured = true;
                        }
                    } catch (\Exception $e) {
                        $cf->addText(" ", "error");
                        $cf->addText(get_class($builder) . "::" . $method, "errorLabel");
                        $cf->addText(" :\n");
                        $cf->addText(" ", "error");
                        $cf->addText("\t" . $errorLabel . ": ");
                        $cf->addText($e->getMessage() . "\n");
                        $errorOccured = true;
                    }
                }
            } else {
                try {
                    if (! $this->apply2Builder($builder, $method, $value, $errorLabel, $errorCase, $cf)) {
                        $errorOccured = true;
                    }
                } catch (\Exception $e) {
                    $cf->addText(" ", "error");
                    $cf->addText(get_class($builder) . "::" . $method, "errorLabel");
                    $cf->addText(" :\n");
                    $cf->addText(" ", "error");
                    $cf->addText("\t" . $errorLabel . ": ");
                    $cf->addText($e->getMessage() . "\n");
                    $errorOccured = true;
                }
            }
        }
        
        if ($errorOccured && $verbose) {
            $cf->addText("\n");
            $cf->write();
        } else {
            $cf->clear();
            $cf->addText("\n");
            $cf->addText("\n$success.\n", "success");
            $cf->addText("\n");
            $cf->write();
        }
        
        return ! $errorOccured;
    }

    /**
     * Apply to builder.
     * 
     * This method allow to use a
     * method of the builder to set
     * a parameter.
     * 
     * @param ErrorRegisteryInterface &$builder   The builder to use
     * @param string                  $method     The builder method to use
     * @param mixed                   $value      The value to use as method parameter
     * @param string                  $errorLabel The error string label
     * @param string                  $errorCase  An array that contain the builder last error value as key and the error label as value
     * @param CommandColorFacade      $cf         The command color facade to use to display
     * 
     * @return boolean
     */
    protected function apply2Builder(ErrorRegisteryInterface &$builder, $method, $value, $errorLabel, $errorCase, CommandColorFacade $cf)
    {
        $errorBool = $builder->$method($value);
        
        if ($errorBool) {
            return true;
        } else {
            $labelFinded = false;
            $cf->addText(" ", "error");
            $cf->addText(get_class($builder) . "::" . $method, "errorLabel");
            $cf->addText(" :\n");
            $cf->addText(" ", "error");
            $cf->addText("\t" . $errorLabel . ": ");
            
            foreach ($errorCase as $case => $label) {
                if ($builder->getLastError() == $case) {
                    $cf->addText($label . "\n");
                    $labelFinded = true;
                }
            }
            
            if (! $labelFinded) {
                $cf->addText("Undefined error\n");
            }
            
            return false;
        }
    }
}
