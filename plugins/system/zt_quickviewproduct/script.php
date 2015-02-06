<?php
// No direct access
defined( '_JEXEC' ) or die;

class PlgSystemQuickViewProductInstallerScript
{

	function postflight( $type, $parent )
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery( true );
		$query->update( '#__extensions' )->set( 'enabled=1' )->where( 'type=' . $db->q( 'plugin' ) )->where( 'element=' . $db->q( 'zt_quickviewproduct' ) );
		$db->setQuery( $query )->execute();

	}
}