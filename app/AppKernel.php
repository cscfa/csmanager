<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Cscfa\Bundle\CSManager\CoreBundle\CscfaCSManagerCoreBundle(),
            // new Cscfa\Bundle\CSManager\DashboardBundle\CscfaCSManagerDashboardBundle(),
            new Cscfa\Bundle\CSManager\ProjectBundle\CscfaCSManagerProjectBundle(),
            // new Cscfa\Bundle\CSManager\TaskBundle\CscfaCSManagerTaskBundle(),
            new Cscfa\Bundle\CSManager\UserBundle\CscfaCSManagerUserBundle(),
            new Cscfa\Bundle\CSManager\ConfigBundle\CscfaCSManagerConfigBundle(),
            new Cscfa\Bundle\TwigUIBundle\CscfaTwigUIBundle(),
            new Cscfa\Bundle\SecurityBundle\CscfaSecurityBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Cscfa\Bundle\ToolboxBundle\CscfaToolboxBundle(),
            new Cscfa\Bundle\MailBundle\CscfaMailBundle(),
            new Cscfa\Bundle\CSManager\SecurityBundle\CscfaCSManagerSecurityBundle(),
            new Cscfa\Bundle\CSManager\NavbarBundle\CscfaCSManagerNavbarBundle(),
            new Cscfa\Bundle\NavBarBundle\CscfaNavBarBundle(),
            new Cscfa\Bundle\CacheSystemBundle\CscfaCacheSystemBundle(),
            new Cscfa\Bundle\DataGridBundle\CscfaDataGridBundle(),
            new Cscfa\Bundle\CSManager\TrackBundle\CscfaCSManagerTrackBundle(),
        );
        
        if (in_array($this->getEnvironment(), array(
            'dev',
            'test'
        ))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Cscfa\Bundle\QUnitTestBundle\CscfaQUnitTestBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }
        
        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
