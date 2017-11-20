<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\View\Model;

require_once getcwd() . '/vendor/jpgraph/src/jpgraph.php';
require_once getcwd() . '/vendor/jpgraph/src/jpgraph_bar.php';
require_once getcwd() . '/vendor/jpgraph/src/jpgraph_table.php';
/**
 * Description of GraphBarModel
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @version 1.0  
 * @since 19-12-2016 
 */
class GraphBarModel extends AbstractGraphModel{
    
    public function newGraph($aWidth=300,$aHeight=200,$aCachedName='',$aTimeout=0,$aInline=true)  {
        $this->graph = new \Graph($aWidth,$aHeight,$aCachedName,$aTimeout,$aInline);
        return $this->graph;
    }         
    
    public function newBarPlot($datay,$datax=false) {
        return new \BarPlot($datay,$datax);
    } 
    
    public function newAccBarPlot($plots=null) {
        return new \AccBarPlot($plots);
    }
    
    public function newGTextTable() {
        return new \GTextTable();
    }
    
    public function newGroupBarPlot($plots) {
        return new \GroupBarPlot($plots);
    }
    
    public function set($graph) {
        $this->graph = $graph;        
    }
}
