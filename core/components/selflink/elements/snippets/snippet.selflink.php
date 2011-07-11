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
 * [[selfLink? &id=`[[*id]]` &direction=`next` &tpl=`commonName`]]
 *
 * @author Bert Oost <bertoost85@gmail.com>
 * @author Bruno Perner <b.perner@gmx.de>
 * @supporter: Anselm Hannemann (www.novolo.de)
 * @version 0.2-rc1
 */
$resource = $modx->getOption('id', $scriptProperties, $modx->resource->get('id'));
$direction = $modx->getOption('direction', $scriptProperties, false);
$tpl = $modx->getOption('tpl', $scriptProperties, false);
$linktxt = $modx->getOption('linktext', $scriptProperties, false);

if($res = $modx->getObject('modResource', $resource)) {
	
	$menuindex = $res->get('menuindex');
	$parentid = $res->get('parent');
	$c = $modx->newQuery('modResource');
	$c->limit(1);

	switch($direction) {

		case 'up':
		case 'parent':
			$c->where(array('id' => $parentid));
		break;

		case 'next':
		case 'prev':
		case 'previous':
			$c->where(array('id:!=' => $resource));
			$c->where(array('parent' => $parentid));
			$c->where(array('published' => true));
			$c->where(array('deleted' => false));
		break;
	}
	
	switch($direction) {

		case 'next':
			$c->where("IF(menuindex = ". $menuindex .",id > ". $resource .",menuindex >". $menuindex . ")" , xPDOQuery::SQL_AND);		
			$c->sortby('menuindex,id','ASC');
		break;

		case 'prev':
		case 'previous':
			$c->where("IF(menuindex = ". $menuindex .",id < ". $resource .",menuindex <". $menuindex . ")" , xPDOQuery::SQL_AND);		
			$c->sortby('menuindex','DESC');
			$c->sortby('id','DESC');
		break;
	}
	
	if($linkResource = $modx->getObject('modResource', $c)) {
		
		// build placeholders
		$placeholders = array(
			'id' => $linkResource->get('id'),
			'pagetitle' => (!empty($linktxt)) ? $linktxt : $linkResource->get('pagetitle'), 
			'longtitle' => (!empty($linktxt)) ? $linktxt : $linkResource->get('longtitle'),
			'menutitle' => (!empty($linktxt)) ? $linktxt : $linkResource->get('menutitle')
		);
		
		// parse chunk
		$chunk = $modx->getObject('modChunk', array('name' => $tpl));

		if(!$chunk) {

			$useChunk = '<a href="[[~[[+id]]]]">[[+menutitle:isempty=`[[+pagetitle]]`]]</a>';
			$chunk = $modx->newObject('modChunk');
			$chunk->setCacheable(false);
			$chunk->setContent($useChunk);
		}
		
		return $chunk->process($placeholders);
	}
}