<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Example
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Role
 * @see      Cscfa\Bundle\SecurityBundle\Entity\StackUpdate
 * @see      Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder
 * @see      Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager
 * @see      Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Base\StackableObject
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Repository\RoleRepository
 * @see      Cscfa\Bundle\SecurityBundle\Exception\CircularReferenceException
 */
namespace Cscfa\Bundle\SecurityBundle\Example\Role;

use Cscfa\Bundle\SecurityBundle\Example\ExampleInterface;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder;
use Doctrine\ORM\OptimisticLockException;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Entity\Role;

/**
 * The HowToCreate class.
 *
 * This class present the Role instance
 * creation.
 *
 * @category Example
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class HowTo extends Controller implements ExampleInterface
{

    /**
     * The howItWork method.
     *
     * This method present the creation
     * of a Role instance.
     *
     * @see    \Cscfa\Bundle\SecurityBundle\Example\ExampleInterface::howItWork()
     * @return void
     */
    public function howItWork()
    {
        
        /*
         * In this example, we will consider that
         * the RoleManager instance is retreived by
         * the application container. RoleManager
         * is a service stored as
         * 'core.manager.role_manager'.
         */
        $roleManager = $this->get('core.manager.role_manager');
        
        /*
         * In this example, we will consider that
         * the RoleProvider instance is retreived by
         * the application container. RoleProvider
         * is a service stored as
         * 'core.provider.role_provider'.
         */
        $roleProvider = $this->get('core.provider.role_provider');
        
        /*
         * Two things exist to create a Role
         * instance. We can create a Role
         * instance to create a new Role, or
         * create an instance from an existing
         * Role stored into the database.
         *
         * First, we will see how to create a
         * Role instance from nothing, and after
         * we will create a Role from an existing
         * instance.
         *
         * To see how to create an instance from
         * nothing, view the
         * createRoleInstanceFromNothing() method.
         */
        
        // Create a new Role instance from nothing.
        $this->createRoleInstanceFromNothing($roleManager);
        
        // Create a new Role instance from existing role.
        $this->createRoleInstanceFromExisting($roleManager, $roleProvider);
        
        /*
         * The RoleManager grant the logic
         * of Role instance, it's important
         * to use it or use RoleBuilder to
         * work with Role instances. The
         * RoleManager usage is implicitly
         * use into the RoleBuilder instance.
         */
        
        // RoleManager usage example
        $this->useRoleManager($roleManager);
        
        /*
         * The RoleProvider allow an
         * abstraction state between
         * the logic of the RoleManager
         * and the Role repository. It
         * is used to manage the database
         * results.
         */
        $this->useRoleProvider($roleProvider);
    }

    /**
     * The createRoleInstanceFromNothing.
     *
     * This method present the empty Role
     * instance creation.
     *
     * @param RoleManager $roleManager A RoleManager to manage Role instance.
     *
     * @return void
     */
    public function createRoleInstanceFromNothing(RoleManager $roleManager)
    {
        
        /*
         * Here we create a RoleBuilder instance and
         * not a Role instance from it own constructor.
         *
         * We do that to provide security and logical
         * access to the Role instance. In fact, the
         * Role class only provide basic getter and
         * setters to store data. This is a bad thing
         * because in case of error like Role duplication
         * we will create a MySQL Doctrine exception.
         */
        $newRoleInstance = $roleManager->getNewInstance();
        
        // The current date and time.
        $creationDate = new \DateTime();
        
        /*
         * Now we have an instance of RoleBuilder and
         * we can start to inject values.
         *
         * Here we update the creation time of the Role
         * instance and we can see that the opération
         * can fail if the creation date have a ligocal
         * problem an represent a date past to present
         * time. In this case, the setter method of
         * RoleBuilder will return false and store
         * the error code into a variable that can be
         * retreive behing getLastError() method.
         */
        if (! $newRoleInstance->getCreatedAt($creationDate)) {
            switch ($newRoleInstance->getLastError()) {
            case RoleBuilder::CREATION_AFTER_NOW:
                throw new \Exception("The date of the creation is can't be past of present date.");
            case RoleBuilder::NO_ERROR:
                continue;
            }
        }
        
        // The new Role name.
        $name = "ROLE_TEST";
        
        /*
         * Now, we will assign a name for
         * this new Role.
         *
         * We can make it by two way. The first
         * way is the same of the creation date
         * setting. So, we can check the setName()
         * return value and check the lastError()
         * result in failure case. The possibilities
         * of error in this case are :
         * <ul>
         * <li>RoleBuilder::INVALID_ROLE_NAME</li>
         * <li>RoleBuilder::DUPLICATE_ROLE_NAME</li>
         * </ul>
         *
         * The second way is to check manually the
         * validity of the name and the duplication
         * state with the RoleManager and force the
         * setter by passing true as second parameter.
         *
         * Some methods of RoleBuilder allow to force
         * the validation.
         */
        if ($roleManager->nameIsValid($name) && ! $roleManager->roleExists($name)) {
            $newRoleInstance->setName($name, true);
        }
        
        /*
         * Now we can persist the role into the database.
         *
         * The role manager provide persist access for
         * RoleBuilder that apply storage for Role instance.
         * This method can thorw an OptimisticLockException
         * if Doctrine return an error.
         */
        try {
            $roleManager->persist($newRoleInstance);
        } catch (OptimisticLockException $exception) {
            Throw new \Exception("An error occured during the example. The doctrine persistance failed", 500, $exception);
        }
        
        return;
    }

    /**
     * The createRoleInstanceFromExisting.
     *
     * This method present the Role
     * instance creation from persisted
     * role.
     *
     * @param RoleManager  $roleManager  A RoleManager to manage Role instance.
     * @param RoleProvider $roleProvider A RoleProvider to get Role from the databaase.
     *
     * @return void
     */
    public function createRoleInstanceFromExisting(RoleManager $roleManager, RoleProvider $roleProvider)
    {
        /*
         * To start, we will create a new
         * RoleBuilder instance from nothing.
         */
        $newRole = $roleManager->getNewInstance();
        
        /*
         * We set it's properties to refer
         * a name and a creation date.
         */
        $newRole->setCreatedAt(new \DateTime());
        $newRole->setName("ROLE_TEST");
        
        /*
         * Here, we consider a role with
         * ROLE_USER name already exist
         * into the database.
         */
        $existingName = "ROLE_USER";
        
        /*
         * We check if this role realy exist
         */
        if ($roleManager->roleExists($existingName)) {
            /*
             * Now we get the existing instance
             * from the database. The Role
             * provider will get it and store
             * it into a RoleBuilder instance.
             */
            $existingRole = $roleProvider->findOneByName($existingName);
            
            /*
             * It's possible to get only the Role
             * instance behind the getRole() method
             * of the RoleBuilder instance.
             *
             * Here we store the existing Role instance
             * like a child of the new Role.
             *
             * We can see that the setChild method
             * can fail if the given child create a
             * circular reference to another child.
             *
             * A circular reference occure when the
             * wire of child create an infinite loop.
             */
            if (! $newRole->setChild($existingRole->getRole())) {
                switch ($newRole->getLastError()) {
                case RoleBuilder::INVALID_ROLE_INSTANCE_OF:
                    throw new \Exception("The given child is invalid");
                case RoleBuilder::CIRCULAR_REFERENCE:
                    throw new \Exception("The given child create a circular reference");
                case RoleBuilder::NO_ERROR:
                    continue;
                }
            }
            
            /*
             * Here we set the update date
             * of the new role instance.
             *
             * We can see that the setter of
             * the update date can fail in two
             * case.
             *
             * The first case inform that the
             * update date is before the creation
             * date an create a logic problem.
             *
             * The second case inform that the
             * update date is after the present
             * day.
             */
            if (! $newRole->setUpdatedAt(new \DateTime())) {
                switch ($newRole->getLastError()) {
                case RoleBuilder::UPDATE_BEFORE_CREATION:
                    throw new \Exception("The update date is before the creation date", 500);
                case RoleBuilder::UPDATE_AFTER_NOW:
                    throw new \Exception("The update date is after the present date", 500);
                case RoleBuilder::NO_ERROR:
                    continue;
                }
            }
            
            /*
             * Now we persist the new role
             * instance.
             */
            $roleManager->persist($newRole);
        } else {
            throw new \Exception("The role ROLE_USER is not defined.", 500);
        }
        
        return;
    }

    /**
     * The useRoleManager.
     *
     * This method present the RoleManager
     * instance usage.
     *
     * @param RoleManager $roleManager A RoleManager to manage Role instance.
     *
     * @return void
     */
    public function useRoleManager(RoleManager $roleManager)
    {
        
        /*
         * Here we can create a new instance.
         * This thing is developed into
         * createRoleInstanceFromNothing().
         */
        $roleBuilder = $roleManager->getNewInstance();
        
        /*
         * Now, imagine that we need to
         * create a ne Role dirctly from
         * it's own constructor for everyelse
         * reason. If we need to persist
         * it with the StackObject or
         * prevent logic errors, it will
         * be necessary to create a RoleBuilder
         * object. To make this, the RoleManager
         * provide the convertInstance() method,
         * that encapsulate a Role instance
         * into a RoleBuilder instance. This
         * method return the RoleBuilder.
         */
        $newRole = new Role();
        $roleBuilderOfNewRole = $roleManager->convertInstance($newRole);
        unset($roleBuilderOfNewRole);
        
        /*
         * Here we get all of the role
         * names that exist into the database.
         *
         * This set of names are serv into
         * an array of string.
         */
        $allRolesNames = $roleManager->getRolesName();
        unset($allRolesNames);
        
        /*
         * Here we can check that a role
         * already exist into the database
         * and get all of it childs names.
         *
         * This set of name is serv into
         * an array of string.
         */
        if ($roleManager->roleExists("ROLE_ADMIN")) {
            $roleManager->getRoleWire("ROLE_ADMIN");
        }
        
        /*
         * Now we créate two role with
         * circular reference and check
         * if realy exist with the
         * hasCircularReference method.
         *
         * This method will return true.
         */
        $roleOne = new Role();
        $roleOne->setName("roleOne");
        $roleTwo = new Role();
        $roleTwo->setName("roleTwo")->setChild($roleOne);
        $roleOne->setChild($roleTwo);
        // the hasCircularReference method
        $roleManager->hasCircularReference($roleOne);
        
        /*
         * Here we can see that the RoleManager
         * instance is in capacity to grant the
         * validation of the name format. By
         * default, the name contain only
         * alphabetics characters without
         * accent. Underscore is allowed.
         *
         * The first method will return
         * true, according with the default
         * format schema.
         *
         * The second method will return
         * false because the name contain
         * special characters.
         */
        $roleManager->nameIsValid("ROLE_name_VALID");
        $roleManager->nameIsValid("ROLE@name°INVALID");
        
        /*
         * Now we can see the RoleManager
         * methods that allow to persist
         * and remove an entity.
         *
         * We can see that this two methods
         * can throw an OptimisticLockException
         * if something goes wrong with doctrine.
         */
        $roleBuilder->setName("ROLE_TEST");
        try {
            $roleManager->persist($roleBuilder);
            $roleManager->remove($roleBuilder);
        } catch (OptimisticLockException $exception) {
            throw new \Exception("An error occured during the example. The doctrine calling fail", 500, $exception);
        }
        
        return;
    }

    /**
     * The useRoleProvider.
     *
     * This method present the RoleProvider
     * instance usage.
     *
     * @param RoleProvider $roleProvider A RoleProvider to get Role from the databaase.
     *
     * @return void
     */
    public function useRoleProvider(RoleProvider $roleProvider)
    {
        
        /*
         * Here we get all of the roles
         * existing into the database.
         *
         * We can see that we receive an
         * array of Role instead of RoleBuilder.
         */
        $allRoles = $roleProvider->findAll();
        foreach ($allRoles as $role) {
            if (! $role instanceof Role) {
                Throw new \Exception('$role must be instance of Role.');
            }
        }
        
        /*
         * We can see here that the RoleProvider
         * is able to check if a Role exist as
         * RoleManager. In fact, RoleManager ask
         * RoleProvider for this features types.
         *
         * Also, we see that the RoleProvider is
         * able to find a role by it name and
         * serve it into a RoleBuilder instance.
         * If the role doesn't exist, it will
         * return null.
         */
        if ($roleProvider->isExistingByName("ROLE_USER")) {
            if (! $roleProvider->findOneByName("ROLE_USER") instanceof Role) {
                throw new \Exception('$roleProvider->findOneByName("ROLE_USER") must be instance of Role');
            }
        } else {
            if (! $roleProvider->findOneByName("ROLE_USER") === null) {
                throw new \Exception('$roleProvider->findOneByName("ROLE_USER") must be null');
            }
        }
    }
}






















