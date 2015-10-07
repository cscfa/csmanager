<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Test
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
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
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
     * 
     * @return void
     */
    public function setUp()
    {
    }

    /**
     * The testFileSystemElement test.
     *
     * This test is used to confirm
     * the FileSystemElement class.
     *
     * @return void
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
     *
     * @return void
     */
    public function testDirectoryScan()
    {
        $ds = new DirectoryScan();

        $this->assertTrue($ds->getDirectories() == array());
        $this->assertTrue($ds->getFiles() == array());
        $this->assertFalse($ds->hasDirectory());
        $this->assertFalse($ds->hasFile());
        
        $sa = array(
            new FileSystemElement(__DIR__.'/usage'), 
            new FileSystemElement(__DIR__.'/usage/usageFile1.txt')
        );
        foreach ($sa as $v) {
            $ds->addFilesystem($v);
        }
        
        $this->assertTrue($ds->hasDirectory());
        $this->assertTrue($ds->hasFile());
        
        $this->assertTrue(count($ds->getFiles()) == 1);
        $this->assertTrue(isset($ds->getFiles()[0]));
        $this->assertTrue(in_array($sa[1], $ds->getFiles()));

        $this->assertTrue(count($ds->getDirectories()) == 1);
        $this->assertTrue(isset($ds->getDirectories()[0]));
        $this->assertTrue(in_array($sa[0], $ds->getDirectories()));
        
        $ds2 = new DirectoryScan();
        $sa2 = array(
            new FileSystemElement(__DIR__.'/usage/usage2'), 
            new FileSystemElement(__DIR__.'/usage/usage2/usageFile21.txt'),
            new FileSystemElement(__DIR__.'/usage/usageFile2.txt')
        );
        foreach ($sa2 as $v) {
            $ds2->addFilesystem($v);
        }
        
        $this->assertTrue(count($ds2->getFiles()) == 2);
        $this->assertTrue(in_array($sa2[1], $ds2->getFiles()));
        $this->assertTrue(in_array($sa2[2], $ds2->getFiles()));

        $this->assertTrue(count($ds2->getDirectories()) == 1);
        $this->assertTrue(in_array($sa2[0], $ds2->getDirectories()));
        
        $ds->merge($ds2);

        $this->assertTrue($ds->hasDirectory());
        $this->assertTrue($ds->hasFile());
        
        $this->assertTrue(count($ds->getDirectories()) == 2);
        $this->assertTrue(in_array($sa2[0], $ds->getDirectories()));
        $this->assertTrue(in_array($sa[0], $ds->getDirectories()));

        $this->assertTrue(count($ds->getFiles()) == 3);
        $this->assertTrue(in_array($sa2[1], $ds->getFiles()));
        $this->assertTrue(in_array($sa2[2], $ds->getFiles()));
        $this->assertTrue(in_array($sa[1], $ds->getFiles()));
    }

    /**
     * The testDirectorySearchTool test.
     *
     * This test is used to confirm
     * the testDirectorySearchTool class.
     *
     * @return void
     */
    public function testDirectorySearchTool()
    {
        $dst = new DirectorySearchTool();
        
        try {
            $dst->setDir(__DIR__."/unexistingDirectory");
        } catch (DirectoryException $e) {
            $this->assertTrue(true);
        }

        try {
            $dst->setDir(__DIR__."/DirectoryTest.php");
        } catch (NotDirectoryException $e) {
            $this->assertTrue(true);
        }
        
        try {
            $dst->setDir(__DIR__."/usage");
        } catch (DirectoryException $e) {
            $this->fail();
        }
        $this->assertTrue(true);
        
        $sf = $dst->searchFilename("/^none$/");
        $this->assertTrue(empty($sf));
        $sf = $dst->searchFilename("/usage.+$/");
        $this->assertTrue(!empty($sf));
        $this->assertTrue(in_array(__DIR__."/usage/usageFile1.txt", $sf));
        $this->assertTrue(in_array(__DIR__."/usage/usageFile2.txt", $sf));

        $sf = $dst->searchFilename("/^usage.+$/", true, __DIR__."/usage");
        $this->assertTrue(!empty($sf));
        $this->assertTrue(in_array("usageFile1.txt", $sf));
        $this->assertTrue(in_array("usageFile2.txt", $sf));
        $this->assertTrue(in_array("usage2/usageFile21.txt", $sf));
        
        $sd = $dst->searchDirectories("/usage/", true);
        $this->assertTrue(!empty($sd));
        $this->assertTrue(in_array(__DIR__."/usage/usage2", $sd));
        
        $sd = $dst->searchDirectories("/usage\/usage/", true);
        $this->assertTrue(!empty($sd));
        $this->assertTrue(in_array(__DIR__."/usage/usage2", $sd));
    }
}
