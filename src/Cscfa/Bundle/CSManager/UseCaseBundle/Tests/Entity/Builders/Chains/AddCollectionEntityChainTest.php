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

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddCollectionEntityChain;
use Cscfa\Bundle\CSManager\UseCaseBundle\Tests\MockObject\AddEntityChainTestObject;
use Doctrine\ORM\EntityRepository;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

/**
 * AddCollectionEntityChainTest.
 *
 * The AddCollectionEntityChainTest
 * process the AddCollectionEntityChain
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddCollectionEntityChain
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class AddCollectionEntityChainTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * AddCollectionEntityChain
     * 
     * This property register
     * the AddCollectionEntityChain
     * tested instance
     * 
     * @var AddCollectionEntityChain
     */
    protected $addCollectionEntityChain;
    
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
    
    /**
     * Collection object
     * 
     * The object the have
     * method to add the
     * object.
     * 
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionObject;

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
        $this->addCollectionEntityChain = new AddCollectionEntityChain();

        $this->currentEntity = new AddEntityChainTestObject();
        $this->routedEntity = new AddEntityChainTestObject();
        
        $this->entityRepository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(array("find"))
            ->getMock();
        

        $this->next = $this->getMockBuilder(ChainOfResponsibilityInterface::class)
            ->setMethods(array("setNext", "getNext", "process", "support", "getAction"))
            ->getMock();
        
        $this->addCollectionEntityChain->setEntityRepository($this->entityRepository)
            ->setPropertyClass(AddEntityChainTestObject::class)
            ->setProperty(self::SUPPORTED_PROPERTY_NAME);
        
        $this->containerObject = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array("get".ucfirst(self::SUPPORTED_PROPERTY_NAME)))
            ->getMock();
        
        $this->collectionObject = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array("add"))
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
            $this->addCollectionEntityChain,
            $this->addCollectionEntityChain->process(
                    self::UNSUPPORTED_PROPERTY_NAME,
                    $data,
                    array()
                    ),
            "The AddCollectionEntityChain must return itself if no one next chain exist"
        );
        //test unsupported action with next
        $this->addCollectionEntityChain->setNext($this->next);
        $this->assertSame(
            $this->next,
            $this->addCollectionEntityChain->process(
                self::UNSUPPORTED_PROPERTY_NAME,
                $data,
                array()
            ),
            "The AddCollectionEntityChain must return the end chain if one next chain exist"
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
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$this->currentEntity)
        );
    
        $this->assertEquals(
            array($this->currentEntity),
            $data[self::SUPPORTED_PROPERTY_NAME],
            "The AddCollectionEntityChain must inject the object given as argument if it match the needles"
        );
        //test supported action *find from data with true object * inject with object method
        $data = clone $this->containerObject;
        $collection = clone $this->collectionObject;
        $collection->expects($this->once())
            ->method("add")
            ->with($this->identicalTo($this->currentEntity))
            ->will($this->returnSelf());
        $data->expects($this->exactly(2))
            ->method("get".ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->will($this->returnValue($collection));
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$this->currentEntity)
        );
        //test supported action *find from data with true object * inject into object property
        $data = clone $this->currentEntity;
        $collection = clone $this->collectionObject;
        $collection->expects($this->once())
            ->method("add")
            ->with($this->identicalTo($this->currentEntity))
            ->will($this->returnSelf());
        $data->testProperty = $collection;
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$this->currentEntity)
        );
    
        //test supported action *find from data with false object * inject into array
        $data = array();
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$this->getMock(\stdClass::class))
        );
    
        $this->assertFalse(
            array_key_exists(self::SUPPORTED_PROPERTY_NAME, $data),
            "The AddCollectionEntityChain mustn't inject the object given as argument if it not match the needles"
        );
    
        //test supported action *find from data with false object * inject with object method
        $data = clone $this->containerObject;
        $data->expects($this->never())
            ->method("get".ucfirst(self::SUPPORTED_PROPERTY_NAME));
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$this->getMock(\stdClass::class))
        );
        //test supported action *find from data with false object * inject into object property
        $data = clone $this->currentEntity;
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$this->getMock(\stdClass::class))
        );
        $this->assertNull(
            $data->testProperty,
            "The AddCollectionEntityChain mustn't inject data into property if no one setter exist and data to inject not match the needles"
        );
    
        //test supported action *find from data collection with true object * inject into array
        $data = array();
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>array(clone $this->currentEntity, clone $this->currentEntity))
        );

        $this->assertEquals(
            array($this->currentEntity, $this->currentEntity),
            $data[self::SUPPORTED_PROPERTY_NAME],
            "The AddCollectionEntityChain must inject the object collection given as argument if it match the needles"
        );
    
        //test supported action *find from data with false object * inject with object method
        $data = clone $this->containerObject;
        $collection = clone $this->collectionObject;
        $collection->expects($this->exactly(2))
            ->method("add")
            ->with($this->equalTo($this->currentEntity))
            ->will($this->returnSelf());
        $data->expects($this->exactly(2*2))
            ->method("get".ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->will($this->returnValue($collection));
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>array(clone $this->currentEntity, clone $this->currentEntity))
        );
        //test supported action *find from data with false object * inject into object property
        $data = clone $this->currentEntity;
        $collection = clone $this->collectionObject;
        $collection->expects($this->exactly(2))
            ->method("add")
            ->with($this->equalTo($this->currentEntity))
            ->will($this->returnSelf());
        $data->testProperty = $collection;
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>array(clone $this->currentEntity, clone $this->currentEntity))
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
    
        $this->addCollectionEntityChain->setEntityRepository($repository);
    
        //test supported action *find from data with string 'id' * inject into array
        $data = array();
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$id)
        );
    
        $this->assertSame(
            array($entity),
            $data[self::SUPPORTED_PROPERTY_NAME],
            "The AddCollectionEntityChain must inject the repository finded object by method if the data injected is an id and entity exist"
        );
        //test supported action *find from data with string 'id' * inject with object method
        $data = clone $this->containerObject;
        $collection = clone $this->collectionObject;
        $collection->expects($this->once())
            ->method("add")
            ->with($this->equalTo($this->currentEntity))
            ->will($this->returnSelf());
        $data->expects($this->exactly(2))
            ->method("get".ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->will($this->returnValue($collection));
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$id)
        );
        //test supported action *find from data with string 'id' * inject into object property
        $data = clone $this->currentEntity;
        $collection = clone $this->collectionObject;
        $collection->expects($this->once())
            ->method("add")
            ->with($this->equalTo($this->currentEntity))
            ->will($this->returnSelf());
        $data->testProperty = $collection;
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$id)
        );
    
        //test supported action *find from data with multiple string 'id' * inject into array
        $id1 = openssl_random_pseudo_bytes(10);
        $entity1 = clone $this->currentEntity;
        $id2 = openssl_random_pseudo_bytes(10);
        $entity2 = clone $this->currentEntity;
        $repository = clone $this->entityRepository;
    
        $repository->expects($this->exactly(2))
            ->method("find")
            ->will($this->onConsecutiveCalls($entity1, $entity2));
        $repository->expects($this->at(0))
            ->method("find")
            ->with($this->equalTo($id1));
        $repository->expects($this->at(1))
            ->method("find")
            ->with($this->equalTo($id2));
    
        $this->addCollectionEntityChain->setEntityRepository($repository);
        
        $data = array();
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>array($id1, $id2))
        );
        
        $this->assertSame(
            $entity1,
            $data[self::SUPPORTED_PROPERTY_NAME][0],
            "The AddCollectionEntityChain must inject the repository finded object by method if the data injected is an id and entity exist"
        );
        $this->assertSame(
            $entity2,
            $data[self::SUPPORTED_PROPERTY_NAME][1],
            "The AddCollectionEntityChain must inject the repository finded object by method if the data injected is an id and entity exist"
        );
    
        //test supported action *find from data with unexisting string 'id' * inject into array
        $repository = clone $this->entityRepository;
    
        $repository->expects($this->exactly(1))
            ->method("find")
            ->with($this->equalTo($id))
            ->will($this->returnValue(null));
    
        $this->addCollectionEntityChain->setEntityRepository($repository);
    
        $data = array();
        $this->addCollectionEntityChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array("data"=>$id)
        );
    
        $this->assertFalse(
            array_key_exists(self::SUPPORTED_PROPERTY_NAME, $data),
            "The AddCollectionEntityChain mustn't inject the repository finded object by method if the data injected is an id and entity not exist"
        );
    }
    
    
}