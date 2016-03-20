<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Facade
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Facade\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\Exception\Type\UnexpectedTypeException;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandColorBuilder;
use Cscfa\Bundle\ToolboxBundle\Converter\Command\CommandTypeConverter;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\Converter\Reflective\ReflectionTool;
use Cscfa\Bundle\ToolboxBundle\Type\Command\DebugingSubtype;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Event\PreProcessEventInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Event\PostProcessEventInterface;

/**
 * CommandFacade class.
 *
 * The CommandFacade class is an utility
 * tool to use command.
 *
 * @category Facade
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
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
     *
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
                throw new UnexpectedTypeException(
                    'The selected type is unknow.',
                    404,
                    null,
                    array(
                    'out.ask_confirmation',
                    'out.ask',
                    'out.select',
                    )
                );
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
     * @return bool
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
            return $dialog->ask(
                $this->output,
                $builder->getQuestion(),
                $builder->getDefault(),
                $builder->getCompletion()
            );
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

        $default = $builder->getDefault();
        $limit = $builder->getLimite();
        $index = count($limit);
        $limit[] = CommandTypeConverter::convertToString($default);
        $builder->setDefault($index);
        $builder->setLimite($limit);

        $result = $dialog->select(
            $this->output,
            $builder->getQuestion(),
            $builder->getLimite(),
            $builder->getDefault(),
            false,
            'Value "%s" is invalid',
            ($builder->getOptions() & CommandAskBuilder::OPTION_ASK_MULTI_SELECT)
        );

        if ((is_array($result) && count($result) == 1 && in_array($index, $result)) || $result == $index) {
            return $default;
        } else {
            return $result;
        }
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
        return $this->command->getHelperSet()->get('dialog');
    }

    /**
     * Get the progress helper.
     *
     * This method allow to
     * get the progress helper
     * from the command.
     *
     * @return \Symfony\Component\Console\Helper\ProgressHelper
     */
    protected function getProgress()
    {
        return $this->command->getHelperSet()->get('progress');
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
            try {
                $result = $this->input->getOption($name);
            } catch (\InvalidArgumentException $e) {
                $result = false;
            }
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
     * @return null|mixed
     */
    public function getOrAskMulti($params)
    {
        if (is_string($params)) {
            $params = json_decode($params, true);
        }

        if (!is_array($params)) {
            return;
        }

        $defaultArray = array(
            'var' => '',
            'type' => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
            'question' => null,
            'default' => null,
            'option' => null,
            'completion' => null,
            'limit' => null,
            'extra' => array(
                'empty' => true,
                'default' => true,
                'active' => true,
                'unactive' => null,
                'transform' => function ($param) {
                    return $param;
                },
            ),
        );

        $results = array();
        foreach ($params as $value) {
            $parameters = array_merge($defaultArray, $value);

            if (isset($value['extra'])) {
                $parameters['extra'] = array_merge($defaultArray['extra'], $value['extra']);
            } else {
                $parameters['extra'] = $defaultArray['extra'];
            }

            if (!$parameters['extra']['active']) {
                $results[] = $parameters['extra']['unactive'];
                continue;
            }

            $question = new CommandColorFacade($this->output);
            $question->addColor('def', CommandColorBuilder::GREEN, null, null);
            $question->addText($parameters['question']);

            if ($parameters['extra']['default']) {
                $question->addText(' [');
                $question->addText(CommandTypeConverter::convertToString($parameters['default']), 'def');
                $question->addText('] ');
            }

            $question->addText(': ');
            $parameters['question'] = $question->getString();

            $askBuilder = new CommandAskBuilder();
            $askBuilder->setType($parameters['type'])
                ->setQuestion($parameters['question'])
                ->setDefault($parameters['default'])
                ->setOptions($parameters['option'])
                ->setCompletion($parameters['completion'])
                ->setLimite($parameters['limit']);

            $skip = false;
            $result = null;

            while (!$skip) {
                $result = $this->getOrAsk($parameters['var'], $askBuilder);

                if (!$parameters['extra']['empty'] && (empty($result) || is_bool($result))) {
                    $skip = false;
                } else {
                    $skip = true;
                }
            }

            $results[] = $parameters['extra']['transform']($result);
        }

        return $results;
    }

    /**
     * Get confirmation.
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
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getConfirmation($values)
    {
        $colorOutput = new CommandColorFacade($this->output);

        $colorOutput->addColor('default', 'green', null, 'bold')
            ->addColor('value', 'black', 'cyan', null)
            ->addColor('ask', null, null, null);

        foreach ($values as $text => $value) {
            $value = CommandTypeConverter::convertToString($value);

            $colorOutput->addText("\n".$text.' : ');
            $colorOutput->addText($value, 'value');
        }

        $colorOutput->write();

        $askBuilder = new CommandAskBuilder();

        $askBuilder->setDefault(true)->setQuestion('Did you confirm [<fg=green>yes</fg=green>] : ');

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
     * @param bool                    $verbose  A boolean that enable or desable the automatic message display
     *
     * @return bool
     */
    public function applyAndValidate(ErrorRegisteryInterface &$builder, $params, $error, $success, $verbose = true)
    {
        if (is_string($params)) {
            $params = json_decode($params, true);
        }

        if (!is_array($params)) {
            return false;
        }

        $errorOccured = false;
        $outputColor = new CommandColorFacade($this->output);
        $outputColor->addColor('error', CommandColorInterface::BLACK, CommandColorInterface::RED, null);
        $outputColor->addColor('errorLabel', CommandColorInterface::BLUE, null, CommandColorBuilder::BOLD);
        $outputColor->addColor('success', CommandColorInterface::BLACK, CommandColorInterface::GREEN, null);
        $outputColor->addColor('default', null, null, null);

        $outputColor->addText("\n");
        $outputColor->addText("\n$error.\n", 'error');
        $outputColor->addText("\n");

        foreach ($params as $method => $param) {
            list($value, $errorLabel, $errorCase) = $param;

            if (is_array($value)) {
                foreach ($value as $valueElement) {
                    try {
                        if (!$this->apply2Builder(
                            $builder,
                            $method,
                            $valueElement,
                            $errorLabel,
                            $errorCase,
                            $outputColor
                        )
                        ) {
                            $errorOccured = true;
                        }
                    } catch (\Exception $e) {
                        $outputColor->addText(' ', 'error');
                        $outputColor->addText(get_class($builder).'::'.$method, 'errorLabel');
                        $outputColor->addText(" :\n");
                        $outputColor->addText(' ', 'error');
                        $outputColor->addText("\t".$errorLabel.': ');
                        $outputColor->addText($e->getMessage()."\n");
                        $errorOccured = true;
                    }
                }
            } else {
                try {
                    if (!$this->apply2Builder($builder, $method, $value, $errorLabel, $errorCase, $outputColor)) {
                        $errorOccured = true;
                    }
                } catch (\Exception $e) {
                    $outputColor->addText(' ', 'error');
                    $outputColor->addText(get_class($builder).'::'.$method, 'errorLabel');
                    $outputColor->addText(" :\n");
                    $outputColor->addText(' ', 'error');
                    $outputColor->addText("\t".$errorLabel.': ');
                    $outputColor->addText($e->getMessage()."\n");
                    $errorOccured = true;
                }
            }
        }

        if ($errorOccured && $verbose) {
            $outputColor->addText("\n");
            $outputColor->write();
        } else {
            $outputColor->clear();
            $outputColor->addText("\n");
            $outputColor->addText("\n$success.\n", 'success');
            $outputColor->addText("\n");
            $outputColor->write();
        }

        return !$errorOccured;
    }

    /**
     * Apply to builder.
     *
     * This method allow to use a
     * method of the builder to set
     * a parameter.
     *
     * @param ErrorRegisteryInterface &$builder    The builder to use
     * @param string                  $method      The builder method to use
     * @param mixed                   $value       The value to use as method parameter
     * @param string                  $errorLabel  The error string label
     * @param string                  $errorCase   An array that contain the builder last error
     *                                             value as key and the error label as value
     * @param CommandColorFacade      $outputColor The command color facade to use to display
     *
     * @return bool
     */
    protected function apply2Builder(
        ErrorRegisteryInterface &$builder,
        $method,
        $value,
        $errorLabel,
        $errorCase,
        CommandColorFacade $outputColor
    ) {
        $errorBool = $builder->$method($value);

        if ($errorBool) {
            return true;
        } else {
            $labelFinded = false;
            $outputColor->addText(' ', 'error');
            $outputColor->addText(get_class($builder).'::'.$method, 'errorLabel');
            $outputColor->addText(" :\n");
            $outputColor->addText(' ', 'error');
            $outputColor->addText("\t".$errorLabel.': ');

            foreach ($errorCase as $case => $label) {
                if ($builder->getLastError() == $case) {
                    $outputColor->addText($label."\n");
                    $labelFinded = true;
                }
            }

            if (!$labelFinded) {
                $outputColor->addText("Undefined error\n");
            }

            return false;
        }
    }

    /**
     * Ask and apply to while not finish into a limit.
     *
     * This method allow to get a set of values from the
     * user.
     *
     * In this process, the user can use an interactive shell
     * to navigate between each element of the set.
     *
     * For each of this elements, the shell will provide a
     * question.
     *
     * The $to parameter allow to give an ErrorRegisteryInterface
     * that will be hydrated.
     *
     * The $stopValue parameter allow to give a value to display
     * as stop element to quit the selection menu.
     *
     * The $whileQuestion parameter allow to give a value that
     * will be displayed for each selection menu occurence.
     *
     * The $param parameter allow to give the specific configuration
     * of the menu. This parameter must be an associative array that
     * specify each ErrorRegisteryInterface parameters allowed to set
     * as array. The value of this array are an associative array
     * builded with the following values :
     * <ul>
     *      <li>
     *          <h4>preProcess :</h4>
     *          <p>
     *              a php function that is used before the question construction.
     *              This function take two parameters that are the current array and the current
     *              CommandFacade instance. Also, this function <strong>must</strong> return the
     *              current array.
     *          </p>
     *          <aside>
     *              <cite>Optional value :</cite> can be pull out if useless.
     *          </aside>
     *      </li>
     *      <li>
     *          <h4>ask :</h4>
     *          <p>
     *              an associative array that represent how to build the internal element question.
     *              It will be build with the following values :
     *              <ul>
     *                  <li>type: the question type as CommandAskBuilder constant</li>
     *                  <li>question: the question to display as string</li>
     *                  <li>default: the default value to return if the user answer nothing</li>
     *                  <li>completion: the completion array that assist the user</li>
     *                  <li>limit: the select case array of answers</li>
     *                  <li>option: the CommandAskBuilder options</li>
     *              </ul>
     *          </p>
     *      </li>
     *      <li>
     *          <h4>active :</h4>
     *          <p>
     *              a boolean value that represent the state of the process to run. If it set to
     *              false, the question will not be displayed. If set to true, the question will
     *              be processed.
     *
     *              If this value is set to false when the $param parameter is passed, the concerned
     *              index will be not displayed in the principal menu.
     *
     *              Note if this value is set to true before the $param parameter is passed, it can
     *              be desable by the preProcess function.
     *
     *              Also, note that the preProcess function can enable an active parameter set to
     *              false and process to the question hydratation when the user select the concerned
     *              index into the main menu.
     *          </p>
     *          <aside>
     *              <cite>Optional value :</cite> can be pull out if useless. Set true as default.
     *          </aside>
     *      </li>
     *      <li>
     *          <h4>success :</h4>
     *          <p>
     *              a string that will be displayed when a value is successfull setted into the
     *              ErrorRegisteryInterface.
     *          </p>
     *          <aside>
     *              <cite>Optional value :</cite> can be pull out if useless. Set true as default.
     *          </aside>
     *      </li>
     *      <li>
     *          <h4>failure :</h4>
     *          <p>
     *              a string that will be displayed when a value is unsuccessfull setted into the
     *              ErrorRegisteryInterface.
     *          </p>
     *          <aside>
     *              <cite>Optional value :</cite> can be pull out if useless. Set true as default.
     *          </aside>
     *      </li>
     *      <li>
     *          <h4>postProcess :</h4>
     *          <p>
     *              a php function that will be called between the user answering and the builder
     *              setter. This function will take five parameters that are first the result,
     *              the builder, the current configuration array, the current CommandFacade
     *              instance and finaly the current CommandColorFacade.
     *
     *              This function <strong>must</strong> return the processed result.
     *          </p>
     *          <aside>
     *              <cite>Optional value :</cite> can be pull out if useless.
     *          </aside>
     *      </li>
     * </ul>
     *
     * @param ErrorRegisteryInterface $errorBuilder  The builder to affect
     * @param mixed                   $stopValue     The value that will quit the main menu
     * @param string                  $whileQuestion The question to display on top of the main menu
     * @param array                   $param         An associative array that will configure the method
     *
     * @return ErrorRegisteryInterface
     */
    public function askATWIL(ErrorRegisteryInterface &$errorBuilder, $stopValue, $whileQuestion, array $param)
    {
        $reflex = new ReflectionTool($errorBuilder);
        $methods = $reflex->getSettable();
        $options = array_keys($methods);

        foreach ($options as $key => $value) {
            if (!array_key_exists($value, $param)) {
                unset($options[$key]);
            } elseif (isset($param[$value]['active']) && $param[$value]['active'] === false) {
                unset($options[$key]);
            }
        }

        if (!empty($options)) {
            while (true) {
                $outputColor = new CommandColorFacade($this->output);
                $outputColor->addColor(
                    'success',
                    CommandColorInterface::BLACK,
                    CommandColorInterface::GREEN,
                    null
                )->addColor(
                    'failure',
                    CommandColorInterface::BLACK,
                    CommandColorInterface::RED,
                    null
                );

                $ask = new CommandAskBuilder();
                $ask->setType(CommandAskBuilder::TYPE_ASK_SELECT)
                    ->setLimite($options)
                    ->setDefault($stopValue)
                    ->setQuestion($whileQuestion);

                $choice = $this->ask($ask);

                if ($choice == $stopValue) {
                    break;
                } else {
                    $method = $methods[$options[$choice]];
                    $paramOption = $param[$options[$choice]];

                    if (isset($paramOption['preProcess'])) {
                        if ($paramOption['preProcess'] instanceof PreProcessEventInterface) {
                            $paramOption = $paramOption['preProcess']->preProcess($paramOption, $this);
                        }
                    }
                    if (isset($paramOption['ask']) &&
                        (
                            (isset($paramOption['active']) && $paramOption['active']) ||
                            !isset($paramOption['active'])
                        )
                    ) {
                        $defaultAsk = array(
                            'type' => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
                            'question' => null,
                            'default' => null,
                            'completion' => null,
                            'limit' => null,
                            'option' => null,
                        );

                        $ask = array_merge($defaultAsk, $paramOption['ask']);
                        $builder = new CommandAskBuilder();
                        $builder->setType($ask['type'])
                            ->setQuestion($ask['question'])
                            ->setDefault($ask['default'])
                            ->setCompletion($ask['completion'])
                            ->setLimite($ask['limit'])
                            ->setOptions($ask['option']);

                        $result = $this->ask($builder);
                    }
                    if (isset($paramOption['postProcess']) &&
                        (
                            (isset($paramOption['active']) && $paramOption['active']) ||
                            !isset($paramOption['active'])
                        )
                    ) {
                        if ($paramOption['postProcess'] instanceof PostProcessEventInterface) {
                            $result = $paramOption['postProcess']
                                ->postProcess($result, $errorBuilder, $paramOption, $this, $outputColor);
                        }
                    }
                    if ((isset($paramOption['active']) && $paramOption['active']) || !isset($paramOption['active'])) {
                        if ($this->apply2Builder($errorBuilder, $method, $result, '', array(), $outputColor)) {
                            if (isset($paramOption['success'])) {
                                $outputColor->clear();
                                $outputColor->addText("\n");
                                $outputColor->addText($paramOption['success'], 'success');
                                $outputColor->addText("\n");
                                $outputColor->write();
                            }
                        } else {
                            if (isset($paramOption['failure'])) {
                                $outputColor->clear();
                                $outputColor->addText("\n");
                                $outputColor->addText($paramOption['failure'], 'failure');
                                $outputColor->addText("\n");
                                $outputColor->write();
                            }
                        }
                    }
                }
            }
        }

        return $errorBuilder;
    }

    /**
     * Debug multiple instances.
     *
     * This method allow to check
     * if an array of instance
     * are in an error state
     * by testing a set of property.
     *
     * @param array  $values   The values set to test
     * @param array  $selector The selector definition that contain the target and the test class
     * @param array  $label    The label set
     * @param string $success  The success string to display if the test success
     * @param string $error    The success string to display if the test fail
     *
     * @return array Contain as first index the row set and the error count as second
     */
    public function debugMulti(array $values, array $selector, array $label, $success, $error)
    {
        $progress = $this->getProgress();
        $progress->start($this->output, count($values));

        $result = array();

        foreach ($values as $value) {
            $result[] = new DebugingSubtype($value, $selector, $label, $success, $error);

            $progress->advance();
        }
        $progress->finish();

        $rows = array();
        $error = 0;
        foreach ($result as $r) {
            $rows[] = $r->getRow();
            $error += $r->getErrorCount();
        }

        return array(
            $rows,
            $error,
        );
    }
}
