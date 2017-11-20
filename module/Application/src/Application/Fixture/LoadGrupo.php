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
class LoadGrupo extends AbstractFixture implements OrderedFixtureInterface {

    public $entity;

    public function getOrder() {
        return 8;
    }

    public function load(ObjectManager $manager) {
        $this->entity = 'Application\Entity\Grupo';
        echo 'entity ', $this->entity;
        $mysql = new Mysql();

        $mysql->truncate($this->entity, $manager);
        $mysql->truncate($this->entity, $manager, "users_groups");

        $data = [
            [
                "nome"   => "TCMED",
                "status_msg"      => "entregue",
                "status_chat"  => "online",
                "status"  => "ATIVO",
            ],
        ];

        foreach ($data as $key => $item) {
            /* @var $ent \Application\Entity\Grupo */
            $ent = new $this->entity($item);
            $manager->persist($ent);
            $data[$key] = $ent;
        }
        $manager->flush();

        //Adicionando pessoas (TODOS) no grupo da Tecnomed
        $users = $manager->getRepository("\Application\Entity\User")->findAll();
        foreach ($users as $user) {
            $data[0]->addUsers($user);
            
        }
        $manager->persist($data[0]);
        $manager->flush();
        
        echo ' ok', PHP_EOL;
    }

}
