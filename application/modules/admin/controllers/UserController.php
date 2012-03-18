<?php

use App\Controller as AppController;
use App\Entity\User as User;
use App\Form\Admin\User\Edit as EditForm;

class Admin_UserController extends AppController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        // Check user list is in cache
        if ($this->_cache->contains('users')) {
            $users = $this->_cache->fetch('users');
        }else{
            $query = $this->_em->createQueryBuilder()
            ->select('u')
            ->from('App\Entity\User', 'u')
            ->getQuery();
            
            // Get users from query
            $users = $query->getResult();
            
            // Save users to cache
            $this->_cache->save('users', $users);
        }
        $this->view->users = $users;
    }
    
    public function editAction()
    {   
        $id = $this->_request->getParam('id');
        // Create new user edit form
        $form = new EditForm($id);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {     
                // Process registration data
                $data = $request->getPost();
                
                // Get user object with id of the user we've edited
                $user = $this->_em->find('App\Entity\User', $id);
                
                // Update user object
                $user->setUsername($data['_username']);
                $user->setEmailAddress($data['_emailAddress']);
                $user->setActiveStatus($data['_active']);
                
                // Merge it with our persisted object
                $this->_em->persist($this->_em->merge($user));
                // Save updated object
                $this->_em->flush();
                
                // Clear cache
                $this->_cache->delete('users');
                
                // Redirect back to users front page
                $this->_helper->redirector(array('module' => 'admin', 'controller' => 'user', 'action' => 'index'));
            }
        }else{
            // Get user data from id
            $query = $this->_em->createQueryBuilder()
                ->select('u')
                ->from('App\Entity\User', 'u')
                ->where('u._id = ?1')
                ->setParameter(1, $id)
                ->getQuery();
            
            // Persist user object
            $object = $query->getResult();
            $this->_em->persist($object[0]);
            
            // Populate user as array
            $data = $query->getArrayResult();
            $form->populate($data[0]);
            $this->view->form = $form;
        }
    }
    
    public function deleteAction()
    {        
        // Get Id of user to delete
        $id = $this->_request->getParam('id'); 
        if(isset($id))
        {
            // Remove from entity manager
            $user = $this->_em->find('App\Entity\User', $id);
            $this->_em->remove($user);

            // Commit unit of work
            $this->_em->flush();
            
            // Clear cache
            $this->_cache->delete('users');
        }
        
        // Redirect back to users front page
        $this->_helper->redirector(array('module' => 'admin', 'controller' => 'user', 'action' => 'index'));
    }
}





