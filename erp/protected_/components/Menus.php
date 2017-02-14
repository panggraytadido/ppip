<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/

Yii::import('application.models.*');
class Menus extends CApplicationComponent {
    
    function displayMenu()
    {
        
        Yii::app()->session['roles'];    
        $menus = isset(Yii::app()->session['menus']) ? Yii::app()->session['menus'] : array();
        $rootMenu = Menu::model()->getAuthParentMenu();        
        
        foreach($rootMenu as $k=>$v)
        {
            echo "<li>";
            echo $v['label'];
            $childMenu = Menu::model()->getAuthSubMenu($v['id']);
            foreach($childMenu as $k1=>$v1)
            {
                echo "<ul class='nav nav-second-level'>";
                    echo "<li><a href='".Yii::app()->baseUrl."/".$v1['url']."'>".$v1['label']."</a></li>";
                echo "</ul>";
            }
        }                    
        echo "</li>";
    }
}