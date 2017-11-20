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
class LoadResource extends AbstractFixture implements OrderedFixtureInterface{

    
    public $mysql;
    
    public $data;
    
    public function getOrder() {
        return 2;
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
        $this->entity = 'Application\Entity\AppResource';
        echo 'entity ', $this->entity ; 
        // Limpar tabela
        if(!is_null($manager)){
            $this->getMysql()->truncate($this->entity,$manager);
        }
        
        $this->get('menu:app:sistema');
        
        // Retorna o data pois sera tratado na fixture filha
        if(is_null($manager)){
            return $this->data;
        }
        
        foreach ($this->data as $item) {
            $manager->persist(new $this->entity($item)); 
        }

        $manager->flush();
                
        echo ' ok itens ', count($this->data) , PHP_EOL;
    }
    
    /**
     * 
     * @param string $name
     * @return nothing
     */
    public function get($name) {
        if(empty($name)){
            return;
        }
        $this->data[] = [
            "name" => $name,
        ];
    }

}
