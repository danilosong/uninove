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
class LoadContato extends AbstractFixture implements OrderedFixtureInterface{
    
    public $entity;

    public function getOrder() {
        return 10;
    }
    
    public function load(ObjectManager $manager) {
        $this->entity = 'Application\Entity\Contato';
        echo 'entity ', $this->entity ; 
        $mysql = new Mysql();
        
        $mysql->truncate($this->entity,$manager);
return;        
        $Paulo = $manager->getReference("\Application\Entity\User", 1);
        $Danilo = $manager->getReference("\Application\Entity\User", 2);
        $Allan = $manager->getReference("\Application\Entity\User", 3);
        $Andressa = $manager->getReference("\Application\Entity\User", 4);
        $kalini = $manager->getReference("\Application\Entity\User", 5);
        $Alexia = $manager->getReference("\Application\Entity\User", 6);
        $Beatriz = $manager->getReference("\Application\Entity\User", 7);
        $Camila = $manager->getReference("\Application\Entity\User", 8);
        $Sthefany = $manager->getReference("\Application\Entity\User", 9);
        $Miriam = $manager->getReference("\Application\Entity\User", 10);
        $Aldo = $manager->getReference("\Application\Entity\User", 11);
        $Arthur = $manager->getReference("\Application\Entity\User", 12);
        $Sala1 = $manager->getReference("\Application\Entity\User", 13);
        $Sala2 = $manager->getReference("\Application\Entity\User", 14);
        $Sala3 = $manager->getReference("\Application\Entity\User", 15);
        $Sala4 = $manager->getReference("\Application\Entity\User", 16);
        $Recepcao1 = $manager->getReference("\Application\Entity\User", 17);
        $Recepcao2 = $manager->getReference("\Application\Entity\User", 18);
        $Recepcao3 = $manager->getReference("\Application\Entity\User", 19);
        
        $users = [];
        $users[] = $Paulo;
        $users[] = $Danilo;
        $users[] = $Allan;
        $users[] = $Andressa;
        $users[] = $kalini;
        $users[] = $Alexia;
        $users[] = $Beatriz;
        $users[] = $Camila;
        $users[] = $Sthefany;
        $users[] = $Miriam;
        $users[] = $Aldo;
        $users[] = $Arthur;
        $users[] = $Sala1;
        $users[] = $Sala2;
        $users[] = $Sala3;
        $users[] = $Sala4;
        $users[] = $Recepcao1;
        $users[] = $Recepcao2;
        $users[] = $Recepcao3;

        foreach ($users as $user) {
            foreach($users as $contato){
                if($user == $contato){
                    continue;
                }
                $data[] = [
                    "userUser"   => $user,
                    "contatoUser"      => $contato,
               //   "grupoGrupo"  => NULL,
                ];

            }
            
        }
        
        
        foreach ($data as $item) {
            $ent = new $this->entity($item);
            $manager->persist($ent); 
        }

        $manager->flush();
                
        echo ' ok' , PHP_EOL; 
    }

}
