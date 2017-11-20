<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 24-05-2017
 */
class ColaboradorPonto extends AdmAbstractForm{

    protected $selectStatus;
    
    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 26-05-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\ColaboradorPontoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
        
        $this->setInputHidden('id');
        
        $this->setInputHidden('colaborador');
                
        $this->setInputText('colaboradorNome', 'Nome', ["value" => $this->name('colaborador')]);
        
        $this->setInputText('hora', 'Horas', ["value" => date('H:i')]);
        
        $this->setInputText('data', 'Data', ["value" => date('d/m/Y')]);
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->setInputText('ip', 'Ip', ["value" => $ip]);
        
        if(substr($ip, 0, 3) == "192"){
            $origem = "INTERNO";
        }else{
            $origem = "EXTERNO";
        }
        $this->setInputText('origem', 'Origem', ["value" => $origem]);
        
        $this->selectStatus = $this->getParametroChave("status_ponto", FALSE);
        $this->setInputSelect('tipo', 'Tipo', $this->selectStatus, ["value" => 'ENTRADA']);    
        

        $this->setInputText('obs', 'Observação');
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
    public function setReadOnly() {
        $inputs = [
            'hora'
            ,'colaboradorNome'
            ,'data'
            ,'ip'
            ,'origem'
        ];
        foreach ($inputs as $input){
            $this->readOnly($input);
        }
    }
    
    public function readOnly($input) {
        if($this->has($input)){
            $this->get($input)->setAttribute('readOnly', true);
        }
    }
    
    public function setEntrada($colaborador) {
        $data = new \DateTime('now');
        $data->setTime(0, 0, 0);
        /* @var $rpColaboradorPonto \Adm\Entity\Repository\ColaboradorPontoRepository */
        $rpColaboradorPonto = $this->em->getRepository('\Adm\Entity\ColaboradorPonto');
        /* @var $lastPonto \Adm\Entity\ColaboradorPonto */
        $lastPonto = $rpColaboradorPonto->pesquisa(array(
            'colaborador'=> $colaborador->getId(),
            'data'       => $data
            ))->orderBy('e.hora', 'DESC')
              ->setMaxResults(1)  
              ->getQuery()
              ->getOneOrNullResult();
        $nextTipo = '';
        if($lastPonto){
            $lastTipo = $lastPonto->getTipo();
            $flag = false;
            foreach ($this->selectStatus as $tipo){
                if($flag){
                    $nextTipo = $tipo;
                    break;
                }
                if($tipo == $lastTipo){
                    $flag = true;
                }
            }
        }
        if(!empty($nextTipo) AND $this->has('tipo')){
            $this->get('tipo')->setValue($nextTipo);
        }
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 08-06-2017
     * @return array
     */
    public function getSelectStatus() {
        return $this->selectStatus;
    }

}
