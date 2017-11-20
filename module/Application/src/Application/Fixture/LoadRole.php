<?php

namespace Application\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;

use Application\Conexao\Mysql;

/**
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @tutorial https://github.com/doctrine/data-fixtures
 * @version 1.0
 */
class LoadRole extends AbstractFixture implements OrderedFixtureInterface{

    public $mysql;
    
    public $data;
    
    public function getOrder() {
        return 1;
    }
    
    /**
     * 
     * @return \Application\Conexao\Mysql
     */
    public function getMysql() {
        if(is_null($this->mysql)){
            $this->mysql = new Mysql();
        }
        return $this->mysql;
    }

    public function load(ObjectManager $manager = null) {
        $this->entity = 'Application\Entity\AppRole';
        echo 'entity ', $this->entity ; 
        // Limpar tabela
        if(!is_null($manager)){
            $this->getMysql()->truncate($this->entity,$manager);
        }
        
        $this->get('admin'     , TRUE);
        $this->get('visitante');
        
        // Retorna o data pois sera tratado na fixture filha
        if(is_null($manager)){
            return $this->data;
        }
        
        foreach ($this->data as $item) {
            if ($item["parent"] != null) {
                $item["parent"] = $this->getParent($item["parent"]);
            }
            $manager->persist(new $this->entity($item)); 
        }
        $manager->flush();
        
        echo ' ok itens ', count($this->data) , PHP_EOL;
    }

    public function get($n, $a=null, $p=null) {
        $this->data[] = [
            'nome' => $n,
            'isAdmin'   => $a,
            'parent'  => $p,
        ];
    }

    /**
     * Retorna o Role parent
     * 
     * @param int $idRole
     * @return \Application\Entity\AppRole|null
     */
    public function getParent($role) {
        return $this->em->getRepository($this->entity)->findOneBy(['role' => $role]);
    }
}
