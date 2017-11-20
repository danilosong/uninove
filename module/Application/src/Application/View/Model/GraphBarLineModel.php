<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\View\Model;


require_once getcwd() . '/vendor/jpgraph/src/jpgraph.php';
require_once getcwd() . '/vendor/jpgraph/src/jpgraph_line.php';


/**
 * Description of GraphBarLineModel
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @version 1.0  
 * @since 28-12-2016 
 */
class GraphBarLineModel extends GraphBarModel{
    
    /**
     * Facilitador Cria uma instacia de lineplot com os dados do eixo y e x ja existentes
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @return \LinePlot
     */
    public function newLinePlot($datay,$datax=false) {
        return new \LinePlot($datay,$datax);
    }
}
