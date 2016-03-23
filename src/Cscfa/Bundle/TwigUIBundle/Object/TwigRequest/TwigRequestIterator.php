<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Iterable
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Object\TwigRequest;

/**
 * TwigRequestIterator.
 *
 * The TwigRequestIterator store a set of TwigRequest
 * and purpose Traversable access as iterator.
 *
 * Each TwigRequest is stored into an alias
 * name key, so only one TwigRequest is allow
 * by alias.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigRequestIterator implements \Iterator
{
    /**
     * Twig requests.
     *
     * This property store a set of
     * TwigRequest instances.
     *
     * @var array
     */
    protected $twigRequests;

    /**
     * Aliases.
     *
     * This property store the existants
     * alias of the TwigRequests.
     *
     * @var array
     */
    protected $aliases = array();

    /**
     * Position.
     *
     * Tis property store the current
     * iterator position.
     *
     * @var int
     */
    protected $position;

    /**
     * Constructor.
     *
     * The default TwigRequestIterator
     * constructor.
     *
     * @param array $twigRequests A set of TwigRequest to store. The array must be
     *                            formated as [$alias=>TwigRequest]
     *
     * @example new TwigRequestIterator();
     * @example new TwigRequestIterator(array("myAwesomeTemplate"=>new TwigRequest()));
     */
    public function __construct(array $twigRequests = array())
    {
        $this->twigRequests = array();
        $this->position = 0;

        foreach ($twigRequests as $aliasName => $twigRequest) {
            $this->addTwigRequest($twigRequest, $aliasName);
        }
    }

    /**
     * Add TwigRequest.
     *
     * This method allow to store a new alias, TwigRequest couple
     * into the Iterator.
     *
     * @param TwigRequest $twigRequest The TwigRequest to store
     * @param string      $aliasName   The alias name of the TwigRequest
     *
     * @return TwigRequestIterator
     */
    public function addTwigRequest(TwigRequest $twigRequest, $aliasName)
    {
        $this->aliases[] = $aliasName;
        $this->aliases = array_unique($this->aliases);
        $this->twigRequests[$aliasName] = $twigRequest;

        return $this;
    }

    /**
     * Current.
     *
     * Return the current element
     *
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->twigRequests[$this->aliases[$this->position]];
    }

    /**
     * Next.
     *
     * Move forward to next element
     *
     * @see Iterator::next()
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Key.
     *
     * Return the key of the current element
     *
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->aliases[$this->position];
    }

    /**
     * Valid.
     *
     * Checks if current position is valid
     *
     * @see Iterator::valid()
     */
    public function valid()
    {
        return array_key_exists($this->position, $this->aliases);
    }

    /**
     * Rewind.
     *
     * Rewind the Iterator to the first element
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
