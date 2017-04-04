<?php

use \Psr\Http\Message\ServerRequestInterface as Request;

class ODataRequest{
    private $entity;
    private $pk;
    private $isGuid;
    private $method;
    private $body;
    
    private $filter;
    private $expand;
    private $select;
    private $top;
    private $skip;
    private $count;
    private $search;
    private $format;
    
    
    function __construct(Request $request) {
        $this->method=$request->getMethod();
                
        //Get entity
        $entity=$request->getAttribute('entityStr');
        
        //Get body
        $this->body=$request->getBody()->read(1000000);
                
        //Check for ID
        $rex="/^(?P<entity>\w+)(\(((?P<id>\w+)|guid'(?P<guid>[\w-]+)')\))?$/i";
            
        if (!preg_match($rex, $entity, $matches)){
            ODataHTTP::error(ODataHTTP::E_bad_request);
        }
        
        $this->entity=$matches["entity"];
        
        //TODO : Implement multiple PK and PK nammed
        $this->pk=[];
                
        if (isset($matches["guid"]) && $matches["guid"]!=""){
            $this->pk[]=$matches["guid"];
            $this->isGuid=true;
        }
        else{
            if (isset($matches["id"]) && $matches["id"]!=""){
                $this->pk[]=$matches["id"];
                $this->isGuid=false;
            }
        }
        
        $this->filter=$_GET["\$filter"];
        $this->expand=$_GET["\$expand"];
        $this->select=$_GET["\$select"];
        $this->orderby=$_GET["\$orderby"];
        $this->top=$_GET["\$top"];
        $this->skip=$_GET["\$skip"];
        $this->count=$_GET["\$count"];
        $this->search=$_GET["\$search"];
        $this->format=$_GET["\$format"];
        
    }
    
    public function getEntity() {return $this->entity;}
    public function getPk() {return $this->pk;}
    public function getMethod() {return $this->method;}
    public function getBody(){return $this->body;}
    
    
    public function getFilter() {return $this->filter;}
    public function getExpand() {return $this->expand;}
    public function getSelect() {return $this->select;}
    public function getTop() {return $this->top;}
    public function getSkip() {return $this->skip;}
    public function getCount() {return $this->count;}
    public function getSearch() {return $this->search;}
    public function getFormat() {return $this->format;}
    
}