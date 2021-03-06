<?php
/**
 * JL Content Fields Filter
 *
 * @version 	@version@
 * @author		Joomline
 * @copyright	(C) 2017 Arkadiy Sedelnikov, Joomline. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the helper.
require_once __DIR__ . '/helper.php';
require_once JPATH_ROOT. '/components/com_content/helpers/route.php';

$app = JFactory::getApplication();
$input = $app->input;

$option = $input->getString('option', '');
$view = $input->getString('view', '');
$catid = $input->getInt('catid', 0);
$id = $input->getInt('id', 0);

if($view == 'category')
{
	$catid = $id;
}

$jlContentFieldsFilter = $app->getUserStateFromRequest($option.'.cat_'.$catid.'.jlcontentfieldsfilter', 'jlcontentfieldsfilter', array(), 'array');

$allowedCats = $params->get('categories', array());
$allowedContactCats = $params->get('contact_categories', array());
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$form_method = $params->get('form_method', 'post');
$autho_send = (int)$params->get('autho_send', 0);
$ajax = (int)$params->get('ajax', 0);
$ajax_selector = $params->get('ajax_selector', '#content');
$enableOrdering = $params->get('enable_ordering', 0);
$ajax_loader = $params->get('ajax_loader', '');
$ajax_loader = !empty(($ajax_loader)) ? JUri::root().$ajax_loader : '';
$ajax_loader_width = (int)$params->get('ajax_loader_width', 32);

if(
	!in_array($option, array('com_content', 'com_contact'))
    || ($option == 'com_content' && !(!count($allowedCats) || in_array($catid, $allowedCats) || $allowedCats[0] == -1))
    || ($option == 'com_contact' && !(!count($allowedContactCats) || in_array($catid, $allowedContactCats) || $allowedContactCats[0] == -1))
	|| $catid == 0
)
{
    return;
}
if($option == 'com_content'){
	$action = JRoute::_(ContentHelperRoute::getCategoryRoute($catid));
}
else{
	$action = JRoute::_(ContactHelperRoute::getCategoryRoute($catid));
}

$fields = ModJlContentFieldsFilterHelper::getFields($params, $catid, $jlContentFieldsFilter, $module->id, $option);

if(count($fields)){
	if($enableOrdering){
		$selectedOrdering = !empty($jlContentFieldsFilter['ordering']) ? $jlContentFieldsFilter['ordering'] : '';
		$orderingSelect = ModJlContentFieldsFilterHelper::getOrderingSelect($selectedOrdering, $module->id, $option);
	}
	require JModuleHelper::getLayoutPath('mod_jlcontentfieldsfilter', $params->get('layout', 'default'));
}

