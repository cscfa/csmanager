<?php
/**
 * This file is a part of CSCFA mail project.
 * 
 * The mail project is a tool bundle written in php
 * with Symfony2 framework to abstract a mail service
 * usage. It prevent the mail service change.
 * 
 * PHP version 5.5
 * 
 * @category Bundle
 * @package  CscfaQUnitTestBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\QUnitTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\ToolboxBundle\Search\Directory\DirectorySearchTool;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * DefaultController class.
 *
 * The DefaultController implement
 * access method to QUnit system.
 *
 * @category Controller
 * @package  CscfaQUnitTestBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class DefaultController extends Controller
{

    /**
     * Index action.
     * 
     * This action render the
     * QUnit default page.
     * 
     * @return Response
     */
    public function indexAction()
    {
        return $this->render("CscfaQUnitTestBundle:Default:index.html.twig");
    }

    /**
     * Search dir action.
     * 
     * This method return the
     * place Resources/QUnit
     * directories emplacements.
     * 
     * @param string $place The directory emplacement where search
     * 
     * @return Response
     */
    public function searchDirAction($place)
    {
        $basePath = realpath($this->get('kernel')->getRootDir() . "/../$place/");
        $directorySearch = new DirectorySearchTool($basePath);
        
        $qunitDirectories = $directorySearch->searchDirectories("/Resources\/QUnit/", true);
        
        $response = new Response(json_encode($qunitDirectories));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    /**
     * Test action.
     * 
     * This action return the
     * QUnit test scripts.
     * 
     * The scripts are search
     * in each Resources/QUnit
     * directories.
     * 
     * @param Request $request The current request instance
     * 
     * @return Response
     */
    public function testAction(Request $request)
    {
        $basePath = realpath($this->get('kernel')->getRootDir() . "/../");
        $directorySearch = new DirectorySearchTool();
        
        $qunitDirectories = $request->request->get("paths");
        
        $files = array();
        $scripts = "";
        $requires = array();
        $imports = array();
        
        if ($qunitDirectories !== null) {
            foreach ($qunitDirectories as $qunitDirectory) {
                $directorySearch->setDir($qunitDirectory);
                
                $dirName = str_replace($basePath, "", $qunitDirectory);
                
                $scripts .= "QUnit.module( \"$dirName\" );";
                $files = $directorySearch->searchFilename("/\.js/", true);
                
                foreach ($files as $file) {
                    $fileName = str_replace($qunitDirectory . "/", "", $file);
                    
                    $content = file_get_contents($file);
                    $matches = array();
                    if (preg_match_all("/\/\/@require:(.+)\\n/", $content, $matches)) {
                        foreach ($matches[1] as $match) {
                            $content = str_replace("//@require:" . $match, "", $content);
                            
                            $path = substr($file, 0, strrpos($file, "/") + 1);
                            $requirePath = $path . trim($match);
                            $requires[] = $requirePath;
                        }
                    }
                    if (preg_match_all("/\/\/@import:(.+)\\n/", $content, $matches)) {
                        foreach ($matches[1] as $match) {
                            $content = str_replace("//@import:" . $match, "", $content);
                            
                            $match = trim($match);
                            $imports[] = $match;
                        }
                    }
                    
                    $scripts .= "QUnit.test( \"$fileName\", function( assert ) {\n";
                    $scripts .= $content;
                    $scripts .= "});\n";
                }
            }
        }
        
        $importSkip = array(
            "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"
        );
        
        return $this->render(
            "CscfaQUnitTestBundle:Default:test.html.twig", 
            array(
                "scriptImport" => $this->getFileContent($imports, $importSkip),
                "scriptRequire" => $this->getFileContent($requires),
                "testScript" => $scripts
            )
        );
    }

    /**
     * Get files content.
     * 
     * This method return each
     * files content of a file
     * emplacement array.
     * 
     * @param array $files The file emplacement array
     * @param array $skip  The file array to skip
     * 
     * @return string
     */
    protected function getFileContent(array $files, $skip = array())
    {
        $result = "";
        
        $files = array_diff(array_unique($files), $skip);
        
        foreach (array_unique($files) as $file) {
            try {
                $result .= file_get_contents($file);
            } catch (Exception $e) {
            }
        }
        
        return $result;
    }
}
