<?php
/**
 * This file is a part of CSCFA TwigUi project.
 * 
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Singleton
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\TwigUIBundle\Element\Singleton;

/**
 * IdGenerator class.
 *
 * The IdGenerator class
 * is used to generate
 * unique id.
 *
 * @category Singleton
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
final class IdGenerator
{
    /**
     * The static id.
     * 
     * This property indicate the
     * base id.
     * 
     * @var integer
     */
    static $id = 0x0;
    
    /**
     * Get id.
     * 
     * This method return a
     * unique generated id.
     * 
     * @return string
     */
    static function getId()
    {
        self::$id += 0x1;
        
        return "idg_".dechex(self::$id);
    }
}
