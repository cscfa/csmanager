<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Test
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Tests\Search;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\ToolboxBundle\Search\FileSystem\FileSystemElement;
use Cscfa\Bundle\ToolboxBundle\Search\Directory\DirectoryScan;
use Cscfa\Bundle\ToolboxBundle\Search\Directory\DirectorySearchTool;
use Cscfa\Bundle\ToolboxBundle\Exception\Directory\DirectoryException;
use Cscfa\Bundle\ToolboxBundle\Exception\Directory\NotDirectoryException;

/**
 * DirectoryTest class.
 *
 * The DirectoryTest class provide
 * test to valid DirectorySearchTool
 * class and dependencies class.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class DirectoryTest extends WebTestCase
{
    /**
     * The setUp.
     *
     * This method is used to configure
     * and process the initialisation of
     * the test class.
     */
    public function setUp()
    {
    }

    /**
     * The testFileSystemElement test.
     *
     * This test is used to confirm
     * the FileSystemElement class.
     */
    public function testFileSystemElement()
    {
        $fse = new FileSystemElement(__DIR__.'/usage/usageFile1.txt');

        $this->assertTrue($fse->isFile());
        $this->assertTrue($fse->isReadable());
        $this->assertTrue($fse->isWritable());
        $this->assertFalse($fse->isDir());

        $this->assertTrue($fse->getEmplacement() === __DIR__.'/usage/usageFile1.txt');
        $this->assertTrue($fse->getBasename() === 'usageFile1.txt');
        $this->assertTrue($fse->getDirname() === __DIR__.'/usage');
        $this->assertTrue($fse->getExtension() === 'txt');
        $this->assertTrue($fse->getFilename() === 'usageFile1');
        $this->assertTrue($fse->getPathInfo() == pathinfo(__DIR__.'/usage/usageFile1.txt'));

        $fse = new FileSystemElement(__DIR__.'/usage');

        $this->assertFalse($fse->isFile());
        $this->assertTrue($fse->isReadable());
        $this->assertTrue($fse->isWritable());
        $this->assertTrue($fse->isDir());

        $this->assertTrue($fse->getEmplacement() === __DIR__.'/usage');
        $this->assertTrue($fse->getBasename() === 'usage');
        $this->assertTrue($fse->getDirname() === __DIR__);
        $this->assertTrue($fse->getExtension() === null);
        $this->assertTrue($fse->getFilename() === 'usage');
        $this->assertFalse($fse->getPathInfo() == pathinfo(__DIR__.'/usage'));
    }

    /**
     * The testDirectoryScan test.
     *
     * This test is used to confirm
     * the testDirectoryScan class.
     */
    public function testDirectoryScan()
    {
        $directory = new DirectoryScan();

        $this->assertTrue($directory->getDirectories() == array());
        $this->assertTrue($directory->getFiles() == array());
        $this->assertFalse($directory->hasDirectory());
        $this->assertFalse($directory->hasFile());

        $searchDatas = array(
            new FileSystemElement(__DIR__.'/usage'),
            new FileSystemElement(__DIR__.'/usage/usageFile1.txt'),
        );
        foreach ($searchDatas as $data) {
            $directory->addFilesystem($data);
        }

        $this->assertTrue($directory->hasDirectory());
        $this->assertTrue($directory->hasFile());

        $this->assertTrue(count($directory->getFiles()) == 1);
        $this->assertTrue(isset($directory->getFiles()[0]));
        $this->assertTrue(in_array($searchDatas[1], $directory->getFiles()));

        $this->assertTrue(count($directory->getDirectories()) == 1);
        $this->assertTrue(isset($directory->getDirectories()[0]));
        $this->assertTrue(in_array($searchDatas[0], $directory->getDirectories()));

        $ds2 = new DirectoryScan();
        $searchDatas2 = array(
            new FileSystemElement(__DIR__.'/usage/usage2'),
            new FileSystemElement(__DIR__.'/usage/usage2/usageFile21.txt'),
            new FileSystemElement(__DIR__.'/usage/usageFile2.txt'),
        );
        foreach ($searchDatas2 as $data) {
            $ds2->addFilesystem($data);
        }

        $this->assertTrue(count($ds2->getFiles()) == 2);
        $this->assertTrue(in_array($searchDatas2[1], $ds2->getFiles()));
        $this->assertTrue(in_array($searchDatas2[2], $ds2->getFiles()));

        $this->assertTrue(count($ds2->getDirectories()) == 1);
        $this->assertTrue(in_array($searchDatas2[0], $ds2->getDirectories()));

        $directory->merge($ds2);

        $this->assertTrue($directory->hasDirectory());
        $this->assertTrue($directory->hasFile());

        $this->assertTrue(count($directory->getDirectories()) == 2);
        $this->assertTrue(in_array($searchDatas2[0], $directory->getDirectories()));
        $this->assertTrue(in_array($searchDatas[0], $directory->getDirectories()));

        $this->assertTrue(count($directory->getFiles()) == 3);
        $this->assertTrue(in_array($searchDatas2[1], $directory->getFiles()));
        $this->assertTrue(in_array($searchDatas2[2], $directory->getFiles()));
        $this->assertTrue(in_array($searchDatas[1], $directory->getFiles()));
    }

    /**
     * The testDirectorySearchTool test.
     *
     * This test is used to confirm
     * the testDirectorySearchTool class.
     */
    public function testDirectorySearchTool()
    {
        $dst = new DirectorySearchTool();

        try {
            $dst->setDir(__DIR__.'/unexistingDirectory');
        } catch (DirectoryException $e) {
            $this->assertTrue(true);
        }

        try {
            $dst->setDir(__DIR__.'/DirectoryTest.php');
        } catch (NotDirectoryException $e) {
            $this->assertTrue(true);
        }

        try {
            $dst->setDir(__DIR__.'/usage');
        } catch (DirectoryException $e) {
            $this->fail();
        }
        $this->assertTrue(true);

        $searchfiles = $dst->searchFilename('/^none$/');
        $this->assertTrue(empty($searchfiles));
        $searchfiles = $dst->searchFilename('/usage.+$/');
        $this->assertTrue(!empty($searchfiles));
        $this->assertTrue(in_array(__DIR__.'/usage/usageFile1.txt', $searchfiles));
        $this->assertTrue(in_array(__DIR__.'/usage/usageFile2.txt', $searchfiles));

        $searchfiles = $dst->searchFilename('/^usage.+$/', true, __DIR__.'/usage');
        $this->assertTrue(!empty($searchfiles));
        $this->assertTrue(in_array('usageFile1.txt', $searchfiles));
        $this->assertTrue(in_array('usageFile2.txt', $searchfiles));
        $this->assertTrue(in_array('usage2/usageFile21.txt', $searchfiles));

        $searchData = $dst->searchDirectories('/usage/', true);
        $this->assertTrue(!empty($searchData));
        $this->assertTrue(in_array(__DIR__.'/usage/usage2', $searchData));

        $searchData = $dst->searchDirectories("/usage\/usage/", true);
        $this->assertTrue(!empty($searchData));
        $this->assertTrue(in_array(__DIR__.'/usage/usage2', $searchData));
    }
}
