<?php

namespace Application\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Application\Conexao\Mysql;

/**
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @tutorial https://github.com/doctrine/data-fixtures
 * @version 2.0
 * @since 21-025-2016
 */
class LoadParametros extends AbstractFixture implements OrderedFixtureInterface {

    public $entity;
    
    public $mysql;
    
    public $data;

    public function getOrder() {
        return 7;
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
        $this->entity = 'Application\Entity\Parametros';
        echo 'entity ', $this->entity ; 
        // Limpar tabela
        if(!is_null($manager)){
            $this->getMysql()->truncate($this->entity,$manager);
        }
        
        $this->get('webServiceForCep'        , 'http://republicavirtual.com.br/web_cep.php?formato=json&cep=', "url apontando para web service para pesquisa de cep");
        $this->get('logica_cond'             , 'SIM'                                                         , 'SIM'       );
        $this->get('logica_cond'             , 'NÃO'                                                         , 'NAO'       );
        $this->get('opcao_sexo'              , 'MASCULINO'                                                   , 'MASCULINO' );
        $this->get('opcao_sexo'              , 'FEMININO'                                                    , 'FEMININO'  );
        $this->get('opcao_estadocivil'       , 'SOLTEIRO(A)'                                                 , 'SOLTEIRO'  );
        $this->get('opcao_estadocivil'       , 'CASADO(A)'                                                   , 'CASADO'    );
        $this->get('opcao_estadocivil'       , 'VIUVO(A)'                                                    , 'VIÚVO'     );
        $this->get('opcao_estadocivil'       , 'DIVORCIADO(A)'                                               , 'DIVORCIADO');
        $this->get('opcao_estadocivil'       , 'AMASIADO(A)'                                                 , 'AMASIADO'  );
        // opçoes para select status                                                                   
        $this->get('selectStatus'            , 'ATIVO'                                                         );
        $this->get('selectStatus'            , 'INATIVO'                                                       );
        $this->get('selectStatus'            , 'BLOQUEADO'                                                     );
        // opçoes para select tipo em usuario                                                                   
        $this->get('usuario_tipo_options'            , 'Administrador'                                             );
        $this->get('usuario_tipo_options'            , 'Diretor'                                                   );
        $this->get('usuario_tipo_options'            , 'Gerente'                                                   );
        $this->get('usuario_tipo_options'            , 'Lider'                                                     );
        $this->get('usuario_tipo_options'            , 'Colaborador'                                               );
         
        // route padrão do sistema onde ficara o menu do usuario padrao é app/default
        $this->get('defaultRoute'            ,'app/default'                     ,'route'  );
        // Menu padrão do sistema menu a ser exibido inicialmente na tela defaul Sistema
        $this->get('defaultMenu'             ,'Sistema'                         ,'menu'  );
        // Imagem padrao para exibir quando a imagem não for encontrada
        $this->get('APP_NO_IMAGEM'           ,'/var/www/tcmed/public/img/sem_imagem.jpg', 'ALL'  );
        
        $this->get('usuarioDir'             ,'/var/www/tcmed/data/usuarioDir/', 'all'  );

        // select para mes descrito
        $this->get('selectMes'  , 'Janeiro'               ,'01'  );
        $this->get('selectMes'  , 'Fevereiro'             ,'02'  );
        $this->get('selectMes'  , 'Março'                 ,'03'  );
        $this->get('selectMes'  , 'Abril'                 ,'04'  );
        $this->get('selectMes'  , 'Maio'                  ,'05'  );
        $this->get('selectMes'  , 'Junho'                 ,'06'  );
        $this->get('selectMes'  , 'Julho'                 ,'07'  );
        $this->get('selectMes'  , 'Agosto'                ,'08'  );
        $this->get('selectMes'  , 'Setembro'              ,'09'  );
        $this->get('selectMes'  , 'Outubro'               ,'10'  );
        $this->get('selectMes'  , 'Novembro'              ,'11'  );
        $this->get('selectMes'  , 'Dezembro'              ,'12'  );
        
        // select para mes numeral
        $this->get('selectMesN'             ,'01', '01'  );
        $this->get('selectMesN'             ,'02', '02'  );
        $this->get('selectMesN'             ,'03', '03'  );
        $this->get('selectMesN'             ,'04', '04'  );
        $this->get('selectMesN'             ,'05', '05'  );
        $this->get('selectMesN'             ,'06', '06'  );
        $this->get('selectMesN'             ,'07', '07'  );
        $this->get('selectMesN'             ,'08', '08'  );
        $this->get('selectMesN'             ,'09', '09'  );
        $this->get('selectMesN'             ,'10', '10'  );
        $this->get('selectMesN'             ,'11', '11'  );
        $this->get('selectMesN'             ,'12', '12'  );
         
        // Retorna o data pois sera tratado na fixture filha
        if(is_null($manager)){
            return $this->data;
        }
        
        foreach ($this->data as $item) {
            $ent = new $this->entity($item);
            $manager->persist($ent);
        }

        $manager->flush();
                        
        echo ' ok itens ', count($this->data) , PHP_EOL; 
    }

    /**
     * Gera um registro para parametro em form de array e o inclui em data
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $chave
     * @param string $descricao
     * @param string $conteudo
     * @param string $status
     */
    public function get($chave,$descricao, $conteudo='', $status='ativo') {
        if(empty($conteudo) AND $conteudo !== "0"){
            $conteudo = $descricao;
        }
        $this->data[] = [
                "chave"     => $chave,
                "descricao" => $descricao,
                "conteudo"  =>  $conteudo,
                "status"    => $status,
        ];
    }
    
    /**
     * Retirar um chave do array para nao gravar no bd.
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $chave
     * @return \Application\Fixture\LoadParametros
     */
    public function removeChave($chave='') {
        foreach ($this->data as $key => $data){
            if($data['chave'] == $chave){
                unset($this->data[$key]);
            }
        }
        return $this;
    }
}
