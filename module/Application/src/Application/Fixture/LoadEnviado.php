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
class LoadEnviado extends AbstractFixture implements OrderedFixtureInterface{
    
    public $entity;

    public function getOrder() {
        return 11;
    }
    
    public function load(ObjectManager $manager) {
        $this->entity = 'Application\Entity\Enviado';
        echo 'entity ', $this->entity ; 
        $mysql = new Mysql();
        
        $mysql->truncate($this->entity,$manager);
        
        $Paulo = $manager->getReference("\Application\Entity\User", 1);
        $Danilo = $manager->getReference("\Application\Entity\User", 2);
        $Allan = $manager->getReference("\Application\Entity\User", 3);
        
        $Mensagem = $manager->getReference("\Application\Entity\Mensagem", 1);
        $Grupo = $manager->getReference("\Application\Entity\Grupo", 1);

        $data = [
            [
                "dateEnviado"   => "25/08/2015",
                "dateRecebido"      => "25/08/2015",
                "mensagemMensagem"  => $Mensagem,
                "fromUser"  => $Danilo,
                "toUser"  => $Paulo,
                "toGrupo"  => $Grupo,
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
