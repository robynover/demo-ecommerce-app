<?php
/*
 * Simplified Form class
 */

class Form{
    private $action;
    private $method;
    private $elements= array();
    
    public function __construct($method='post',$action=null){
        $this->action = basename($_SERVER['SCRIPT_NAME']);
        $this->method = $method;
    }
    
    public function addInput($name,$label=null,$value=null,$type='text',$size=null){
        $type = strtolower($type);
        if ($type != 'submit' && $type != 'hidden' && $type != 'password'){
            $type = 'text';
        }
        //<input type='text' name='product_name' />
        $input = new stdClass();
        $input->elementType = 'input';
        $input->type = $type;
        $input->name = strip_tags($name);
        $input->value = strip_tags($value);
        if ((int)$size){
            $input->size = $size;
        }
        if ($label){
            $input->label = strip_tags($label);
        }
        
        $this->elements[] = $input;
    }
    public function addSelectList($name,$label=null,$values){
        /*
         <select>
              <option value="volvo">Volvo</option>
              <option value="saab">Saab</option>
         </select>
         */
        $select = new stdClass();
        $select->elementType = 'select';
        $select->name = strip_tags($name);
        if ($label){
            $select->label = strip_tags($label);
        }
        //values should be a multidimensional array, with key 0 = option name & key 1 = option display name
        if (is_array($values) && isset($values[0][0]) && isset($values[0][0])){
            $select->values = $values;
            $this->elements[] = $select;
        }
        
    }
    public function addTextArea($name,$label=null,$value=null){
        $tarea = new stdClass();
        $tarea->elementType = 'textarea';
        $tarea->name = strip_tags($name);
        $tarea->value = strip_tags($value);
        if ($label){
            $tarea->label = strip_tags($label);
        }
        
        $this->elements[] = $tarea;
    }
    
    public function addText($text, $tag='p'){
        $allowed_tags = array('p','h2','h3','h4');
        if (!in_array($tag, $allowed_tags)){
            $tag = 'p';
        }
        $text = strip_tags($text);
        $freeText = new stdClass();
        $freeText->elementType = 'freetext';
        $freeText->content = '<'.$tag.'>'.$text.'</'.$tag.'>';
        $this->elements[] = $freeText;
    }
    
    public function render(){
        $html = "<form method='{$this->method}' action='{$this->action}'>\n";
        foreach ($this->elements as $field){
            //we're only dealing with form <input> tags for now
            if ($field->elementType == 'input'){
                if (isset($field->label)){ 
                    $html .= "<label>{$field->label} </label>";
                }
                
                $html .= "<input type='{$field->type}' name='{$field->name}' value='{$field->value}' ";
                if (isset($field->size)){
                    $html.= 'size="'.$field->size.'" ';
                }
                $html .= "/>";
                $html .= "<br/>\n";
            }  
            
            if ($field->elementType == 'select'){
                if (isset($field->label)){ 
                    $html .= "<label>{$field->label} </label>";
                }
                $html .= "<select name ='{$field->name}'>";
                foreach ($field->values as $option){
                    $html .= "<option value='{$option[0]}'>{$option[1]}</option>";
                }
                
                $html .= '</select>';
                $html .= "<br/>\n";
            }
            if ($field->elementType == 'freetext'){
                $html .= $field->content;
            }
            if ($field->elementType == 'textarea'){
                if (isset($field->label)){ 
                    $html .= "<label>{$field->label} </label>";
                }
                $html .= "<p><textarea name='{$field->name}'>{$field->value}</textarea></p>";
                $html .= "\n";
            }
        }
        
        //end form
        $html .= '</form>';
        return $html;
    }
    
    public function setAction($script){
        $script = basename($script);
        if (substr($script,-4,4) == '.php'){
            $this->action = $script;
        }
        
    }
}