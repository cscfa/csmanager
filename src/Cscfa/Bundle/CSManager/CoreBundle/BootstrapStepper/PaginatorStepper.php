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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\CoreBundle\BootstrapStepper;

use Cscfa\Bundle\DataGridBundle\Objects\DataGridStepper;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * PaginatorStepper class.
 *
 * This class allow to use a bootstrap
 * display for pagination.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class PaginatorStepper extends DataGridStepper
{
    /**
     * PaginatorStepper attribute.
     *
     * This attribute register
     * the symfony router
     * service.
     *
     * @var Router
     */
    protected $router;

    /**
     * PaginatorStepper attribute.
     *
     * This attribute indicate
     * the route to use to access
     * to the datagrid page.
     *
     * @var string
     */
    protected $pageRoutes;

    /**
     * PaginatorStepper attribute.
     *
     * This attribute indicate
     * the route to use to access
     * to the limit setter url.
     *
     * @var string
     */
    protected $limitRoute;

    /**
     * Set arguments.
     *
     * This method allow the
     * service container to
     * initialize the class
     * attributes.
     *
     * @param Router $router     The router service
     * @param string $pageRoutes The route to use on page generation
     * @param string $limitRoute The route to use on limit setter url
     */
    public function setArguments(Router $router, $pageRoutes = null, $limitRoute = null)
    {
        $this->router = $router;
        $this->pageRoutes = $pageRoutes;
        $this->limitRoute = $limitRoute;
    }

    /**
     * Set callbacks.
     *
     * This method allow the
     * container to initialize
     * the callbacks.
     */
    public function setCallBacks()
    {
        $this->addCallback('onPagerList', function () {
            return "class='pagination'";
        });

        $this->addCallback('onPagerListPreppend', function () {
            return '
<li>
    <a href="#PaginatorPrevious" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
    </a>
</li>
                ';
        }, true);

        $this->addCallback('onPagerListAppend', function () {
            return '
<li>
    <a href="#PaginatorNext" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
    </a>
</li>
                ';
        }, true);

        if ($this->pageRoutes !== null) {
            $this->addCallback('onHref', function ($type, $process, $row, $data) {

                return $data['router']->generate(
                    $data['route'],
                    array('page' => $data['page'], 'limit' => $data['limit'])
                );

            }, true, array('router' => $this->router, 'route' => $this->pageRoutes));
        }

        $this->addCallback('onSelectLabelStart', function () {
            return '<!-- ';
        }, true);

        $this->addCallback('onSelectLabelStop', function () {
            return ' --!>';
        }, true);

        $this->addCallback('onSelectStart', function () {
            return "<div class='input-group'>";
        }, true);

        $this->addCallback('onSubmitStop', function () {
            return '</div>';
        }, true);

        $this->addCallback('onSelect', function () {
            return array('attr' => array('class' => 'form-control', 'title' => 'limit per page'));
        }, true);

        $this->addCallback('onSelector', function () {
            return 'class=cs-paginator-selector';
        });

        $this->addCallback('onSubmitStart', function () {
            return "<span class='input-group-btn'>";
        }, true);

        $this->addCallback('onSubmitStop', function () {
            return '</span>';
        }, true);

        $this->addCallback('onSubmit', function () {
            return array('label' => 'limit', 'attr' => array('class' => 'btn btn-default'));
        }, true);

        if ($this->limitRoute !== null) {
            $this->addCallback('onLimitFirst', function ($type, $process, $row, $data) {
                return array('action' => $data['router']->generate($data['route'], array()));
            }, true, array('router' => $this->router, 'route' => $this->limitRoute));
        }
    }
}
