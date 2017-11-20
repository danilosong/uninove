<?php

/*
 * To change this license 
 */

namespace Application\Form\FieldSet;

use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Description of AbstractFieldSet
 * Metodos basico para criar um filed set para formularios dinamicos
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
abstract class AbstractFieldSet extends Fieldset{
    
    /**
     * Objeto para manipular dados do BD
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em; 
    
    /**
     * Para setar o form corretamente para edição de dados
     * @var bollean 
     */
    protected $isEdit = false;
    
    /**
     * Para setar o form corretamente para edição de dados
     * @var bollean 
     */
    protected $isAdmin = false;
    
    /**
     * O nome do Modulo
     * @var string
     */
    protected $moduloName;

    
    public function __construct($name,$options=[])
     {
        parent::__construct($name,$options);
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setDefaultAttributes(['onKeyDown' => 'return changeEnterToTab(this,event)']);
     }
        
    /**
     * Nome do campo oculto do form
     * @param string $name
     * @return \Zend\Form\Element\Hidden
     */
    public function setInputHidden($name){
        $hidden = new \Zend\Form\Element\Hidden();
        $hidden->setAttributes(['name' => $name, 'id' => $name]);
        $this->add($hidden);
        return $hidden;
    }
        
    /**
     * Monta os paramentro basicos para se fazer um input text
     * @param string $name
     * @param string $label
     * @param array $attributes
     * @return \Zend\Form\Element\Text
     */
    public function setInputText($name,$label='',$attributes=[]){
        $text = new \Zend\Form\Element\Text();
        $text->setAttributes(['name'=> $name, 'id' => $name]);
        if(is_array($label)){
            $attributes = $label;
            $label = '';
        }
        if(empty($label)){
            $label = ucfirst($name);
        }
        $text->setLabel($label);
        $text->setAttributes($this->getDefaultAttributes());
        if(!empty($attributes)){
            $text->setAttributes($attributes);
        }        
        $this->add($text);
        return $text;
    }
    
    /**
     * Funçao basica para inserir um radio no formulario
     * 
     * @param string $name   
     * @param string $label
     * @param array $options
     * @param array $attributes
     * @return \Zend\Form\Element\Radio
     */
    public function setInputRadio($name, $label, $options, $attributes=[]){
        $radio = new \Zend\Form\Element\Radio();
        $radio->setAttributes(['name'=> $name, 'id' => $name]);
        $radio->setAttributes($this->getDefaultAttributes());
        $radio->setLabel($label);
        if(!empty($attributes)){
            $radio->setAttributes($attributes);
        }
        $radio->setOptions($options);
        $this->add($radio); 
        return $radio;       
    }
    
    /**
     * Funçao basica para inserir um checkbox no formulario
     * 
     * @param string $name   
     * @param string $label
     * @param array $options
     * @param array $attributes
     * @return \Zend\Form\Element\Checkbox
     */
    public function setInputCheckbox($name, $label, array $options, array $attributes=[]){
        $checkbox = new \Zend\Form\Element\Checkbox();
        $checkbox->setAttributes(['name'=> $name, 'id' => $name]);
        $checkbox->setAttributes($this->getDefaultAttributes());
        $checkbox->setUseHiddenElement(FALSE);
        $checkbox->setLabel($label);
        if(!empty($attributes)){
            $checkbox->setAttributes($attributes);
        }
        $checkbox->setOptions($options);
        $this->add($checkbox); 
        return $checkbox;
    }
    
    /**
     * 
     * @param string $name
     * @param string $label
     * @param array $options
     * @param array $attributes
     * @return \Zend\Form\Element\MultiCheckbox
     */
    public function setInputMultiCheckbox($name, $label, array $options, array $attributes = []) {
        $mCheckebox = new \Zend\Form\Element\MultiCheckbox();
        $mCheckebox->setAttributes(['name'=> $name, 'id' => $name]);
        $mCheckebox->setAttributes($this->getDefaultAttributes());
        $mCheckebox->setUseHiddenElement(FALSE);
        if(!empty($attributes)){
            $mCheckebox->setAttributes($attributes);
        }
        $mCheckebox->setLabel($label)->setOptions(['value_options' => $options]);
        $this->add($mCheckebox);
        return $mCheckebox;
    }
        
    
    /**
     * Retorna um array com as opções padrão de um formulario.
     * @param boolean $onBlur
     * @return array
     */
    function getDefaultAttributes($onBlur=FALSE) {
        if($onBlur){
            return array_merge($this->defaultAttributes, ['onBlur' => 'toUp(this)']);            
        }
        return $this->defaultAttributes;
    }

    function setDefaultAttributes(array $defaultAttributes = []) {
        $this->defaultAttributes = $defaultAttributes;
        return $this;
    }

}
