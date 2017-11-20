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
class LoadPrivilege extends AbstractFixture implements OrderedFixtureInterface {

    /**
     *
     * @var \Application\Entity\Repository\AppResourceRepository
     */
    public $rpRole;
    
    /**
     *
     * @var \Application\Entity\Repository\AppRoleRepository
     */
    public $rpResource;
    
    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager 
     */
    public $em;
    
    /**
     * Vai acumulando os papeis usados(cache)
     * 
     * @var array
     */
    public $role = [];
    
    /**
     * Vai acumulando os recursos usados(cache)
     * 
     * @var array
     */
    public $resource = [];
    
    /**
     * Conexão extra com o BD utilizando PDO
     * 
     * @var \Application\Conexao\Mysql 
     */
    public $mysql;
    
    /**
     * Dados a serem gravados no bd
     * 
     * @var array 
     */
    public $data = [];
    
    public function getOrder() {
        return 3;
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
        $this->entity = 'Application\Entity\AppPrivilege';
        echo 'entity ', $this->entity;
        if(!is_null($manager)){
            $this->em = $manager;
            $this->getMysql()->truncate($this->entity,$manager);
            $this->rpResource = $this->em->getRepository("\Application\Entity\AppResource");
            $this->rpRole     = $this->em->getRepository("\Application\Entity\AppRole");
        }

        // Menu sistema para Admin
        $this->generate('menu:app:sistema', "allow", ['admin']);

        // Retorna o data pois sera tratado na fixture filha
        if(is_null($manager)){
            return $this->data;
        }
        
        foreach ($this->data as $item) {
            $this->em->persist(new $this->entity($item));
        }

        $this->em->flush();

        echo ' ok itens ', count($this->data) , PHP_EOL; 
    }

    public function generate($resource, $privilege,array $roles) {
        if(!isset($this->resource[$resource])){
            $this->resource[$resource] = $this->rpResource->findOneByName($resource); 
            if(!$this->resource[$resource]){
                echo 'Recursos nao encontrado ' , $resource;
                die;
            }
        }
        foreach ($roles as $role) {
            if(!isset($this->role[$role])){
                $this->role[$role] = $this->rpRole->findOneByNome($role); 
                if(!$this->role[$role]){
                    echo 'Recursos nao encontrado ' , $resource;
                    die;
                }
            }
            $this->data[] = [
                'role' => $this->role[$role],
                'resource' => $this->resource[$resource],
                'nome' => $privilege,
            ];
        }
    }
    
    public function generateArray(array $resources, $privilege,array $roles) {
        foreach ($resources as $resource){            
            if(!isset($this->resource[$resource])){
                $this->resource[$resource] = $this->rpResource->findOneByName($resource); 
                if(!$this->resource[$resource]){
                    echo 'Recursos nao encontrado ' , $resource;
                    die;
                }
            }
            foreach ($roles as $role) {
                if(!isset($this->role[$role])){
                    $this->role[$role] = $this->rpRole->findOneByNome($role); 
                    if(!$this->role[$role]){
                        echo 'Recursos nao encontrado ' , $resource;
                        die;
                    }
                }
                $this->data[] = [
                    'role' => $this->role[$role],
                    'resource' => $this->resource[$resource],
                    'nome' => $privilege,
                ];
            }
        }
    }
}
