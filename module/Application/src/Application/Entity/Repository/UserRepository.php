<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Entity\Repository;

/**
 * Description of UserRepository
 *
 * @author user
 */
class UserRepository extends AbstractRepository {

    /**
     * Auto complete em ajax esta função retorna as entitys encontradas
     * com a ocorrencia passada por parametro
     * @param string $user
     * @return array
     */
    public function autoComp($column = 'nome', $data = '', $anotherFilters = array(), $orderBy = '', $qtd = 40) {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('u')
                ->from('Application\Entity\User', 'u')
                ->where("u.nome LIKE :nome")
                ->setParameter('nome', trim($column))
                ->setMaxResults(20)
                ->getQuery()
                ;
        return $query->getResult();
    }
            
    /**
     * Pesquisa quem esta diferente de busy(offline) na tabela user 
     * No tempo menor que o especificado no param $date
     * Caso encontre alguem ele troca o status para busy e atualiza statusDatetime
     * @param \DateTime $date
     */
    public function setAllInactiveOffLine(\DateTime $date){
        /* @var $entity \Application\Entity\User */
        $em = $this->getEntityManager();
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('u')
                ->from('Application\Entity\User', 'u')
                ->where("u.accessDatetime < :date AND u.statusChat NOT LIKE 'offline'")
                ->setParameter('date', $date)
                ->getQuery()
                ;
        $users =  $query->getResult();
        $now = new \DateTime('now');
        echo "ok ", $date->format('d/m/Y H:i:s'), "\r\n";
        foreach ($users as $entity) {
            echo $entity->getNome(), "\r\n";
            $entity->setStatusChat('offline');
            $entity->setStatusDatetime($now);
            $em->persist($entity);
        }
        $em->flush();
    }
    
    /**
     * Atualiza campo accessDatetime na tabela user 
     * Assim indica que ainda esta diferente de busy no sistema de chat
     * 
     * @param string | integer $id
     */
    public function upgradeMeIsOnline($id) {
        /* @var $user \Application\Entity\User */
        $user = $this->find($id);
        $user->setAccessDatetime('');
        $this->getEntityManager()->flush($user);        
    }
}
