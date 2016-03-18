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

use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandColorBuilder;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CommandColorFacade class.
 *
 * The CommandColorFacade class is an utility
 * tool to use CommandColorBuilder.
 *
 * @category Facade
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class CommandColorFacade
{
    /**
     * The colors.
     *
     * This is the array
     * of color.
     *
     * @var array
     */
    protected $colors;

    /**
     * The builder.
     *
     * This is the CommandColorBuilder
     * that is used to register colored
     * string and display.
     *
     * @var CommandColorBuilder
     */
    protected $builder;

    /**
     * CommandColorFacade constructor.
     *
     * This is the default CommandColorFacade
     * constructor that initialize the instance.
     *
     * @param OutputInterface     $output  The OutputInterface that is used to create the CommandColorBuilder
     * @param array               $colors  The array of colors
     * @param CommandColorBuilder $builder The CommandColorBuilder that is used to register colored string and display
     */
    public function __construct(OutputInterface $output, array $colors = array(), CommandColorBuilder $builder = null)
    {
        if (!isset($colors['default'])) {
            $colors['default'] = array(
                null,
                null,
                null,
            );
        }
        $this->colors = $colors;

        if ($builder === null) {
            $this->builder = new CommandColorBuilder($output);
        } else {
            $this->builder = $builder;
        }
    }

    /**
     * Add color.
     *
     * This method allow to add a
     * color into the stored color
     * array.
     *
     * @param string $name       The name to to register the color
     * @param string $foreground The foreground color
     * @param string $background The background color
     * @param string $option     The font style option
     *
     * @return \Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade
     */
    public function addColor($name, $foreground = null, $background = null, $option = null)
    {
        $this->colors[$name] = array(
            $foreground,
            $background,
            $option,
        );

        return $this;
    }

    /**
     * Has color.
     *
     * Check if a given color
     * already exist into the
     * stored array of color.
     *
     * @param string $name the color name to check
     *
     * @return bool
     */
    public function hasColor($name)
    {
        return isset($this->colors[$name]);
    }

    /**
     * Remove color.
     *
     * This method allow to
     * remove a color from the
     * stored color array.
     *
     * @param string $name The indexed name of the color to remove
     */
    public function removeColor($name)
    {
        if ($this->hasColor($name)) {
            unset($this->colors[$name]);
        }
    }

    /**
     * Get color.
     *
     * Return a stored color
     * register by it's name
     * or null if desn't exist.
     *
     * @param string $name The indexed name of the color to get
     *
     * @return array|null
     */
    public function getColor($name)
    {
        if ($this->hasColor($name)) {
            return $this->colors[$name];
        } else {
            return;
        }
    }

    /**
     * Add text.
     *
     * This method allow to
     * add a colored string.
     *
     * @param string $text  The text string to inject
     * @param string $color The color to assign
     *
     * @return bool
     */
    public function addText($text, $color = 'default')
    {
        $color = $this->getColor($color);
        if ($color !== null) {
            list($foreground, $background, $option) = $color;
            $this->builder->addColoredString($text, $foreground, $background, $option);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Write.
     *
     * This method allow to display the
     * current string.
     *
     * When the string is displayed,
     * the current contained text is
     * cleared. It's possible to desable
     * the clearing by passing false as
     * second parameter.
     *
     * The first parameter allow
     * to add a text glue inserted
     * between each string piece.
     *
     * @param string $glue  The glue to inject
     * @param string $clear The post clearing status
     */
    public function write($glue = '', $clear = true)
    {
        $this->builder->write($glue, $clear);
    }

    /**
     * Get string.
     *
     * This method allow to get
     * the computed string.
     *
     * @param string $glue The glue to inject
     *
     * @return string
     */
    public function getString($glue = '')
    {
        return $this->builder->getString($glue);
    }

    /**
     * Clear.
     *
     * Remove all elements from
     * the text set.
     */
    public function clear()
    {
        $this->builder->clear();
    }
}
