<?php

/*
 * Displays all Flash Messages
 */

class Zend_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{
    /**
     * // Returns HTML to display messages.
     * @return mixed HTML 
     */
    public function flashMessages($messages)
    {
        $html = "";
        
        // Check we have messages to display
        if(count($messages) > 0)
        {
            $html .= "<ul id='messages' style='list-style:none; margin-left:0; padding-left:0;'>";
            
            foreach($messages as $key => $message)
            {
                $html .= "<li class='alert alert-".key($message)."'>".current($message)." <a class='close' data-dismiss='alert'>x</a></li>";
            }
            
            $html .= "</ul>";
        }
 
        return $html;
    }
}

?>
