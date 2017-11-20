<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\View\Model;

require_once getcwd() . '/vendor/jpgraph/src/jpgraph.php';
require_once getcwd() . '/vendor/jpgraph/src/jpgraph_pie.php';
/**
 * Description of GraphPieModel
 *
 * @author user
 */
class GraphPieModel extends AbstractGraphModel{
    
    
    public function newGraphPie($width=400,$height=400,$cachedName='auto',$timeout=0,$inline=1) {
        $this->graph = new PieGraph($width,$height,$cachedName,$timeout,$inline);
        return $this->graph;
    } 
    
    public function set($graph) {
        $this->graph = $graph;        
    }
}
