<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\BaseInterface\Command;

/**
 * CommandOptionInterface interface.
 *
 * The CommandOptionInterface interface
 * is used to store the available
 * command color.
 *
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface CommandOptionInterface
{

    /**
     * A font style option.
     * 
     * The bold font size option.
     * 
     * @var string
     */
    const BOLD = "bold";

    /**
     * A font style option.
     * 
     * The underscore font size option.
     * 
     * @var string
     */
    const UNDERSCORE = "underscore";

    /**
     * A font style option.
     * 
     * The blink font size option.
     * 
     * @var string
     */
    const BLINK = "blink";

    /**
     * A font style option.
     * 
     * The reverse font size option.
     * 
     * @var string
     */
    const REVERSE = "reverse";

    /**
     * A font style option.
     * 
     * The conceal font size option.
     * 
     * @var string
     */
    const CONCEAL = "conceal";
}