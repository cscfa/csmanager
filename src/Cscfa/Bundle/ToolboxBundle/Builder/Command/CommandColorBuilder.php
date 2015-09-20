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
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandOptionInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface;
use Cscfa\Bundle\ToolboxBundle\Factory\Command\ColoredStringFactory;

/**
 * CommandColorBuilder class.
 *
 * The CommandColorBuilder class is used
 * to build formated colored string to 
 * display behind a CommandFacade instance.
 *
 * @category Builder
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CommandColorBuilder implements CommandColorInterface, CommandOptionInterface, SetInterface
{

    /**
     * The output
     * 
     * This is the OutputInterface
     * coming from the command.
     * 
     * @var OutputInterface
     */
    protected $output;

    /**
     * The colored string array
     * 
     * This is a colored string
     * factory array.
     * 
     * @var array
     */
    protected $coloredStrings;

    /**
     * CommandColorBuilder constructor.
     * 
     * This is the default CommandColorBuilder
     * constructor. It register an OutputInterface
     * that allow to display strings.
     * 
     * @param OutputInterface $output        The output interface
     * @param array           $coloredString The started colored string array
     */
    public function __construct(OutputInterface $output, array $coloredString = array())
    {
        $this->output = $output;
        $this->coloredStrings = $coloredString;
    }

    /**
     * Add a colored string
     * 
     * This method allow to add a
     * colored string factory.
     * 
     * @param string|null $text The text to display
     * @param string|null $fg   The foreground color
     * @param string|null $bg   The background color
     * @param string|null $op   The font style option
     * 
     * @return void
     */
    public function addColoredString($text, $fg = null, $bg = null, $op = null)
    {
        $this->add(new ColoredStringFactory($fg, $bg, $op, $text));
    }

    /**
     * Add
     *
     * add an element to the set.
     *
     * @param mixed $element The element to add
     *
     * @return void
     */
    public function add($element)
    {
        $this->coloredStrings[] = $element;
    }

    /**
     * Add all
     *
     * add an array of elements
     * to the set.
     *
     * @param array $elements The array of elements to add
     *
     * @return void
     */
    public function addAll(array $elements)
    {
        foreach ($elements as $element) {
            if (! $this->contain($element)) {
                $this->add($element);
            }
        }
    }

    /**
     * Clear
     *
     * Remove all elements from
     * this set.
     *
     * @return void
     */
    public function clear()
    {
        $this->coloredStrings = array();
    }

    /**
     * Contain
     *
     * Check if the set contain
     * a specified element.
     *
     * @param mixed $element The element to check
     *
     * @return boolean
     */
    public function contain($element)
    {
        return in_array($element, $this->coloredStrings);
    }

    /**
     * Contain all
     *
     * Check if the set contain all
     * of the elements of an other set.
     *
     * @param array $elements The element array to check
     *
     * @return boolean
     */
    public function containsAll(array $elements)
    {
        foreach ($elements as $element) {
            if (! $this->contain($element)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Is empty
     *
     * Check if the set is
     * empty.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->coloredStrings);
    }

    /**
     * Remove
     *
     * Remove an element from
     * the set and return it.
     *
     * @param mixed $element The element to remove
     *
     * @return void
     */
    public function remove($element)
    {
        if ($this->contain($element)) {
            $key = array_search($element, $this->coloredStrings);
            unset($this->coloredStrings[$key]);
        }
    }

    /**
     * Remove all
     *
     * Remove all contained elements
     * by a set from the current set.
     *
     * @param array $elements The element array to remove
     * 
     * @return void
     */
    public function removeAll(array $elements)
    {
        foreach ($elements as $element) {
            $this->remove($element);
        }
    }

    /**
     * Get
     *
     * Get an element from
     * the set.
     *
     * @param mixed $element The element to get
     *
     * @return mixed
     */
    public function get($element)
    {
        if ($this->contain($element)) {
            $key = array_search($element, $this->coloredStrings);
            return $this->coloredStrings[$key];
        } else {
            return null;
        }
    }

    /**
     * Get all
     *
     * Get all of the set
     * contained elements.
     *
     * @return array
     */
    public function getAll()
    {
        return array_merge($this->coloredStrings);
    }

    /**
     * Get string.
     *
     * This method allow to get
     * the computed string.
     *
     * @param string $glue The glue as string to use between each elements
     *
     * @return string
     */
    public function getString($glue = '')
    {
        $result = "";
        
        foreach ($this->coloredStrings as $key => $coloredString) {
            if ($coloredString instanceof ColoredStringFactory) {
                if ($key > 0) {
                    $result .= $glue;
                }
                
                $result .= $coloredString->getString();
            } else if (is_string($coloredString)) {
                if ($key > 0) {
                    $result .= $glue;
                }
                
                $result .= $coloredString;
            }
        }
        
        return $result;
    }

    /**
     * to string.
     * 
     * This method compute the
     * strings to create the
     * complete colored string.
     * 
     * @return string
     */
    public function __toString()
    {
        $result = "";
        
        foreach ($this->coloredStrings as $coloredString) {
            if ($coloredString instanceof ColoredStringFactory) {
                $result .= $coloredString->getString();
            } else if (is_string($coloredString)) {
                $result .= $coloredString;
            }
        }
        
        return $result;
    }

    /**
     * Write
     * 
     * This method allow to display the
     * current string. It use the
     * output writeln method.
     * 
     * When the string is displayed,
     * the current instance is cleared.
     * It's possible to desable the
     * clearing by passing false as
     * second parameter.
     * 
     * The first parameter allow
     * to add a text glue inserted
     * between each string piece.
     * 
     * @param string $glue  The glue to inject
     * @param string $clear The post clearing status
     * 
     * @return void
     */
    public function write($glue = '', $clear = true)
    {
        if ($this->isEmpty()) {
            return;
        }
        
        $this->output->writeln($this->getString($glue));
        
        if ($clear) {
            $this->clear();
        }
    }
}
