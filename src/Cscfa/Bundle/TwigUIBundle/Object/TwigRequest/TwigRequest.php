<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Object\TwigRequest;

/**
 * TwigRequest.
 *
 * The TwigRequest store a twig template
 * path and it's variables.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigRequest
{
    /**
     * Twig path.
     *
     * This property register the path of
     * the twig template to render.
     *
     * @var string
     */
    protected $twigPath;

    /**
     * Twig arguments.
     *
     * This property register a list of
     * variables to pass to the twig
     * template rendering process.
     *
     * @var array
     */
    protected $twigArguments;

    /**
     * Childs requests.
     *
     * This property register
     * the twig requests childs
     * of the current element.
     *
     * @var TwigRequestIterator
     */
    protected $childsRequests;

    /**
     * Constructor.
     *
     * The default TwigRequest constructor.
     */
    public function __construct($twigPath = null, array $twigArguments = array(), array $childs = array())
    {
        $this->setTwigPath($twigPath);
        $this->setArguments($twigArguments);

        $this->childsRequests = new TwigRequestIterator();
        foreach ($childs as $alias => $request) {
            $this->addChildRequest($request, $alias);
        }
    }

    /**
     * Add ChildRequest.
     *
     * This method allow to store a new alias, TwigRequest couple
     * into the child Iterator.
     *
     * @param TwigRequest $twigRequest The TwigRequest to store
     * @param string      $aliasName   The alias name of the TwigRequest
     *
     * @return TwigRequest
     */
    public function addChildRequest(TwigRequest $twigRequest, $aliasName)
    {
        $this->childsRequests->addTwigRequest($twigRequest, $aliasName);

        return $this;
    }

    /**
     * Get childs.
     *
     * This method return the TwigRequest childs.
     *
     * @return TwigRequestIterator
     */
    public function getChilds()
    {
        return $this->childsRequests;
    }

    /**
     * Get twig path.
     *
     * This method return the twig template
     * path that want to be rendered.
     *
     * @return string
     */
    public function getTwigPath()
    {
        return $this->twigPath;
    }

    /**
     * Set twig path.
     *
     * This method allow to set the twig
     * template path that want to be rendered.
     *
     * @param string $twigPath The twig template path
     *
     * @throws \Exception The setTwigPath throw exception code 500
     *                    if the given path is not string or null
     *
     * @return TwigRequest
     */
    public function setTwigPath($twigPath)
    {
        if (is_string($twigPath) || $twigPath === null) {
            $this->twigPath = $twigPath;
        } else {
            throw new \Exception(
                'Twig template path must be a string',
                500
            );
        }

        return $this;
    }

    /**
     * Get arguments.
     *
     * This method return the twig arguments
     * to pass to the twig template on rendering.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->twigArguments;
    }

    /**
     * Set arguments.
     *
     * This method allow to clear and hydrate
     * the twig arguments.
     *
     * @param array $twigArguments The new twig template arguments
     *
     * @return TwigRequest
     */
    public function setArguments(array $twigArguments)
    {
        $this->clearArguments();
        foreach ($twigArguments as $argName => $value) {
            $this->addArgument($argName, $value);
        }

        return $this;
    }

    /**
     * Clear arguments.
     *
     * This method allow to clear the twig template arguments.
     *
     * @return TwigRequest
     */
    protected function clearArguments()
    {
        $this->twigArguments = array();

        return $this;
    }

    /**
     * Add argument.
     *
     * This method allow to inject a new argument
     * into the twig template rendering arguments
     * list.
     *
     * @param string $argName The argument name
     * @param mixed  $value   The value of the variable
     *
     * @return TwigRequest
     */
    public function addArgument($argName, $value)
    {
        if (!is_string($argName)) {
            throw new \Exception(
                'Twig variable name must be passed as string',
                500
            );
        }
        $this->twigArguments[$argName] = $value;

        return $this;
    }

    /**
     * Has argument.
     *
     * This method return true if the given argument
     * name already exist into the stored arguments.
     *
     * @param string $argName The argument name
     *
     * @return bool
     */
    public function hasArgument($argName)
    {
        return array_key_exists($argName, $this->twigArguments);
    }

    /**
     * Rem argument.
     *
     * This method remove an argument from the template
     * rendering argument lists.
     *
     * @param string $argName The argument name
     *
     * @return TwigRequest
     */
    public function remArgument($argName)
    {
        if ($this->hasArgument($argName)) {
            unset($this->twigArguments[$argName]);
        }

        return $this;
    }
}
