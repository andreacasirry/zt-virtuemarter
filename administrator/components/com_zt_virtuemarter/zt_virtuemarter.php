<?php

$controller = JControllerLegacy::getInstance('ZT_Virtuemarter');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
