<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initConfig()
    {
        Zend_Registry::set('config', $this->getOptions());
        
        // Set application salt
        Zend_Registry::set("salt", "84fjfn393ks@dnc94843n,vs43");
    }
    
    public function _initAutoloaderNamespaces()
    {
        require_once APPLICATION_PATH .
            '/../library/Doctrine/Common/ClassLoader.php';

        require_once APPLICATION_PATH .
            '/../library/Symfony/Component/Di/sfServiceContainerAutoloader.php';

        sfServiceContainerAutoloader::register();
        $autoloader = \Zend_Loader_Autoloader::getInstance();

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');
        
        $fmmAutoloader = new \Doctrine\Common\ClassLoader('App');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'App');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Boilerplate');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Boilerplate');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL\Migrations');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Doctrine\DBAL\Migrations');
    }

    public function _initModuleLayout()
    {
        $front = Zend_Controller_Front::getInstance();

        $front->registerPlugin(
            new Boilerplate_Controller_Plugin_ModuleLayout()
        );
        
        $front->setParam('prefixDefaultModule', true);
        $eh = new Zend_Controller_Plugin_ErrorHandler();
        $front = Zend_Controller_Front::getInstance()->registerPlugin($eh);
    }

    public function _initServices()
    {
        $sc = new sfServiceContainerBuilder();
        $loader = new sfServiceContainerLoaderFileXml($sc);
        $loader->load(APPLICATION_PATH . "/configs/services.xml");
        Zend_Registry::set('sc', $sc);
    }

    public function _initLocale()
    {
        $config = $this->getOptions();
        
        try{
            $locale = new Zend_Locale(Zend_Locale::BROWSER);
        } catch (Zend_Locale_Exception $e) {
            $locale = new Zend_Locale($config['resources']['locale']['default']);
        }

        Zend_Registry::set('Zend_Locale', $locale);

        $translator = new Zend_Translate(
            array(
                'adapter' => 'Csv',
                'content' => APPLICATION_PATH . '/../data/lang/',
                'scan' => Zend_Translate::LOCALE_DIRECTORY,
                'delimiter' => ',',
                'disableNotices' => true,
            )
        );

        if (!$translator->isAvailable($locale->getLanguage()))
            $translator->setLocale($config['resources']['locale']['default']);

        Zend_Registry::set('Zend_Translate', $translator);
        Zend_Form::setDefaultTranslator($translator);
    }

    public function _initElasticSearch()
    {
        $es = new Elastica_Client();
        Zend_Registry::set('es', $es);
    }
    
    public function _initLocaleDateTime()
    {
        date_default_timezone_set('Europe/London');
    }

    /**
    * init jquery view helper, enable jquery, jqueryui, jquery ui css
    */

    public function _initJquery()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view'); //get the view object

        //add the jquery view helper path into your project
        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");

        //jquery lib includes here (default loads from google CDN)
        $view->jQuery()->enable()//enable jquery ; ->setCdnSsl(true) if need to load from ssl location
             ->setVersion('1.6.2')//jQuery version, automatically 1.5 = 1.5.latest
             ->setUiVersion('1.8')//jQuery UI version, automatically 1.8 = 1.8.latest
             ->addStylesheet('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-lightness/jquery-ui.css')//add the css
             ->uiEnable();//enable ui

    }

    /**
     * init auto-complete location list from database
     */
    public function _initAutoCompleteLocationList()
    {
        // Get locations from database
        $locations = array('Carlisle', 'Lancaster', 'Liverpool', 'Doncaster', 'Manchester', 'Newcastle', 'York');
        // Encode locations into JSON
        //$locations = \Zend_Json::encode($locations);
        // Save locations to registry
        Zend_Registry::set('locations', $locations);
    }

    /**
     * init shopping basket
     */
    public function _initBasket()
    {
        // Start Zend session
        Zend_Session::start();
        // Initilize cart object into session
        \App\Entity\Cart::init();

        // Set max item quantity available for purchase
        Zend_Registry::set('max_purchase_amount', 5);
        // Define currency - GBP
        Zend_Registry::set('currency', '&pound;');
    }
    
    /**
     * init Front Controller plugins 
     */
    public function _initAcl()
    {
        $this->bootstrap('frontController');
        $fc = $this->getResource('frontController');
        
        // Define acl from xml
        $aclDefinition = new \Zend_Config_Xml(APPLICATION_PATH.'/configs/acl.xml');
        
        // Create the ACL object from defination
        $acl = new App\Acl(\App\Acl::DB, $aclDefinition);
        
        // Initialise ACL and auth controller plugin
        $fc->registerPlugin(new App\Plugin\Auth($acl));
        
        return $acl;
    }
    
}