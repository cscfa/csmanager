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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\BaseInterface\Command;

/**
 * CommandColorInterface interface.
 *
 * The CommandColorInterface interface
 * is used to store the available
 * command color.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface CommandColorInterface
{
    /**
     * A color.
     *
     * The black color.
     *
     * @var string
     */
    const BLACK = 'black';

    /**
     * A color.
     *
     * The red color.
     *
     * @var string
     */
    const RED = 'red';

    /**
     * A color.
     *
     * The green color.
     *
     * @var string
     */
    const GREEN = 'green';

    /**
     * A color.
     *
     * The yellow color.
     *
     * @var string
     */
    const YELLOW = 'yellow';

    /**
     * A color.
     *
     * The blue color.
     *
     * @var string
     */
    const BLUE = 'blue';

    /**
     * A color.
     *
     * The magenta color.
     *
     * @var string
     */
    const MAGENTA = 'magenta';

    /**
     * A color.
     *
     * The cyan color.
     *
     * @var string
     */
    const CYAN = 'cyan';

    /**
     * A color.
     *
     * The white color.
     *
     * @var string
     */
    const WHITE = 'white';
}
