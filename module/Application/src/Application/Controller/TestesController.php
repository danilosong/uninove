<?php

namespace Application\Controller;

use Application\View\Model\GraphModel;

class TestesController extends CrudController
{
    public function __construct() {
        parent::__construct('teste');
        $this->setFormWithEntityManager(TRUE);
        $this->setLog(TRUE);
    }
    
    public function emailAction() {
        $email = $this->getEmail();
        $data = [
            [
                'Ação',
                'Balão',
                'Casão',
                'Dedé',
                'Eís',
            ],
            [
                'A',
                'B',
                'C',
                'D',
                'E',
            ],
        ];
        $email->enviaEmail([
            'to' => 'danilosong@outlook.com',
            'cc' => ['danilosong@outlook.com'],
            'cco' => ['danilosong@outlook.com'],
            'toName' => 'Danilo Song',
            'subject' => 'testando',
            'data' => $data,
        ]);
        echo 'enviado';
        return $this->setRedirect();
    }
    
    public function generateAction() {
        $dataView = $this->getDataView('Gerar Seters e Geters ');
        $options['path']        = "/var/www/uninove/module/Application/src/...";
        $options['returnClass'] = "\Adm\Entity\...";
        $options['author']      = "Danilo Song <danilosong@outlook.com>";
        $options['version']     = "1.0";
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            foreach ($post as $key => $inp){
                if(empty($inp)){
                    unset($post[$key]);
                }
            }
            $srv = new \Application\Service\GenerateSetsGets($post);
            $srv->openFile();
            $srv->readListOfVars();
            $srv->readListOfDes();
            $srv->generatePairs($post['opt']);
            die;
        }
        
        $form = $this->getForm();
        $form->setData($options);
        return $this->makeView(compact("form", "dataView"));
        
    }
    
    public function imageAction() {
        
        return $this->makeView(compact("path"));
        
        $ext = substr(trim($path), -3);
        
        // sem prefixo
        $token = md5(uniqid(""));

        // melhor, dificil de adivinhar
        $better_token = md5(uniqid(rand(), true));
        
        
        echo '<pre>', var_dump(uniqid("")), '</pre>';    
        echo '<pre>', var_dump($ext), '</pre>';    
        echo '<pre>', var_dump($token), '</pre>';    
        echo '<pre>', var_dump($better_token), '</pre>';    
        echo '<pre>', var_dump($path), '</pre>';    
        echo '<pre>', var_dump(function_exists("imagejpeg")), '</pre>';  
        echo '<pre>', var_dump(function_exists("imagepng")), '</pre>';   
        echo '<pre>', var_dump(function_exists("imagegif")), '</pre>';   
        echo '<pre>', var_dump(function_exists("imagewbmp")), '</pre>';    die;
        
        function_exists("imagejpeg");
        header("Content-type: image/jpeg");
        imagejpeg(imagecreatefromjpeg($path));      die;

        function_exists("imagepng");
        header("Content-type: image/png");
        imagepng(imagecreatefrompng($path));        die;
        
        function_exists("imagegif");
        header("Content-type: image/gif");
        imagegif(imagecreatefromgif($path));        die;
        
        function_exists("imagewbmp");
        header("Content-type: image/vnd.wap.wbmp");
        imagewbmp(imagecreatefromwbmp($path));      die;
        
        if (function_exists("imagegif")) {
            header("Content-type: image/gif");
            imagegif($im);
        } elseif (function_exists("imagejpeg")) {
            header("Content-type: image/jpeg");
            imagejpeg($im, "", 0.5);
        } elseif (function_exists("imagepng")) {
            header("Content-type: image/png");
            imagepng($im);
        } elseif (function_exists("imagewbmp")) {
            header("Content-type: image/vnd.wap.wbmp");
            imagewbmp($im);
        } else {
            die("No image support in this PHP server");
        }
    }

}

