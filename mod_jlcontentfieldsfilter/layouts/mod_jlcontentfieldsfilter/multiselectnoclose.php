<?php
/**
 * JL Content Fields Filter
 *
 * @version 	1.6.2
 * @author		Joomline
 * @copyright	(C) 2017 Arkadiy Sedelnikov, Joomline. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 */

defined('_JEXEC') or die;

if (!key_exists('field', $displayData))
{
	return;
}

$moduleId = $displayData['moduleId'];
$field = $displayData['field'];
$label = JText::_($field->label);
$value = $field->value;
$listOptions = (array)$field->fieldparams->get('options', array());
$options = array();
if(is_array($listOptions)){
	foreach ( $listOptions as $listOption ) {
		$options[] = JHtml::_('select.option', $listOption->value, $listOption->name);
    }
}
if(!count($options)){
	return;
}
?>
<label class="jlmf-label" for="<?php echo $field->name.'-'.$moduleId; ?>"><?php echo $label; ?></label>
<select
    name="jlcontentfieldsfilter[<?php echo $field->id; ?>][]"
    id="<?php echo $field->name.'-'.$moduleId; ?>"
    class="jlmf-select jlcontentfieldsfilter-<?php echo $field->id; ?>"
    multiple
>
	<?php foreach($options as $k => $v) : ?>
	    <?php if(in_array($v->value ,$value)) {
	        $checked = ' selected="selected"';
	    } else {
	        $checked = '';
	    } ?>
        <option value="<?php echo $v->value; ?>" <?php echo $checked; ?>><?php echo $v->text; ?></option>
	<?php endforeach; ?>
</select>

<?php
    JHtml::_('jquery.framework');
    JHtml::_('script', 'jui/chosen.jquery.min.js', array('version' => 'auto', 'relative' => true));
    JHtml::_('stylesheet', 'jui/chosen.css', array('version' => 'auto', 'relative' => true));
?>

<script type="text/javascript">
    jQuery(document).ready(function () {

        let $chosen = jQuery('.jlcontentfieldsfilter-<?= $field->id ?>').chosen({disable_search: false,placeholder_text_multiple:'<?php echo $label; ?>'});
        let chosen = $chosen.data("chosen");
        let _fn = chosen.result_select;

        chosen.result_select = function(evt) {
            evt["metaKey"] = true;
            evt["ctrlKey"] = true;
            chosen.result_highlight.addClass("result-selected");
            return _fn.call(chosen, evt);
        };


    });
</script>
