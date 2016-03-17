<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Factory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Factory\Command;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandOptionInterface;

/**
 * ColoredStringFactory class.
 *
 * The ColoredStringFactory class is used
 * to build formated colored string to
 * collect into CommandColorBuilder.
 *
 * @category Factory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ColoredStringFactory implements CommandColorInterface, CommandOptionInterface
{
    /**
     * The foreground color.
     *
     * This represent the foreground
     * color. The allowed colors are
     * stored as constant into the
     * CommandColorInterface interface.
     *
     * @var string
     */
    protected $foreground;

    /**
     * The background color.
     *
     * This represent the background
     * color. The allowed colors are
     * stored as constant into the
     * CommandColorInterface interface.
     *
     * @var string
     */
    protected $background;

    /**
     * The font style options.
     *
     * This represent the font style
     * options. The allowed options are
     * stored as constant into the
     * CommandOptionInterface interface.
     *
     * @var string
     */
    protected $option;

    /**
     * The text.
     *
     * This is the text
     * to display as colored
     * string.
     *
     * @var string
     */
    protected $text;

    /**
     * ColoredStringFactory constructor.
     *
     * This is the default ColoredStringFactory
     * constructor.
     *
     * @param string $foreground The foreground color
     * @param string $background The background color
     * @param string $option     The font style option
     * @param string $text       The text to display
     */
    public function __construct($foreground = null, $background = null, $option = null, $text = '')
    {
        $this->foreground = $foreground;
        $this->background = $background;
        $this->option = $option;
        $this->text = $text;
    }

    /**
     * Set foreground.
     *
     * This method allow to set
     * the foreground color.
     *
     * @param string $foreground The foreground color
     *
     * @return \Cscfa\Bundle\ToolboxBundle\Factory\Command\ColoredStringFactory
     */
    public function setForeground($foreground)
    {
        $this->foreground = $foreground;

        return $this;
    }

    /**
     * Set background.
     *
     * This method allow to set
     * the background color.
     *
     * @param string $background The backgound color
     *
     * @return \Cscfa\Bundle\ToolboxBundle\Factory\Command\ColoredStringFactory
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Set option.
     *
     * This method allow to set
     * the font style option.
     *
     * @param string $option The font style option
     *
     * @return \Cscfa\Bundle\ToolboxBundle\Factory\Command\ColoredStringFactory
     */
    public function setOption($option)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Set text.
     *
     * This method allow to set
     * the text to display.
     *
     * @param string $text The text to display
     *
     * @return \Cscfa\Bundle\ToolboxBundle\Factory\Command\ColoredStringFactory
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get foreground.
     *
     * This method allow to
     * get the foreground color.
     *
     * @return string
     */
    public function getForeground()
    {
        return $this->foreground;
    }

    /**
     * Get background.
     *
     * This method allow to
     * get the background color.
     *
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Get option.
     *
     * This method allow to get
     * the font style option.
     *
     * @return string
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Get text.
     *
     * This method allow to get
     * the text to display.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get string.
     *
     * This method allow to get
     * the computed string.
     *
     * @return string
     */
    public function getString()
    {
        return $this->__toString();
    }

    /**
     * to string.
     *
     * This method compute the
     * parameters to create the
     * colored string.
     *
     * @return string
     */
    public function __toString()
    {
        $start = '';
        $end = '';

        if ($this->foreground !== null || $this->background !== null || $this->option !== null) {
            $start = '<';
            $end = '</';

            $foreground = ($this->foreground !== null ? 'fg='.$this->foreground : '');
            if ($foreground !== '') {
                $start .= $foreground.($this->background !== null || $this->option !== null ? ';' : '');
                $end .= $foreground.($this->background !== null || $this->option !== null ? ';' : '');
            }

            $background = ($this->background !== null ? 'bg='.$this->background : '');
            if ($background !== '') {
                $start .= $background.($this->option !== null ? ';' : '');
                $end .= $background.($this->option !== null ? ';' : '');
            }

            $options = ($this->option !== null ? 'options='.$this->option : '');
            if ($options !== '') {
                $start .= $options;
                $end .= $options;
            }

            $start .= '>';
            $end .= '>';
        }

        return $start.$this->text.$end;
    }
}
