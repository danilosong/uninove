<?php

namespace Application\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;    

use Application\Conexao\Mysql;


/**
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @Author Allan Davini <allan_mv19@hotmail.com>
 * @tutorial https://github.com/doctrine/data-fixtures
 * @version 1.0
 */
class LoadMensagem extends AbstractFixture implements OrderedFixtureInterface{
    
    public $entity;

    public function getOrder() {
        return 9;
    }
    
    public function load(ObjectManager $manager) {
        $this->entity = 'Application\Entity\Mensagem';
        echo 'entity ', $this->entity ; 
        $mysql = new Mysql();
        
        $mysql->truncate($this->entity,$manager);
        
        $data = [
            [
                "texto"   => "teste",
                "link"      => "asdasfsdfgsdag",
            ],
            
            
            
        ];           
        
        foreach ($data as $item) {
            $ent = new $this->entity($item);
            $manager->persist($ent); 
        }

        $manager->flush();
                
        echo ' ok' , PHP_EOL; 
    }

}
