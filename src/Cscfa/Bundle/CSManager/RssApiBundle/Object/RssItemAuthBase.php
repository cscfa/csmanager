<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Object
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\RssApiBundle\Object;

use Cscfa\Bundle\CSManager\RssApiBundle\Interfaces\RssItemAuthInterface;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssItem;
use Cscfa\Bundle\SecurityBundle\Entity\User;

/**
 * RssItemAuthBase class.
 *
 * The RssItemAuthBase implement
 * access method to rss
 * item authorization
 * base service.
 *
 * @category Object
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
abstract class RssItemAuthBase implements RssItemAuthInterface {
    
    /**
     * Extra
     * 
     * The authorized service
     * extra information
     * 
     * @var array
     */
    protected $extra;
    
    /**
     * RssItemAuthBase constructor
     * 
     * The RssItemAuthBase default
     * constructor
     */
    public function __construct(){
        $this->extra = array();
    }
    
	/**
	 * Add
	 * 
	 * This method allow to
	 * add several informations
	 * to the authentifyer.
	 * 
	 * @param string $key   The storage key
	 * @param string $value The value to store
	 */
    public function add($key, $value) {
        $this->extra[$key] = $value;
    }

    /**
     * Dump data information
     *
     * This method return the
     * serialized data that
     * will be stored into
     * the database.
     *
     * @return string
     */
    public function dumpDataInfo() {
        return serialize(array("service"=>$this->getName(), "extra"=>$this->extra));
    }
    
	/**
	 * Parse data information
	 * 
	 * This method return the
	 * unserialized data of the
	 * stored informations.
	 * 
	 * @param RssAuthInfo $data
	 */
    public function parseDataInfo(RssAuthInfo $data) {
        if ($this->getName() !== $data->getName()) {
            throw new \Exception(
                sprintf("The information name the service '%s'. Excpected '%s'.", $data->getName(), $this->getName()),
                500,
                null
            );
        } else {
            $this->extra = $data->getExtra();
        }
    }

    /**
     * Get name
     *
     * Return the service
     * name
     *
     * @return string
     */
    abstract public function getName();
	
	/**
	 * Is authorized
	 * 
	 * Check if an item is
	 * currently allowed to
	 * be showed by the current 
	 * user.
	 * 
	 * @param RssItem $item The rss item
	 * @param User    $user The current user
	 * 
	 * @return boolean
	 */
	abstract public function isAuthorized(RssItem $item, User $user);
}
