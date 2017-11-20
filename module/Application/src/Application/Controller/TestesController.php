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
    
    public function testgraphAction() {
        return $this->makeView(['titulo' => 'teste']);
    }
    
    public function getGraphAction() {        
        /* @var $audiometria \Tcmed\Entity\Audiometria */
        $ydata = [10,15,20,20,15,10,5,10];
        $ydata2 =    [5,10,15,5,10];
        $lado = 'direito';
        $graphModel = $this->getGraphModel('getGraphOrelha');
        $graphModel->setVars(compact("ydata", "ydata2", "lado"));
        // Add the plot to the graph
        $graphModel->printGraph();
    }
    
    public function testeGraphBarAction() {        
        $dataView = $this->getDataView('Teste ' . $this->getTitle(), 'testeGraphBar');
        return $this->makeView(compact("dataView"));
    }
    
    public function getGraphPieAction() {        
        /* @var $audiometria \Tcmed\Entity\Audiometria */
        // Some data
        $data = array(70,30);
        $graphModel = $this->getGraphModel('getGraphPie', 'GraphPie');
        $graphModel->setVars(compact("data"));
        // Add the plot to the graph
        $graphModel->printGraph();
    }
    
    public function testeGraphPieAction() {        
        $dataView = $this->getDataView('Teste ' . $this->getTitle(), 'testeGraphPie');
        return $this->makeView(compact("dataView"));
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
            'to' => 'watakabe05@gmail.com',
            'cc' => ['paulo@tcmed.com.br'],
            'cco' => ['watakabe98@hotmail.com'],
            'toName' => 'Paulo Japa',
            'subject' => 'testando',
            'data' => $data,
//            'anexos' => ['/var/www/tcmed/data/asoImages/asoVV.png','/var/www/tcmed/data/medicoAssin/medico1.jpg','/var/www/tcmed/data/anexos/mpdf.pdf'],
        ]);
        echo 'enviado';
        return $this->setRedirect();
    }
    
    public function generateAction() {
        $dataView = $this->getDataView('Gerar Seters e Geters ' . $this->name, 'generate');
        $options['path']        = "/var/www/tcmed/module/Application/src/Tcmed/Entity/LiberacaoExame.php";
        $options['returnClass'] = "\Tcmed\Entity\LiberacaoExame";
        $options['author']      = "Paulo Watakabe <watakabe05@gmail.com>";
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
        $path = "/var/www/tcmed/data/medicoAssin/medico2.jpg";
        
        return $this->makeView(compact("path"));
        
        $path = "/var/www/tcmed/data/asoImages/asoVV.png";
        
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

