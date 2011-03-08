<?php
 /**
  * selfLink
  * To navigate up, next or previous from the current resource or from the given resource
  *
  * Options
  *
  * id - The id where the direction is from. Default the current resource id
  * direction - To create a link in the direction up, previous or next
  * tpl - The chunkname wich is used for the view of the link
  * linktext - The text used for the link instead of the pagetitle
  *
  * Example usage:
  * [[!selfLink? &id=`[[*id]]` &direction=`next` &tpl=`commonName`]]
  *
  * @author Bert Oost <bertoost85@gmail.com>
  * @version 0.1-rc1
  */
  $resource = $modx->getOption('id', $scriptProperties, $modx->resource->get('id'));
  $direction = $modx->getOption('direction', $scriptProperties, false);
  $tpl = $modx->getOption('tpl', $scriptProperties, false);
  $linktxt = $modx->getOption('linktext', $scriptProperties, false);
  
  $linkResource = $modx->getObject('modResource', $resource);
  
  switch($direction) {
    
    case 'up':
    case 'parent':
      $parentid = $linkResource->get('parent');
      $linkResource = $modx->getObject('modResource', $parentid);
    break;
    
    case 'next':
      $menuindex = $linkResource->get('menuindex');
      $parentid = $linkResource->get('parent');
      $linkResource = $modx->getObject('modResource', array(
        'parent' => $parentid,
        'menuindex' => ($menuindex+1)
      ));
      
      if(is_object($linkResource)) {
        while($linkResource->get('published') == 0) {
          $menuindex = $linkResource->get('menuindex');
          $linkResource = $modx->getObject('modResource', array(
            'parent' => $parentid,
            'menuindex' => ($menuindex+1)
          ));
        }
      }
    break;
  
    case 'prev':
    case 'previous':
      $menuindex = $linkResource->get('menuindex');
      $parentid = $linkResource->get('parent');
      $linkResource = $modx->getObject('modResource', array(
        'parent' => $parentid,
        'menuindex' => ($menuindex-1)
      ));
      
      if(is_object($linkResource)) {
        while($linkResource->get('published') == 0) {
          $menuindex = $linkResource->get('menuindex');
          $linkResource = $modx->getObject('modResource', array(
            'parent' => $parentid,
            'menuindex' => ($menuindex-1)
          ));
        }
      }
    break;
  }
  
  if(isset($linkResource) && is_object($linkResource)) {
  
    // build placeholders
    $placeholders = array(  
      'id' => $linkResource->get('id'),
      'pagetitle' => (!empty($linktxt)) ? $linktxt : $linkResource->get('pagetitle'),
      'longtitle' => (!empty($linktxt)) ? $linktxt : $linkResource->get('longtitle')
    );
    
    // parse chunk
    $chunk = $modx->getObject('modChunk', array('name' => $tpl));
   
    if(!$chunk) {
      
      $useChunk = '<a href="[[~[[+id]]]]">[[+pagetitle]]</a>';
      $chunk = $modx->newObject('modChunk');
      $chunk->setCacheable(false);
      
      return $chunk->process($placeholders, $useChunk);
    }
    
    return $chunk->process($placeholders);
  }

?>