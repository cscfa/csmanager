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

/**
 * RssAuthInfo class.
 *
 * The RssAuthInfo implement
 * access method to rss 
 * item authorization
 * service information.
 *
 * @category Object
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RssAuthInfo {
    
    /**
     * Name
     * 
     * The service name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Extra
     * 
     * The service extra
     * information
     * 
     * @var array
     */
    protected $extra;
    
    /**
     * RssAuthInfo constructor
     * 
     * The RssAuthInfo default
     * constructor.
     * 
     * @param string $data The serialized data
     */
    public function __construct($data){
        $array = unserialize($data);
        
        $this->name = $array["service"];
        $this->extra = $array["extra"];
    }
    
    /**
     * Get name
     * 
     * This method return
     * the authorized service
     * name
     * 
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Get extra
     * 
     * This method return the
     * authorized service extra
     * informations
     * 
     * @return string
     */
    public function getExtra(){
        return $this->extra;
    }
    
}
