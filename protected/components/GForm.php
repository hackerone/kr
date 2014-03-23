<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class GForm extends CForm {

    public function renderElement($element) {
        if (is_string($element)) {
            if (($e = $this[$element]) === null && ($e = $this->getButtons()->itemAt($element)) === null)
                return $element;
            else
                $element = $e;
        }


        if(isset($element->attributes['title'])){
            $element->label = $element->label . " <a href='#' class='info-tip hint--top' data-hint='".$element->attributes['title']."'><i class='fa-info-circle'></i></a>";
        }

        if (isset($element->type)) {
            switch($element->type){
                
                case 'img' : return $this->renderImg($element); // mongodb GImage
                    break;
                    
                case 'address' : return $this->renderAddress($element);
                    break;
                case 'group' : return $this->renderGroup($element);
                    break;

                case 'content' : return $this->renderContent($element);
                    break;

                case 'repeater' : return $this->renderRepeater($element);
                    break;
                default:
                    break;
            }
        }

        if ($element->getVisible()) {
            if ($element instanceof CFormInputElement) {
                $error = 0;

                if ($this->model->getError($element->name))
                    $error = 1;
                if ($element->type === 'hidden')
                    return "<div style=\"visibility:hidden\">\n" . $element->render() . "</div>\n";
                else
                    return "<div class=\"form-group " . ($error ? "error" : "") . " field_{$element->name}\">\n" . $element->render() . "</div>\n";
            }
            else if ($element instanceof CFormButtonElement)
                return $element->render() . "\n";
            else
                return $element->render();
        }
        return '';
    }

    public function renderImg($element){
        $i = $element->name;
        $model = isset($this->model->owner)?$this->model->owner:$this->model;
        $images = $model->$i;

        /*if(!isset($this->model->_id)){

            $label = $element->label ? $element->label : 'Images';
            return "<div class='form-group field_{$element->name} hint'><label>$label - Please save first to upload images.</label></div>";
        }*/

        if(!is_array($images) || !isset($images[0]))$images = array($images);
        
        $name = get_class($model);
        $id = isset($model->_id) ? $model->_id : false;
        $label = $element->label ? $element->label : 'Images';
        $multiple = (isset($element->attributes['multiple']) && $element->attributes['multiple'])? true : false;
        
        $minSize = explode('x',$element->dim);

        $fieldName = $name . "[" .$element->name . "]";
        
        $sortLink = "";

        if($multiple){
            $fieldName .= "[]";
            $sortLink = CHtml::link('Sort Images', [], ['class' => 'btn btn-primary btn-sort', 'data-target' => '.images_'.$element->name, 'data-imagesort' => true]);
        }



        $template = "<div class='template' data-width='{$minSize[0]}' data-height='{$minSize[1]}'>".CHtml::hiddenField($fieldName."[src]",null, array('disabled' => true, 'class' => 'image_src')).CHtml::hiddenField($fieldName."[file_id]",null, array('disabled' => true, 'class' => 'image_id')).CHtml::hiddenField($fieldName."[heading]",null, array('disabled' => true, 'class' => 'image_heading')).CHtml::hiddenField($fieldName."[content]",null, array('disabled' => true, 'class' => 'image_content')).CHtml::image("","",array('class' => 'image-holder'))."</div>";

        $template .= "<div class='empty-field'>".CHtml::hiddenField($fieldName.'[empty]', false)."</div>";
        
        $output = "<div class='form-group field_{$element->name}'>
                    <label>$label <a href='#' class='info-tip hint--left' data-hint='Minimum Image Dimension - Width : {$minSize[0]}px, Height : {$minSize[1]}px'><i class='fa-info-circle'></i></a></label>
                    <button class='image-uploader' data-multiple='$multiple' data-width='{$minSize[0]}' data-height='{$minSize[1]}'>Upload / Select Image</button>
                    ".$sortLink."
                    {$template}
                    <div class='uploaded-images row images_{$element->name}'>";
        foreach ($images as $k => $i) {
            $field = str_replace("[]", "[$k]", $fieldName);
            $img = new Image;
            $img->attributes = $i;
            $output .= $img->renderElement($field, $minSize);
        }
        $output .= "</div><div class='hint'>{$element->hint}</div></div>";
        return $output;
    }

    public function renderButtons() {
        $output = '';
        foreach ($this->getButtons() as $button)
            $output.=$this->renderElement($button);
        return $output !== '' ? "<div class=\"form-group buttons btn-group\">" . $output . "</div>\n" : '';
    }

    public function renderGroup($element)
    {
        $name = $element->name;
        $model = isset($this->model->owner)?$this->model->owner:$this->model;
        $groups = $model->$name ? $model->$name : [];
        $label = $element->label;
        $modelName = get_class($model);

        $fieldName = $modelName . "[" .$name . "][]";

        $output = "<div class='form-group field_{$element->name}'>
                    <label>$label</label>
                    
                    <button class='btn-primary add-to-group'>Add to Group</button>";
        $output .= "<div class='empty-field'>".CHtml::hiddenField($fieldName.'[empty]', false)."</div>";
        $group = new Group;
        $output .= "<div class='template'>".$group->renderElement($fieldName, true)."</div><div class='rows groups'>"; // Template

        foreach($groups as $k => $groupAtt){
            $field = str_replace("[]", "[$k]", $fieldName);
            $group = new Group;
            $group->attributes = $groupAtt;
            $output .= $group->renderElement($field);
        }
        $output .= "</div><div class='hint'>{$element->hint}</div></div>";

        return $output;

    }


    public function renderRepeater($element)
    {
        $name = $element->name;
        $model = $this->model;

        $modelName = get_class($model);
        
        $fields = $model->$name;

        $els = $element->elements; // repeating elements
        $label = $element->label ? $element->label : $element->name;

        $output = "<div class='form-group field_{$element->name}'>{$element->renderLabel()}{$element->renderHint()}<table class='table repeater'>";

        if(!isset($fields[0]))$fields = array(array());

        foreach($fields as $i => $field){

            if(!$i){
                $output .= "<tr>";
                $template = "<tr class='hidden template'>";
                foreach($els as $k=>$el){
                    $e = new CFormInputElement($el, $this);
                    $output .= "<th>". $e->label . "</th>";
                    $e->name = '_'.$name. '['.$k.']';
                    $e->tname =  $modelName.'['.$name. '][{num}]['.$k.']';
                    $e->label = false;
                    $template .= "<td>". $this->renderElement($e) . "</td>";
                }
                $template .= "<td><a href='' class='remove-field'> <i class='icon icon-remove'></i> </a></td></tr>";
                $output .="<th><a href='' class='add-field'> <i class='icon icon-plus'></i> </a></th></tr>";
                $output .= $template;
            }
            $output .= "<tr>";
            foreach($els as $k=>$el){
                $e = new CFormInputElement($el, $this);
                $e->name =  $name . '['.$i.']['.$k.']';
                $e->label = false;
                $output .= "<td>". $this->renderElement($e) . "</td>";
            }
            $output .= "<td> <a href='' class='remove-field'> <i class='icon icon-remove'></i> </a> </td>";
            $output .= "</tr>";
        }
        $output .= "</table></div>";
        return $output;
    }

    public function renderContent($el)
    {
        $langs = $el->items;
        $url = $el->url;
        $label = $el->label;
        $name = $el->name;

        $model = isset($this->model->owner)?$this->model->owner:$this->model;
        $contents =  $model->$name ? $model->$name : [];

        $modelName = get_class($model);
        $fieldName = $modelName . "[" .$name . "][]";

        $output = "<div class='form-group'>";

        foreach($langs as $lang => $name){
            $field = str_replace("[]", "[$lang]", $fieldName);
            $attr = isset($contents[$lang]) ? $contents[$lang] : [];
            $content = new Content;
            $content->attributes = $attr;
            $output .= $content->renderElement($field, $lang, $model->id);
        }

        $output .= "</div>";

        return $output;
    }


    // render multiple addresses.

    public function renderAddress($element)
    {
        $name = $element->name;
        $model = isset($this->model->owner)?$this->model->owner:$this->model;
        $addresses = $model->$name ? $model->$name : [];
        $label = $element->label;
        $modelName = get_class($model);

        $fieldName = $modelName . "[" .$name . "][]";

        $output = "<div class='form-group field_{$element->name}'>
                    <label>$label</label>
                    
                    <button class='btn-primary add-address'>Add Address</button>";
        $output .= "<div class='empty-field'>".CHtml::hiddenField($fieldName.'[empty]', false)."</div>";
        $address = new Address;
        $output .= "<div class='template'>".$address->renderElement($fieldName, true)."</div><div class='rows addresses'>"; // Template

        foreach($addresses as $k => $addressAtt){
            $field = str_replace("[]", "[$k]", $fieldName);
            $group = new Address;
            $group->attributes = $addressAtt;
            $output .= $group->renderElement($field);
        }
        $output .= "</div><div class='hint'>{$element->hint}</div></div>";

        return $output;
    }

}
?>