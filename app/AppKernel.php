<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    /**
     * @return array
     */
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
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Ali\DatatableBundle\AliDatatableBundle(),

            new Cwd\GenericBundle\CwdGenericBundle(),
            new Cwd\Admin\MetronicBundle\CwdAdminMetronicBundle(),

            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new C33s\SymfonyConfigManipulatorBundle\C33sSymfonyConfigManipulatorBundle(),
            new FOS\UserBundle\FOSUserBundle(),

            new Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle(),

            new Aspetos\Bundle\LegacyBundle\AspetosLegacyBundle(),
            new Aspetos\Bundle\AdminBundle\AspetosAdminBundle(),

            new Cwd\MediaBundle\CwdMediaBundle(),
            new Cwd\VtigerBundle\CwdVtigerBundle(),
            new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),

            new KPhoen\DoctrineStateMachineBundle\KPhoenDoctrineStateMachineBundle(),
            new Aspetos\Bundle\FrontendBundle\AspetosFrontendBundle(),
            new Lexik\Bundle\TranslationBundle\LexikTranslationBundle(),
            new Cwd\WordpressApiBundle\CwdWordpressApiBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($environment, $debug)
    {
        date_default_timezone_set('Europe/Vienna');

        parent::__construct($environment, $debug);
    }
}
