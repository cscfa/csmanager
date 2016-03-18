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
 * @category   Bundle
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
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
        return $this->render('CscfaQUnitTestBundle:Default:index.html.twig');
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
        $basePath = realpath($this->get('kernel')->getRootDir()."/../$place/");
        $directorySearch = new DirectorySearchTool($basePath);

        $qunitDirectories = $directorySearch->searchDirectories(
            "/Resources\/QUnit/",
            true
        );

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
        $qunitDirectories = $request->request->get('paths');

        return $this->getTestScript($qunitDirectories);
    }

    /**
     * Get test script.
     *
     * This method return the
     * test script from the
     * Resources/QUnit directories.
     *
     * @param array $qunitDirectories The Resources/QUnit directories array
     *
     * @return Response
     */
    protected function getTestScript($qunitDirectories)
    {
        $basePath = realpath($this->get('kernel')->getRootDir().'/../');
        $directorySearch = new DirectorySearchTool();

        $files = $requires = $imports = array();
        $scripts = '';

        if ($qunitDirectories !== null) {
            foreach ($qunitDirectories as $qunitDirectory) {
                $directorySearch->setDir($qunitDirectory);
                $dirName = str_replace($basePath, '', $qunitDirectory);
                $scripts .= "QUnit.module( \"$dirName\" );";
                $files = $directorySearch->searchFilename("/\.js/", true);

                foreach ($files as $file) {
                    $this->processFile(
                        $file,
                        $qunitDirectory,
                        $requires,
                        $imports,
                        $scripts
                    );
                }
            }
        }

        $importSkip = array(
            'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',
        );

        $response = $this->getFileContent($imports, $importSkip);
        $response .= $this->getFileContent($requires);
        $response .= $scripts;
        $response = new Response($response);

        return $response;
    }

    /**
     * Process file.
     *
     * This method process a file
     * and extract resuire script,
     * import script and text script.
     *
     * @param string $file           The file emplacement to process
     * @param string $qunitDirectory The base directory emplacement
     * @param array  $requires       The require script emplacements array
     * @param array  $imports        The import script emplacements array
     * @param string $scripts        The text script
     */
    protected function processFile(
        $file,
        $qunitDirectory,
        &$requires,
        &$imports,
        &$scripts
    ) {
        $fileName = str_replace($qunitDirectory.'/', '', $file);

        $content = file_get_contents($file);
        $matches = array();
        if (preg_match_all("/\/\/@require:(.+)\\n/", $content, $matches)) {
            foreach ($matches[1] as $match) {
                $content = str_replace('//@require:'.$match, '', $content);

                $path = substr($file, 0, strrpos($file, '/') + 1);
                $requirePath = $path.trim($match);
                $requires[] = $requirePath;
            }
        }
        if (preg_match_all("/\/\/@import:(.+)\\n/", $content, $matches)) {
            foreach ($matches[1] as $match) {
                $content = str_replace('//@import:'.$match, '', $content);

                $match = trim($match);
                $imports[] = $match;
            }
        }

        $scripts .= "QUnit.test( \"$fileName\", function( assert ) {\n";
        $scripts .= $content;
        $scripts .= "});\n";
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
        $result = '';

        $files = array_diff(array_unique($files), $skip);

        foreach (array_unique($files) as $file) {
            try {
                $result .= file_get_contents($file);
            } catch (\Exception $e) {
            }
        }

        return $result;
    }
}
