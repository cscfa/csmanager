<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Entity\Builders\Chains;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddEntityChain;
use Doctrine\ORM\EntityRepository;
use Cscfa\Bundle\CSManager\UseCaseBundle\Tests\MockObject\AddEntityChainTestObject;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

/**
 * AddDataChainTest.
 *
 * The AddDataChainTest
 * process the AddDataChain
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddDataChain
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class AddEntityChainTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * AddEntityChain
     * 
     * This property register
     * the AddEntityChain
     * tested instance
     * 
     * @var AddEntityChain
     */
    protected $addEntityChain;
    
    /**
     * Entity repository
     * 
     * The EntityRepository
     * 
     * @var \PHPUnit_Framework_MockObject_MockObject|EntityRepository
     */
    protected $entityRepository;
    
    /**
     * Current entity
     * 
     * The current entity
     * 
     * @var AddEntityChainTestObject
     */
    protected $currentEntity;

    /**
     * Routed entity
     *
     * The routed entity
     *
     * @var AddEntityChainTestObject
     */
    protected $routedEntity;
    
    /**
     * Container object
     * 
     * The object the have
     * method to set the
     * object.
     * 
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $containerObject;
    
    /**
     * Next
     * 
     * The next chain instance
     * 
     * @var \PHPUnit_Framework_MockObject_MockObject|ChainOfResponsibilityInterface
     */
    protected $next;

    const SUPPORTED_PROPERTY_NAME = "testProperty";
    const UNSUPPORTED_PROPERTY_NAME = "unsupportProperty";
    
    /**
     * Set up
     * 
     * This mehod set up the
     * test class before tests.
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp(){
        $this->addEntityChain = new AddEntityChain();

        $this->currentEntity = new AddEntityChainTestObject();
        $this->routedEntity = new AddEntityChainTestObject();
        
        $this->entityRepository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(array("find"))
            ->getMock();
        

        $this->next = $this->getMockBuilder(ChainOfResponsibilityInterface::class)
            ->setMethods(array("setNext", "getNext", "process", "support", "getAction"))
            ->getMock();
        
        $this->addEntityChain->setEntityRepository($this->entityRepository)
            ->setProperty(self::SUPPORTED_PROPERTY_NAME)
            ->setRouteEntity($this->routedEntity)
            ->setPropertyClass(AddEntityChainTestObject::class);
        
        $this->containerObject = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array("set".ucfirst(self::SUPPORTED_PROPERTY_NAME)))
            ->getMock();
    }
    
    /**
     * Test unsupported process
     * 
     * This method test the
     * unsupported actions
     * process.
     */
    public function testUnsupportedProcess(){
        $this->next->expects($this->once())
            ->method("process")
            ->will($this->returnSelf());
        $data = null;
        //test unsupported action without next
        $this->assertSame(
            $this->addEntityChain, 
            $this->addEntityChain->process(
                self::UNSUPPORTED_PROPERTY_NAME, 
                $data, 
                array()
            ),
            "The AddEntityChain must return itself if no one next chain exist"
        );
        //test unsupported action with next
        $this->addEntityChain->setNext($this->next);
        $this->assertSame(
            $this->next, 
            $this->addEntityChain->process(
                self::UNSUPPORTED_PROPERTY_NAME, 
                $data, 
                array()
            ),
            "The AddEntityChain must return the end chain if one next chain exist"
        );
    }
    
    /**
     * Test process with true object
     * 
     * This method process test
     * with passing directly the
     * object to inject.
     */
    public function testProcessWithTrueObject(){
        //test supported action *find from data with true object * inject into array
        $data = array();
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$this->currentEntity)
        );
        
        $this->assertSame(
            $this->currentEntity, 
            $data[self::SUPPORTED_PROPERTY_NAME], 
            "The AddEntityChain must inject the object given as argument if it match the needles"
        );
        //test supported action *find from data with true object * inject with object method
        $data = clone $this->containerObject;
        $data->expects($this->once())
            ->method("set".ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->with($this->identicalTo($this->currentEntity))
            ->will($this->returnSelf());
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$this->currentEntity)
        );
        //test supported action *find from data with true object * inject into object property
        $data = clone $this->currentEntity;
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$this->currentEntity)
        );
        $this->assertSame(
            $this->currentEntity, 
            $data->testProperty,
            "The AddEntityChain must inject data into property if no one setter exist"
        );

        //test supported action *find from data with false object * inject into array
        $data = array();
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$this->getMock(\stdClass::class))
        );
        
        $this->assertFalse(
            array_key_exists(self::SUPPORTED_PROPERTY_NAME, $data), 
            "The AddEntityChain mustn't inject the object given as argument if it not match the needles"
        );
        
        //test supported action *find from data with false object * inject with object method
        $data = clone $this->containerObject;
        $data->expects($this->never())
            ->method("set".ucfirst(self::SUPPORTED_PROPERTY_NAME));
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$this->getMock(\stdClass::class))
        );
        //test supported action *find from data with false object * inject into object property
        $data = clone $this->currentEntity;
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$this->getMock(\stdClass::class))
        );
        $this->assertNull(
            $data->testProperty,
            "The AddEntityChain mustn't inject data into property if no one setter exist and data to inject not match the needles"
        );
    }
    
    /**
     * Test process with request object
     * 
     * This method process
     * test with routed object.
     */
    public function testProcessWithRequestObject(){
        //test supported action *find from data with string 'currentRequest' * inject into array
        $data = array();
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>"currentRequest")
        );
        
        $this->assertSame(
            $this->routedEntity, 
            $data[self::SUPPORTED_PROPERTY_NAME], 
            "The AddEntityChain must inject the routed object by method if the data injected match 'currentRequest'"
        );
        //test supported action *find from data with string 'currentRequest' * inject with object method
        $data = clone $this->containerObject;
        $data->expects($this->once())
            ->method("set".ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->with($this->identicalTo($this->routedEntity))
            ->will($this->returnSelf());
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>"currentRequest")
        );
        //test supported action *find from data with string 'currentRequest' * inject into object property
        $data = clone $this->currentEntity;
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>"currentRequest")
        );
        $this->assertSame(
            $this->routedEntity, 
            $data->testProperty,
            "The AddEntityChain must inject the routed object into property if the data injected match 'currentRequest'"
        );
    }
    
    /**
     * Test process with repository object
     * 
     * This method test the repository
     * injection process.
     */
    public function testProcessWithRepositoryObject(){
        $id = openssl_random_pseudo_bytes(10);
        $entity = clone $this->currentEntity;
        $repository = clone $this->entityRepository;
        
        $repository->expects($this->exactly(3))
            ->method("find")
            ->with($this->equalTo($id))
            ->will($this->returnValue($entity));
        
        $this->addEntityChain->setEntityRepository($repository);
            
        //test supported action *find from data with string 'id' * inject into array
        $data = array();
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$id)
        );
        
        $this->assertSame(
            $entity, 
            $data[self::SUPPORTED_PROPERTY_NAME], 
            "The AddEntityChain must inject the repository finded object by method if the data injected is an id and entity exist"
        );
        //test supported action *find from data with string 'id' * inject with object method
        $data = clone $this->containerObject;
        $data->expects($this->once())
            ->method("set".ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->with($this->identicalTo($entity))
            ->will($this->returnSelf());
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$id)
        );
        //test supported action *find from data with string 'id' * inject into object property
        $data = clone $this->currentEntity;
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$id)
        );
        $this->assertSame(
            $entity, 
            $data->testProperty,
            "The AddEntityChain must inject the repository finded object into property if the data injected is an id and entity exist"
        );

        //test supported action *find from data with unexisting string 'id' * inject into array
        $repository = clone $this->entityRepository;
        
        $repository->expects($this->exactly(1))
            ->method("find")
            ->with($this->equalTo($id))
            ->will($this->returnValue(null));
        
        $this->addEntityChain->setEntityRepository($repository);
        
        $data = array();
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data,
            array("data"=>$id)
        );
        
        $this->assertFalse(
            array_key_exists(self::SUPPORTED_PROPERTY_NAME, $data), 
            "The AddEntityChain mustn't inject the repository finded object by method if the data injected is an id and entity not exist"
        );
    }
    
    /**
     * Test process with routed object
     * 
     * This method process test
     * for routed injection of
     * object.
     */
    public function testProcessWithRoutedObject(){
        //test supported action *find without data (direct routed object) * inject into array
        $data = array();
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data
        );
        
        $this->assertSame(
            $this->routedEntity, 
            $data[self::SUPPORTED_PROPERTY_NAME], 
            "The AddEntityChain must inject the routed object"
        );
        //test supported action *find without data (direct routed object) * inject with object method
        $data = clone $this->containerObject;
        $data->expects($this->once())
            ->method("set".ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->with($this->identicalTo($this->routedEntity))
            ->will($this->returnSelf());
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data
        );
        
        //test supported action *find without data (direct routed object) * inject into object property
        $data = clone $this->currentEntity;
        $this->addEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME, 
            $data
        );
        $this->assertSame(
            $this->routedEntity, 
            $data->testProperty,
            "The AddEntityChain must inject routed object into property if no one setter exist"
        );
    }
    
    /**
     * Test action
     * 
     * This method return
     * the get action method
     * of the AddEntityChain.
     */
    public function testAction(){
        $this->assertSame(
            self::SUPPORTED_PROPERTY_NAME, 
            $this->addEntityChain->getAction(),
            "The AddEntityChain must return it's own property as action"
        );
    }
    
    /**
     * Test support
     * 
     * This method test the 
     * AddEntityChain
     * support process.
     */
    public function testSupport(){
        
        $this->assertTrue(
            $this->addEntityChain->support(self::SUPPORTED_PROPERTY_NAME),
            "The add entity chain must support it's own property"
        );
        
        $this->assertFalse(
            $this->addEntityChain->support(self::UNSUPPORTED_PROPERTY_NAME),
            "The add entity chain mustn't support other property than it's own property"
        );
        
    }
    
}