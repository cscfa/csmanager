<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
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

namespace Cscfa\Bundle\TwigUIBundle\Object\TwigHierarchy;

use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;

/**
 * TwigHierarchy.
 *
 * The TwigHierarchy is used to manage
 * the creation location of the twig
 * requests.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigHierarchy
{
    /**
     * Registry.
     *
     * This property store the twig request
     * registry to be managed.
     *
     * @var TwigRequestIterator
     */
    protected $registry;

    /**
     * @var TwigRequest
     */
    protected $currentParent;

    /**
     * @var TwigRequest
     */
    protected $current;

    /**
     * Set main registry.
     *
     * This method allow to register
     * the TwigRequest registry.
     *
     * @param TwigRequest $registry The TwigRequest registry object
     *
     * @return TwigHierarchy
     */
    public function setMainRegistry(TwigRequestIterator $registry)
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * Get main registry.
     *
     * This method return the
     * TwigRequest registry.
     *
     * @return TwigRequestIterator
     */
    public function getMainRegistry()
    {
        return $this->registry;
    }

    /**
     * Has parent.
     *
     * This method return true if a parent
     * is currently defined into the current
     * hierarchy verticality. If not, return
     * false.
     *
     * @return bool
     */
    public function hasParent()
    {
        return $this->currentParent !== null;
    }

    /**
     * Start hierachy.
     *
     * This method allow to start
     * a new hierarchy verticality.
     *
     * @return TwigHierarchy
     */
    public function startHierarchy()
    {
        $this->currentParent = $this->current = null;

        return $this;
    }

    /**
     * Down step.
     *
     * This method inform that the system
     * take the current hierarchy verticality
     * down by one step.
     *
     * @return TwigHierarchy
     */
    public function downStep()
    {
        if ($this->current !== null) {
            $this->currentParent = $this->current;
            $this->current = null;
        }

        return $this;
    }

    /**
     * Register.
     *
     * This method inject a TwigRequest
     * into the current hierarchy.
     *
     * @param TwigRequest $twigRequest The TwigRequest to store
     * @param string      $aliasName   The alias name of the TwigRequest
     *
     * @return TwigHierarchy
     */
    public function register(TwigRequest $request, $alias)
    {
        if ($this->hasParent()) {
            $this->currentParent->getChilds()->addTwigRequest($request, $alias);
        } else {
            $this->registry->addTwigRequest($request, $alias);
        }

        $this->current = $request;

        return $this;
    }
}
