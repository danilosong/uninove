<?php

/*
 * License
 */

namespace Application\Controller;

/**
 * Description of GrupoRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */

class UsersController extends CrudController
{

    public function __construct() {
        parent::__construct('user');
        $this->setFormWithEntityManager(TRUE);
    }
    
    public function allAction() {
        /* @var $user \Application\Entity\User */
        $users = $this->getEm()->getRepository($this->entity)->findAll();
        foreach ($users as $user) {
            $user->myContatos = $users;
            $this->getEm()->persist($user);
        }
        $this->getEm()->flush();
        
        echo 'Foi criado contatos para (', count($users), ') usuarios com (', count($users), ') contatos cada.';
        die;
    }
    
}

