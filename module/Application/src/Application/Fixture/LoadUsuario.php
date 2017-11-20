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
class LoadUsuario extends AbstractFixture implements OrderedFixtureInterface{
    
    public $entity;
    
    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    public $manager;
    
    /**
     *
     * @var \Application\Entity\Repository\AppResourceRepository
     */
    public $rpRole;
    
    /**
     * Vai acumulando os papeis usados(cache)
     * 
     * @var array
     */
    public $role = [];
    
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
        return 4;
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
    
    public function load(ObjectManager $manager) {
        $this->entity = 'Application\Entity\Usuario';
        echo 'entity ', $this->entity ; 
        $this->em = $manager;
        $mysql = new Mysql();
        $mysql->truncate($this->entity,$manager);
        $this->rpRole     = $this->em->getRepository("\Application\Entity\AppRole");
        
        $this->get('Paulo Watakabe' , 'PauloSis'  , '123', 'watakabe05@gmail.com'   , 'ATIVO', 'true' , 'senha padrão', 'true', 'admin'   , 'admin'        );
        $this->get('Danilo Dorotheu', 'DaniloSis' , '123', 'danilo@tcmed.com.br'    , 'ATIVO', 'false', 'senha padrão', 'true', 'admin'   , 'admin'        );
        $this->get('Allan Davini'   , 'AllanSis'  , '123', 'allan@tcmed.com.br'     , 'ATIVO', 'false', 'senha padrão', 'true', 'admin'   , 'ti_nivel10'   );
        $this->get('Kalini'         , 'kalini'    , '123', 'kalini@tcmed.com.br'    , 'ATIVO', 'false', 'senha padrão', 'true', 'Auxiliar', 'adm_nivel10'  );
        $this->get('Giulia'         , 'Giulia'    , '123', 'giulia@tcmed.com.br'    , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'recep_nivel30');
        $this->get('Camila'         , 'Camila'    , '123', 'camila@tcmed.com.br'    , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'recep_nivel30');
        $this->get('Sthefany'       , 'Sthefany'  , '123', 'sthefany@tcmed.com.br'  , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'adm_nivel10'  );
        $this->get('Miriam'         , 'Miriam'    , '123', 'miriam@tcmed.com.br'    , 'ATIVO', 'false', 'senha padrão', 'true', 'Gerente' , 'ger_nivel10'  );
        $this->get('Aldo'           , 'Aldo'      , '123', 'aldo.lelis@tcmed.com.br', 'ATIVO', 'false', 'senha padrão', 'true', 'Diretor' , 'dir_nivel10'  );
        $this->get('Arthur'         , 'Arthur'    , '123', 'arthurmr60@gmail.com'   , 'ATIVO', 'false', 'senha padrão', 'true', 'Diretor' , 'dir_nivel10'  );
        $this->get('Sala-1'         , 'sala1'     , '123', 'sala1@tcmed.com.br'     , 'ATIVO', 'false', 'senha padrão', 'true', 'Sala'    , 'med_nivel10'  );
        $this->get('Sala-2'         , 'sala2'     , '123', 'sala2@tcmed.com.br'     , 'ATIVO', 'false', 'senha padrão', 'true', 'Sala'    , 'med_nivel20'  );
        $this->get('Sala-3'         , 'Sala-3'    , '123', 'sala3@tcmed.com.br'     , 'ATIVO', 'false', 'senha padrão', 'true', 'Sala'    , 'med_nivel10'  );
        $this->get('Sala-4'         , 'Sala-4'    , '123', 'sala4@tcmed.com.br'     , 'ATIVO', 'false', 'senha padrão', 'true', 'Sala'    , 'med_nivel20'  );
        $this->get('Recepção-1'     , 'recepcao1' , '123', 'recepcao1@tcmed.com.br' , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'recep_nivel10');
        $this->get('Recepção-2'     , 'recepcao2' , '123', 'recepcao2@tcmed.com.br' , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'recep_nivel20');
        $this->get('Recepção-3'     , 'recepcao3' , '123', 'recepcao3@tcmed.com.br' , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'recep_nivel30');
        $this->get('ti20'           , 'ti20'      , '123', 'recepcao3@tcmed.com.br' , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'ti_nivel20'   );
        $this->get('Sistema'        , 'Sistema'   , '123', 'sistema@tcmed.com.br'   , 'ATIVO', 'false', 'senha padrão', 'true', 'Recepção', 'admin'        );
        
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
    
    public function getRole($role) {
        if (!isset($this->role[$role])) {
            $this->role[$role] = $this->rpRole->findOneByNome($role);
            if (!$this->role[$role]) {
                echo 'Recursos nao encontrado ', $role;
                die;
            }
        }
        return $this->role[$role];
    }

    /**
     * Gera um registro para parametro em form de array e o inclui em data
     * 
     * @author Allan Davini <allan_mv19@hotmail.com>
     * @param string $nomeUsuario
     * @param string $nickname
     * @param string $senhausuario
     * @param string $emailUsuario
     * @param string $situacao
     * @param boolean $is_admin
     * @param string $lembreteSenha
     * @param boolean $active
     * @param string $tipoUsuario
     * @param string $role
     */
    public function get($nomeUsuario,$nickname, $senhausuario='123', $emailUsuario, $situacao='ATIVO', $is_admin, $lembreteSenha='senha padrão', $active='true', $tipoUsuario, $role) {
        if(empty($nickname)){
            $nickname = $nomeUsuario;
        }
        $this->data[] = [
                "nomeUsuario"   => $nomeUsuario,
                "nickname"      => $nickname,
                "senhaUsuario"  => $senhausuario,
                "emailUsuario"  => $emailUsuario,
                "situacao"      => $situacao,
                "is_admin"      => $is_admin,
                "lembreteSenha" => $lembreteSenha,
                "active"        => $active,
                "tipoUsuario"   => $tipoUsuario,
                "role"          => $this->getRole($role), 
        ];
    }
}
