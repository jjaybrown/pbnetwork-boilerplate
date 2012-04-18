<?php
use App\Controller as AppController;
use App\Classes\MCAPI as MCAPI;

class Site_IndexController extends AppController
{

    public function init()
    {
        parent::init();
    }
        
    public function indexAction()
    {
        if ($this->_request->isPost())
        {
            $data = $this->_request->getPost();
            /*$api = new MCAPI("3d731852c1bbaa408abe64f3848f1f62-us2");
            $retval = $api->listSubscribe("6684851a7a", $data["email"], array(), 'html', false);

            if($api->errorCode){
                // There was an error
                $this->_flashMessenger->addMessage(array('error' => $api->errorMessage));
            }else{
                // Successful
            }*/
            
            $validator = new Zend_Validate_EmailAddress();
            if ($validator->isValid($data["email"]))
            {
                $mail = new Zend_Mail();
                $mail->setBodyText('New Subscriber: '.$data["email"]);
                $mail->setFrom('no-reply@thepaintballnetwork.co.uk', 'the Paintball Network');
                $mail->addTo('subscriptions@thepaintballnetwork.co.uk', 'Subscriptions');
                $mail->setSubject('New Subscription');
                $mail->send();
                
                // Send Confirmation
                $mail = new Zend_Mail();
                $mail->setBodyText('Thank you for subscribing, we\'ll send you updates and hopefully soon, an invite to an early preview of the site.');
                $mail->setFrom('no-reply@thepaintballnetwork.co.uk', 'the Paintball Network');
                $mail->addTo($data["email"], '');
                $mail->setSubject('Your Subscription');
                $mail->send();
                
            }else{
                $this->view->error = "Invalid email address";
            }
        }
    }
    
    public function headerAction()
    {
        $container = new Zend_Navigation(
            array(
                array(
                    'action'     => 'index',
                    'controller' => 'index',
                    'module'     => 'site',
                    'label'      => 'Home'
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'index',
                    'module'        => 'news',
                    'label'      => 'News',
                    'pages' => array(
                        array(
                            'action' => 'archive',
                            'controller' => 'index',
                            'module' => 'news',
                            'label' => 'Archive'
                        )
                    )
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'index',
                    'module'        => 'event',
                    'label'      => 'Events',
                    'pages' => array(
                        array(
                            'action' => 'index',
                            'controller' => 'calendar',
                            'module' => 'event',
                            'label' => 'Calendar'
                        )
                    )
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'index',
                    'module'        => 'community',
                    'label'      => 'Community',
                    'pages' => array(
                        array(
                            'action' => 'index',
                            'controller' => 'roundup',
                            'module' => 'community',
                            'label' => 'Roundup'
                        ),
                        array(
                            'action' => 'index',
                            'controller' => 'forums',
                            'module' => 'community',
                            'label' => 'Forums'
                        ),
                        array(
                            'action' => 'index',
                            'controller' => 'groups',
                            'module' => 'community',
                            'label' => 'Groups'
                        )
                    )
                ),
                array(
                    'action'     => 'index',
                    'controller' => 'index',
                    'module'     => 'magazine',
                    'label'      => 'Paintball Scene Magazine'
                )
            )
        );

        $this->view->navigation($container);
    }

    public function footerAction()
    { 
        /*$cache = Zend_Registry::get('cache');

        if ($cache->contains('timestamp')) {
            $timestamp = $cache->fetch('timestamp');
            $this->view->cachedTimestamp = true;
        } else {
            $timestamp = time();
            $cache->save('timestamp', $timestamp);
        }

        $this->view->timestamp = $timestamp;*/
    }
}